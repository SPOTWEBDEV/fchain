
<style>
  
</style>


<!-- Hamburger Button (fixed at top-left) -->
<div class="md:hidden fixed top-4 left-4 z-50" x-data="{ open: false }">
  <button @click="open = !open" class="p-2 text-gray-400 hover:text-white bg-[#0b1120] rounded">
    <i class="fas fa-bars text-xl"></i>
  </button>

  <!-- Sidebar -->
  <aside
    x-show="open || window.innerWidth >= 768"
    @click.away="open = false"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-[#0b1120] border-r border-gray-800 py-2  transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out"
    :class="{ 'translate-x-0': open, '-translate-x-full': !open }">

    <div class="h-full flex flex-col overflow-y-auto p-2 ">

      <!-- Sidenav content -->
      <div class="flex items-center space-x-3 mb-10">
        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
          <i class="fas fa-shop"></i>
        </div>
        <h1 class="text-xl font-semibold"><?php echo $sitename ?></h1>
      </div>

      <!-- Rest of nav items -->
      <!-- ... your nav links ... -->
      <p class="text-gray-400 text-sm mb-4">PLATFORM</p>

      <nav class="space-y-2">

        <a href="<?php echo $domain ?>users/dashboard" class="flex items-center space-x-3 bg-gray-700/40 p-3 rounded-xl">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </a>

        <a href="<?php echo $domain ?>users/exchange" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-coins"></i>
          <span>Exchange</span>
        </a>

        <a href="<?php echo $domain ?>users/investment" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-chart-pie"></i>
          <span>Investments</span>
        </a>

        <a href="<?php echo $domain ?>users/investment/history" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-clock-rotate-left"></i>
          <span>Investment History</span>
        </a>

        <a href="<?php echo $domain ?>users/trading" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-robot"></i>
          <span>Auto Trading</span>
        </a>

        <a href="<?php echo $domain ?>users/sport-betting" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-futbol"></i>
          <span>Sport Betting</span>
        </a>

      </nav>


      <p class="text-gray-400 text-sm mt-8 mb-4">BANKING</p>

      <nav class="space-y-2">

        <a href="<?php echo $domain ?>users/deposit" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-arrow-down"></i>
          <span>Deposit</span>
        </a>

        <a href="<?php echo $domain ?>users/deposit/history" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-receipt"></i>
          <span>Deposit History</span>
        </a>

        <a href="<?php echo $domain ?>users/withdrawal" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-arrow-up"></i>
          <span>Withdrawal</span>
        </a>

        <a href="<?php echo $domain ?>users/virtual-card" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-credit-card"></i>
          <span>Virtual Cards</span>
        </a>

        <a href="<?php echo $domain ?>users/kyc" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-user-shield"></i>
          <span>KYC Verification</span>
        </a>

        <a href="<?php echo $domain ?>users/setting" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-cog"></i>
          <span>Setting</span>
        </a>

      </nav>

    </div>




  </aside>
</div>


<div class="hidden md:block " >
 

  <!-- Sidebar -->
  <aside
    class="inset-y-0 left-0 z-40 w-64 bg-[#0b1120] border-r border-gray-800 py-2  transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out"
    :class="{ 'translate-x-0': open, '-translate-x-full': !open }">

    <div class="h-full flex flex-col overflow-y-auto p-2 ">

      <!-- Sidenav content -->
      <div class="flex items-center space-x-3 mb-10">
        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
          <i class="fas fa-shop"></i>
        </div>
        <h1 class="text-xl font-semibold"><?php echo $sitename ?></h1>
      </div>

      <!-- Rest of nav items -->
      <!-- ... your nav links ... -->
      <p class="text-gray-400 text-sm mb-4">PLATFORM</p>

      <nav class="space-y-2">

        <a href="<?php echo $domain ?>users/dashboard" class="flex items-center space-x-3 bg-gray-700/40 p-3 rounded-xl">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </a>

        <a href="<?php echo $domain ?>users/exchange" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-coins"></i>
          <span>Exchange</span>
        </a>

        <a href="<?php echo $domain ?>users/investment" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-chart-pie"></i>
          <span>Investments</span>
        </a>

        <a href="<?php echo $domain ?>users/investment/history" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-clock-rotate-left"></i>
          <span>Investment History</span>
        </a>

        <a href="<?php echo $domain ?>users/trading" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-robot"></i>
          <span>Auto Trading</span>
        </a>

        <a href="<?php echo $domain ?>users/sport-betting" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-futbol"></i>
          <span>Sport Betting</span>
        </a>

      </nav>


      <p class="text-gray-400 text-sm mt-8 mb-4">BANKING</p>

      <nav class="space-y-2">

        <a href="<?php echo $domain ?>users/deposit" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-arrow-down"></i>
          <span>Deposit</span>
        </a>

        <a href="<?php echo $domain ?>users/deposit/history" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-receipt"></i>
          <span>Deposit History</span>
        </a>

        <a href="<?php echo $domain ?>users/withdrawal" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-arrow-up"></i>
          <span>Withdrawal</span>
        </a>

        <a href="<?php echo $domain ?>users/virtual-card" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-credit-card"></i>
          <span>Virtual Cards</span>
        </a>

        <a href="<?php echo $domain ?>users/kyc" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-user-shield"></i>
          <span>KYC Verification</span>
        </a>

        <a href="<?php echo $domain ?>users/setting" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700/30">
          <i class="fas fa-cog"></i>
          <span>Setting</span>
        </a>

      </nav>

    </div>




  </aside>
</div>