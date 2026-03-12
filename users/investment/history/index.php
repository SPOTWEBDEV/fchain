<?php
include("../../../server/connection.php");
include("../../../server/auth/client.php");

// Fetch user investments with plan details
$investmentQuery = mysqli_query($connection, "
    SELECT 
        i.id as investment_id,
        i.amount,
        i.roi,
        i.duration,
        i.status,
        i.created_at as invested_at,
        p.name as plan_name,
        p.description as plan_description
    FROM investments i
    JOIN investment_plan p ON i.plan_id = p.id
    WHERE i.user_id='$id'
    ORDER BY i.created_at DESC
");
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
        <?php include("../../includes/sidenav.php"); ?>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">
            <?php include("../../includes/header.php"); ?>



            <div class="max-w-5xl mx-auto mt-6">
                <div class="bg-[#0f172a] p-6 rounded-2xl border border-gray-800 shadow-lg">
                    <h2 class="text-3xl font-bold mb-4">Investment History</h2>

                    <?php if (mysqli_num_rows($investmentQuery) > 0) { ?>
                        <table class="w-full table-auto text-sm text-white bg-[#111827] rounded-xl border border-gray-700 overflow-hidden">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-4 py-2">#</th>
                                    <th class="px-4 py-2">Plan</th>
                                    <th class="px-4 py-2">Amount ($)</th>
                                    <th class="px-4 py-2">ROI (%)</th>
                                    <th class="px-4 py-2">Duration (Days)</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Invested At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 0;
                                while ($inv = mysqli_fetch_assoc($investmentQuery)) {
                                    $count++; ?>
                                    <tr class="border-t border-gray-700 text-center">
                                        <td class="px-4 py-2"><?php echo $count; ?></td>
                                        <td class="px-4 py-2"><?php echo $inv['plan_name']; ?></td>
                                        <td class="px-4 py-2"><?php echo number_format($inv['amount'], 2); ?></td>
                                        <td class="px-4 py-2"><?php echo $inv['roi']; ?></td>
                                        <td class="px-4 py-2"><?php echo $inv['duration']; ?></td>
                                        <td class="px-4 py-2">
                                            <?php
                                            $status = strtolower($inv['status']);
                                            $statusColor = "bg-gray-500"; // default

                                            if ($status == 'pending') $statusColor = "bg-yellow-500";
                                            else if ($status == 'active' || $status == 'running') $statusColor = "bg-blue-600";
                                            else if ($status == 'completed' || $status == 'closed') $statusColor = "bg-green-600";
                                            else if ($status == 'failed') $statusColor = "bg-red-600";
                                            ?>
                                            <span class="px-3 py-1 rounded-full text-white font-bold text-sm <?php echo $statusColor; ?>">
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2"><?php echo date("d M Y H:i", strtotime($inv['invested_at'])); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p class="text-gray-400 text-center mt-4">You have no investment history yet.</p>
                    <?php } ?>
                </div>
            </div>

         

        </main>
    </div>

</body>

</html>