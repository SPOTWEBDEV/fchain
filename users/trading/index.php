<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");

/* =========================
Fetch user data
=========================*/
$userQuery = mysqli_query($connection, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($userQuery);

/* =========================
Fetch site settings
=========================*/
$siteQuery = mysqli_query($connection, "SELECT * FROM sitedetails LIMIT 1");
$site = mysqli_fetch_assoc($siteQuery);
$minTradeAmount = $site['min_autotrade_amount'] ?? 10; // default 10 if not set

/* =========================
Fetch user's auto trades
=========================*/
$tradeQuery = mysqli_query($connection, "
    SELECT * FROM auto_trading WHERE user_id='$id' ORDER BY created_at DESC
");

/* =========================
Handle form submission
=========================*/
if (isset($_POST['start_autotrade'])) {
    $amount = floatval($_POST['amount']);

    if ($amount > $balance) {
        echo "<script>showAlert('Insufficient balance','error')</script>";
    } else {
        if ($amount < $minTradeAmount) {
            echo "<script>showAlert('Minimum trade amount is $" . $minTradeAmount . "','error');</script>";
        } else {
            // Check if user has any open or pending auto trades
            $checkQuery = mysqli_query($connection, "SELECT * FROM auto_trading WHERE user_id='$id' AND status IN ('open','pending')");
            if (mysqli_num_rows($checkQuery) > 0) {
                echo "<script>showAlert('You already have an open or pending auto trade','error');</script>";
            } else {
                // Insert new auto trade (default status: pending)
                $stmt = $connection->prepare("
                INSERT INTO auto_trading (user_id, amount, state, status, created_at, range_score)
                VALUES (?, ?, 'breakeven', 'pending', NOW(), '-2:5')
                ");

                $stmt->bind_param("id", $id, $amount);
                if ($stmt->execute()) {
                    echo "<script>showAlert('Auto trade started successfully','success'); setTimeout(()=>{window.location.href='./'},2000);</script>";
                } else {
                    $err = addslashes(mysqli_error($connection));
                    echo "<script>showAlert('Error: $err','error');</script>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> - Auto Trading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-[#060b1f] via-[#050a25] to-[#020617] text-white">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include("../includes/sidenav.php"); ?>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">
            <?php include("../includes/header.php"); ?>

            <!-- Auto Trading Info -->
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="bg-[#0f172a] p-6 rounded-2xl border border-gray-800 shadow-lg">
                    <h1 class="text-3xl font-bold mb-4">Auto Trading</h1>
                    <p class="text-gray-400 mb-4">
                        Auto trading allows you to invest your funds automatically according to pre-set strategies.
                        The minimum trade amount is $<?php echo $minTradeAmount; ?>.
                        Admin sets trading range, stop-loss, take-profit, and system status.
                    </p>

                    <?php
                    // Check if user has any open or pending auto trades
                    $checkQuery = mysqli_query($connection, "SELECT * FROM auto_trading WHERE user_id='$id' AND status IN ('open','pending')");
                    if (mysqli_num_rows($checkQuery) == 0) {
                        // No open/pending trades, show approved message and form
                    ?>
                        <div class="bg-red-900/30 border border-red-500 p-6 rounded-xl mb-6">
                            <i class="fa-solid fa-circle-check text-red-400 text-3xl mb-2"></i>
                            <p class="text-red-200">You have no active auto trades. You can start a new auto trade below by entering an amount.</p>
                        </div>

                        <form method="POST" class="space-y-4 bg-[#111827] p-6 rounded-xl border border-gray-800">
                            <label class="block text-gray-400">Amount ($)</label>
                            <input type="number" name="amount" min="<?php echo $minTradeAmount; ?>" class="w-full bg-[#0f172a] border border-gray-700 rounded-xl px-4 py-2 text-white">

                            <button type="submit"
                                name="start_autotrade"
                                class="w-full md:w-auto bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-3 rounded-xl font-semibold hover:scale-105 transition">
                                Submit
                            </button>
                        </form>

                    <?php
                    } else {
                        // Display current/past trades
                    ?>

                        <table class="w-full table-auto text-sm text-white bg-[#111827] rounded-xl border border-gray-700 overflow-hidden">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-4 py-2">Id</th>
                                    <th class="px-4 py-2">Amount ($)</th>
                                    <!-- <th class="px-4 py-2">State</th> -->
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Profit</th>
                                    <th class="px-4 py-2">Started At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $count = 0;
                                 while ($trade = mysqli_fetch_assoc($tradeQuery)) { $count++ ?>
                                    <tr class="border-t border-gray-700 text-center">
                                        <td class="px-4 py-2"><?php echo $count ?></td>
                                        <td class="px-4 py-2"><?php echo number_format($trade['amount'], 2) . ' USD' ?></td>
                                        <!-- <td class="px-4 py-2"><?php echo ucfirst($trade['state']); ?></td> -->
                                        <td class="px-4 py-2"><?php echo ucfirst($trade['status']); ?></td>
                                        <td class="px-4 py-2"><?php echo ucfirst($trade['profit']); ?></td>
                                        <td class="px-4 py-2"><?php echo date("d M Y H:i", strtotime($trade['created_at'])); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>

            <div class="max-w-4xl mx-auto mt-4">

                <div class="bg-[#0f172a] p-6 rounded-2xl border border-gray-800 shadow-lg">
                    <h1 class="text-3xl font-bold mb-4">Auto Trading History</h1>
                   

                    <?php
                    // Check if user has any open or pending auto trades
                    $checkQuery = mysqli_query($connection, "SELECT * FROM auto_trading WHERE user_id='$id' AND status = 'closed' ");
                    if (mysqli_num_rows($checkQuery)) { ?>
                        
                      <table class="w-full table-auto text-sm text-white bg-[#111827] rounded-xl border border-gray-700 overflow-hidden">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-4 py-2">Id</th>
                                    <th class="px-4 py-2">Amount ($)</th>
                                    <!-- <th class="px-4 py-2">State</th> -->
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Profit</th>
                                    <th class="px-4 py-2">Started At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $count = 0;
                                 while ($trade = mysqli_fetch_assoc($tradeQuery)) { $count++ ?>
                                    <tr class="border-t border-gray-700 text-center">
                                        <td class="px-4 py-2"><?php echo $count ?></td>
                                        <td class="px-4 py-2"><?php echo number_format($trade['amount'], 2) . ' USD' ?></td>
                                        <!-- <td class="px-4 py-2"><?php echo ucfirst($trade['state']); ?></td> -->
                                        <td class="px-4 py-2"><?php echo ucfirst($trade['status']); ?></td>
                                        <td class="px-4 py-2"><?php echo ucfirst($trade['profit']); ?></td>
                                        <td class="px-4 py-2"><?php echo date("d M Y H:i", strtotime($trade['created_at'])); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    
                          
                    <?php } ?>
                </div>
            </div>

            <!-- Custom Alert Modal -->
            <?php include("../includes/modal.php"); ?>

        </main>
    </div>

</body>

</html>