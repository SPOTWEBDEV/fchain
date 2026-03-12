<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");


$query = mysqli_query($connection, "SELECT * FROM card WHERE user='$id' AND is_active='active' LIMIT 1");
$cardDetail = mysqli_fetch_assoc($query);



if (isset($_POST['apply_card'])) {

    $amount = mysqli_real_escape_string($connection, $_POST['amount']);
    $pin = mysqli_real_escape_string($connection, $_POST['pin']);
    $card_type = mysqli_real_escape_string($connection, $_POST['card_type']);

    // Encrypt PIN
    $card_pin = password_hash($pin, PASSWORD_DEFAULT);

    // Generate 16 digit card number
    function generateCardNumber()
    {
        return mt_rand(4000, 4999) . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999);
    }

    // Generate CVV
    function generateCVV()
    {
        return mt_rand(100, 999);
    }

    $card_number = generateCardNumber();
    $card_cvv = generateCVV();

    // Expiry Date (4 years)
    $expires = date("m/y", strtotime("+4 years"));

    // Default card status
    $is_active = 1;

    // Insert card
    $insert = mysqli_query($connection, "
        INSERT INTO card 
        (user, balance, expires, card_number, card_cvv, card_type, card_pin, is_active)
        VALUES
        ('$id', '$amount', '$expires', '$card_number', '$card_cvv', '$card_type', '$card_pin', '$is_active')
    ");

    if ($insert) {
        echo "<script>showAlert('Card Created Successfully','success');
             setTimeout(()=>{window.location.href='./'},2000)
        </script>";
    } else {
        echo "<script>showAlert('Error Creating Card','error');
        </script>";
    }
}



/* =========================
   CARD ACTIONS
========================= */

