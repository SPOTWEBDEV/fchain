<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../includes/modal.php");





// Simulate last portfolio for dynamic change percentage
$lastTotal = rand(max(0, $balance - 50), $balance + 50);
$change = $balance - $lastTotal;
$changePercent = ($lastTotal > 0) ? ($change / $lastTotal) * 100 : 0;
$changeSign = ($change >= 0) ? '+' : '';
$changeColor = ($change >= 0) ? 'text-green-400' : 'text-red-400';


// Fetch user's wallet
$userQuery = mysqli_query($connection, "SELECT wallet FROM users WHERE id='$id' LIMIT 1");
$userData = mysqli_fetch_assoc($userQuery);
$wallets = json_decode($userData['wallet'], true); // e.g. ["btc"=>110,"eth"=>20,"sol"=>50]


$cardQuery = mysqli_query($connection, "SELECT * FROM card WHERE user='$id' AND is_active='active' ORDER BY id DESC LIMIT 1");
$card = mysqli_fetch_assoc($cardQuery);

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
    <main class="flex-1 p-6 lg:p-10">

      <!-- Top Bar -->
      <?php include("../includes/header.php"); ?>


      <!-- Dashboard Grid -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <!-- Portfolio Section -->
        <div class="xl:col-span-2 space-y-8">



          <div class="bg-[#0f172a] p-8 rounded-2xl border border-gray-800 shadow-lg">

            <!-- Portfolio Value -->
            <p class="text-gray-400 mb-2">User Balance</p>
            <h2 class="text-5xl font-bold">$<?php echo number_format($balance, 2); ?></h2>

            <!-- Portfolio Change -->
            <div class="flex items-center space-x-2 <?php echo $changeColor; ?> mt-3">
              <i class="fa-solid fa-chart-line"></i>
              <span><?php echo $changeSign . number_format($changePercent, 2); ?>%</span>
              <span class="text-gray-500">(Real-time)</span>
            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-4 mt-8">
              <a href="../deposit/">
                <button class="bg-purple-600 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:scale-105 transition flex items-center gap-2">
                <i class="fa-solid fa-wallet"></i>
                Deposit
              </button>
              </a>
            

              <a href="../withdrawal/">
                <button class="bg-red-600 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:scale-105 transition flex items-center gap-2">
                <i class="fa-solid fa-money-bill-transfer"></i>
                Withdrawal
              </button>
              </a>

              <a href="../trading/">
                <button class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:scale-105 transition flex items-center gap-2">
                <i class="fa-solid fa-chart-simple"></i>
                Trading
              </button>
              </a>
            </div>

          </div>

          <!-- Market Performance -->
          <div class="bg-[#0f172a] p-8 rounded-2xl border border-gray-800 shadow-lg">

            <div class="flex justify-between items-center mb-6">
              <h3 class="text-xl font-semibold">Market Performance</h3>
              <div class="flex space-x-2">
                <button class="px-3 py-1 bg-gray-700 rounded-lg text-sm">1D</button>
                <button class="px-3 py-1 bg-purple-600 rounded-lg text-sm">1W</button>
                <button class="px-3 py-1 bg-gray-700 rounded-lg text-sm">1M</button>
              </div>
            </div>

            <!-- Simple Graph -->
            <div class="h-48 bg-gradient-to-r from-purple-600/30 to-indigo-600/30 rounded-xl flex items-end justify-center">
              <p class="text-gray-400">Chart Placeholder</p>
            </div>

          </div>

        </div>


        <!-- Right Column -->
        <div class="space-y-8">

          <!-- Virtual Card -->
          <?php if ($card): ?>
            <a href="../virtual-card/">
              <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-6 rounded-2xl shadow-lg">
              <p class="text-sm  text-white"><?php echo $sitename; ?></p>
              <p class="text-xs uppercase tracking-wide opacity-60 mb-6">Virtual Mastercard</p>

              <div class="w-12 h-8 bg-yellow-400 rounded-md mb-6"></div>

              <p class="tracking-widest mb-4">
                <?php
                // Insert a space every 4 digits
                echo chunk_split($card['card_number'], 4, ' ');
                ?>
              </p>

              <div class="flex justify-between text-sm">
                <div>
                  <p class="opacity-70">Cardholder</p>
                  <p class="font-semibold"><?php echo $fullname; ?></p>
                </div>
                <div>
                  <p class="opacity-70">Expires</p>
                  <p class="font-semibold"><?php echo $card['expires']; ?></p>
                </div>
              </div>
            </div>
            </a>
          <?php endif; ?>

          <!-- Total Assets -->
          <div class="bg-[#0f172a] p-6 rounded-2xl border border-gray-800 shadow-lg">

            <h3 class="text-lg font-semibold mb-6">Total Assets</h3>

            <div class="space-y-6">
              <?php foreach ($wallets as $coin => $balance): ?>
                <div class="flex justify-between items-center bg-[#111827] p-4 rounded-xl border border-gray-700">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-full flex items-center justify-center font-bold text-white">
                      <?php
                      // Coin symbol or first letter
                      echo strtoupper($coin[0]);
                      ?>
                    </div>
                    <div>
                      <p class="font-semibold"><?php echo ucfirst($coin); ?></p>
                      <p class="text-gray-400 text-sm"><?php echo strtoupper($coin); ?></p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p>$<?php echo number_format($balance, 2); ?></p>
                    <p class="text-green-400 text-sm">Balance</p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

          </div>

        </div>

      </div>

    </main>
  </div>

</body>

</html>