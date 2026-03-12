<?php
include("../../server/connection.php");
include("../../server/auth/client.php");


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
        <main class="flex-1 p-6 lg:p-10">

            <!-- Top Bar -->
            <?php include("../includes/header.php"); ?>


            <!-- Dashboard Grid -->
            <section id="wallet" class="py-16 relative bg-[#0f172a] border-t border-dark-border">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                   

                        <div class="lg:col-span-2">
                            <div class="glass-card rounded-2xl overflow-hidden shadow-card h-full">
                                <div class="p-6 border-b border-dark-border flex justify-between items-center bg-dark-panel/50">
                                    <div>
                                        <h3 class="text-xl font-bold text-white">My Wallet</h3>
                                        <p class="text-xs text-dark-muted">Multi-Chain Support (ERC20, BEP20, TRC20)</p>
                                    </div>
                                    <div class="bg-dark-bg px-3 py-1 rounded border border-dark-border flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        <span class="text-xs font-mono">Connected</span>
                                    </div>
                                </div>

                                <div class="p-6 grid md:grid-cols-2 gap-8">
                                    <div class="flex flex-col justify-center">
                                        <p class="text-dark-muted text-sm mb-1">Total Balance</p>
                                        <h2 class="text-4xl font-display font-bold text-white mb-6">$42,894.52</h2>

                                        <div class="grid grid-cols-3 gap-3">
                                            <button onclick="switchTab('send')" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-dark-bg border border-dark-border hover:border-brand-primary hover:bg-brand-primary/10 transition-all group">
                                                <div class="w-10 h-10 rounded-full bg-brand-primary/20 flex items-center justify-center text-brand-primary group-hover:bg-brand-primary group-hover:text-white">
                                                    <i class="fa-solid fa-paper-plane"></i>
                                                </div>
                                                <span class="text-xs font-medium">Send</span>
                                            </button>
                                            <button onclick="switchTab('receive')" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-dark-bg border border-dark-border hover:border-brand-primary hover:bg-brand-primary/10 transition-all group">
                                                <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 group-hover:bg-green-500 group-hover:text-white">
                                                    <i class="fa-solid fa-qrcode"></i>
                                                </div>
                                                <span class="text-xs font-medium">Receive</span>
                                            </button>
                                            <button onclick="switchTab('swap')" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-dark-bg border border-dark-border hover:border-brand-primary hover:bg-brand-primary/10 transition-all group">
                                                <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:bg-purple-500 group-hover:text-white">
                                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                                </div>
                                                <span class="text-xs font-medium">Swap</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="bg-dark-bg rounded-xl border border-dark-border p-5 min-h-[300px] relative">

                                        <div id="tab-send" class="space-y-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <h4 class="font-bold text-white">Send Crypto</h4>
                                                <span class="text-xs text-brand-primary cursor-pointer">Max Amount</span>
                                            </div>
                                            <div>
                                                <label class="text-xs text-dark-muted block mb-1">Asset</label>
                                                <select class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white focus:border-brand-primary outline-none">
                                                    <option>Bitcoin (BTC)</option>
                                                    <option>Ethereum (ETH)</option>
                                                    <option>USDT (TRC20)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-xs text-dark-muted block mb-1">Recipient Address</label>
                                                <div class="relative">
                                                    <input type="text" placeholder="Paste address..." class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white focus:border-brand-primary outline-none pl-9">
                                                    <i class="fa-solid fa-wallet absolute left-3 top-3 text-dark-muted text-xs"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs text-dark-muted block mb-1">Amount</label>
                                                <input type="number" placeholder="0.00" class="w-full bg-dark-panel border border-dark-border rounded-lg p-2.5 text-sm text-white focus:border-brand-primary outline-none">
                                            </div>
                                            <button class="w-full bg-brand-primary hover:bg-brand-accent text-white font-bold py-2.5 rounded-lg mt-2 transition-colors">Confirm Send</button>
                                        </div>

                                        <div id="tab-receive" class="hidden flex flex-col items-center justify-center h-full text-center space-y-4">
                                            <h4 class="font-bold text-white mb-2">Receive Bitcoin</h4>
                                            <div class="bg-white p-3 rounded-lg">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&amp;data=3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy" alt="QR">
                                            </div>
                                            <div class="w-full">
                                                <p class="text-xs text-dark-muted mb-1">Wallet Address</p>
                                                <div class="flex items-center bg-dark-panel border border-dark-border rounded-lg overflow-hidden">
                                                    <input type="text" value="3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy" readonly class="bg-transparent text-xs text-brand-secondary p-2 w-full outline-none">
                                                    <button class="px-3 py-2 bg-dark-border hover:bg-brand-primary hover:text-white transition-colors"><i class="fa-regular fa-copy"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="tab-swap" class="hidden space-y-3">
                                            <h4 class="font-bold text-white mb-2">Instant Swap</h4>
                                            <div class="bg-dark-panel p-3 rounded-lg border border-dark-border">
                                                <div class="flex justify-between text-xs text-dark-muted mb-1"><span>From</span><span>Bal: 1.2 ETH</span></div>
                                                <div class="flex items-center justify-between">
                                                    <input type="number" placeholder="0.0" class="bg-transparent text-white font-bold text-lg w-20 outline-none">
                                                    <span class="bg-dark-bg px-2 py-1 rounded text-xs font-bold border border-dark-border">ETH</span>
                                                </div>
                                            </div>
                                            <div class="flex justify-center -my-3 relative z-10">
                                                <div class="bg-brand-primary p-1.5 rounded-full text-white text-xs border-4 border-dark-bg">
                                                    <i class="fa-solid fa-arrow-down"></i>
                                                </div>
                                            </div>
                                            <div class="bg-dark-panel p-3 rounded-lg border border-dark-border">
                                                <div class="flex justify-between text-xs text-dark-muted mb-1"><span>To</span><span>~ $0.00</span></div>
                                                <div class="flex items-center justify-between">
                                                    <input type="number" placeholder="0.0" class="bg-transparent text-white font-bold text-lg w-20 outline-none">
                                                    <span class="bg-dark-bg px-2 py-1 rounded text-xs font-bold border border-dark-border">USDT</span>
                                                </div>
                                            </div>
                                            <button class="w-full bg-brand-primary hover:bg-brand-accent text-white font-bold py-2.5 rounded-lg mt-2 transition-colors">Swap Assets</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 mt-4">
                            <div class="glass-card rounded-2xl p-6 h-full border border-dark-border">
                                <h3 class="font-bold text-white mb-4">Recent Activity</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-dark-bg/50 transition-colors border border-transparent hover:border-dark-border">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center">
                                                <i class="fa-solid fa-arrow-down"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-white">Received USDT</p>
                                                <p class="text-xs text-dark-muted">Today, 10:23 AM</p>
                                            </div>
                                        </div>
                                        <span class="text-green-400 font-mono text-sm">+$500.00</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-dark-bg/50 transition-colors border border-transparent hover:border-dark-border">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-400 flex items-center justify-center">
                                                <i class="fa-solid fa-arrow-up"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-white">Sent Bitcoin</p>
                                                <p class="text-xs text-dark-muted">Yesterday</p>
                                            </div>
                                        </div>
                                        <span class="text-white font-mono text-sm">-0.02 BTC</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-dark-bg/50 transition-colors border border-transparent hover:border-dark-border">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-brand-primary/10 text-brand-primary flex items-center justify-center">
                                                <i class="fa-solid fa-repeat"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-white">Swapped ETH</p>
                                                <p class="text-xs text-dark-muted">2 days ago</p>
                                            </div>
                                        </div>
                                        <span class="text-white font-mono text-sm">1.5 ETH</span>
                                    </div>
                                </div>
                                <button class="w-full mt-6 py-2 text-sm text-dark-muted border border-dark-border rounded-lg hover:text-white hover:border-white transition-colors">View All History</button>
                            </div>
                        </div>
                    
                </div>
            </section>

        </main>
    </div>

    <script>
         function switchTab(tabName) {
            // Hide all
            document.getElementById('tab-send').classList.add('hidden');
            document.getElementById('tab-receive').classList.add('hidden');
            document.getElementById('tab-swap').classList.add('hidden');
            document.getElementById('tab-receive').classList.remove('flex'); 
           
            const el = document.getElementById('tab-' + tabName);
            el.classList.remove('hidden');
            
            if(tabName === 'receive') el.classList.add('flex');
        }
    </script>

</body>

</html>