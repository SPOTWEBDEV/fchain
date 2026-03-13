<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");

// Fetch available payment methods from DB
$paymentMethodsQuery = mysqli_query($connection, "SELECT * FROM deposit_methods WHERE status='active'");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> - Deposit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-[#060b1f] via-[#050a25] to-[#020617] text-white">

    <?php

    // Handle final deposit confirmation
    if (isset($_POST['confirm_deposit'])) {
        $method_id = intval($_POST['method_id']);
        $amount = floatval($_POST['amount']);

        $methodQuery = mysqli_query($connection, "SELECT * FROM deposit_methods WHERE id='$method_id'");
        $method = mysqli_fetch_assoc($methodQuery);

        if ($method) {
            // Insert deposit record as 'pending'
            $insert = mysqli_query($connection, "
            INSERT INTO deposits (user_id, method_id, amount, status, created_at)
            VALUES ('$id','$method_id','$amount','pending',NOW())
        ");

            if ($insert) {
                echo "<script>showAlert('Deposit request submitted! Please wait for confirmation.','success');
                setTimeout(function(){ window.location.href = './history'}, 1000);
                
                </script>";
            } else {
                echo "<script>showAlert('Error: " . mysqli_error($connection) . "','error');</script>";
            }
        } else {
            echo "<script>showAlert('Invalid Payment Method','error');</script>";
        }
    }


    ?>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include("../includes/sidenav.php"); ?>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">
            <?php include("../includes/header.php"); ?>

            <div class="max-w-4xl mx-auto">

                <div class="bg-[#0f172a] p-8 rounded-2xl border border-gray-800 shadow-lg mb-6">
                    <h1 class="text-3xl font-bold mb-4">Deposit Funds</h1>
                    <p class="text-gray-400 mb-6">Enter the amount you want to deposit and select your payment method.</p>

                    <!-- Deposit Form -->
                    <form id="depositForm">
                        <div class="mb-4">
                            <label class="block mb-2 text-gray-400">Amount ($)</label>
                            <input type="number" id="depositAmount" name="amount" placeholder="Enter amount" class="w-full p-3 rounded-xl bg-[#111827] border border-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        </div>

                        <button type="button" onclick="openMethodModal()" class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-3 rounded-xl font-semibold">
                            Proceed
                        </button>
                    </form>
                </div>

                <!-- Step 1 Modal: Select Method -->
                <div id="methodModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                    <div class="bg-[#0f172a] p-8 rounded-2xl w-full max-w-md border border-gray-700">
                        <h2 class="text-2xl font-bold mb-6">Select Payment Method</h2>
                        <div class="space-y-4">
                            <?php while ($method = mysqli_fetch_assoc($paymentMethodsQuery)) { ?>
                                <div class="flex items-center justify-between p-4 border border-gray-700 rounded-xl hover:border-purple-500 cursor-pointer methodOption"
                                    data-account='<?php echo htmlspecialchars($method['account_details'], ENT_QUOTES); ?>'
                                    data-id="<?php echo $method['id']; ?>"
                                    data-name="<?php echo $method['name']; ?>">
                                    <span><?php echo $method['name']; ?></span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button onclick="closeMethodModal()" type="button" class="bg-gray-700 px-4 py-2 rounded-xl">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Step 2 Modal: Show Account Details -->
                <div id="accountModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                    <div class="bg-[#0f172a] p-8 rounded-2xl w-full max-w-md border border-gray-700">
                        <h2 class="text-2xl font-bold mb-4">Deposit Details</h2>
                        <p class="text-gray-400 mb-4">Send exactly <span id="finalAmount" class="font-bold"></span> USD to the account below:</p>
                        <div class="flex items-center justify-between bg-gray-800 p-4 rounded-xl mb-6">
                            <pre id="accountInfo" class="text-gray-200 font-mono flex-1 whitespace-pre-wrap"></pre>
                            <button id="copyAccountBtn" class="ml-4 bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-xl font-semibold">
                                Copy
                            </button>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="amount" id="finalAmountInput">
                            <input type="hidden" name="method_id" id="finalMethodInput">

                            <button type="submit" name="confirm_deposit" class="w-full bg-green-600 hover:bg-green-700 px-6 py-3 rounded-xl font-semibold">
                                I Have Sent The Money
                            </button>
                        </form>

                        <div class="mt-4 flex justify-end">
                            <button onclick="closeAccountModal()" type="button" class="bg-gray-700 px-4 py-2 rounded-xl">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>
        let selectedMethodId = null;
        let selectedAccount = '';
        let depositAmount = 0;

        function openMethodModal() {
            depositAmount = document.getElementById('depositAmount').value;
            if (depositAmount <= 0) {
                alert("Enter a valid amount");
                return;
            }
            document.getElementById('methodModal').classList.remove('hidden');
            document.getElementById('methodModal').classList.add('flex');
        }

        function closeMethodModal() {
            document.getElementById('methodModal').classList.add('hidden');
        }

        function closeAccountModal() {
            document.getElementById('accountModal').classList.add('hidden');
        }

        // Select payment method
        // Select payment method
        document.querySelectorAll('.methodOption').forEach(el => {
            el.addEventListener('click', function() {
                selectedMethodId = this.dataset.id;

                // Parse the JSON string
                let accountJson = this.dataset.account;
                console.log(accountJson)
                let accountObj = {};
                try {
                    accountObj = JSON.parse(accountJson);
                } catch (e) {
                    console.error("Invalid JSON in account details:", accountJson);
                }

                // Format account info nicely
                let accountText = '';
                for (const key in accountObj) {
                    accountText += `${key.charAt(0).toUpperCase() + key.slice(1)}: ${accountObj[key]}\n`;
                }

                // Update account modal
                document.getElementById('accountInfo').innerText = accountText;
                depositAmount = document.getElementById('depositAmount').value;
                document.getElementById('finalAmount').innerText = depositAmount;
                document.getElementById('finalAmountInput').value = depositAmount;
                document.getElementById('finalMethodInput').value = selectedMethodId;

                // Show account modal
                document.getElementById('methodModal').classList.add('hidden');
                document.getElementById('accountModal').classList.remove('hidden');
                document.getElementById('accountModal').classList.add('flex');
            });
        });

        document.getElementById('copyAccountBtn').addEventListener('click', function() {
            const accountText = document.getElementById('accountInfo').innerText;
            navigator.clipboard.writeText(accountText)
                .then(() => {
                    alert('Account info copied to clipboard!');
                })
                .catch(err => {
                    alert('Failed to copy: ' + err);
                });
        });
    </script>

</body>

</html>