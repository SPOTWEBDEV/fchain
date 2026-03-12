<!-- AlpineJS (Add once before closing </body>) -->
<script src="//unpkg.com/alpinejs" defer></script>

<div class="flex flex-wrap-reverse items-center justify-between gap-4 mb-10">

    <!-- Wallet Status -->
    <div class="bg-green-600/20 text-green-400 px-4 py-2 rounded-full text-sm font-medium w-fit">
        ● Wallet Connected
    </div>

    <!-- User Section -->
    <div class="flex justify-end w-full sm:w-fit items-center space-x-4 relative" x-data="{ open: false }">

        <!-- User Info -->
        <div class="text-right hidden sm:block">
            <p class="font-semibold"><?php echo $fullname  ?></p>
            <p class="text-yellow-400 text-xs">PENDING REVIEW</p>
        </div>

        <!-- Avatar Button -->
        <div @click="open = !open"
             class="w-10 h-10 bg-yellow-400 text-black font-bold rounded-full flex items-center justify-center cursor-pointer hover:scale-105 transition">
            UT
        </div>

        <!-- Dropdown -->
        <div x-show="open"
             @click.outside="open = false"
             x-transition
             class="absolute right-0 top-14 w-56 bg-[#0f172a] border border-gray-800 rounded-xl shadow-xl overflow-hidden z-50">

            <a href="<?php echo $domain  ?>users/dashboard" class="block px-5 py-3 hover:bg-gray-800 transition">
                📊 Dashboard
            </a>

            <a href="<?php echo $domain  ?>users/investment" class="block px-5 py-3 hover:bg-gray-800 transition">
                📈 Investments
            </a>

            <a href="<?php echo $domain  ?>users/virtual-card" class="block px-5 py-3 hover:bg-gray-800 transition">
                💳 Virtual Cards
            </a>

            <a href="<?php echo $domain  ?>users/exchange" class="block px-5 py-3 hover:bg-gray-800 transition">
                🔁  Assets Exchange
            </a>

            <div class="border-t border-gray-800"></div>

            <a href="<?php echo $domain  ?>users/logout" class="block px-5 py-3 text-red-400 hover:bg-gray-800 transition">
                🚪 Logout
            </a>

        </div>

    </div>

</div>