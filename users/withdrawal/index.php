<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");



// Fetch user's wallet
$userQuery = mysqli_query($connection, "SELECT wallet FROM users WHERE id='$id' LIMIT 1");
$userData = mysqli_fetch_assoc($userQuery);
$wallets = json_decode($userData['wallet'], true); // e.g. ["btc"=>110,"eth"=>20,"sol"=>50]



$query = mysqli_query($connection, "SELECT sum(balance) as total FROM card WHERE user='$id' AND is_active='active' LIMIT 1");
$cardDetail = mysqli_fetch_assoc($query);


$selectSite = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `withdraw_charge` FROM `sitedetails`"));


$withdrawals = [];

$stmt = $connection->prepare("SELECT id, account, amount, status, created_at 
FROM withdrawals 
WHERE login_id = ? 
ORDER BY created_at DESC 
LIMIT 5");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $withdrawals[] = $row;
}



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

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include("../includes/sidenav.php"); ?>


        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">

            <!-- Top Bar -->
            <?php include("../includes/header.php"); ?>


            <!-- Dashboard Grid -->
            <div class="max-w-6xl mx-auto space-y-10">

                <!-- Page Header -->
                <div>
                    <h1 class="text-3xl font-bold">Withdraw Funds</h1>
                    <p class="text-gray-400 mt-2">Transfer your funds securely to your wallet or bank account.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Left Side -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Available Balance Card -->
                        <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-8 shadow-lg">
                            <p class="text-gray-400 mb-2">Available Balance</p>
                            <h2 class="text-4xl font-bold">$139.55</h2>

                            <!-- Assets -->
                            <div class="grid grid-cols-3 gap-4 mt-2">

                                <?php foreach ($wallets as $symbol => $balances): ?>

                                    <div class="bg-gradient-to-br from-[#060b1f] via-[#050a25] to-[#020617] p-4 rounded-xl text-center">
                                        <p class="text-lg font-semibold text-gray-400"><?php echo strtoupper($symbol); ?></p>
                                        <p class="font-semibold"><?php echo $balances; ?></p>
                                    </div>

                                <?php endforeach; ?>

                            </div>
                            <p class="text-green-400 mt-2 text-sm">✔ Funds available for withdrawal</p>
                        </div>



                        <!-- Withdrawal Form -->
                        <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-8 shadow-lg space-y-6">

                            <h2 class="text-xl font-semibold border-b border-gray-700 pb-4">Withdrawal Details</h2>

                            <!-- Withdrawal Method -->
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Withdrawal Account</label>
                                <select id="withdrawAccount" name="method" class="w-full bg-dark-panel border border-dark-border uppercase rounded-lg p-2.5 text-sm text-white outline-none">

                                    <option value="main" data-balance="<?php echo $balance ?>">
                                        Main Balance - Balance: <?php echo number_format($balance, 2) ?>
                                    </option>

                                    <option value="card" data-balance="<?php echo $cardDetail['total'] ?>">
                                        Virtual Card - Balance: <?php echo number_format($cardDetail['total'], 2) ?>
                                    </option>

                                    <?php foreach ($wallets as $symbol => $bal): ?>
                                        <option value="<?php echo $symbol ?>" data-balance="<?php echo $bal ?>">
                                            <?php echo strtoupper($symbol) . " - Balance: " . number_format($bal, 4) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>



                            <!-- Amount -->
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Withdrawal Amount</label>
                                <input type="number" id="amount" placeholder="Enter amount"
                                    class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>

                            <!-- Fee Preview -->
                            <div class="bg-[#111827] p-4 rounded-xl border border-gray-700 text-sm space-y-2">

                                <div class="flex justify-between">
                                    <span class="text-gray-400">Processing Fee (2%)</span>
                                    <span id="fee">$0.00</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-400">You Will Receive</span>
                                    <span id="receive" class="text-green-400 font-semibold">$0.00</span>
                                </div>

                            </div>



                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button id="withdrawal_modal" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 py-3 rounded-xl font-semibold hover:scale-105 transition">
                                    Connect Wallet
                                </button>
                            </div>

                        </div>

                    </div>


                    <!-- Right Side -->
                    <div class="space-y-8">

                        <!-- Security Info -->
                        <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-6 shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Security Notice</h3>
                            <ul class="text-gray-400 text-sm space-y-3">
                                <li>✔ Manual Withdrawals are processed within 48 - 72 hours.</li>
                                <li>✔ Ensure wallet address is correct.</li>
                                <li>✔ Incorrect details may result in loss of funds.</li>
                                <li>✔ Large withdrawals may require manual approval.</li>
                            </ul>
                        </div>

                        <!-- Recent Withdrawals -->
                        <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-6 shadow-lg">
                            <h3 class="text-lg font-semibold mb-6">Recent Withdrawals</h3>

                            <div class="space-y-4 text-sm">

                                <?php if (!empty($withdrawals)): ?>

                                    <?php foreach ($withdrawals as $w): ?>

                                        <?php
                                        $statusColor = "text-yellow-400";

                                        if ($w['status'] == "completed") {
                                            $statusColor = "text-green-400";
                                        } elseif ($w['status'] == "rejected") {
                                            $statusColor = "text-red-400";
                                        }

                                        $accountName = $w['account'];
                                        ?>

                                        <div class="flex justify-between border-b border-gray-800 pb-3">
                                            <div>
                                                <p>
                                                    <?php
                                                    if ($w['account'] == "main" || $w['account'] == "card") {
                                                        echo "$" . number_format($w['amount'], 2);
                                                    } else {
                                                        echo number_format($w['amount'], 6) . " " . strtoupper($w['account']);
                                                    }
                                                    ?>
                                                </p>

                                                <p class="text-gray-400 uppercase  text-xs">
                                                    <?php

                                                    if ($accountName == 'card') {
                                                        echo  'Virtual ' .  $accountName;
                                                    }
                                                    else if($accountName == 'main') {
                                                        echo   $accountName . ' Balance';
                                                    } else {
                                                        echo  'Wallet Balance -> ' .  $accountName;
                                                    }

                                                    ?>
                                                </p>
                                            </div>

                                            <span class="<?php echo $statusColor; ?>">
                                                <?php echo ucfirst($w['status']); ?>
                                            </span>
                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <p class="text-gray-400 text-sm">No withdrawals yet.</p>

                                <?php endif; ?>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </main>
    </div>

    <?php include("../includes/wallet.php"); ?>


    <script>
        const amountInput = document.getElementById("amount");
        const feeText = document.getElementById("fee");
        const receiveText = document.getElementById("receive");
        const accountSelect = document.getElementById("withdrawAccount");

        function updatePreview() {

            let amount = parseFloat(amountInput.value) || 0;

            let fee = amount * ("<?php echo $selectSite['withdraw_charge'] ?>" / 100);
            let receive = amount - fee;

            let account = accountSelect.value;

            // USD accounts
            if (account === "main" || account === "card") {
                feeText.innerText = "$" + fee.toFixed(2);
                receiveText.innerText = "$" + receive.toFixed(2);
            }
            // Crypto accounts
            else {
                feeText.innerText = fee.toFixed(6) + " " + account.toUpperCase();
                receiveText.innerText = receive.toFixed(6) + " " + account.toUpperCase();
            }

        }

        // Update when typing amount
        amountInput.addEventListener("input", updatePreview);

        // Update when account changes
        accountSelect.addEventListener("change", updatePreview);
    </script>

</body>

</html>