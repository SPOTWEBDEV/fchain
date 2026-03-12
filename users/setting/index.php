<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");

/* =========================
Fetch user data
=========================*/
$userQuery = mysqli_query($connection,"SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($userQuery);

/* =========================
Handle form submissions
=========================*/

/* -------------------------
Update Profile
-------------------------*/
if(isset($_POST['update_profile'])){
    $fullname = mysqli_real_escape_string($connection,$_POST['fullname']);
    $email = mysqli_real_escape_string($connection,$_POST['email']);
    $phone = mysqli_real_escape_string($connection,$_POST['phone']);

    $update = mysqli_query($connection,"
        UPDATE users SET name='$fullname', email='$email', phone='$phone' WHERE id='$id'
    ");

    if($update){
        $user['name'] = $fullname;
        $user['email'] = $email;
        $user['phone'] = $phone;
        echo "<script>
            showAlert('Profile updated successfully','success');
        </script>";
    } else {
        $err = addslashes(mysqli_error($connection));
        echo "<script>
            showAlert('Error: $err','error');
        </script>";
    }
}

/* -------------------------
Change Password
-------------------------*/
if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if(!password_verify($current,$user['password'])){
        echo "<script>
            showAlert('Current password is incorrect','error');
        </script>";
    } elseif($new != $confirm){
        echo "<script>
            showAlert('Passwords do not match','error');
        </script>";
    } else {
        $newpass = password_hash($new,PASSWORD_DEFAULT);
        $update = mysqli_query($connection,"UPDATE users SET password='$newpass' WHERE id='$id'");
        if($update){
            echo "<script>
                showAlert('Password updated successfully','success');
            </script>";
        } else {
            $err = addslashes(mysqli_error($connection));
            echo "<script>
                showAlert('Error: $err','error');
            </script>";
        }
    }
}

/* -------------------------
Change Transaction PIN
-------------------------*/
if(isset($_POST['change_pin'])){
    $current = $_POST['current_pin'];
    $new = $_POST['new_pin'];
    $confirm = $_POST['confirm_pin'];

    if($current != $user['pin']){
        echo "<script>
            showAlert('Current PIN incorrect','error');
        </script>";
    } elseif($new != $confirm){
        echo "<script>
            showAlert('PIN does not match','error');
        </script>";
    } elseif(strlen($new) != 4){
        echo "<script>
            showAlert('PIN must be 4 digits','error');
        </script>";
    } else {
        $update = mysqli_query($connection,"UPDATE users SET pin='$new' WHERE id='$id'");
        if($update){
            echo "<script>
                showAlert('PIN updated successfully','success');
            </script>";
        } else {
            $err = addslashes(mysqli_error($connection));
            echo "<script>
                showAlert('Error: $err','error');
            </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php  echo $sitename ?> Dashboard</title>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-[#060b1f] via-[#050a25] to-[#020617] text-white">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php include("../includes/sidenav.php"); ?>

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-10">

        <!-- Top Bar -->
        <?php include("../includes/header.php"); ?>

        <div class="max-w-6xl mx-auto space-y-10">

            <!-- Page Header -->
            <div>
                <h1 class="text-3xl font-bold">Account Settings</h1>
                <p class="text-gray-400 mt-2">Manage your profile, security and preferences.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Profile Information -->
                    <form method="POST" class="bg-[#0f172a] border border-gray-800 rounded-2xl p-8 shadow-lg space-y-6">
                        <h2 class="text-xl font-semibold border-b border-gray-700 pb-4">Profile Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm text-gray-400">Full Name</label>
                                <input type="text" name="fullname" value="<?php echo $user['name']; ?>"
                                    class="w-full mt-2 bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="text-sm text-gray-400">Email Address</label>
                                <input type="email" name="email" value="<?php echo $user['email']; ?>"
                                    class="w-full mt-2 bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="text-sm text-gray-400">Phone Number</label>
                                <input type="tel" name="phone" value="<?php echo $user['phone']; ?>"
                                    class="w-full mt-2 bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>
                        </div>
                        <button name="update_profile" class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-3 rounded-xl font-semibold hover:scale-105 transition">Save Changes</button>
                    </form>

                    <!-- Change Password -->
                    <form method="POST" class="bg-[#0f172a] border border-gray-800 rounded-2xl p-8 shadow-lg space-y-6">
                        <h2 class="text-xl font-semibold border-b border-gray-700 pb-4">Change Password</h2>
                        <input type="password" name="current_password" placeholder="Current Password" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <input type="password" name="new_password" placeholder="New Password" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <input type="password" name="confirm_password" placeholder="Confirm New Password" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <button type="submit" name="change_password" class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-3 rounded-xl font-semibold hover:scale-105 transition">Update Password</button>
                    </form>

                    <!-- Change PIN -->
                    <form method="POST" class="bg-[#0f172a] border border-gray-800 rounded-2xl p-8 shadow-lg space-y-6">
                        <h2 class="text-xl font-semibold border-b border-gray-700 pb-4">Change Transaction PIN</h2>
                        <input type="password" name="current_pin" placeholder="Current PIN " class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <input type="password" name="new_pin" placeholder="New 4-digit PIN" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <input type="password" name="confirm_pin" placeholder="Confirm New PIN" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <button type="submit" name="change_pin" class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-3 rounded-xl font-semibold hover:scale-105 transition">Update PIN</button>
                    </form>

                </div>

                <!-- Right Column -->
                <div class="space-y-8">

                    <!-- Security Settings -->
                    <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Security Settings</h3>
                        <div class="flex justify-between items-center mb-4">
                            <span>Two-Factor Authentication (2FA)</span>
                            <input type="checkbox" class="accent-purple-600 w-5 h-5">
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Email Login Alerts</span>
                            <input type="checkbox" checked class="accent-purple-600 w-5 h-5">
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Notifications</h3>
                        <div class="space-y-3 text-sm">
                            <label class="flex items-center justify-between">
                                <span>Deposit Alerts</span>
                                <input type="checkbox" checked class="accent-purple-600">
                            </label>
                            <label class="flex items-center justify-between">
                                <span>Withdrawal Alerts</span>
                                <input type="checkbox" checked class="accent-purple-600">
                            </label>
                            <label class="flex items-center justify-between">
                                <span>Promotional Emails</span>
                                <input type="checkbox" class="accent-purple-600">
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </main>
</div>
</body>
</html>