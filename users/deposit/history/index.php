<?php
include("../../../server/connection.php");
include("../../../server/auth/client.php");

// Fetch user deposits with payment method details
$depositQuery = mysqli_query($connection, "
    SELECT 
        d.id as deposit_id,
        d.amount,
        d.status,
        d.created_at as deposited_at,
        m.name as method_name,
        m.account_details
    FROM deposits d
    JOIN deposit_methods m ON d.method_id = m.id
    WHERE d.user_id='$id'
    ORDER BY d.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> - Deposit History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-[#060b1f] via-[#050a25] to-[#020617] text-white">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include("../../includes/sidenav.php"); ?>

        <!-- Main Content -->
        <main class="flex-1 p-2 sm:p-6 lg:p-10">
            <?php include("../../includes/header.php"); ?>


            <div class="max-w-5xl mx-auto mt-6">
                <div class="bg-[#0f172a] p-2 sm:p-6 rounded-2xl border border-gray-800 shadow-lg">
                    <h2 class="text-3xl font-bold mb-4">Deposit History</h2>

                    <?php if (mysqli_num_rows($depositQuery) > 0) { ?>
                        <!-- Responsive wrapper -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full min-w-[700px] table-auto text-sm text-white bg-[#111827] rounded-xl border border-gray-700">
                                <thead class="bg-gray-800 text-gray-300">
                                    <tr>
                                        <th class="px-4 py-2">#</th>
                                        <th class="px-4 py-2">Amount ($)</th>
                                        <th class="px-4 py-2">Method</th>
                                        <th class="px-4 py-2">Account Details</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Deposited At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    while ($dep = mysqli_fetch_assoc($depositQuery)) {
                                        $count++;
                                        $status = strtolower($dep['status']);
                                        $statusColor = "bg-gray-500"; // default
                                        if ($status == 'pending') $statusColor = "bg-yellow-500";
                                        else if ($status == 'approved') $statusColor = "bg-green-600";
                                        else if ($status == 'rejected' || $status == 'failed') $statusColor = "bg-red-600";
                                    ?>
                                        <tr class="border-t border-gray-700 text-center align-top">
                                            <td class="px-4 py-2"><?php echo $count; ?></td>
                                            <td class="px-4 py-2"><?php echo number_format($dep['amount'], 2); ?></td>
                                            <td class="px-4 py-2"><?php echo $dep['method_name']; ?></td>
                                            <td class="px-4 py-2 text-left">
                                                <div class="text-sm font-mono text-gray-200 break-words">
                                                    <?php
                                                    $account = json_decode($dep['account_details'], true);
                                                    if (isset($account['address'])) {
                                                        // Crypto wallet
                                                        echo "Wallet Address: " . $account['address'];
                                                    } elseif (isset($account['bank_name']) && isset($account['account_number'])) {
                                                        // Bank account
                                                        echo "Account Name: " . ($account['account_name'] ?? '-') . "<br>";
                                                        echo "Bank Name: " . ($account['bank_name'] ?? '-') . "<br>";
                                                        echo "Account Number: " . ($account['account_number'] ?? '-');
                                                    } else {
                                                        echo $dep['account_details'];
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-3 py-1 rounded-full text-white font-bold text-sm <?php echo $statusColor; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-2"><?php echo date("d M Y H:i", strtotime($dep['deposited_at'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="md:hidden space-y-4 mt-4">

                            <?php
                            mysqli_data_seek($depositQuery, 0);
                            $count = 0;

                            while ($dep = mysqli_fetch_assoc($depositQuery)) {
                                $count++;

                                $status = strtolower($dep['status']);
                                $statusColor = "bg-gray-500";

                                if ($status == 'pending') $statusColor = "bg-yellow-500";
                                else if ($status == 'approved') $statusColor = "bg-green-600";
                                else if ($status == 'rejected' || $status == 'failed') $statusColor = "bg-red-600";
                            ?>

                                <div class="bg-[#111827] border border-gray-700 rounded-xl p-4 shadow-md">

                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="font-semibold text-white">
                                            Deposit #<?php echo $count ?>
                                        </h3>

                                        <span class="px-2 py-1 rounded-full text-white text-xs <?php echo $statusColor ?>">
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </div>

                                    <p class="text-gray-400 text-sm">
                                        <strong>Amount:</strong> $<?php echo number_format($dep['amount'], 2); ?>
                                    </p>

                                    <p class="text-gray-400 text-sm">
                                        <strong>Method:</strong> <?php echo $dep['method_name']; ?>
                                    </p>

                                    <p class="text-gray-400 text-sm mt-1">
                                        <strong>Account Details:</strong>
                                    </p>

                                    <div class="text-sm font-mono text-gray-200 break-words">

                                        <?php
                                        $account = json_decode($dep['account_details'], true);

                                        if (isset($account['address'])) {

                                            
                                            $address = $account['address'];

                                            $masked = substr($address, 0, 5) . "..." . substr($address, -4);

                                            echo "Wallet Address: " . $masked;
                                        } elseif (isset($account['bank_name']) && isset($account['account_number'])) {

                                            echo "Account Name: " . ($account['account_name'] ?? '-') . "<br>";
                                            echo "Bank Name: " . ($account['bank_name'] ?? '-') . "<br>";
                                            echo "Account Number: " . ($account['account_number'] ?? '-');
                                        } else {

                                            echo $dep['account_details'];
                                        }
                                        ?>

                                    </div>

                                    <p class="text-gray-400 text-sm mt-2">
                                        <strong>Date:</strong>
                                        <?php echo date("d M Y H:i", strtotime($dep['deposited_at'])); ?>
                                    </p>

                                </div>

                            <?php } ?>

                        </div>
                    <?php } else { ?>
                        <p class="text-gray-400 text-center mt-4">You have no deposit history yet.</p>
                    <?php } ?>
                </div>
            </div>



        </main>
    </div>

    <script>
        function copyToClipboard(btn) {
            const account = btn.previousElementSibling.innerText;
            navigator.clipboard.writeText(account)
                .then(() => {
                    btn.innerText = 'Copied!';
                    setTimeout(() => btn.innerText = 'Copy', 1500);
                });
        }
    </script>

</body>

</html>