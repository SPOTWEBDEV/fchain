<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");


$selectPlan = mysqli_query($connection, "SELECT * FROM `investment_plan`");






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php  echo $sitename ?> Dashboard</title>

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


if (isset($_POST['submit_investment'])) {

    $plan_id = $_POST['plan_id'];
    $account_type = $_POST['account_type'];
    $amount = floatval($_POST['amount']); // ensure it's numeric

    // Fetch the plan details
    $getPlan = mysqli_query($connection, "SELECT * FROM investment_plan WHERE id='$plan_id'");
    $plan = mysqli_fetch_assoc($getPlan);

    if (!$plan) {
        echo "<script>showAlert('Invalid Investment Plan','error');</script>";
    } else {

        $roi = $plan['roi'];
        $duration = $plan['duration'];

        // Fetch user balance
        $getUser = mysqli_query($connection, "SELECT mainBalance FROM users WHERE id='$id'");
        $user = mysqli_fetch_assoc($getUser);
        $balance = floatval($user['mainBalance']);

        // Check if user has enough balance
        if ($balance < $amount) {
            echo "<script>showAlert('Insufficient Balance','error');</script>";
        } else {
            // Begin transaction
            mysqli_begin_transaction($connection);

            try {
                // Insert investment
                $insert = mysqli_query($connection, "
                    INSERT INTO investments (user_id, plan_id, account_type, amount, roi, duration, created_at)
                    VALUES ('$id','$plan_id','$account_type','$amount','$roi','$duration', NOW())
                ");

                if (!$insert) {
                    throw new Exception(mysqli_error($connection));
                }

                // Debit user balance
                $newBalance = $balance - $amount;
                $update = mysqli_query($connection, "
                    UPDATE users SET mainBalance='$newBalance' WHERE id='$id'
                ");

                if (!$update) {
                    throw new Exception(mysqli_error($connection));
                }

                // Commit transaction
                mysqli_commit($connection);

                echo "<script>showAlert('Investment Successful','success');</script>";
            } catch (Exception $e) {
                mysqli_rollback($connection);
                echo "<script>showAlert('Transaction Failed: " . $e->getMessage() . "','error');</script>";
            }
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
            <div class=" gap-8">

                <section id="invest" class="py-20 bg-[#0f172a] rounded-2xl border border-[#1e293b]">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 class="text-4xl font-display font-bold text-white mb-4">Investment <span class="text-brand-primary">Plans</span></h2>
                        <p class="text-dark-muted max-w-2xl mx-auto mb-16">Choose a staking plan that fits your financial goals. Earn passive income with daily payouts.</p>



                        <div class="grid md:grid-cols-3 gap-8">

                            <?php while ($plan = mysqli_fetch_assoc($selectPlan)) { ?>

                                <div class="glass-card p-8 border-2 border-white rounded-2xl hover:border-brand-primary transition-colors text-left group relative">
                                    <div class="absolute top-0 right-0 bg-brand-primary text-white text-xs px-3 py-1 rounded-bl-lg rounded-tr-lg font-bold">POPULAR</div>

                                    <h3 class="text-2xl font-bold text-white mb-2">
                                        <?php echo $plan['name']; ?>
                                    </h3>

                                    <p class="text-sm text-dark-muted mb-6">
                                        <?php echo $plan['description']; ?>
                                    </p>

                                    <div class="mb-6">
                                        <span class="text-4xl font-bold text-brand-secondary">
                                            <?php echo $plan['roi']; ?>%
                                        </span>
                                        <span class="text-dark-muted">/ Monthly ROI</span>
                                    </div>

                                    <ul class="space-y-3 mb-8 text-sm text-gray-300">

                                        <li class="flex items-center gap-2">
                                            <i class="fa-solid fa-check text-green-400"></i>
                                            Min Invest: $<?php echo number_format($plan['min_amount']); ?>
                                        </li>

                                        <li class="flex items-center gap-2">
                                            <i class="fa-solid fa-check text-green-400"></i>
                                            Max Invest: $<?php echo number_format($plan['max_amount']); ?>
                                        </li>

                                        <li class="flex items-center gap-2">
                                            <i class="fa-solid fa-check text-green-400"></i>
                                            Duration: <?php echo $plan['duration']; ?> Days
                                        </li>

                                        <li class="flex items-center gap-2">
                                            <i class="fa-solid fa-check text-green-400"></i>
                                            Capital Back: <?php echo $plan['capital_back']; ?>
                                        </li>

                                    </ul>

                                    <button
                                        onclick="openInvestModal(
<?php echo $plan['id']; ?>,
<?php echo $plan['min_amount']; ?>,
<?php echo $plan['max_amount']; ?>
)"
                                        class="block text-center w-full bg-dark-bg border border-dark-border text-white py-3 rounded-lg group-hover:bg-brand-primary transition-all font-bold">
                                        Invest Now
                                    </button>

                                </div>

                            <?php } ?>

                        </div>
                    </div>
                    <div id="investModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 ">

                        <div class="bg-[#0f172a] p-8 rounded-2xl w-full max-w-md border border-[#1e293b]">

                            <h2 class="text-2xl font-bold mb-6">Invest Now</h2>

                            <form method="POST">

                                <input type="hidden" name="plan_id" id="plan_id">

                                <div class="mb-4">
                                    <label class="text-sm">Account Type</label>

                                    <select name="account_type" class="w-full mt-2 p-3 bg-[#020617] border border-gray-700 rounded-lg text-white">

                                        <option value="main_balance">Main Balance</option>
                                        <option value="wallet_balance">Wallet Balance</option>
                                        <option value="card_balance">Card Balance</option>

                                    </select>
                                </div>

                                <div class="mb-6">
                                    <label class="text-sm">Amount</label>

                                    <input
                                        type="number"
                                        name="amount"
                                        class="w-full mt-2 p-3 bg-[#020617] border border-gray-700 rounded-lg text-white"
                                        placeholder="Enter amount"
                                        required>
                                </div>

                                <div class="flex gap-3">

                                    <button type="submit"
                                        name="submit_investment"
                                        class="flex-1 bg-brand-primary py-3 rounded-lg font-bold">
                                        Confirm Investment
                                    </button>

                                    <button type="button"
                                        onclick="closeInvestModal()"
                                        class="flex-1 bg-gray-700 py-3 rounded-lg">
                                        Cancel
                                    </button>

                                </div>

                            </form>

                        </div>
                    </div>
                </section>

            </div>

        </main>
    </div>

    <script>
        function openInvestModal(planId, min, max) {

            document.getElementById('investModal').classList.remove('hidden')
            document.getElementById('investModal').classList.add('flex')
            document.getElementById('plan_id').value = planId

        }

        function closeInvestModal() {

            document.getElementById('investModal').classList.add('hidden')

        }
    </script>

</body>

</html>