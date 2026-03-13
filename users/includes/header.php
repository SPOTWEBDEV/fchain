<?php

$kycStatus = "PENDING REVIEW";

$stmt = $connection->prepare("SELECT status FROM kyc_verification WHERE user_id=? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    if ($row['status'] == "approved") {
        $kycStatus = "APPROVED";
    } elseif ($row['status'] == "rejected") {
        $kycStatus = "REJECTED";
    }
}

?>


<!-- AlpineJS (Add once before closing </body>) -->
<script src="//unpkg.com/alpinejs" defer></script>

<div class="flex items-center justify-between gap-4 mb-10">

    <!-- Wallet Status -->
    <div id="walletStatus"
        class="px-4 py-2 rounded-full text-sm font-medium w-fit">
    </div>



    <!-- User Section -->
    <div class="flex justify-end w-fit items-center space-x-4 relative" x-data="{ open: false }">
        <!-- User Info -->
        <div class="text-right hidden sm:block">
            <p class="font-semibold"><?php echo $fullname  ?></p>
            <p class="text-xs 
                <?php
                if ($kycStatus == 'APPROVED') echo 'text-green-400';
                elseif ($kycStatus == 'REJECTED') echo 'text-red-400';
                else echo 'text-yellow-400';
                ?>
                ">
                <?php echo $kycStatus; ?>
            </p>
        </div>

        <!-- Avatar Button -->
        <?php
        function getInitials($fullname)
        {
            $words = explode(' ', trim($fullname)); // split by space
            if (count($words) === 1) {
                // Only one name → take first letter
                return strtoupper(substr($words[0], 0, 1));
            } else {
                // Multiple words → take first letters of first two words
                return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            }
        }

        // Usage
        $initials = getInitials($fullname);
        ?>
        <div class="w-10 h-10 bg-yellow-400 text-black font-bold rounded-full flex items-center justify-center cursor-pointer hover:scale-105 transition">
            <?php echo $initials; ?>
        </div>



    </div>

</div>


<script>
    const walletStatus = document.getElementById("walletStatus");

    const walletconnect = localStorage.getItem("walletconnect");

    if (walletconnect) {

        walletStatus.className = "bg-green-600/20 text-green-400 px-4 py-2 rounded-full text-sm font-medium w-fit";
        walletStatus.innerText = "● Wallet Connected";

    } else {

        walletStatus.className = "bg-red-600/20 text-red-400 px-4 py-2 rounded-full text-sm font-medium w-fit";
        walletStatus.innerText = "● Wallet Not Connected";

    }
</script>