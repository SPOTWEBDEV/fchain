<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");

// Fetch user's wallet
$stmt = $connection->prepare("SELECT wallet FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$userWallets = json_decode($userData['wallet'], true); // e.g. ["BTC"=>1.23,"ETH"=>10,"SOL"=>50]

// Admin wallet addresses for Receive
$adminWallets = [
    'btc' => ['name' => 'Bitcoin', 'address' => '1AdminBTCAddressXYZ'],
    'eth' => ['name' => 'Ethereum', 'address' => '0xAdminETHAddressXYZ'],
    'sol' => ['name' => 'Solana', 'address' => 'AdminSOLAddressXYZ']
];

// Prepare user wallets array for display
$wallets = [
    ['name' => 'Bitcoin', 'symbol' => 'btc', 'balance' => $userWallets['btc'] ?? 0],
    ['name' => 'Ethereum', 'symbol' => 'eth', 'balance' => $userWallets['eth'] ?? 0],
    ['name' => 'Solana', 'symbol' => 'sol', 'balance' => $userWallets['sol'] ?? 0],
];

// Fetch user transactions
$transactions = [];
$sql = $connection->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

// Calculate total balance
$totalBalance = 0;
foreach ($wallets as $w) {
    $totalBalance += $w['balance']; // can convert to USD if you have rates
}

// Example crypto rates for Swap display (replace with live API later)
$cryptoRates = [
    'btc' => 27000,
    'eth' => 1800,
    'sol' => 25
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> Dashboard</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Heroicons -->
    <script src="https://unpkg.com/heroicons@2.0.13/dist/heroicons.min.js"></script>

    <script src="https://cdn.tailwindcss.com/"></script>

    <link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Inter:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            primary: '#6366F1', // Indigo
                            secondary: '#818CF8',
                            accent: '#4F46E5',
                            dark: '#312E81'
                        },
                        dark: {
                            bg: '#02040a',
                            panel: '#0B0F19',
                            card: '#111827',
                            border: '#1E293B',
                            text: '#E2E8F0',
                            muted: '#94A3B8'
                        }
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(99, 102, 241, 0.3)',
                        'card': '0 8px 32px 0 rgba(0, 0, 0, 0.4)',
                    },
                    breakpoints: {
                        'small': '300px',
                        'xs': '480px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-[#060b1f] via-[#050a25] to-[#020617] text-white">

    <?php

    if (isset($_POST['send_crypto'])) {

        $asset = $_POST['asset'];
        $amount = floatval($_POST['amount']);
        $recipient = trim($_POST['recipient']);

        echo "<script>alert('Amount: $amount  and  assets: $asset');</script>";

        if ($amount <= 0) {
            echo "<script>showAlert('Invalid amount','error');</script>";
        } else {

            $wallet = json_decode($userData['wallet'], true);

            $balance = $wallet[$asset] ?? 0;

            if ($amount > $balance) {
                echo "<script>showAlert('Insufficient balance','error');</script>";
            } else {

                // Debit wallet
                $wallet[$asset] = $balance - $amount;

                $newWallet = json_encode($wallet);

                mysqli_query($connection, "UPDATE users SET wallet='$newWallet' WHERE id='$id'");

                // Save transaction
                $stmt = $connection->prepare("
                    INSERT INTO transactions 
                    (user_id,type,asset,amount,address,status) 
                    VALUES (?,?,?,?,?,?)
                    ");

                $type = "send";
                $status = "completed";

                $stmt->bind_param("issdss", $id, $type, $asset, $amount, $recipient, $status);
                $stmt->execute();

                echo "<script>showAlert('Transaction Sent','success');
             setTimeout(()=>{window.location.href='./'},1000)
        </script>";
            }
        }
    }

    if (isset($_POST['swap_crypto'])) {

        $from = $_POST['from_asset'];
        $to = $_POST['to_asset'];
        $amount = floatval($_POST['swap_amount']);

        if ($from == $to) {
            echo "<script>alert('Cannot swap same asset');</script>";
        } else {

            $wallet = json_decode($userData['wallet'], true);

            $balance = $wallet[$from] ?? 0;

            if ($amount > $balance) {
                echo "<script>alert('Insufficient balance');</script>";
            } else {

                global $cryptoRates;

                $usd = $amount * $cryptoRates[$from];

                $converted = $usd / $cryptoRates[$to];

                // debit
                $wallet[$from] -= $amount;

                // credit
                $wallet[$to] += $converted;

                $newWallet = json_encode($wallet);

                mysqli_query($connection, "UPDATE users SET wallet='$newWallet' WHERE id='$id'");

                // save transaction
                $stmt = $connection->prepare("
            INSERT INTO transactions 
            (user_id,type,asset,amount,status) 
            VALUES (?,?,?,?,?)
            ");

                $type = "swap";
                $status = "completed";

                $stmt->bind_param("issds", $id, $type, $from, $amount, $status);
                $stmt->execute();

                echo "<script>alert('Swap Completed');window.location.reload();</script>";
            }
        }
    }



    ?>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include("../includes/sidenav.php"); ?>


        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">

            <!-- Top Bar -->
            <?php include("../includes/header.php"); ?>


            <!-- Dashboard Grid -->
            <section id="wallet" class="py-16 relative bg-[#0f172a] border-t border-dark-border">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


                    <div class="lg:col-span-2">
                        <div class="glass-card rounded-2xl overflow-hidden shadow-card ">
                            <div class="p-6 border-b border-dark-border flex justify-between items-center bg-dark-panel/50">
                                <div>
                                    <h3 class="text-xl font-bold text-white">Exchange</h3>
                                    <p class="text-xs text-dark-muted">Multi-Chain Support (ERC20, BEP20, TRC20)</p>
                                </div>
                                <div class="bg-dark-bg px-3 py-1 rounded border border-dark-border flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    <span class="text-xs font-mono">Connected</span>
                                </div>
                            </div>

                            <div class="p-6 grid md:grid-cols-2 gap-8">
                                <div class="flex flex-col justify-center">
                                    <p class="text-dark-muted text-sm mb-1">Total Balance</p>
                                    <h2 class="text-4xl font-display font-bold text-white mb-6">$42,894.52</h2>

                                    <div class="grid grid-cols-3 gap-3">
                                        <button onclick="switchTab('send')" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-dark-bg border border-dark-border hover:border-brand-primary hover:bg-brand-primary/10 transition-all group">
                                            <div class="w-10 h-10 rounded-full bg-brand-primary/20 flex items-center justify-center text-brand-primary group-hover:bg-brand-primary group-hover:text-white">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </div>
                                            <span class="text-xs font-medium">Send</span>
                                        </button>
                                        <button onclick="switchTab('receive')" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-dark-bg border border-dark-border hover:border-brand-primary hover:bg-brand-primary/10 transition-all group">
                                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 group-hover:bg-green-500 group-hover:text-white">
                                                <i class="fa-solid fa-qrcode"></i>
                                            </div>
                                            <span class="text-xs font-medium">Receive</span>
                                        </button>
                                        <button onclick="switchTab('swap')" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-dark-bg border border-dark-border hover:border-brand-primary hover:bg-brand-primary/10 transition-all group">
                                            <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:bg-purple-500 group-hover:text-white">
                                                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                            </div>
                                            <span class="text-xs font-medium">Swap</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-dark-bg rounded-xl border border-dark-border p-5 min-h-[300px] relative">

                                    <form method="POST" id="tab-send" class="space-y-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-bold text-white">Send Crypto</h4>
                                            <span class="text-xs text-brand-primary cursor-pointer">Max Amount</span>
                                        </div>
                                        <div>
                                            <label class="text-xs text-dark-muted block mb-1">Asset</label>
                                            <select name="asset" class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white outline-none">
                                                <?php foreach ($wallets as $w): ?>
                                                    <option value="<?php echo $w['symbol']; ?>">
                                                        <?php echo $w['name'] . " (" . $w['symbol'] . ") - Balance: " . $w['balance'] . " (~$" . ($w['balance'] * $cryptoRates[$w['symbol']]) . ")"; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-dark-muted block mb-1">Recipient Address</label>
                                            <div class="relative">
                                                <input type="text" name="recipient" placeholder="Paste address..." class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white focus:border-brand-primary outline-none pl-9">
                                                <i class="fa-solid fa-wallet absolute left-3 top-3 text-dark-muted text-xs"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-dark-muted block mb-1">Amount</label>
                                            <input type="number" name="amount" placeholder="0.00" class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white focus:border-brand-primary outline-none">
                                        </div>
                                        <button name="send_crypto" class="w-full bg-brand-primary hover:bg-brand-accent text-white font-bold py-2.5 rounded-lg mt-2 transition-colors">Confirm Send</button>
                                    </form>

                                    <!-- Receive Tab -->
                                    <div id="tab-receive" class="hidden flex flex-col items-center justify-center h-full text-center space-y-4">
                                        <h4 class="font-bold text-white mb-2">Receive Crypto</h4>
                                        <label class="text-xs text-dark-muted block mb-1">Select Wallet</label>
                                        <select id="receiveWalletSelect" class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white outline-none">
                                            <?php foreach ($adminWallets as $symbol => $w): ?>
                                                <option value="<?php echo $symbol; ?>"><?php echo $w['name'] . " (" . $symbol . ")"; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php $firstSymbol = array_key_first($adminWallets); ?>
                                        <?php $firstAddress = $adminWallets[$firstSymbol]['address']; ?>

                                        <div id="receiveWalletDetails" class="space-y-2 mt-3">
                                            <img id="receiveQR"
                                                src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo $firstAddress; ?>"
                                                class="mx-auto bg-white p-3 rounded-lg">

                                            <p class="text-xs text-dark-muted mb-1">Wallet Address</p>

                                            <input type="text"
                                                id="receiveAddress"
                                                value="<?php echo $firstAddress; ?>"
                                                readonly
                                                class="bg-dark-panel border border-dark-border rounded-lg p-2.5 text-xs text-brand-secondary w-full outline-none">

                                        </div>


                                    </div>

                                    <!-- Swap Tab -->
                                    <form method="POST" id="tab-swap" class="hidden space-y-3">
                                        <h4 class="font-bold text-white mb-2">Instant Swap</h4>
                                        <label class="text-xs text-dark-muted block mb-1">From</label>
                                        <!-- SWAP TAB -->
                                        <select id="swapFrom" class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white outline-none" name="from_asset">
                                            <?php foreach ($wallets as $w): ?>
                                                <option value="<?php echo $w['symbol']; ?>">
                                                    <?php echo $w['name'] . " (" . $w['symbol'] . ") - Balance: " . $w['balance'] . " (~$" . ($w['balance'] * $cryptoRates[$w['symbol']]) . ")"; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="text-xs text-dark-muted block mb-1 mt-2">To</label>

                                        <select id="swapTo" class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white outline-none mt-2" name="to_asset">
                                            <?php foreach ($wallets as $w): ?>
                                                <option value="<?php echo $w['symbol']; ?>">
                                                    <?php echo $w['name'] . " (" . $w['symbol'] . ")"  ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="number" step="0.00000001" name="swap_amount"
                                            placeholder="Amount"
                                            class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white outline-none">
                                        <button class="w-full bg-brand-primary hover:bg-brand-accent text-white font-bold py-2.5 rounded-lg mt-2 transition-colors">Swap Assets</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="glass-card rounded-2xl p-6 mt-2 border border-dark-border">
                            <h3 class="font-bold text-white mb-4">Recent Activity</h3>
                            <div class="space-y-4">
                                <?php foreach ($transactions as $tx): ?>
                                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-dark-bg/50 transition-colors border border-transparent hover:border-dark-border">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center <?php
                                                                                                                echo $tx['type'] == 'deposit' || $tx['type'] == 'receive' ? 'bg-green-500/10 text-green-400' : '';
                                                                                                                echo $tx['type'] == 'withdrawal' || $tx['type'] == 'send' ? 'bg-red-500/10 text-red-400' : '';
                                                                                                                echo $tx['type'] == 'swap' ? 'bg-brand-primary/10 text-brand-primary' : '';
                                                                                                                ?>">
                                                <i class="fa-solid <?php
                                                                    echo $tx['type'] == 'deposit' || $tx['type'] == 'receive' ? 'fa-arrow-down' : '';
                                                                    echo $tx['type'] == 'withdrawal' || $tx['type'] == 'send' ? 'fa-arrow-up' : '';
                                                                    echo $tx['type'] == 'swap' ? 'fa-repeat' : '';
                                                                    ?>"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-white"><?php echo ucfirst($tx['type']) . " " . $tx['asset']; ?></p>
                                                <p class="text-xs text-dark-muted"><?php echo date("M d, Y H:i", strtotime($tx['created_at'])); ?></p>
                                            </div>
                                        </div>
                                        <span class="font-mono text-sm <?php echo $tx['type'] == 'deposit' || $tx['type'] == 'receive' ? 'text-green-400' : 'text-white'; ?>">
                                            <?php echo ($tx['type'] == 'deposit' || $tx['type'] == 'receive' ? '+' : '-') . $tx['amount'] . " " . $tx['asset']; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Recent Activity -->


                </div>
            </section>

        </main>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all
            document.getElementById('tab-send').classList.add('hidden');
            document.getElementById('tab-receive').classList.add('hidden');
            document.getElementById('tab-swap').classList.add('hidden');
            document.getElementById('tab-receive').classList.remove('flex');

            const el = document.getElementById('tab-' + tabName);
            el.classList.remove('hidden');

            if (tabName === 'receive') el.classList.add('flex');
        }
    </script>

    <script>
        function switchTab(tabName) {
            document.getElementById('tab-send').classList.add('hidden');
            document.getElementById('tab-receive').classList.add('hidden');
            document.getElementById('tab-swap').classList.add('hidden');
            const el = document.getElementById('tab-' + tabName);
            el.classList.remove('hidden');
            if (tabName === 'receive') el.classList.add('flex');
        }

        const adminWallets = <?php echo json_encode($adminWallets); ?>;

        document.getElementById('receiveWalletSelect').addEventListener('change', function() {

            const symbol = this.value;

            const wallet = adminWallets[symbol];

            const address = wallet.address;

            document.getElementById("receiveQR").src =
                "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + address;

            document.getElementById("receiveAddress").value = address;

        });
    </script>

</body>

</html>