<?php
include("../server/connection.php");


?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sitename ?> Coin</title>

    <script src="https://cdn.tailwindcss.com/"></script>

    <link rel="stylesheet" href="<?php echo $domain; ?>assets/vendor/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="shortcut icon" href="<?php echo $domain; ?>assets/images/coin.png" type="image/x-icon">

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

    <style>
        body {
            background-color: #02040a;
            color: #E2E8F0;
        }

        /* Glassmorphism Card Effect */
        .glass-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(30, 41, 59, 0.5);
        }

        /* Virtual Card Gradient */
        .credit-card-bg {
            background: linear-gradient(135deg, #6366F1 0%, #A855F7 50%, #EC4899 100%);
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 30s linear infinite;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Mobile Menu */
        #mobile-menu {
            transition: all 0.3s ease-in-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }

        #mobile-menu.open {
            max-height: 500px;
            opacity: 1;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden selection:bg-brand-primary selection:text-white">

    <div class="bg-dark-panel border-b border-dark-border py-2 overflow-hidden whitespace-nowrap z-50">
        <div id="crypto-ticker" class="inline-block animate-marquee pl-4 text-xs font-mono">
            <span class="text-dark-muted">Loading live market data...</span>
        </div>
    </div>

    <nav class="sticky top-0 z-40 bg-dark-bg/90 backdrop-blur-md border-b border-dark-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-primary flex items-center justify-center shadow-neon">
                        <span class="font-bold text-white text-xl fas fa-coins"></span>
                    </div>
                    <span class="font-sans font-bold text-xl tracking-tight text-white"><?php echo $sitename ?> Coin</span>
                </div>



                <div class="flex items-center gap-4">
                    <a href='#form'> <button class="bg-white hover:bg-gray-200 text-black px-6 py-2.5 rounded-full text-sm font-bold shadow-lg transition-all hover:scale-105">Purchase Now</button></a>
                    <button id="mobile-menu-btn" class="lg:hidden text-gray-300 hover:text-white"><i class="fa-solid fa-bars text-2xl"></i></button>
                </div>
            </div>
        </div>
    </nav>



    <section class="py-20 bg-dark-panel border-y border-dark-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-2 gap-16 items-center">

                <!-- Text -->
                <div>

                    <div class="inline-flex items-center gap-2 text-brand-secondary font-bold mb-4">
                        <i class="fa-solid fa-coins"></i> METASTAKE TOKEN
                    </div>

                    <h2 class="text-4xl font-display font-bold text-white mb-6">
                        Introducing the <span class="text-brand-primary">MetaStake Coin</span>
                    </h2>

                    <p class="text-dark-muted text-lg mb-6">
                        MetaStake Coin is the official digital asset powering the MetaStake ecosystem.
                        The project is owned and managed by <strong class="text-brand-primary">Antonio Gracias</strong>, the founder of
                        <strong class="text-brand-primary">Valor Equity Partners</strong>. Valor Equity Partners, which serves as the parent company of the
                        MetaStake platform. Valor Equity Partners is responsible for the strategic vision, governance,
                        and long-term development of the MetaStake Coin ecosystem.
                    </p>

                    <p class="text-dark-muted text-lg mb-6">
                        The project is financially supported and strategically partnered with
                        <strong class="text-brand-primary">Elon Musk</strong>, founder of <strong class="text-brand-primary">Tesla</strong>.
                        Tesla plays a key role in providing financial backing and technical
                        support for the MetaStake Coin project, ensuring the infrastructure and
                        resources needed to build a sustainable and scalable ecosystem.
                    </p>

                    <p class="text-dark-muted text-lg">
                        Through the collaboration between <strong class="text-brand-primary">Antonio Gracias</strong>  and <strong class="text-brand-primary">Elon Musk</strong>, MetaStake Coin
                        is designed to power a wide range of services within the MetaStake platform,
                        including AI auto trading, Web3 sports betting, investment tools, virtual cards,
                        and exchange services. This partnership structure provides both strong leadership
                        and the financial support required to grow the MetaStake ecosystem globally.
                    </p>

                </div>

                <!-- Image -->
                <div class="relative flex justify-center">
                    <div class="absolute inset-0 bg-brand-primary blur-[80px] opacity-20"></div>

                    <img src="<?php echo $domain ?>assets/images/formate.jpg"
                        class="relative z-10 w-full max-w-[420px] rounded-2xl shadow-neon border border-dark-border">

                </div>

            </div>
        </div>
    </section>


    <section class="py-20 bg-dark-bg">

        <div class="relative flex justify-center">

            <img src="<?php echo $domain ?>assets/images/coin.png"
                class="relative z-10 w-[80%] max-w-[220px]">

        </div>

        <div class="max-w-7xl mx-auto px-4 mt-3 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-16">

                <h2 class="text-4xl font-display font-bold text-white mb-6">
                    The Power Behind <span class="text-brand-primary">MetaStake</span>
                </h2>

                <p class="text-dark-muted text-lg mb-4">
                    MetaStake Coin is designed to fuel every activity across our ecosystem.
                    From AI trading rewards to staking opportunities and platform discounts,
                    the token serves as the backbone of our decentralized financial network.
                </p>

                <p class="text-dark-muted text-lg">
                    By holding MetaStake Coin, users gain access to exclusive benefits including
                    reduced trading fees, priority investment opportunities, and community
                    governance participation.
                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-8 text-center">

                <div class="bg-dark-panel border border-dark-border rounded-xl p-8">
                    <i class="fa-solid fa-chart-line text-brand-primary text-3xl mb-4"></i>
                    <h4 class="text-white font-bold mb-2">Staking Rewards</h4>
                    <p class="text-dark-muted text-sm">
                        Earn passive income by staking your MetaStake tokens.
                    </p>
                </div>

                <div class="bg-dark-panel border border-dark-border rounded-xl p-8">
                    <i class="fa-solid fa-bolt text-brand-primary text-3xl mb-4"></i>
                    <h4 class="text-white font-bold mb-2">Platform Utility</h4>
                    <p class="text-dark-muted text-sm">
                        Use the token for trading, investments, and service discounts.
                    </p>
                </div>

                <div class="bg-dark-panel border border-dark-border rounded-xl p-8">
                    <i class="fa-solid fa-gift text-brand-primary text-3xl mb-4"></i>
                    <h4 class="text-white font-bold mb-2">Exclusive Rewards</h4>
                    <p class="text-dark-muted text-sm">
                        Get access to airdrops and special ecosystem bonuses.
                    </p>
                </div>

            </div>

        </div>
    </section>


    <section class="py-20 bg-dark-panel border-y border-dark-border">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-3 gap-8 text-center">

                <div class="bg-dark-bg border border-dark-border rounded-xl p-8">
                    <h3 class="text-4xl font-bold text-brand-primary mb-2">12,450</h3>
                    <p class="text-dark-muted">Participants</p>
                </div>

                <div class="bg-dark-bg border border-dark-border rounded-xl p-8">
                    <h3 class="text-4xl font-bold text-brand-primary mb-2">$2.8M</h3>
                    <p class="text-dark-muted">Liquidity Raised</p>
                </div>

                <div class="bg-dark-bg border border-dark-border rounded-xl p-8">
                    <h3 class="text-4xl font-bold text-brand-primary mb-2">June 30, 2026</h3>
                    <p class="text-dark-muted">Token Sale Ends</p>
                </div>

            </div>

        </div>
    </section>

    <section id="form" class="py-20 bg-dark-bg">

        <div class="max-w-3xl mx-auto px-4">

            <div class="bg-dark-panel border border-dark-border rounded-2xl p-6 shadow-neon">

                <h2 class="text-3xl font-bold text-white mb-6 text-center">
                    Buy <span class="text-brand-primary">MetaStake Coin</span>
                </h2>

                <form class="space-y-6" id="buyForm">

                    <div>
                        <label class="text-dark-muted text-sm">Amount In Dollar</label>
                        <input type="number" id="usdAmount"
                            class="w-full mt-2 bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white">
                    </div>

                    <div>
                        <label class="text-dark-muted text-sm">Amount of <?php echo $sitename ?> Coin</label>
                        <input type="text" id="coinAmount" readonly
                            class="w-full mt-2 bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white">
                            <small class="text-red-600">Minimum purchase is 10,000 MetaStake coins</small>
                    </div>


                    <div>
                        <label class="text-dark-muted text-sm">Payment Method</label>
                        <select id="paymentMethod"
                            class="w-full mt-2 bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white">
                            <option value="crypto">Crypto</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="gift">Gift Card</option>
                        </select>
                    </div>

                    <button type="button" id="purchaseBtn"
                        class="w-full bg-brand-primary hover:bg-brand-accent text-white py-3 rounded-lg font-bold transition px-2">
                        Purchase MetaStake Coin
                    </button>

                </form>

            </div>

        </div>

    </section>


    <div id="billingModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

        <div class="bg-dark-panel p-6 rounded-xl w-full  md:w-[70%]">

            <h3 class="text-xl font-bold text-white mb-4">Billing Information</h3>

            <input placeholder="Full Name"
                class="w-full mb-3 px-4 py-2 rounded bg-dark-bg border border-dark-border text-white">

            <input placeholder="Email Address"
                class="w-full mb-3 px-4 py-2 rounded bg-dark-bg border border-dark-border text-white">

            <input placeholder="Country"
                class="w-full mb-3 px-4 py-2 rounded bg-dark-bg border border-dark-border text-white">


            <div class="flex gap-3">
                <button onclick="closeModal()"
                class="w-fit px-2 bg-brand-primary py-2 rounded text-white">
                Continue Payment
            </button>

            <button onclick="closeModal()"
                class="w-fit px-2 bg-red-600 py-2 rounded text-white">
                Cancel
            </button>
            </div>

        </div>

    </div>



    <footer class="bg-dark-bg pt-16 pb-8 border-t border-dark-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <h3 class="text-xl font-bold text-white mb-4"><?php echo $sitename ?></h3>
                    <p class="text-sm text-dark-muted">The future of decentralized finance, investment, and payments.</p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-dark-muted">
                        <li><a href="#" class="hover:text-brand-primary">Wallet</a></li>
                        <li><a href="#" class="hover:text-brand-primary">Card</a></li>
                        <li><a href="#" class="hover:text-brand-primary">Plans</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-dark-muted">
                        <li><a href="#" class="hover:text-brand-primary">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-brand-primary">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-dark-muted">
                        <li><?php echo $siteemail ?></li>
                        <li>+123456789</li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-xs text-dark-muted pt-8 border-t border-dark-border">
                &copy; 2026 <?php echo $sitename ?>. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('open');
        });





        async function fetchData() {
            try {
                const res = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&amp;order=market_cap_desc&amp;per_page=5&amp;sparkline=false');
                const data = await res.json();


                const ticker = document.getElementById('crypto-ticker');
                let tickerHtml = '';
                data.forEach(coin => {
                    const color = coin.price_change_percentage_24h >= 0 ? 'text-green-400' : 'text-red-400';
                    tickerHtml += `<span class="mx-4"><span class="font-bold text-white">${coin.symbol.toUpperCase()}</span> $${coin.current_price} <span class="${color}">${coin.price_change_percentage_24h.toFixed(2)}%</span></span>`;
                });

                ticker.innerHTML = tickerHtml + tickerHtml;


                const table = document.getElementById('market-body');
                let tableHtml = '';
                data.forEach(coin => {
                    const color = coin.price_change_percentage_24h >= 0 ? 'text-green-400' : 'text-red-400';
                    tableHtml += `
                        <tr class="hover:bg-dark-panel transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="${coin.image}" class="w-6 h-6 rounded-full">
                                <span class="text-white font-bold">${coin.name}</span>
                            </td>
                            <td class="px-6 py-4 text-white font-mono">$${coin.current_price}</td>
                            <td class="px-6 py-4 ${color} font-medium">${coin.price_change_percentage_24h.toFixed(2)}%</td>
                            <td class="px-6 py-4 text-right"><button class="text-brand-primary hover:text-white border border-brand-primary hover:bg-brand-primary px-3 py-1 rounded text-xs transition-colors">Trade</button></td>
                        </tr>
                    `;
                });
                table.innerHTML = tableHtml;

            } catch (e) {
                console.log(e);
            }
        }
        fetchData();


        const coinPrice = 0.02;
        // example: 1 MetaStake coin = $0.02

        const minCoins = 10000;

        const usdInput = document.getElementById("usdAmount");
        const coinInput = document.getElementById("coinAmount");
        const purchaseBtn = document.getElementById("purchaseBtn");
        const modal = document.getElementById("billingModal");


        // Convert USD → Coin
        usdInput.addEventListener("input", function() {

            let usd = parseFloat(this.value);

            if (!usd || usd <= 0) {
                coinInput.value = "";
                return;
            }

            let coins = usd / coinPrice;

            let rawCoins = Math.floor(coins);
            console.log(rawCoins)
            console.log(rawCoins.toLocaleString())
            coinInput.value = rawCoins.toLocaleString();

        });



        // Purchase button
        purchaseBtn.addEventListener("click", function() {

            let coins = coinInput.value

            console.log(coins , minCoins)

            if (!coins || coins.replace(',','') < minCoins) {

                alert("Minimum purchase is 10,000 MetaStake coins");

                return;

            }


            // open modal
            modal.classList.remove("hidden");
            modal.classList.add("flex");

        });



        function closeModal() {

            modal.classList.add("hidden");
            modal.classList.remove("flex");

        }
    </script>
</body>

</html>