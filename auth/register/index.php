<?php
include('../../server/connection.php');




?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | <?php  echo $sitename ?></title>
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
    </style>
</head>

<body class="flex min-h-screen bg-slate-50 dark:bg-[#02040a] text-slate-800 dark:text-slate-300 items-center justify-center p-4 relative">

    <?php

    function toast($type, $msg)
    {
        echo "<script>
            window.onload = function(){
                toastr.$type('$msg');
            }
          </script>";
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {

        $name     = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Optional fields (if you later add them)
        $username = $name;
        $phone = "";

        $referral_code = $_POST['referral_code'] ?? '';



        if (empty($name) || empty($email) || empty($password)) {
            toast("error", "All fields are required.");
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            toast("error", "Invalid email format.");
            return;
        }



        $check = $connection->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            toast("error", "Email already registered.");
            $check->close();
            return;
        }
        $check->close();


        $wallet = json_encode([
            "eth" => 0,
            "btc" => 0,
            "sol" => 0
        ]);



        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $connection->prepare(
            "INSERT INTO users (name, email, password , wallet)
         VALUES (?, ?, ? ,?)"
        );

        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $hashedPassword,
            $wallet
        );

        if ($stmt->execute()) {

            toast("success", "Account created successfully!");

            echo "<script>
            setTimeout(()=>{
                window.location.href='../login';
            },2000);
        </script>";
        } else {
            toast("error", "Something went wrong. Please try again.");
        }

        $stmt->close();
    }



    ?>

    <div class="fixed -top-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="fixed top-1/2 -right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="glass-panel w-full max-w-md rounded-3xl p-8 md:p-10 relative z-10 my-10">

        <div class="text-center mb-8">
            <div class="flex items-center justify-center space-x-3 mb-10">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                    <i style="color: white;" class="fas fa-shop"></i>
                </div>
                <h1 class="text-xl font-semibold"><?php echo $sitename ?></h1>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Account</h1>

        </div>

        <form method="POST">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Full Name</label>
                    <div class="relative">
                        <input type="text" name="fullname" placeholder="John Doe" required class="w-full bg-slate-50 dark:bg-[#0B0F19] text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pl-10 focus:outline-none focus:border-indigo-500 transition-all">
                        <i class="fa-regular fa-id-card absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" placeholder="name@example.com" required class="w-full bg-slate-50 dark:bg-[#0B0F19] text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pl-10 focus:outline-none focus:border-indigo-500 transition-all">
                        <i class="fa-regular fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" placeholder="••••••••" required class="w-full bg-slate-50 dark:bg-[#0B0F19] text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pl-10 focus:outline-none focus:border-indigo-500 transition-all">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-3 mb-6">
                <input type="checkbox" required class="mt-1 w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300 dark:border-white/10 dark:bg-[#0B0F19]">
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    I agree to the <a href="#" class="text-indigo-500 hover:underline">Terms of Service</a> and <a href="#" class="text-indigo-500 hover:underline">Privacy Policy</a>.
                </p>
            </div>

            <button type="submit" name="register" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold shadow-lg hover:opacity-90 transition-all hover:scale-[1.02]">
                Create Account
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
            Already have an account? <a href="../login" class="text-indigo-500 font-bold hover:underline">Log in</a>
        </p>
    </div>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000"
        };
    </script>



</body>

</html>