if (isset($_POST['fund_card'])) {

    $amount = floatval($_POST['amount']);

    if ($amount > 0) {

        mysqli_query($connection, "
        UPDATE card 
        SET balance = balance + '$amount'
        WHERE user='$id'
        ");

        echo "<script>showAlert('Card funded successfully','success');
        setTimeout(()=>{window.location.href='./'},1500)</script>";
    }
}


if (isset($_POST['withdraw_card'])) {

    $amount = floatval($_POST['amount']);

    $check = mysqli_query($connection, "SELECT balance FROM card WHERE user='$id'");
    $card = mysqli_fetch_assoc($check);

    if ($amount > $card['balance']) {
        echo "<script>showAlert('Insufficient balance','error')</script>";
    } else {

        mysqli_query($connection, "
        UPDATE card 
        SET balance = balance - '$amount'
        WHERE user='$id'
        ");

        echo "<script>showAlert('Withdrawal successful','success');
        setTimeout(()=>{window.location.href='./'},1500)</script>";
    }
}



if (isset($_POST['freeze_card'])) {

    $query =  mysqli_query($connection, "
    UPDATE card 
    SET is_active = 'freeze'
    WHERE user='$id'
    ");

    if ($query) {
        echo "<script>showAlert('Card frozen','success');
    setTimeout(()=>{window.location.href='./'},1500)</script>";
    } else {
        echo "<script>showAlert('Something went wrong when frozen card ','error');
    setTimeout(()=>{window.location.href='./'},1500)</script>";
    }
}



if (isset($_POST['unfreeze_card'])) {

    $query =  mysqli_query($connection, "
    UPDATE card 
    SET is_active = 'active'
    WHERE user='$id'
    ");

    if ($query) {
        echo "<script>showAlert('Card unfrozen','success');
    setTimeout(()=>{window.location.href='./'},1500)</script>";
    } else {
        echo "<script>showAlert('Something went wrong when unfrozen card ','error');
    setTimeout(()=>{window.location.href='./'},1500)</script>";
    }
}



if (isset($_POST['delete_card'])) {

    mysqli_query($connection, "
    UPDATE card 
    SET is_active = 'delete'
    WHERE user='$id'
    ");

    echo "<script>showAlert('Card deleted','success');
    setTimeout(()=>{window.location='./'},1500)</script>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> Pro Dashboard</title>

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
        <main class="flex-1 p-2 sm:p-6 lg:p-10">

            <!-- Top Bar -->
            <?php include("../includes/header.php"); ?>


            <section id="cards" class="py-20 bg-dark-panel rounded-2xl border-y border-dark-border overflow-hidden">
                <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">


                    <?php

                    if (mysqli_num_rows($query) <= 0  &&  !isset($_GET['apply'])) { ?>

                        <div class="grid md:grid-cols-2 gap-16 items-center">

                            <div class="relative flex justify-center perspective-1000">
                                <div class="absolute inset-0 bg-brand-primary blur-[80px] opacity-20"></div>

                                <div class="w-full max-w-[380px] aspect-[1.586/1] rounded-2xl credit-card-bg relative p-6 text-white shadow-neon transform rotate-3 hover:rotate-0 transition-transform duration-500 z-10 animate-float flex flex-col justify-between border border-white/20 h-auto">
                                    <div class="flex justify-between items-start">
                                        <i class="fa-solid fa-wifi text-2xl opacity-80"></i>
                                        <span class="font-display font-bold text-xl italic"><?php echo $sitename ?> Pro</span>
                                    </div>
                                    <div class="my-2 md:my-4">
                                        <i class="fa-solid fa-microchip text-4xl text-yellow-300 opacity-80"></i>
                                    </div>
                                    <div>
                                        <p class="font-mono text-lg md:text-xl tracking-widest mb-2 md:mb-4 drop-shadow-md">**** **** **** 4289</p>
                                        <div class="flex justify-between items-end">
                                            <div>
                                                <p class="text-[10px] md:text-xs opacity-70 uppercase">Card Holder</p>
                                                <p class="font-bold tracking-wide text-sm md:text-base">ALEXANDER DOE</p>
                                            </div>
                                            <div class="flex flex-col items-end">
                                                <p class="text-[10px] md:text-xs opacity-70 uppercase">Expires</p>
                                                <p class="font-bold text-sm md:text-base">12/28</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="inline-flex items-center gap-2 text-brand-secondary font-bold mb-4">
                                    <i class="fa-regular fa-credit-card"></i> SPEND ANYWHERE
                                </div>
                                <h2 class="text-4xl font-display font-bold text-white mb-6">The <span class="text-brand-primary">Virtual Card</span> for the DeFi Era.</h2>
                                <p class="text-dark-muted text-lg mb-8">
                                    Instantly issue a virtual Visa/Mastercard funded by your crypto balance. Shop online, pay for subscriptions, and withdraw cash globally.
                                </p>

                                <div class="grid grid-cols-2 gap-6 mb-8">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded bg-dark-bg border border-dark-border flex items-center justify-center text-brand-primary"><i class="fa-brands fa-apple"></i></div>
                                        <div>
                                            <h4 class="font-bold text-white">Apple Pay</h4>
                                            <p class="text-xs text-dark-muted">Instant provisioning</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded bg-dark-bg border border-dark-border flex items-center justify-center text-brand-primary"><i class="fa-brands fa-google-pay"></i></div>
                                        <div>
                                            <h4 class="font-bold text-white">Google Pay</h4>
                                            <p class="text-xs text-dark-muted">Tap to pay anywhere</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded bg-dark-bg border border-dark-border flex items-center justify-center text-brand-primary"><i class="fa-solid fa-percent"></i></div>
                                        <div>
                                            <h4 class="font-bold text-white">3% Cashback</h4>
                                            <p class="text-xs text-dark-muted">On all crypto spends</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded bg-dark-bg border border-dark-border flex items-center justify-center text-brand-primary"><i class="fa-solid fa-globe"></i></div>
                                        <div>
                                            <h4 class="font-bold text-white">No FX Fees</h4>
                                            <p class="text-xs text-dark-muted">Perfect for travel</p>
                                        </div>
                                    </div>
                                </div>

                                <a href="?apply=1"
                                    class="bg-brand-primary hover:bg-brand-accent text-white px-8 py-3 rounded-lg font-bold shadow-neon">
                                    Get Your Card
                                </a>
                            </div>

                        </div>

                    <?php } else {
                    }


                    ?>



                    <?php if (isset($_GET['apply']) && mysqli_num_rows($query) == 0) { ?>

                        <!-- Form Card => to apply for card -->
                        <form method="POST"
                            enctype="multipart/form-data" class="bg-[#0f172a] p-8 rounded-2xl border border-gray-800 shadow-xl space-y-10">

                            <!-- Personal Information -->
                            <div>
                                <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-3">Card Detail</h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Funding Amount</label>
                                        <input name="amount" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Card Pin</label>
                                        <input name="pin" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    </div>



                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Card Type</label>
                                        <select name="card_type" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3">
                                            <option value="">Select Card Type</option>
                                            <option value="Visa">Visa</option>
                                            <option value="Mastercard">Mastercard</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <!-- Declaration -->
                            <div class="space-y-4">
                                <label class="flex items-start space-x-3">
                                    <input type="checkbox" class="mt-1 accent-purple-600">
                                    <span class="text-gray-400 text-sm">
                                        I confirm that the information provided is accurate and I agree to the platform's Terms and AML policies.
                                    </span>
                                </label>
                            </div>


                            <!-- Submit -->
                            <div class="pt-6">
                                <button type="submit"
                                    name="apply_card"
                                    class="w-full md:w-auto bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-3 rounded-xl font-semibold hover:scale-105 transition">
                                    Submit for Verification
                                </button>
                            </div>

                        </form>

                    <?php } ?>





                    <?php if (mysqli_num_rows($query) > 0) { ?>

                        <div class="max-w-5xl mx-auto">

                            <!-- Card -->
                            <div class="flex justify-center mb-10">

                                <div class="w-full max-w-[420px] h-[230px] rounded-2xl p-6 text-white relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 shadow-2xl">

                                    <!-- glow -->
                                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>

                                    <div class="flex justify-between items-start">

                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-credit-card text-lg"></i>
                                            <span class="font-semibold tracking-wide">
                                                <?= $sitename . ' ' . $cardDetail['card_type'] ?>
                                            </span>
                                        </div>

                                        <i class="fa-solid fa-wifi rotate-90 text-xl opacity-80"></i>

                                    </div>


                                    <div class="mt-6">
                                        <i class="fa-solid fa-microchip text-4xl text-yellow-300"></i>
                                    </div>


                                    <div class="mt-6">

                                        <p class="font-mono text-2xl tracking-widest mb-4">
                                            <?= chunk_split($cardDetail['card_number'], 4, ' ') ?>
                                        </p>

                                        <div class="flex justify-between items-end">

                                            <div>
                                                <p class="text-xs opacity-70 uppercase">Card Holder</p>
                                                <p class="font-bold tracking-wider"><?= strtoupper($fullname) ?></p>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-xs opacity-70 uppercase">Expires</p>
                                                <p class="font-bold"><?= $cardDetail['expires'] ?></p>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="flex flex-wrap justify-center gap-4">

                                <button onclick="openModal('fundModal')"
                                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 px-5 py-3 rounded-xl font-semibold shadow-lg">
                                    <i class="fa-solid fa-wallet"></i> Fund Card
                                </button>

                                <button onclick="openModal('withdrawModal')"
                                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl font-semibold shadow-lg">
                                    <i class="fa-solid fa-money-bill-transfer"></i> Withdraw
                                </button>

                                <?php if ($cardDetail['is_active'] == 'active') { ?>

                                    <form method="POST">
                                        <button name="freeze_card"
                                            type="submit"
                                            class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 px-5 py-3 rounded-xl font-semibold shadow-lg">
                                            <i class="fa-solid fa-snowflake"></i> Freeze Card
                                        </button>
                                    </form>

                                <?php } else { ?>

                                    <form method="POST">
                                        <button name="unfreeze_card"
                                            type="submit"
                                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 px-5 py-3 rounded-xl font-semibold shadow-lg">
                                            <i class="fa-solid fa-unlock"></i> Unfreeze Card
                                        </button>
                                    </form>

                                <?php } ?>

                                <button onclick="openModal('deleteModal')"
                                    class="flex items-center gap-2 bg-red-600 hover:bg-red-700 px-5 py-3 rounded-xl font-semibold shadow-lg">
                                    <i class="fa-solid fa-trash"></i> Delete Card
                                </button>

                            </div>

                        </div>


                        <div id="fundModal" onclick="outsideClose(event,'fundModal')" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

                            <div class="bg-[#0f172a] p-8 rounded-xl w-96 relative">

                                <!-- Close Icon -->
                                <button onclick="closeModal('fundModal')" class="absolute top-3 right-4 text-gray-400 hover:text-white">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>

                                <h2 class="text-xl font-bold mb-6">Fund Card</h2>

                                <form method="POST" class="space-y-4">

                                    <input type="number" name="amount" placeholder="Enter amount"
                                        class="w-full p-3 rounded bg-gray-800 border border-gray-700">

                                    <div class="flex gap-3">

                                        <button name="fund_card"
                                            class="flex-1 bg-green-600 hover:bg-green-700 py-3 rounded-lg">
                                            Fund Now
                                        </button>

                                        <button type="button"
                                            onclick="closeModal('fundModal')"
                                            class="flex-1 bg-gray-700 hover:bg-gray-600 py-3 rounded-lg">
                                            Cancel
                                        </button>

                                    </div>

                                </form>

                            </div>
                        </div>

                        <div id="withdrawModal" onclick="outsideClose(event,'withdrawModal')" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

                            <div class="bg-[#0f172a] p-8 rounded-xl w-96 relative">

                                <button onclick="closeModal('withdrawModal')" class="absolute top-3 right-4 text-gray-400 hover:text-white">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>

                                <h2 class="text-xl font-bold mb-6">Withdraw</h2>

                                <form method="POST" class="space-y-4">

                                    <input type="number" name="amount" placeholder="Enter amount"
                                        class="w-full p-3 rounded bg-gray-800 border border-gray-700">

                                    <div class="flex gap-3">

                                        <button name="withdraw_card"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 py-3 rounded-lg">
                                            Withdraw
                                        </button>

                                        <button type="button"
                                            onclick="closeModal('withdrawModal')"
                                            class="flex-1 bg-gray-700 hover:bg-gray-600 py-3 rounded-lg">
                                            Cancel
                                        </button>

                                    </div>

                                </form>

                            </div>
                        </div>



                        <div id="deleteModal" onclick="outsideClose(event,'deleteModal')" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

                            <div class="bg-[#0f172a] p-8 rounded-xl w-96 text-center relative">

                                <button onclick="closeModal('deleteModal')" class="absolute top-3 right-4 text-gray-400 hover:text-white">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>

                                <h2 class="text-xl font-bold mb-4">Delete Card</h2>

                                <p class="text-gray-400 mb-6">
                                    Do you really want to delete this card?
                                </p>

                                <form method="POST" class="flex gap-3 justify-center">

                                    <button name="delete_card"
                                        class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg">
                                        Delete
                                    </button>

                                    <button type="button"
                                        onclick="closeModal('deleteModal')"
                                        class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg">
                                        Cancel
                                    </button>

                                </form>

                            </div>
                        </div>
                    <?php } ?>



            </section>

        </main>
    </div>


    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove("hidden")
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden")
        }
    </script>

</body>

</html>