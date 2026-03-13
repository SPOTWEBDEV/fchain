<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");

$kyc = mysqli_query($connection, "SELECT * FROM kyc_verification WHERE user_id='$id' ORDER BY id DESC LIMIT 1");
$userKyc = mysqli_fetch_assoc($kyc);

if(isset($_POST['submit_kyc'])){

    $first = mysqli_real_escape_string($connection,$_POST['first_name']);
    $last = mysqli_real_escape_string($connection,$_POST['last_name']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = mysqli_real_escape_string($connection,$_POST['email']);
    $phone = mysqli_real_escape_string($connection,$_POST['phone']);
    $address = mysqli_real_escape_string($connection,$_POST['address']);
    $city = mysqli_real_escape_string($connection,$_POST['city']);
    $state = mysqli_real_escape_string($connection,$_POST['state']);
    $country = mysqli_real_escape_string($connection,$_POST['country']);
    $id_type = $_POST['id_type'];
    $id_number = mysqli_real_escape_string($connection,$_POST['id_number']);

    $upload_path = "../../uploads/kyc/";

    /* Upload ID Document */

    $id_file = "";
    if(!empty($_FILES['id_document']['name'])){
        $ext = pathinfo($_FILES['id_document']['name'], PATHINFO_EXTENSION);
        $id_file = "id_".time().rand(1000,9999).".".$ext;
        move_uploaded_file($_FILES['id_document']['tmp_name'], $upload_path.$id_file);
    }

    /* Upload Selfie */

    $selfie_file = "";
    if(!empty($_FILES['selfie_document']['name'])){
        $ext2 = pathinfo($_FILES['selfie_document']['name'], PATHINFO_EXTENSION);
        $selfie_file = "selfie_".time().rand(1000,9999).".".$ext2;
        move_uploaded_file($_FILES['selfie_document']['tmp_name'], $upload_path.$selfie_file);
    }

    $insert = mysqli_query($connection,"
        INSERT INTO kyc_verification
        (user_id,first_name,last_name,dob,gender,email,phone,address,city,state,country,id_type,id_number,id_document,selfie_document)
        VALUES
        ('$id','$first','$last','$dob','$gender','$email','$phone','$address','$city','$state','$country','$id_type','$id_number','$id_file','$selfie_file')
    ");

    if($insert){
        echo "<script>showAlert('KYC submitted successfully','success');
             setTimeout(()=>{window.location.href='./'},2000)
        </script>";
    }else{
        echo "<script>showAlert('".mysqli_error($connection)."','error')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> KYC Page</title>

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


            <div class="max-w-4xl mx-auto">

                <?php if ($userKyc && $userKyc['status'] == "approved") { ?>

                    <div class="bg-green-900/30 border border-green-500 p-8 rounded-xl text-center">
                        <i class="fa-solid fa-circle-check text-green-400 text-4xl mb-4"></i>
                        <h2 class="text-2xl font-bold">KYC Approved</h2>
                        <p class="text-gray-400 mt-2">Your account has been successfully verified.</p>
                    </div>

                <?php } ?>


                <?php if ($userKyc && $userKyc['status'] == "pending") { ?>

                    <div class="bg-yellow-900/30 border border-yellow-500 p-8 rounded-xl text-center">
                        <i class="fa-solid fa-clock text-yellow-400 text-4xl mb-4"></i>
                        <h2 class="text-2xl font-bold">Verification Pending</h2>
                        <p class="text-gray-400 mt-2">Our team is reviewing your KYC documents.</p>
                    </div>

                <?php } ?>


                <?php if ($userKyc && $userKyc['status'] == "declined") { ?>

                    <div class="bg-red-900/30 border border-red-500 p-8 rounded-xl text-center mb-3">
                        <i class="fa-solid fa-circle-xmark text-red-400 text-4xl mb-4"></i>
                        <h2 class="text-2xl font-bold">KYC Declined</h2>

                        <p class="text-gray-400 mt-2">
                            Admin Message: <?php echo ($userKyc['admin_message'] != '')? $userKyc['admin_message'] : "KYC details is falsed"; ?>
                        </p>

                        <p class="text-sm text-gray-500 mt-3">
                            Please correct your information and submit again.
                        </p>

                    </div>

                <?php } ?>

                <?php if (!$userKyc || $userKyc['status'] == "declined") { ?>

                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold">KYC Verification</h1>
                        <p class="text-gray-400 mt-2">Complete your identity verification to unlock full platform access.</p>
                    </div>

                    <!-- Form Card -->
                    <form method="POST"
                        enctype="multipart/form-data" class="bg-[#0f172a] p-4 sm:p-8 rounded-2xl border border-gray-800 shadow-xl space-y-10">

                        <!-- Personal Information -->
                        <div>
                            <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-3">Personal Information</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">First Name</label>
                                    <input name="first_name" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Last Name</label>
                                    <input name="last_name" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Date of Birth</label>
                                    <input name="dob" type="date" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Gender</label>
                                    <select class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none" name="gender">
                                        <option>Select Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <!-- Contact Information -->
                        <div>
                            <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-3">Contact Information</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Email Address</label>
                                    <input type="email" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none" name="email">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Phone Number</label>
                                    <input type="tel" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none" name="phone">
                                </div>

                            </div>
                        </div>


                        <!-- Address Information -->
                        <div>
                            <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-3">Residential Address</h2>

                            <div class="space-y-6">

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Street Address</label>
                                    <input name="address" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">City</label>
                                        <input name="city" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">State</label>
                                        <input name="state" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Country</label>
                                        <input name="country" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    </div>
                                </div>

                            </div>
                        </div>


                        <!-- Identity Verification -->
                        <div>
                            <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-3">Identity Verification</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">ID Type</label>
                                    <select name="id_type" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                        <option>Select ID Type</option>
                                        <option>International Passport</option>
                                        <option>Driver's License</option>
                                        <option>National ID Card</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">ID Number</label>
                                    <input name="id_number" type="text" class="w-full bg-[#111827] border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm text-gray-400 mb-2">Upload Government ID</label>
                                    <input name="id_document" type="file" class="w-full bg-[#111827] border border-dashed border-gray-600 rounded-xl px-4 py-6 text-gray-400">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm text-gray-400 mb-2">Upload Selfie with ID</label>
                                    <input name="selfie_document" type="file" class="w-full bg-[#111827] border border-dashed border-gray-600 rounded-xl px-4 py-6 text-gray-400">
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
                                name="submit_kyc"
                                class="w-full md:w-auto bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-3 rounded-xl font-semibold hover:scale-105 transition">
                                Submit for Verification
                            </button>
                        </div>

                    </form>

                <?php } ?>

            </div>

        </main>
    </div>

</body>

</html>