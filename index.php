<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPOS - Sistem Kasir Masa Depan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { 
                extend: { 
                    colors: { primary: '#1e40af', secondary: '#fbbf24', shopee: '#ee4d2d' },
                    animation: {
                        'gradient-x':'gradient-x 15s ease infinite',
                        'marquee': 'marquee 25s linear infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slideUp': 'slideUp 0.3s ease-out forwards',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': { 'background-size':'200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size':'200% 200%', 'background-position': 'right center' },
                        },
                        marquee: { '0%': { transform: 'translateX(0%)' }, '100%': { transform: 'translateX(-100%)' } },
                        slideUp: { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } }
                    }
                } 
            }
        }
    </script>
    <style>
        .nav-active { border-bottom: 3px solid #fbbf24; color: #fbbf24; font-weight: 800; }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: tabSlide 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes tabSlide { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .floating-wa { animation: bounce 2s infinite; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .dark { background-color: #0f172a; color: #f8fafc; }
        .dark .bg-white { background-color: #1e293b; border-color: #334155; color: #f8fafc; }
        
        .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; width: 100%; max-width: 900px; margin: auto; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2); }
        .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 20px; }

        #notification-toast.show-toast { display: flex; animation: slideRight 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideRight { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #1e40af; border-radius: 10px; }
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        .dark ::-webkit-scrollbar-thumb { background: #3b82f6; }

        .table-row-hover:hover { background-color: rgba(59, 130, 246, 0.05); }
        .dark .table-row-hover:hover { background-color: rgba(255, 255, 255, 0.02); }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 transition-colors duration-500 overflow-x-hidden pb-16 md:pb-0">

   

    <div id="exitPopup" class="hidden fixed inset-0 z-[10000] bg-black/80 backdrop-blur-sm flex-col items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 max-w-md w-full relative shadow-[0_0_50px_rgba(251,191,36,0.3)] text-center border-4 border-yellow-400">
            <span onclick="closeExitPopup()" class="absolute top-4 right-6 text-gray-400 hover:text-red-500 text-3xl font-black cursor-pointer transition">&times;</span>
            <i class="fas fa-gift text-6xl text-yellow-500 mb-4 animate-bounce"></i>
            <h3 class="text-3xl font-black text-red-600 dark:text-red-500 mb-2 uppercase tracking-tight">Tunggu Dulu!</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm mb-6 leading-relaxed">Jangan tutup halaman ini. Khusus untuk Anda yang mengambil keputusan hari ini, kami berikan <strong class="text-blue-600 dark:text-yellow-400 text-lg block mt-2">✨ DISKON TAMBAHAN Rp 200.000 ✨</strong></p>
            <button onclick="triggerConfettiAndWA('Halo, saya mau klaim Diskon Tambahan 200rb dari promo rahasia XPOS!')" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg hover:scale-105 transition transform mb-4">
                Klaim Diskon Sekarang
            </button>
            <p onclick="closeExitPopup()" class="text-xs text-gray-400 cursor-pointer hover:underline">Tidak, terima kasih. Saya rela bayar harga normal.</p>
        </div>
    </div>

    <div id="videoModal" class="hidden fixed inset-0 z-[9999] bg-black/95 backdrop-blur-md flex-col items-center justify-center p-4">
        <span onclick="closeVideo()" class="absolute top-5 right-5 md:right-10 text-white/50 hover:text-white text-5xl font-black cursor-pointer transition">&times;</span>
        <div class="video-container w-full max-w-4xl">
            <iframe id="youtubeVideo" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>

    <div id="imageModal" class="hidden fixed inset-0 z-[9999] bg-black/95 backdrop-blur-md flex-col items-center justify-center p-4">
        <span onclick="closeModal()" class="absolute top-5 right-5 md:right-10 text-white/50 hover:text-white text-5xl font-black cursor-pointer transition">&times;</span>
        <button onclick="prevImage()" class="absolute left-2 md:left-10 text-white/50 hover:text-white text-6xl transition"><i class="fas fa-chevron-left"></i></button>
        <img class="m-auto block max-w-[90%] max-h-[75%] rounded-xl shadow-[0_0_50px_rgba(0,0,0,0.5)] border-4 border-white/10 transition-all duration-300" id="modalImg">
        <div id="caption" class="text-white mt-6 font-bold text-center bg-black/60 backdrop-blur-md px-8 py-3 rounded-full uppercase tracking-widest text-xs border border-white/20"></div>
        <button onclick="nextImage()" class="absolute right-2 md:right-10 text-white/50 hover:text-white text-6xl transition"><i class="fas fa-chevron-right"></i></button>
    </div>

    <div id="notification-toast" class="hidden fixed bottom-32 md:bottom-24 left-5 z-[1000] bg-white/90 backdrop-blur-md dark:bg-slate-800/90 p-4 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] items-center gap-4 border border-blue-100 dark:border-slate-700">
        <div class="bg-gradient-to-tr from-blue-600 to-indigo-500 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-shopping-cart"></i></div>
        <div class="text-[10px] md:text-xs">
            <p class="font-bold text-blue-900 dark:text-blue-300" id="toast-name">Agus di Tambun</p>
            <p class="text-gray-500">Baru saja membeli <span class="font-bold text-blue-600 dark:text-yellow-400">Paket XPOS Pro</span></p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-red-600 via-red-500 to-red-700 text-white py-2 overflow-hidden relative shadow-md z-50">
        <div class="container mx-auto px-4 flex justify-center items-center gap-4 whitespace-nowrap text-xs md:text-sm">
            <i class="fas fa-bolt text-yellow-300 animate-bounce"></i>
            <span class="font-bold text-white uppercase tracking-wider">Promo Spesial XPOS Berakhir Dalam:</span>
            <div id="countdown" class="font-mono bg-black/50 px-3 py-0.5 rounded shadow-inner tracking-widest font-bold text-yellow-300">00:00:00</div>
        </div>
    </div>

    <div class="bg-blue-950 text-white py-2 px-4 text-[10px] md:text-xs relative z-40">
        <div class="container mx-auto flex justify-between items-center flex-wrap gap-2">
            <div class="flex items-center">
                <span class="opacity-80"><i class="fas fa-map-marker-alt text-yellow-400 mr-2"></i> Tambun Utara, Bekasi, Jawa Barat</span>
                <div class="hidden md:flex items-center gap-2 border-l border-white/20 pl-4 ml-4">
                    <i class="fas fa-chart-line text-blue-400"></i>
                    <span class="opacity-80">Total Pengunjung:</span>
                    <span class="font-mono text-yellow-400 font-bold bg-white/10 px-2 py-0.5 rounded" id="totalVisitors">0</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="toggleDarkMode()" class="hover:text-yellow-400 transition font-bold bg-white/10 px-3 py-1 rounded-full"><i class="fas fa-moon mr-1"></i> THEME</button>
                <a href="https://wa.me/6282114975811" class="hover:text-yellow-400 font-bold tracking-widest"><i class="fab fa-whatsapp text-green-400"></i> 0821-1497-5811</a>
            </div>
        </div>
    </div>

    <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-sm sticky top-0 z-50 border-b border-gray-200 dark:border-slate-800 transition-all">
        <div class="container mx-auto px-4 py-4 flex flex-wrap justify-between items-center">
            <div class="flex items-center space-x-2 group cursor-pointer" onclick="switchTab(null, 'home')">
                <div class="bg-gradient-to-br from-blue-800 to-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center font-black text-2xl shadow-lg group-hover:rotate-12 transition-transform">X</div>
                <h1 class="text-2xl font-black text-blue-900 dark:text-white tracking-tight">XPOS <span class="text-blue-500 font-normal italic text-xs tracking-widest uppercase">System Kasir</span></h1>
            </div>

            <div class="relative w-full md:w-64 mt-3 md:mt-0 order-last md:order-none group">
                <input type="text" id="searchInput" onkeyup="liveSearch()" placeholder="Cari fitur/software..." class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 dark:bg-slate-800 dark:border-slate-700 outline-none text-xs focus:ring-2 focus:ring-blue-500 transition-shadow shadow-inner">
                <i class="fas fa-search absolute left-4 top-2.5 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
            </div>

            <div class="flex space-x-4 md:space-x-8 font-bold text-gray-500 text-[10px] md:text-sm uppercase mt-4 md:mt-0">
                <button onclick="switchTab(event, 'home')" class="nav-btn nav-active hover:text-blue-600 transition">Beranda</button>
                <button onclick="switchTab(event, 'produk')" class="nav-btn hover:text-blue-600 transition">Produk</button>
                <button onclick="switchTab(event, 'download')" class="nav-btn text-red-500 hover:text-red-600 transition">Download</button>
                <button onclick="switchTab(event, 'tutorial')" class="nav-btn hover:text-blue-600 transition">Tutorial</button>
                <button onclick="switchTab(event, 'testimoni')" class="nav-btn hover:text-blue-600 transition">FAQ</button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-10 flex flex-wrap">
        <main class="w-full lg:w-3/4 pr-0 lg:pr-10">
            
            <div id="home" class="tab-content active">
                
                <div data-aos="fade-up" class="bg-gradient-to-br from-blue-900 via-indigo-800 to-blue-950 animate-gradient-x rounded-[2.5rem] p-8 md:p-16 text-white shadow-2xl mb-12 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400 rounded-full blur-[150px] opacity-20 -mr-20 -mt-20 mix-blend-overlay"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 rounded-full blur-[100px] opacity-30 -ml-20 -mb-20"></div>
                    
                    <div class="relative z-10 md:w-4/5">
                        <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-xs font-bold mb-4 tracking-widest uppercase shadow-lg">✨ Pilihan #1 UMKM INDONESIA</span>
                        
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-500/20 text-red-100 rounded-full text-[10px] md:text-xs font-bold mb-6 border border-red-500/30 backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span> 
                            <span id="liveViewers">14</span> Orang sedang melihat promo ini
                        </div>

                        <h2 class="text-4xl md:text-6xl font-black leading-tight tracking-tighter">
                            <span id="dynamicGreeting" class="text-xl md:text-3xl text-yellow-300 font-bold block mb-2 opacity-90 tracking-normal">Selamat Datang! 👋</span>
                            Bisnis Lebih <br>
                            <span class="text-yellow-400 italic underline decoration-wavy decoration-yellow-600" id="typewriter"></span><span class="animate-ping font-light">|</span>
                        </h2>
                        <p class="mt-6 text-lg text-blue-100 font-light leading-relaxed">Sistem kasir cerdas yang memudahkan input stok, cetak struk, hingga laporan laba rugi otomatis. Setup selesai dalam 1 hari!</p>
                        
                        <div class="mt-10 flex flex-wrap gap-4 items-center">
                            <button onclick="triggerConfettiAndWA('Halo kak, saya tertarik AMBIL PROMO XPOS 6.7JT dari website')" class="bg-yellow-400 text-blue-900 px-8 py-4 rounded-2xl font-black hover:bg-white hover:-translate-y-1 transition-all shadow-[0_10px_20px_rgba(251,191,36,0.3)] relative overflow-hidden group">
                                <span class="relative z-10">AMBIL PROMO SEKARANG</span>
                                <div class="absolute inset-0 h-full w-0 bg-white transition-all duration-300 ease-out group-hover:w-full opacity-50 z-0"></div>
                            </button>
                            <button onclick="openVideo()" class="bg-white/10 backdrop-blur-md border border-white/30 text-white px-8 py-4 rounded-2xl font-bold hover:bg-white/20 transition-all flex items-center gap-3">
                                <i class="fas fa-play-circle text-2xl text-yellow-400"></i> Tonton Demo Aplikasi X-POS
                            </button>
                        </div>

                        <div class="mt-8 max-w-sm">
                            <div class="flex justify-between text-[10px] md:text-xs font-bold text-blue-200 mb-2 uppercase tracking-widest">
                                <span>Sisa Promo Hardware Hari Ini</span>
                                <span class="text-yellow-400 animate-pulse">Sisa 3 Paket!</span>
                            </div>
                            <div class="w-full bg-blue-950/50 rounded-full h-3 border border-white/10 overflow-hidden relative">
                                <div class="bg-gradient-to-r from-red-500 to-yellow-400 h-3 rounded-full" style="width: 85%"></div>
                                <div class="absolute top-0 left-0 bottom-0 w-full">
                                    <div class="w-10 h-full bg-white/30 skew-x-12 animate-[marquee_2s_linear_infinite]"></div>
                                </div>
                            </div>
                            <p class="text-[10px] mt-2 opacity-70 italic">*Stok hardware bonus cepat habis, amankan sekarang sebelum kehabisan.</p>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-delay="100" class="mb-12 overflow-hidden bg-white dark:bg-slate-800 py-4 border-y border-gray-100 dark:border-slate-700 flex relative">
                    <div class="absolute left-0 w-20 h-full bg-gradient-to-r from-gray-50 dark:from-slate-900 to-transparent z-10"></div>
                    <div class="absolute right-0 w-20 h-full bg-gradient-to-l from-gray-50 dark:from-slate-900 to-transparent z-10"></div>
                    <div class="flex animate-marquee whitespace-nowrap opacity-60 font-bold text-gray-400 text-sm tracking-widest uppercase items-center">
                        <span class="mx-8"><i class="fas fa-check-circle text-green-500 mr-2"></i>Dipercaya 500+ Toko</span> • 
                        <span class="mx-8"><i class="fas fa-check-circle text-green-500 mr-2"></i>Minimarket Satria Jaya</span> • 
                        <span class="mx-8"><i class="fas fa-check-circle text-green-500 mr-2"></i>Kedai Kopi Abimanyu</span> • 
                        <span class="mx-8"><i class="fas fa-check-circle text-green-500 mr-2"></i>Apotek Sehat Tambun</span> •
                        <span class="mx-8"><i class="fas fa-check-circle text-green-500 mr-2"></i>Dipercaya 500+ Toko</span>
                    </div>
                </div>

                <div data-aos="fade-up" class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 mb-12 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-yellow-400 text-blue-900 font-bold text-[10px] px-4 py-1 rounded-bl-xl uppercase">Fitur Interaktif</div>
                    <h3 class="font-black text-2xl mb-8 text-blue-900 dark:text-white tracking-tight"><i class="fas fa-calculator mr-2 text-blue-500"></i> Kalkulator ROI (Penghematan)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                        <div class="space-y-6">
                            <div>
                                <label class="text-sm font-bold block mb-3 text-gray-600 dark:text-gray-300">Berapa transaksi toko Anda per hari?</label>
                                <input type="range" id="txRange" min="10" max="500" value="50" step="10" oninput="calcROI()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                <div class="flex justify-between text-xs font-bold text-gray-400 mt-2">
                                    <span>Sepi (10)</span>
                                    <span class="text-blue-600 dark:text-blue-400 text-sm bg-blue-50 dark:bg-slate-700 px-3 py-1 rounded-full" id="txVal">50 Transaksi</span>
                                    <span>Ramai (500)</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-slate-700 dark:to-slate-700 rounded-2xl border border-blue-100 dark:border-slate-600">
                                <p class="text-xs text-gray-600 dark:text-gray-300"><i class="fas fa-info-circle text-blue-500 mr-1"></i> XPOS menghemat waktu hitung manual dan rekap stok hingga <strong class="text-blue-800 dark:text-white">2 Menit per transaksi</strong>.</p>
                            </div>
                        </div>
                        <div class="text-center bg-gradient-to-br from-blue-900 to-indigo-900 text-white p-8 rounded-[2rem] shadow-lg shadow-blue-900/20 transform transition hover:scale-105">
                            <p class="text-xs uppercase tracking-widest mb-2 opacity-80">Waktu yang Anda Hemat / Bulan:</p>
                            <h4 class="text-5xl font-black text-yellow-400 drop-shadow-md" id="roiResult">3.000 Menit</h4>
                            <div class="mt-4 pt-4 border-t border-white/20">
                                <p class="text-[10px] font-medium opacity-80">Sama dengan menghemat gaji admin rekap!</p>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="bg-gray-100 dark:bg-slate-900 p-6 rounded-3xl border border-gray-200 dark:border-slate-700 max-w-sm mx-auto shadow-inner">
                <h3 class="font-black text-xl text-blue-900 dark:text-white mb-6 uppercase"><i class="fas fa-print mr-2 text-blue-500"></i> Contoh Hasil Cetak Struk</h3>
                <div class="bg-white text-black p-4 shadow-md font-mono text-[11px] leading-tight border-t-4 border-blue-600 rounded-sm mx-auto w-full max-w-[280px]">
        
        <div class="text-center mb-2">
            <h4 class="font-black text-sm uppercase">X-POS TOSERBA</h4>
            <p>JL.ABIMANYU, KEC. TAMBUN UTARA KAB BEKASI</p>
            <p>NO TLP : 0821-1497-5811</p>
            <p>=========================================</p>
            <div class="flex justify-between px-1">
                <span>Tanggal : 02-04-2026</span>
                <span>3:17:24</span>
            </div>
            <p>=========================================</p>
        </div>

        <div class="mb-2">
            <div class="flex justify-between">
                <span>FAKTUR  : 203-0402RPT097</span>
            </div>
            <div class="flex justify-between">
                <span>KASIR   : XPOS</span>
            </div>
            <p>=========================================</p>
        </div>

        <div class="mb-2">
            <p class="uppercase">SELAMAT SW CHOCOLATE 102G</p>
            <div class="flex justify-between italic">
                <span>1 BAG   x   11.200</span>
                <span>11.200</span>
            </div>
            <p>-----------------------------------------</p>
        </div>

        <div class="space-y-1">
            <div class="flex justify-between">
                <span>TOTAL BELANJA    :</span>
                <span>11.200</span>
            </div>
            <div class="flex justify-between">
                <span>TOTAL ITEM /QTY : 1 / 1</span>
            </div>
            <div class="flex justify-between">
                <span>DISCOUNT        :</span>
                <span>0</span>
            </div>
            <div class="flex justify-between">
                <span>VOUCHER         :</span>
                <span>0</span>
            </div>
            <div class="flex justify-between font-bold border-t border-dashed border-black pt-1">
                <span>TOTAL           :</span>
                <span>11.200</span>
            </div>
            <div class="flex justify-between">
                <span>DIBAYAR TUNAI    :</span>
                <span>50.000</span>
            </div>
            <div class="flex justify-between">
                <span>KEMBALIAN       :</span>
                <span>38.800</span>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p>=========================================</p>
            <div class="flex justify-between text-[9px] px-2 mb-2">
                <span>DPP  11.200</span>
                <span>PPN: 0</span>
            </div>
            <p class="font-bold">Terima Kasih</p>
            <p>Sampai Belanja Kembali</p>
        </div>
    </div>
    
    <p class="text-center text-[10px] text-gray-400 mt-4 italic font-medium">
        *Contoh tampilan struk asli dari printer thermal XPOS
    </p>
</div>      


<div data-aos="fade-up" class="mb-16">
    <div class="flex flex-col items-center mb-10">
        <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 border border-blue-200 dark:border-blue-800 shadow-sm">
            Powerful Business Engine
        </span>
        <h3 class="font-black text-3xl md:text-5xl text-blue-900 dark:text-white tracking-tight text-center uppercase">
            Fitur Unggulan <span class="text-blue-500 italic">X-POS</span>
        </h3>
        <div class="w-24 h-1.5 bg-yellow-400 mt-4 rounded-full"></div>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-6 text-center max-w-2xl leading-relaxed">
            Lebih dari sekadar kasir. X-POS adalah sistem manajemen bisnis terintegrasi yang dirancang untuk skala UMKM hingga Retail Modern.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-700 to-indigo-800 p-7 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group hover:-translate-y-2 transition-all duration-500">
            <i class="fas fa-network-wired absolute -bottom-4 -right-4 text-8xl text-white/10 group-hover:scale-125 transition duration-700"></i>
            <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-5 backdrop-blur-md shadow-inner"><i class="fas fa-server"></i></div>
            <h4 class="font-black text-lg mb-2">Support Client Server</h4>
            <p class="text-[11px] text-blue-100 leading-relaxed opacity-90 font-medium">Multi-kasir & admin dalam 1 jaringan database SQL Server yang sinkron secara real-time.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-7 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-lg relative overflow-hidden group hover:border-blue-400 hover:shadow-2xl transition-all duration-500">
            <div class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 w-12 h-12 rounded-2xl flex items-center justify-center mb-5 shadow-sm"><i class="fas fa-chart-line text-lg"></i></div>
            <h4 class="font-black text-lg text-slate-800 dark:text-white mb-2">Laba Rugi Akurat</h4>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Tahu untung bersih setiap detik setelah dipotong HPP & biaya operasional secara otomatis.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-7 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-lg relative overflow-hidden group hover:border-blue-400 hover:shadow-2xl transition-all duration-500">
            <div class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 w-12 h-12 rounded-2xl flex items-center justify-center mb-5 shadow-sm"><i class="fas fa-file-signature text-lg"></i></div>
            <h4 class="font-black text-lg text-slate-800 dark:text-white mb-2">Laporan Penjualan</h4>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Laporan Harian, Bulanan, Per-Kasir, hingga Laporan Pajak yang siap cetak kapan saja.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-7 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-lg relative overflow-hidden group hover:border-blue-400 hover:shadow-2xl transition-all duration-500">
            <div class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 w-12 h-12 rounded-2xl flex items-center justify-center mb-5 shadow-sm"><i class="fas fa-ticket-alt text-lg"></i></div>
            <h4 class="font-black text-lg text-slate-800 dark:text-white mb-2">Voucher & Cashback</h4>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Generate voucher otomatis di ekor struk untuk membuat pelanggan balik lagi belanja.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="bg-white dark:bg-slate-800/40 p-6 rounded-[2rem] border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all">
            <h5 class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase mb-4 flex items-center gap-2 tracking-widest">
                <i class="fas fa-tags"></i> Pricing Strategy
            </h5>
            <ul class="text-[11px] space-y-3 text-gray-600 dark:text-gray-300 font-bold">
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Harga Bertingkat</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Group Pelanggan</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Multi Satuan (Pcs/Dus)</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Harga Khusus Member</li>
            </ul>
        </div>

        <div class="bg-white dark:bg-slate-800/40 p-6 rounded-[2rem] border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all">
            <h5 class="text-xs font-black text-red-600 dark:text-red-400 uppercase mb-4 flex items-center gap-2 tracking-widest">
                <i class="fas fa-bullhorn"></i> Promo & Promo
            </h5>
            <ul class="text-[11px] space-y-3 text-gray-600 dark:text-gray-300 font-bold">
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Discount Periode</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Beli 2 Gratis 1</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Promo Paket Bundling</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Tebus Murah & Berhadiah</li>
            </ul>
        </div>

        <div class="bg-blue-50/50 dark:bg-blue-900/10 p-6 rounded-[2rem] border-2 border-dashed border-blue-200 dark:border-blue-800 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-[8px] font-black px-3 py-1 rounded-bl-xl uppercase tracking-tighter">Recommended</div>
            <h5 class="text-xs font-black text-blue-900 dark:text-blue-400 uppercase mb-4 flex items-center gap-2 tracking-widest">
                <i class="fas fa-hand-holding-usd"></i> CRM & Hutang
            </h5>
            <ul class="text-[11px] space-y-3 text-gray-700 dark:text-gray-200 font-bold">
                <li class="flex items-center gap-2 text-blue-800 dark:text-blue-300"><i class="fas fa-star text-yellow-500 text-xs"></i> Kelola Hutang Piutang</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Kas Bon Karyawan</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Point & Tabungan</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Cetak Kartu Member</li>
            </ul>
        </div>

        <div class="bg-white dark:bg-slate-800/40 p-6 rounded-[2rem] border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all">
            <h5 class="text-xs font-black text-green-700 dark:text-green-400 uppercase mb-4 flex items-center gap-2 tracking-widest">
                <i class="fas fa-print"></i> Store Utilities
            </h5>
            <ul class="text-[11px] space-y-3 text-gray-600 dark:text-gray-300 font-bold">
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Cetak Barcode Produk</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Label Harga Rak (Shelf)</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> Laporan Pajak Otomatis</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-xs"></i> User & Hak Akses</li>
                <i class="fas fa-desktop text-xs animate-pulse"></i> 
                <strong>Support Customer Display</strong>
            </ul>
        </div>

    </div>

    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-slate-800 dark:to-indigo-950/30 p-6 rounded-[2rem] border-2 border-blue-200 dark:border-blue-800 shadow-xl relative overflow-hidden group col-span-1 md:col-span-2 lg:col-span-4 mt-4">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-400 rounded-full blur-[100px] opacity-20 group-hover:opacity-40 transition"></div>
    
    <div class="flex flex-col md:flex-row gap-8 items-center relative z-10">
        <div class="flex-1">
            <h5 class="text-sm font-black text-blue-600 dark:text-blue-400 uppercase mb-4 flex items-center gap-2 tracking-[0.2em]">
                <i class="fas fa-mobile-alt animate-bounce"></i> Fitur PPOB & Digital Hub
            </h5>
            <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-4 uppercase leading-tight">Ubah Toko Jadi <span class="text-blue-600">Pusat Pembayaran Digital</span></h4>
            <div class="grid grid-cols-2 gap-4">
                <ul class="text-[11px] space-y-2 text-gray-700 dark:text-gray-300 font-bold">
                    <li class="flex items-center gap-2"><i class="fas fa-bolt text-yellow-500"></i> Token Listrik (PLN)</li>
                    <li class="flex items-center gap-2"><i class="fas fa-signal text-blue-500"></i> Pulsa & Paket Data</li>
                    <li class="flex items-center gap-2"><i class="fas fa-wallet text-green-500"></i> Top Up (DANA, OVO, Shopee)</li>
                </ul>
                <ul class="text-[11px] space-y-2 text-gray-700 dark:text-gray-300 font-bold">
                    <li class="flex items-center gap-2"><i class="fas fa-car text-indigo-500"></i> Top Up Grab / Gojek</li>
                    <li class="flex items-center gap-2"><i class="fas fa-tv text-red-500"></i> Bayar Tagihan (BPJS/PDAM)</li>
                    <li class="flex items-center gap-2"><i class="fas fa-plus-circle text-blue-600"></i> Dan Produk Digital Lainnya</li>
                </ul>
            </div>
        </div>

        <div class="flex-1 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm p-5 rounded-3xl border border-white/50 dark:border-slate-700">
            <h5 class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase mb-4 tracking-widest text-center">Metode Pembayaran Terlengkap</h5>
            <div class="flex flex-wrap justify-center gap-4 opacity-90">
                <div class="flex flex-col items-center gap-1">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-slate-700"><i class="fas fa-qrcode text-red-600 text-xl"></i></div>
                    <span class="text-[8px] font-bold uppercase">QRIS Midtrans</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-slate-700"><i class="fas fa-credit-card text-blue-600 text-xl"></i></div>
                    <span class="text-[8px] font-bold uppercase">Debit Card</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-slate-700"><i class="fas fa-ticket-alt text-yellow-500 text-xl"></i></div>
                    <span class="text-[8px] font-bold uppercase">Voucher</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-slate-700"><i class="fas fa-hand-holding-usd text-green-600 text-xl"></i></div>
                    <span class="text-[8px] font-bold uppercase">Piutang</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-slate-700"><i class="fas fa-piggy-bank text-pink-500 text-xl"></i></div>
                    <span class="text-[8px] font-bold uppercase">Tabungan</span>
                </div>
            </div>
            <p class="text-[9px] text-gray-500 dark:text-gray-400 text-center mt-4 italic font-medium leading-tight">
                *XPOS terintegrasi langsung dengan <strong>Midtrans Payment Gateway</strong> untuk keamanan transaksi QRIS 24 Jam.
            </p>
        </div>
    </div>
</div>
</div>


                <div data-aos="fade-up" class="mb-16 bg-blue-900 rounded-[3rem] p-8 md:p-12 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 rounded-full blur-[120px] opacity-20"></div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <h3 class="text-3xl font-black mb-6 uppercase tracking-tight">Kenapa Harus <span class="text-yellow-400">X-POS?</span></h3>
            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="bg-yellow-400 text-blue-900 w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-lg"><i class="fas fa-rocket"></i></div>
                    <div>
                        <h4 class="font-bold text-lg">Kecepatan Transaksi</h4>
                        <p class="text-xs text-blue-100">Antrean panjang? XPOS dirancang untuk kecepatan input barang & cetak struk kurang dari 2 detik.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="bg-yellow-400 text-blue-900 w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-lg"><i class="fas fa-lock"></i></div>
                    <div>
                        <h4 class="font-bold text-lg">Keamanan Data SQL Server</h4>
                        <p class="text-xs text-blue-100">Bukan database gratisan biasa. Kami menggunakan SQL Server 2022 yang anti-corrupt dan mampu menampung jutaan data.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="bg-yellow-400 text-blue-900 w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-lg"><i class="fas fa-headset"></i></div>
                    <div>
                        <h4 class="font-bold text-lg">Layanan After Sales</h4>
                        <p class="text-xs text-blue-100">Error? Langsung kami remote via UltraViewer.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative">
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-[2rem] border border-white/20 rotate-3 hover:rotate-0 transition duration-500 shadow-2xl">
                <img src="https://down-id.img.susercontent.com/file/id-11134207-822wr-mme8zy2kef4fb6" class="rounded-xl w-full" alt="Hardware XPOS">
                <div class="absolute -bottom-5 -left-5 bg-yellow-400 text-blue-900 font-black px-6 py-3 rounded-2xl shadow-xl animate-bounce text-xs">
                    STOK TERBATAS!
                </div>
            </div>
        </div>
    </div>
</div>


                <div data-aos="fade-up" class="mb-16 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
    <div class="order-2 md:order-1 relative">
        <div class="absolute -inset-4 bg-blue-500/20 blur-2xl rounded-full"></div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-2xl relative z-10 overflow-hidden">
            <div class="bg-blue-900 rounded-xl p-4 text-white font-mono shadow-inner mb-4">
                <div class="flex justify-between border-b border-white/20 pb-2 mb-2 text-[10px]">
                    <span>X-POS CUSTOMER DISPLAY</span>
                    <span class="text-yellow-400">ONLINE</span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-sm">
                        <span>CHOCOLATE 102G</span>
                        <span>11.200</span>
                    </div>
                    <div class="text-[10px] text-blue-300 italic">Qty: 1</div>
                </div>
                <div class="mt-4 pt-2 border-t-2 border-dashed border-white/30 flex justify-between items-end">
                    <span class="text-xs uppercase font-black">Total Tagihan</span>
                    <span class="text-2xl font-black text-yellow-400 tracking-tighter">Rp 11.200</span>
                </div>
            </div>
            <p class="text-[10px] text-center text-gray-500 font-bold uppercase tracking-widest">Tampilan Layar Pelanggan (Real-Time)</p>
        </div>
    </div>

    <div class="order-1 md:order-2 space-y-6">
        <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-200 dark:border-green-800">
            <i class="fas fa-eye mr-2"></i> Transparansi Transaksi
        </span>
        <h3 class="text-3xl font-black text-blue-900 dark:text-white uppercase leading-tight">Bangun <span class="text-blue-500">Kepercayaan</span> Pelanggan Anda</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            X-POS mendukung penggunaan **Monitor Kedua (Customer Display)**. Pelanggan dapat melihat langsung nama barang, jumlah belanja, hingga total harga saat kasir melakukan scan. 
        </p>
        <ul class="space-y-3 text-xs font-bold text-slate-700 dark:text-slate-300">
            <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Menghindari kesalahan input kasir</li>
            <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Menampilkan promo berjalan di layar depan</li>
            <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Toko terlihat jauh lebih Modern & Profesional</li>
        </ul>
    </div>
</div>

                <div data-aos="fade-up" class="mb-12 bg-white dark:bg-slate-800 p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 relative overflow-hidden">
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-50 dark:bg-slate-700 rounded-full blur-[80px]"></div>

                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <h3 class="font-black text-2xl text-blue-900 dark:text-white tracking-tight uppercase"><i class="fas fa-camera-retro text-blue-500 mr-2"></i> Album Instalasi & Testimoni Visual</h3>
                        <div class="h-1 flex-1 bg-gradient-to-r from-blue-400 to-transparent rounded-full opacity-30"></div>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-10 -mt-4 relative z-10 max-w-2xl">
                        Kumpulan foto nyata pemasangan sistem XPOS Pro di berbagai toko partner kami. Lihat bagaimana XPOS membuat meja kasir menjadi rapi, modern, dan profesional.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 relative z-10" id="albumGrid">
    
    <div data-tilt data-tilt-max="10" data-tilt-scale="1.02" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300 col-span-2 row-span-2" onclick="openGallery('album_instalasi', 0)">
        <img src="img/1.webp" alt="Instalasi XPOS di Minimarket Tambun" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <span class="bg-yellow-400 text-blue-900 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest mb-3 self-start">✨ HIGHLIGHT</span>
            <h5 class="text-white font-black text-lg uppercase tracking-tight mb-1">Minimarket X-POS</h5>
            <p class="text-blue-200 text-xs font-medium">Setup X-POS lengkap di Tambun Utara, Bekasi.</p>
        </div>
        <div class="absolute top-4 right-4 bg-white/30 backdrop-blur-sm text-white w-9 h-9 rounded-full flex items-center justify-center text-sm shadow-md transition-transform group-hover:rotate-90"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="15" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300" onclick="openGallery('album_instalasi', 1)">
        <img src="img/2.webp" alt="Setup Kasir Cafe Bekasi" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 grayscale group-hover:grayscale-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h5 class="text-white font-bold text-[11px] uppercase tracking-wider mb-1">Kedai Kopi Abimanyu</h5>
            <p class="text-blue-200 text-[9px]">Aesthetic Setup - Bekasi</p>
        </div>
        <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-md"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="15" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300" onclick="openGallery('album_instalasi', 2)">
        <img src="img/3.webp" alt="Aplikasi Kasir Apotek" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 grayscale group-hover:grayscale-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h5 class="text-white font-bold text-[11px] uppercase tracking-wider mb-1">Apotek Sehat</h5>
            <p class="text-blue-200 text-[9px]">Manajemen Stok - Tambun</p>
        </div>
        <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-md"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="15" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300" onclick="openGallery('album_instalasi', 3)">
        <img src="img/4.webp" alt="Kasir Grosir Rapi" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 grayscale group-hover:grayscale-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h5 class="text-white font-bold text-[11px] uppercase tracking-wider mb-1">Grosir Jaya Abadi</h5>
            <p class="text-blue-200 text-[9px]">Kasir Heavy Duty - Cikarang</p>
        </div>
        <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-md"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="15" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300" onclick="openGallery('album_instalasi', 4)">
        <img src="img/5.webp" alt="Minimarket Lampung" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 grayscale group-hover:grayscale-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h5 class="text-white font-bold text-[11px] uppercase tracking-wider mb-1">Minimarket Lampung</h5>
            <p class="text-blue-200 text-[9px]">Sistem Cloud - Lampung</p>
        </div>
        <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-md"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="15" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300" onclick="openGallery('album_instalasi', 5)">
        <img src="img/6.webp" alt="Grosir Sembako" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 grayscale group-hover:grayscale-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h5 class="text-white font-bold text-[11px] uppercase tracking-wider mb-1">Grosir Sembako</h5>
            <p class="text-blue-200 text-[9px]">Jawa Timur</p>
        </div>
        <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-md"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="15" class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-md border-4 border-white dark:border-slate-700 hover:shadow-2xl hover:border-blue-400 transition-all duration-300" onclick="openGallery('album_instalasi', 6)">
        <img src="img/7.webp" alt="Toko Plastik Medan" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 grayscale group-hover:grayscale-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h5 class="text-white font-bold text-[11px] uppercase tracking-wider mb-1">Toko Plastik</h5>
            <p class="text-blue-200 text-[9px]">Medan, Sumatera Utara</p>
        </div>
        <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-md"><i class="fas fa-search-plus"></i></div>
    </div>

    <div data-tilt data-tilt-max="10" class="relative aspect-square rounded-[2rem] overflow-hidden cursor-pointer shadow-inner border-4 border-dashed border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex flex-col items-center justify-center text-center p-4 group hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-950 transition-all duration-300" onclick="orderWA('Halo Tim XPOS! Saya mau kirim foto testimoni instalasi toko saya.')">
        <div class="bg-white dark:bg-slate-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl shadow-md border-2 border-gray-100 dark:border-slate-700 text-green-500 group-hover:scale-110 transition-transform duration-500">
            <i class="fab fa-whatsapp"></i>
        </div>
        <h5 class="mt-3 font-black text-blue-900 dark:text-white text-[10px] uppercase tracking-tight">Kirim Foto Anda?</h5>
        <p class="text-[8px] text-gray-500 dark:text-gray-400 leading-tight mt-1">Dapatkan <span class="text-green-600 font-bold">Maintenance Gratis!</span></p>
    </div>

</div>
                </div>

                <div data-aos="fade-up" class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 mb-12 flex flex-col md:flex-row gap-10 items-center relative overflow-hidden">
                    <div class="absolute -left-10 top-10 w-32 h-32 bg-blue-500 rounded-full blur-[80px] opacity-20"></div>
                    
                    <div class="w-full md:w-1/3 text-center relative z-10">
                        <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?auto=format&fit=crop&w=500&q=80" alt="Sistem Windows" onerror="this.src='https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=500'" class="w-full h-48 object-cover rounded-2xl shadow-lg mb-5 border-4 border-gray-50 dark:border-slate-700">
                        <div class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 text-blue-800 dark:text-blue-200 px-5 py-2 rounded-full text-xs font-black shadow-sm border border-blue-200 dark:border-blue-700">
                            <i class="fab fa-windows text-lg text-blue-600 dark:text-blue-400"></i> KHUSUS PENGGUNA WINDOWS
                        </div>
                    </div>
                    
                    <div class="w-full md:w-2/3 relative z-10">
                        <h3 class="font-black text-2xl text-blue-900 dark:text-white tracking-tight mb-3"><i class="fas fa-cogs mr-2 text-blue-500"></i> Spesifikasi & Kebutuhan Sistem</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-6">Agar aplikasi kasir XPOS berjalan mulus tanpa hambatan saat toko Anda sedang ramai, pastikan PC atau Laptop yang digunakan memenuhi syarat berikut:</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            
                            <div class="flex items-start gap-3 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-700/80 dark:to-slate-800 rounded-2xl border border-blue-200 dark:border-blue-800 shadow-md col-span-1 sm:col-span-2">
                                <i class="fas fa-database text-blue-600 dark:text-blue-400 text-3xl mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-sm text-blue-900 dark:text-blue-300 uppercase tracking-tight mb-1">Database Enterprise-Grade</h4>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 font-medium">Sistem didukung penuh oleh <strong>Microsoft SQL Server 2022</strong>. Menjamin data transaksi tetap aman, anti-corrupt, dan sangat stabil meskipun digunakan untuk puluhan ribu transaksi setiap harinya.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-900/10 rounded-2xl border border-red-200 dark:border-red-900/50 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-red-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-bl-lg">WAJIB</div>
                                <i class="fab fa-windows text-red-500 text-2xl mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-sm text-red-700 dark:text-red-400 uppercase tracking-tight mb-1">Sistem Operasi</h4>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 font-semibold">Minimal Windows 10 (64-bit) atau versi yang lebih baru (Win 11).</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-slate-700/50 rounded-2xl border border-gray-100 dark:border-slate-600 shadow-sm">
                                <i class="fas fa-memory text-blue-500 text-2xl mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-xs text-blue-900 dark:text-blue-300 uppercase tracking-tight mb-1">RAM & Penyimpanan</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">Minimal RAM 4GB (Sangat disarankan 8GB) & menggunakan SSD.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-slate-700/50 rounded-2xl border border-gray-100 dark:border-slate-600 shadow-sm">
                                <i class="fas fa-desktop text-blue-500 text-2xl mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-xs text-blue-900 dark:text-blue-300 uppercase tracking-tight mb-1">Resolusi Layar PC</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">Minimal 1024 x 768 pixel ,Tampilan terbaik di 1360 x 768 pixel agar seluruh menu kasir terlihat sempurna.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-slate-700/50 rounded-2xl border border-gray-100 dark:border-slate-600 shadow-sm">
                                <i class="fas fa-layer-group text-blue-500 text-2xl mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-xs text-blue-900 dark:text-blue-300 uppercase tracking-tight mb-1">Komponen Sistem</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">Membutuhkan .NET Framework 4.5 ke atas (Otomatis ter-install).</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-r-2xl flex gap-3 items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            <p class="text-xs text-yellow-800 dark:text-yellow-200 font-medium leading-relaxed">
                                <strong>Informasi Instalasi:</strong> Harap matikan sementara Anti-Virus (termasuk Windows Defender & Smadav) saat proses pemasangan agar file <i>Database SQL Server</i> tidak terblokir.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div id="produk" class="tab-content">
                <div class="flex items-center gap-4 mb-8">
                    <h2 class="text-3xl font-black text-blue-900 dark:text-white uppercase tracking-tight">Katalog Paket</h2>
                    <div class="h-1 flex-1 bg-gradient-to-r from-yellow-400 to-transparent rounded-full"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-16" id="productGrid">
                    
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-yellow-400 via-red-500 to-blue-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-75 transition duration-500"></div>
                        
                        <div data-tilt data-tilt-max="5" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.2" class="product-item relative bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2rem] overflow-hidden shadow-2xl z-10 transform-gpu flex flex-col h-full">
                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-blue-900 text-center py-1.5 font-black text-xs uppercase tracking-widest shadow-sm">👑 PILIHAN SULTAN</div>
                            
                            <div class="relative cursor-pointer overflow-hidden" onclick="openGallery('paket_lengkap')">
                                <img src="https://down-id.img.susercontent.com/file/id-11134207-822wr-mme8zy2kef4fb6" class="w-full h-56 object-cover transform group-hover:scale-110 transition duration-700">
                                <div class="absolute inset-0 bg-blue-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 backdrop-blur-sm">
                                    <span class="bg-white text-blue-900 font-black px-6 py-3 rounded-full text-xs shadow-xl transform -translate-y-4 group-hover:translate-y-0 transition"><i class="fas fa-search-plus mr-2"></i> LIHAT FOTO ASLI</span>
                                </div>
                            </div>
                            
                            <div class="p-8 flex flex-col flex-grow">
                                <h4 class="text-2xl font-black text-blue-900 dark:text-white mb-2">PAKET LENGKAP XPOS PRO</h4>
                                <div class="bg-blue-50 dark:bg-slate-700 p-3 rounded-xl mb-4 flex-grow">
                                    <p class="text-blue-800 dark:text-blue-300 text-[11px] font-bold leading-relaxed">HARDWARE BARU: PC Core i5, RAM 8GB, SSD, Monitor LED 19", Printer Thermal 58mm, Barcode Scanner, Laci Uang Besi.</p>
                                </div>
                                <div class="mb-4">
                                    <span class="text-gray-400 text-[10px] line-through block italic font-bold">Harga Normal Rp 8.500.000</span>
                                    <span class="text-3xl font-black text-red-600 dark:text-red-400 drop-shadow-sm">Rp 6.700.000</span>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="orderWA('Halo Kak, mau order Paket Lengkap Aplikasi Kasir X-POS')" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white px-2 py-3 rounded-xl font-black shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 text-sm">
                                        <i class="fab fa-whatsapp text-lg"></i> VIA WA
                                    </button>
                                    <a href="https://shopee.co.id/Aplikasi-Kasir-XPOS-Full-Set-i.128167746.42862933472?extraParams=%7B%22display_model_id%22%3A89867370682%2C%22model_selection_logic%22%3A3%7D" target="_blank" class="flex-1 bg-shopee hover:bg-[#d74326] text-white px-2 py-3 rounded-xl font-black shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 text-sm">
                                        <i class="fas fa-shopping-bag"></i> SHOPEE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div data-tilt data-tilt-max="5" data-tilt-speed="400" class="product-item product-hover bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2rem] p-6 shadow-lg overflow-hidden flex flex-col justify-between transform-gpu">
                        <div>
                            <div class="relative group mb-6 rounded-2xl overflow-hidden cursor-pointer shadow-inner border border-gray-100 dark:border-slate-700" onclick="openGallery('software_only')">
                                <img src="https://down-id.img.susercontent.com/file/id-11134207-822wr-mme8zy2kef4fb6" class="w-full h-40 object-cover transform group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition backdrop-blur-sm">
                                    <span class="bg-white text-blue-900 font-bold px-4 py-2 rounded-full text-xs shadow-lg"><i class="fas fa-desktop mr-2"></i> PREVIEW SISTEM</span>
                                </div>
                            </div>
                            <h4 class="text-xl font-black text-blue-900 dark:text-white uppercase mb-2">Software XPOS Only ULTIMATE</h4>
                            <ul class="text-xs space-y-2 text-gray-500 dark:text-gray-400 font-medium mb-6">
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Lisensi Permanen (1 PC)</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Modul Kasir, Stok, & Laporan Keuangan</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Support Import Excel</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Bisa di pakai di banyak komputer dalam 1 jaringan</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Fitur-Fitur Lengkap</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Absensi</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Stok Opname</li>
                            </ul>
                        </div>
                        <div class="border-t border-gray-100 dark:border-slate-700 pt-6">
                            <div class="mb-4">
                                <span class="text-gray-400 text-[10px] line-through block italic font-bold">Rp 4.500.000</span>
                                <span class="text-2xl font-black text-gray-800 dark:text-white">Rp 2.700.000</span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="orderWA('Halo, mau order Software Only 2.7jt')" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-2 py-2.5 rounded-xl font-bold transition-colors shadow-md flex items-center justify-center gap-2 text-xs">
                                    <i class="fab fa-whatsapp text-sm"></i> WA
                                </button>
                                <a href="https://shopee.co.id/Program-Kasir-XPOS-Ultimate-i.128167746.25990924545?extraParams=%7B%22display_model_id%22%3A257563693050%2C%22model_selection_logic%22%3A3%7D" target="_blank" class="flex-1 bg-shopee hover:bg-[#d74326] text-white px-2 py-2.5 rounded-xl font-bold transition-colors shadow-md flex items-center justify-center gap-2 text-xs">
                                    <i class="fas fa-shopping-bag"></i> SHOPEE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-up" class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-xl overflow-hidden mb-12">
                    <div class="bg-blue-900 p-6 text-center">
                        <h3 class="text-2xl font-black text-white uppercase tracking-widest"><i class="fas fa-balance-scale mr-2"></i> Perbandingan Fitur</h3>
                    </div>
                   <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-slate-900/50 text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400">
                <th class="p-4 md:p-6 font-black border-b border-gray-100 dark:border-slate-700 w-1/3 text-blue-900 dark:text-blue-400">Analisis Perbandingan</th>
                <th class="p-4 md:p-6 font-bold text-center border-b border-gray-100 dark:border-slate-700">Metode Manual</th>
                <th class="p-4 md:p-6 font-bold text-center border-b border-gray-100 dark:border-slate-700">XPOS Software Only</th>
                <th class="p-4 md:p-6 font-bold text-center border-b border-gray-100 dark:border-slate-700 bg-blue-50/50 dark:bg-blue-900/20 text-blue-600">Paket Lengkap PRO</th>
            </tr>
        </thead>
        <tbody class="text-xs dark:text-gray-300">
            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Kecepatan Transaksi & Struk</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 font-bold uppercase text-[10px]">Cepat (PC Anda)</td>
                <td class="p-4 md:p-5 text-center text-green-500 bg-blue-50/50 dark:bg-blue-900/20 font-black uppercase text-[10px]">Ultra Fast (2 Detik)</td>
            </tr>
            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Laporan Laba Rugi Real-Time</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 text-lg"><i class="fas fa-check-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 bg-blue-50/50 dark:bg-blue-900/20 text-lg"><i class="fas fa-check-circle"></i></td>
            </tr>
            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Sistem Hutang & Piutang</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 text-lg"><i class="fas fa-check-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 bg-blue-50/50 dark:bg-blue-900/20 text-lg"><i class="fas fa-check-circle"></i></td>
            </tr>
            
            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Hardware & Laci Uang Otomatis</td>
                <td class="p-4 md:p-5 text-center text-gray-400 italic">Buku / Kalkulator</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-[10px] font-bold uppercase">Beli Sendiri</td>
                <td class="p-4 md:p-5 text-center text-green-500 bg-blue-50/50 dark:bg-blue-900/20 font-black uppercase text-[10px]">Full Set Include</td>
            </tr>

            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Backup Database SQL Server</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 text-lg"><i class="fas fa-check-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-green-500 bg-blue-50/50 dark:bg-blue-900/20 text-lg"><i class="fas fa-check-circle"></i></td>
            </tr>
            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Setting Barcode & Printer</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-yellow-500 text-[10px] font-bold uppercase leading-tight">Setting Mandiri<br>(Ikuti Tutorial)</td>
                <td class="p-4 md:p-5 text-center text-green-600 bg-blue-50/50 dark:bg-blue-900/20 font-black uppercase text-[10px]">Siap Pakai<br>(Kami Settingkan)</td>
            </tr>
            <tr class="table-row-hover border-b border-gray-100 dark:border-slate-700">
                <td class="p-4 md:p-5 font-bold">Garansi Ganti Baru (Hardware)</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 font-black uppercase text-[10px]">1 Tahun Penuh</td>
            </tr>
            <tr class="table-row-hover">
                <td class="p-4 md:p-5 font-bold">Training & Support Prioritas</td>
                <td class="p-4 md:p-5 text-center text-red-500 text-lg"><i class="fas fa-times-circle"></i></td>
                <td class="p-4 md:p-5 text-center text-gray-500 text-[10px] font-bold uppercase">Via Remote Chat</td>
                <td class="p-4 md:p-5 text-center text-green-600 bg-blue-50/50 dark:bg-blue-900/20 font-black uppercase text-[10px]">On-Site / Video Call VIP</td>
            </tr>
        </tbody>
    </table>
</div>
                </div>
            </div>

            <div id="testimoni" class="tab-content">
                <h2 class="text-3xl font-black text-blue-900 dark:text-white mb-8 uppercase tracking-tight">Testimoni & FAQ</h2>
                
                <div class="mb-16">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-black text-white w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-lg"><i class="fab fa-tiktok"></i></div>
                        <h3 class="text-2xl font-black text-blue-900 dark:text-white tracking-tight">Kata Mereka</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden flex justify-center p-2 relative hover:shadow-xl transition duration-300">
                            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7569232785416801544" data-video-id="7569232785416801544" style="max-width: 605px;min-width: 325px;">
                                <section>
                                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600">@balak_600</a>
                                </section>
                            </blockquote>
                        </div>
                        
                        <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden flex justify-center p-2 relative hover:shadow-xl transition duration-300">
                            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7598412873798405394" data-video-id="7598412873798405394" style="max-width: 605px;min-width: 325px;">
                                <section>
                                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600">@balak_600</a>
                                </section>
                            </blockquote>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden flex justify-center p-2 relative hover:shadow-xl transition duration-300">
                            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7572502831295417607" data-video-id="7572502831295417607" style="max-width: 605px;min-width: 325px;">
                                <section>
                                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600">@balak_600</a>
                                </section>
                            </blockquote>
                        </div>

                         <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden flex justify-center p-2 relative hover:shadow-xl transition duration-300">
                            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7539513665377045768" data-video-id="7539513665377045768" style="max-width: 605px;min-width: 325px;">
                                <section>
                                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600">@balak_600</a>
                                </section>
                            </blockquote>
                        </div>

                         <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden flex justify-center p-2 relative hover:shadow-xl transition duration-300">
                            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7565867972682714375" data-video-id="7565867972682714375" style="max-width: 605px;min-width: 325px;">
                                <section>
                                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600">@balak_600</a>
                                </section>
                            </blockquote>
                        </div>

                         <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden flex justify-center p-2 relative hover:shadow-xl transition duration-300">
                            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7591934142435347733" data-video-id="7591934142435347733" style="max-width: 605px;min-width: 325px;">
                                <section>
                                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600">@balak_600</a>
                                </section>
                            </blockquote>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-lg"><i class="fas fa-question"></i></div>
                    <h3 class="text-2xl font-black text-blue-900 dark:text-white tracking-tight">Pertanyaan Populer</h3>
                </div>
                <div class="space-y-4 mb-12">
                    <details class="group bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 transition shadow-sm open:shadow-md" open>
                        <summary class="font-bold flex justify-between items-center cursor-pointer list-none uppercase text-sm dark:text-white">
                            Apakah software ini sekali bayar?
                            <span class="group-open:rotate-180 transition-transform bg-blue-50 dark:bg-slate-700 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center"><i class="fas fa-chevron-down"></i></span>
                        </summary>
                        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 leading-relaxed border-t dark:border-slate-700 pt-4">Iya betul 100%. XPOS menggunakan sistem lisensi permanen (Lifetime). Beli hari ini, software menjadi milik Anda selamanya tanpa ada tagihan bulanan atau tahunan.</p>
                    </details>
                    <details class="group bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 transition shadow-sm">
                        <summary class="font-bold flex justify-between items-center cursor-pointer list-none uppercase text-sm dark:text-white">
                            Bagaimana jika alat/hardware rusak?
                            <span class="group-open:rotate-180 transition-transform bg-blue-50 dark:bg-slate-700 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center"><i class="fas fa-chevron-down"></i></span>
                        </summary>
                        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 leading-relaxed border-t dark:border-slate-700 pt-4">Tenang! Khusus pembelian Paket Lengkap 6.7jt, Anda mendapatkan garansi hardware penuh selama 1 tahun. Jika rusak karena cacat pabrik, tim kami di Bekasi akan datang mengganti part tersebut.</p>
                    </details>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-white dark:from-slate-800 dark:to-slate-700 p-8 rounded-[2rem] italic shadow-sm border border-blue-100 dark:border-slate-600 relative">
                        <i class="fas fa-quote-left absolute top-6 right-8 text-4xl text-blue-200 dark:text-slate-600 opacity-50"></i>
                        <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300 relative z-10">"XPOS bantu banget atur stok toko saya di Tambun. Laporan labanya akurat, jadi tahu uang muter kemana aja."</p>
                        <div class="mt-6 not-italic font-black text-blue-900 dark:text-blue-400 text-xs tracking-widest uppercase">— Owner Kedai Kopi Abimanyu</div>
                    </div>
                </div>
            </div>

            <div id="download" class="tab-content">
                <h2 class="text-3xl font-black text-blue-900 dark:text-white mb-8 uppercase tracking-tight">Area Download</h2>
                <div class="bg-white dark:bg-slate-800 p-12 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-xl text-center relative overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-64 h-64 bg-blue-50 dark:bg-slate-700 rounded-full blur-[80px]"></div>
                    <i class="fas fa-cloud-download-alt text-7xl text-blue-600 mb-6 drop-shadow-md animate-bounce relative z-10"></i>
                    <h4 class="text-2xl font-black mb-4 uppercase relative z-10 dark:text-white">Aplikasi Sistem Kasir</h4>
                    <p class="text-gray-500 text-sm mb-10 italic relative z-10">Unduh installer XPOS v2.5.2 atau aplikasi Remote Support UltraViewer di bawah ini.</p>
                    
                    <div class="flex flex-col md:flex-row justify-center gap-4 relative z-10">
                        <a href="https://www.mediafire.com/file/dryok1ms7o89s1c/X-POS.EXE/file" download class="bg-gradient-to-r from-blue-700 to-indigo-700 text-white px-8 py-4 rounded-2xl font-black hover:shadow-[0_10px_20px_rgba(29,78,216,0.3)] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1">
                            <i class="fas fa-download text-xl"></i> DOWNLOAD INSTALLER
                        </a>
                        
                        <a href="https://ultraviewer.net/en/download.html" target="_blank" class="bg-gradient-to-r from-slate-700 to-slate-900 text-white px-8 py-4 rounded-2xl font-black hover:shadow-[0_10px_20px_rgba(15,23,42,0.3)] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1">
                            <i class="fas fa-laptop-medical text-xl"></i> DOWNLOAD ULTRAVIEWER
                        </a>
                    </div>
                </div>
            </div>

            <div id="tutorial" class="tab-content">
                <h2 class="text-3xl font-black text-blue-900 dark:text-white mb-8 uppercase tracking-tight">Panduan Instalasi XPOS</h2>
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-5 rounded-r-2xl mb-8 shadow-sm">
                    <div class="flex items-start">
                        <div class="bg-red-100 dark:bg-red-800 p-2 rounded-lg text-red-600 dark:text-red-200 mr-4 mt-1">
                            <i class="fas fa-shield-virus text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-red-800 dark:text-red-400 uppercase tracking-widest mb-1">Peringatan Penting</h4>
                            <p class="text-xs text-red-700 dark:text-red-300 font-medium leading-relaxed">Pastikan <strong>Anti-Virus (Windows Defender / Smadav / lainnya) di-OFF-kan terlebih dahulu</strong> sebelum melakukan instalasi agar file sistem dan database tidak terblokir oleh Windows.</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6 mb-16">
    <div class="p-6 md:p-8 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-blue-400 transition-all duration-300 flex flex-col md:flex-row gap-6 items-center group">
        <div class="flex items-start gap-6 w-full md:w-1/2">
            <div class="text-5xl md:text-6xl font-black text-gray-100 dark:text-slate-700 group-hover:text-blue-500 transition-colors duration-500 shrink-0 mt-1">01</div>
            <div>
                <h4 class="font-black text-lg md:text-xl text-blue-900 dark:text-white uppercase mb-2 tracking-tight">Cari & Buka Installer</h4>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium mb-4">Setelah mendownload file dari menu Download Center, cari file installer XPOS tersebut di folder <span class="bg-blue-50 dark:bg-slate-700 px-2 py-1 rounded text-blue-600 dark:text-yellow-400 font-bold">Downloads</span> PC/Laptop Anda.</p>
            </div>
        </div>
        <div class="w-full md:w-1/2 overflow-hidden rounded-2xl border-4 border-gray-50 dark:border-slate-700 shadow-md bg-gray-100 relative group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors duration-500">
            <img src="img/step2.png" alt="Tutorial Step 1" class="w-full h-48 md:h-56 object-cover transform group-hover:scale-105 transition-transform duration-700">
            <div class="absolute bottom-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">ILUSTRASI 1</div>
        </div>
    </div>

    <div class="p-6 md:p-8 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-blue-400 transition-all duration-300 flex flex-col md:flex-row-reverse gap-6 items-center group">
        <div class="flex items-start gap-6 w-full md:w-1/2">
            <div class="text-5xl md:text-6xl font-black text-gray-100 dark:text-slate-700 group-hover:text-blue-500 transition-colors duration-500 shrink-0 mt-1">02</div>
            <div>
                <h4 class="font-black text-lg md:text-xl text-blue-900 dark:text-white uppercase mb-2 tracking-tight">Run As Administrator</h4>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium mb-4"><strong>Klik Kanan</strong> pada file installer tersebut, kemudian pilih opsi <span class="bg-blue-50 dark:bg-slate-700 px-2 py-1 rounded text-blue-600 dark:text-yellow-400 font-bold">"Run as Administrator"</span> untuk memulai pemasangan dengan hak akses penuh.</p>
            </div>
        </div>
        <div class="w-full md:w-1/2 overflow-hidden rounded-2xl border-4 border-gray-50 dark:border-slate-700 shadow-md bg-gray-100 relative group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors duration-500">
            <img src="img/step1.png" alt="Tutorial Step 2" class="w-full h-48 md:h-56 object-cover transform group-hover:scale-105 transition-transform duration-700">
            <div class="absolute bottom-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">ILUSTRASI 2</div>
        </div>
    </div>

    <div class="p-6 md:p-8 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-blue-400 transition-all duration-300 flex flex-col md:flex-row gap-6 items-center group">
        <div class="flex items-start gap-6 w-full md:w-1/2">
            <div class="text-5xl md:text-6xl font-black text-gray-100 dark:text-slate-700 group-hover:text-blue-500 transition-colors duration-500 shrink-0 mt-1">03</div>
            <div>
                <h4 class="font-black text-lg md:text-xl text-blue-900 dark:text-white uppercase mb-2 tracking-tight">Ikuti Step Instalasi Database</h4>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium mb-4">Ikuti <em>step-by-step</em> di layar monitor (klik Next) sampai proses instalasi selesai. Sistem akan otomatis menyambung ke <span class="bg-blue-50 dark:bg-slate-700 px-2 py-1 rounded text-blue-600 dark:text-yellow-400 font-bold">SQL Server 2022</span>.</p>
            </div>
        </div>
        <div class="w-full md:w-1/2 overflow-hidden rounded-2xl border-4 border-gray-50 dark:border-slate-700 shadow-md bg-gray-100 relative group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors duration-500">
            <img src="img/step3.png" alt="Tutorial Step 3" class="w-full h-48 md:h-56 object-cover transform group-hover:scale-105 transition-transform duration-700">
            <div class="absolute bottom-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">ILUSTRASI 3</div>
        </div>
    </div>
    
</div>

<div class="p-6 md:p-8 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-blue-400 transition-all duration-300 flex flex-col md:flex-row gap-6 items-center group">
        <div class="flex items-start gap-6 w-full md:w-1/2">
            <div class="text-5xl md:text-6xl font-black text-gray-100 dark:text-slate-700 group-hover:text-blue-500 transition-colors duration-500 shrink-0 mt-1">03</div>
            <div>
                <h4 class="font-black text-lg md:text-xl text-blue-900 dark:text-white uppercase mb-2 tracking-tight">Ikuti Step Instalasi Database</h4>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium mb-4">Ikuti <em>step-by-step</em> di layar monitor (klik Next) sampai proses instalasi selesai. Sistem akan otomatis menyambung ke <span class="bg-blue-50 dark:bg-slate-700 px-2 py-1 rounded text-blue-600 dark:text-yellow-400 font-bold">SQL Server 2022</span>.</p>
            </div>
        </div>
        <div class="w-full md:w-1/2 overflow-hidden rounded-2xl border-4 border-gray-50 dark:border-slate-700 shadow-md bg-gray-100 relative group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors duration-500">
            <img src="img/step3.png" alt="Tutorial Step 3" class="w-full h-48 md:h-56 object-cover transform group-hover:scale-105 transition-transform duration-700">
            <div class="absolute bottom-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">ILUSTRASI 3</div>
        </div>
    </div>
</div> <div class="mt-8 bg-slate-50 dark:bg-slate-900/50 rounded-[3rem] p-8 md:p-12 border border-blue-100 dark:border-slate-700">
    <div class="flex flex-col md:flex-row items-center gap-8 mb-10">
        <div class="flex-1 text-center md:text-left">
            <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[0.2em] mb-4 inline-block">
                Video Guide
            </span>
            <h3 class="text-3xl md:text-4xl font-black text-blue-900 dark:text-white leading-tight">
                Masih Bingung? <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-500">Tonton Video Ini</span>
            </h3>
            <p class="mt-4 text-gray-500 dark:text-gray-400 font-medium max-w-md">
                Panduan visual langkah demi langkah untuk memastikan proses instalasi XPOS berjalan lancar tanpa kendala.
            </p>
        </div>
        
        <div class="w-full max-w-[325px] mx-auto md:mx-0 shadow-2xl rounded-3xl overflow-hidden border-8 border-white dark:border-slate-800">
            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@balak_600/video/7592137259143433490" data-video-id="7592137259143433490" style="max-width: 605px;min-width: 325px;">
                <section>
                    <a target="_blank" title="@balak_600" href="https://www.tiktok.com/@balak_600?refer=embed">@balak_600</a>
                </section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        </div>
    </div>

    <div class="flex flex-wrap justify-center gap-4 border-t border-gray-200 dark:border-slate-700 pt-8">
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
            <i class="fas fa-play-circle text-blue-500"></i> High Quality
        </div>
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
            <i class="fas fa-clock text-blue-500"></i> Step-by-step
        </div>
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
            <i class="fas fa-check-circle text-blue-500"></i> Verified Guide
        </div>
    </div>


                <h2 class="text-2xl font-black text-blue-900 dark:text-white mb-6 uppercase tracking-tight"><i class="fas fa-headset text-blue-500 mr-2"></i> Pusat Bantuan & Remote Support</h2>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-red-500 text-white font-bold text-[10px] px-4 py-1 rounded-bl-xl uppercase tracking-widest shadow-sm">Support Teknis</div>
                    
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-full md:w-1/2">
                            <img src="https://images.unsplash.com/photo-1588508065123-287b28e013da?auto=format&fit=crop&w=600&q=80" class="w-full h-56 object-cover rounded-2xl shadow-md border-4 border-gray-50 dark:border-slate-700" alt="Remote Support Ultraviewer">
                            
                            <div class="mt-5 bg-blue-50 dark:bg-slate-900/50 p-4 rounded-xl border border-blue-100 dark:border-slate-600 text-center">
                                <p class="text-[10px] uppercase text-blue-800 dark:text-blue-300 font-bold mb-2 tracking-widest">Contoh Format Laporan Kendala:</p>
                                <p class="text-xs font-mono text-gray-600 dark:text-gray-400 text-left bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-100 dark:border-slate-700 shadow-inner">
                                    Halo Tim Support,<br>
                                    Saya ada kendala: [Jelaskan error secara detail & kirim fotonya]<br><br>
                                    <strong>Akses Remote:</strong><br>
                                    ID UltraViewer: 12 345 678<br>
                                    Password: 1234
                                </p>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-1/2 space-y-4">
                            <h4 class="text-xl font-black text-blue-900 dark:text-white uppercase mb-2 leading-tight">Aplikasi Kasir Bermasalah?</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-4">Jangan panik! Jika Anda menemui <em>error</em> atau kendala di dalam aplikasi kasir XPOS, tim teknisi kami bisa membantu memperbaikinya secara langsung dari jarak jauh menggunakan <strong>UltraViewer</strong>.</p>
                            
                            <ul class="text-xs space-y-4 text-gray-600 dark:text-gray-300 font-medium">
                                <li class="flex items-start gap-3">
                                    <span class="bg-blue-100 dark:bg-slate-700 text-blue-600 dark:text-blue-400 w-6 h-6 rounded-full flex items-center justify-center font-bold shrink-0">1</span> 
                                    <span><strong>Jelaskan Detail Kendala:</strong> Sampaikan bagian mana yang bermasalah secara rinci melalui chat WhatsApp.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="bg-blue-100 dark:bg-slate-700 text-blue-600 dark:text-blue-400 w-6 h-6 rounded-full flex items-center justify-center font-bold shrink-0">2</span> 
                                    <span><strong>Sertakan Foto/Screenshot:</strong> Wajib memfotokan layar PC Anda yang menampilkan pesan <em>error</em> tersebut.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="bg-blue-100 dark:bg-slate-700 text-blue-600 dark:text-blue-400 w-6 h-6 rounded-full flex items-center justify-center font-bold shrink-0">3</span> 
                                    <span><strong>Berikan ID & Pass UltraViewer:</strong> Buka aplikasi UltraViewer di komputer kasir, lalu ketikkan ID dan Password yang muncul ke tim kami agar bisa segera di-remote.</span>
                                </li>
                            </ul>

                            <div class="pt-6">
                                <button onclick="orderWA('Halo Tim Support XPOS.%0A%0ASaya mau melaporkan kendala aplikasi: [Tulis Detail Kendalanya di Sini]%0A%0A[Kirimkan Foto Layar Error]%0A%0AAkses Remote UltraViewer:%0AID: %0APassword: ')" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-4 rounded-xl font-black shadow-[0_10px_20px_rgba(34,197,94,0.3)] transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 text-xs uppercase tracking-widest">
                                    <i class="fab fa-whatsapp text-lg"></i> Laporkan Kendala ke Teknisi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <aside class="w-full lg:w-1/4 mt-12 lg:mt-0 relative z-20">
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-[2.5rem] shadow-2xl p-8 sticky top-32">
                
                <div class="flex justify-center gap-4 mb-6 border-b border-gray-100 dark:border-slate-700 pb-6">
                    <div class="text-center"><i class="fas fa-shield-alt text-2xl text-green-500 mb-1"></i><p class="text-[8px] font-bold uppercase">100% Aman</p></div>
                    <div class="text-center"><i class="fas fa-medal text-2xl text-yellow-500 mb-1"></i><p class="text-[8px] font-bold uppercase">Garansi 1 Thn</p></div>
                    <div class="text-center"><i class="fas fa-users text-2xl text-blue-500 mb-1"></i><p class="text-[8px] font-bold uppercase">Lokal Support</p></div>
                </div>

                <h4 class="font-black text-blue-900 dark:text-white mb-6 uppercase text-lg tracking-widest">Kontak Sales</h4>
                
                <div class="space-y-6">
                    <div class="group cursor-pointer" onclick="orderWA('Halo, mau tanya sistem')">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">WhatsApp Fast Respon</p>
                        <div class="flex items-center gap-4">
                            <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-2xl text-green-600 dark:text-green-400 group-hover:scale-110 transition transform"><i class="fab fa-whatsapp text-2xl"></i></div>
                            <p class="text-lg font-black text-slate-800 dark:text-white group-hover:text-green-500 transition">0821-1497-5811</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 dark:border-slate-700 pt-6">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pusat Layanan</p>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-pin text-blue-500 mt-1"></i>
                            <p class="text-xs leading-relaxed font-semibold text-gray-600 dark:text-gray-300">Jl. Abimanyu RT/RW 005/015 Cluster Kedaton, Perum Darmawangsa, Tambun Utara, Bekasi.</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-slate-700 pt-6">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Ikuti Kami</p>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/share/14XVw7g8Dnj/" target="_blank" class="w-10 h-10 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg hover:bg-blue-600 hover:text-white transition shadow-sm"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.tiktok.com/@balak_600" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-white flex items-center justify-center text-lg hover:bg-black hover:text-white transition shadow-sm"><i class="fab fa-tiktok"></i></a>
                            <a href="https://youtube.com/@xpos2?si=VEqX8Dx0JG_QrSe8" target="_blank" class="text-slate-400 hover:text-red-500 transition text-2xl"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 text-center">Pembayaran Langsung</p>
                    <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-slate-600">
                        <div class="flex items-center gap-3">
                            <div class="font-black text-blue-700 italic text-xl">BCA</div>
                            <div>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase">A.N. SYAHRIL EFENDI</p>
                                <p class="text-sm font-bold dark:text-white font-mono" id="rekBca">7390197441</p>
                            </div>
                        </div>
                        <button onclick="copyRekening()" class="text-blue-500 hover:text-blue-700 bg-blue-50 dark:bg-slate-700 p-2 rounded-lg transition" title="Copy Rekening">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <button onclick="triggerConfettiAndWA('Halo, saya mau Ambil Promo XPOS Paket Lengkap 6.7jt')" class="w-full bg-blue-900 dark:bg-blue-600 text-white mt-6 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-black dark:hover:bg-blue-500 shadow-xl hover:-translate-y-1 transition transform animate-pulse-slow">
                    AMBIL PROMO WA <i class="fas fa-arrow-right ml-2"></i>
                </button>
                
                <a href="https://shopee.co.id/Aplikasi-Kasir-XPOS-Full-Set-i.128167746.42862933472?extraParams=%7B%22display_model_id%22%3A89867370682%2C%22model_selection_logic%22%3A3%7D" target="_blank" class="w-full flex items-center justify-center gap-2 bg-shopee text-white mt-3 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-[#d74326] hover:-translate-y-1 transition transform">
                    <i class="fas fa-shopping-bag text-sm"></i> CHECKOUT SHOPEE
                </a>

            </div>
        </aside>
    </div>

    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-slate-800 p-3 flex gap-2 z-[90] shadow-[0_-10px_20px_rgba(0,0,0,0.1)]">
        <button onclick="orderWA('Halo kak, saya mau ambil Promo XPOS 6.7jt')" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest animate-pulse-slow">
            AMBIL PROMO
        </button>
        <a href="https://wa.me/6282114975811" class="flex-none bg-green-500 text-white w-12 h-12 flex items-center justify-center rounded-xl font-black text-xl shadow-lg">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <div class="fixed bottom-24 md:bottom-6 right-6 z-50 flex flex-col gap-3 items-end">
        <button id="scrollToTopBtn" onclick="scrollToTop()" class="hidden bg-blue-900 text-white w-12 h-12 rounded-full shadow-[0_10px_25px_rgba(30,58,138,0.4)] items-center justify-center hover:bg-blue-700 transition transform hover:-translate-y-1">
            <i class="fas fa-arrow-up text-xl"></i>
        </button>

        <div id="csBubble" class="hidden flex-col items-end mb-2 mr-2">
            <div class="bg-white dark:bg-slate-800 p-3 rounded-2xl rounded-br-none shadow-xl border border-gray-100 dark:border-slate-700 max-w-[200px] cursor-pointer animate-[slideUp_0.3s_ease-out]" onclick="orderWA('Halo kak, mau konsultasi soal XPOS nih.')">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-blue-900 dark:text-blue-400">Teknisi XPOS</span>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-300 leading-tight">Halo! Butuh bantuan panduan instalasi PC / mau tanya fitur?</p>
            </div>
        </div>

        <a href="https://wa.me/6282114975811" target="_blank" class="floating-wa flex items-center bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-4 rounded-full shadow-[0_10px_25px_rgba(34,197,94,0.4)] hover:scale-110 group transition-all hidden md:flex">
            <div class="hidden md:block w-0 group-hover:w-48 overflow-hidden transition-all duration-300">
                <span class="text-sm font-bold whitespace-nowrap pl-2 pr-4">Tanya-tanya? Yuk Chat!</span>
            </div>
            <i class="fab fa-whatsapp text-3xl"></i>
        </a>
    </div>

    <footer class="bg-slate-950 text-slate-500 py-16 mt-10 border-t-4 border-blue-600 relative overflow-hidden pb-24 md:pb-16">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h5 class="text-white font-black text-2xl mb-4 tracking-widest drop-shadow-lg">X-POS <span class="text-blue-500">APLIKASI KASIR</span></h5>
            
            <div class="flex justify-center gap-5 mb-8">
                <a href="https://www.facebook.com/share/14XVw7g8Dnj/" target="_blank" class="text-slate-400 hover:text-blue-500 transition text-2xl"><i class="fab fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@balak_600" target="_blank" class="text-slate-400 hover:text-white transition text-2xl"><i class="fab fa-tiktok"></i></a>
                <a href="https://youtube.com/@xpos2?si=VEqX8Dx0JG_QrSe8" target="_blank" class="text-slate-400 hover:text-red-500 transition text-2xl"><i class="fab fa-youtube"></i></a>
            </div>

            <div class="flex justify-center gap-6 mb-10 opacity-60">
                <span class="bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-lg font-black text-xs uppercase shadow-inner">BCA</span>
                <span class="bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-lg font-black text-xs uppercase shadow-inner">ALADIN</span>
            </div>
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-40">© <span id="currentYear"></span> XPOS Solusi Kasir UMKM Indonesia • Developed in XPOS</p>
        </div>
    </footer>

    <script async src="https://www.tiktok.com/embed.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    <script>
        // Perbaikan Loading Logic (Lebih Cepat)
        document.onreadystatechange = function() {
            if (document.readyState === "complete") {
                const preloader = document.getElementById('preloader');
                preloader.style.opacity = '0';
                setTimeout(() => { 
                    preloader.style.display = 'none'; 
                }, 300); 
            }
        };

        // Auto Year Footer
        document.getElementById('currentYear').innerText = new Date().getFullYear();

        AOS.init({ duration: 800, once: true });
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"));

        function openVideo() {
            const m = document.getElementById('videoModal');
            m.classList.remove('hidden'); m.classList.add('flex');
            document.getElementById('youtubeVideo').src = "https://www.youtube.com/embed/HyDgY-kKMKk?autoplay=1"; 
        }
        function closeVideo() {
            const m = document.getElementById('videoModal');
            m.classList.remove('flex'); m.classList.add('hidden');
            document.getElementById('youtubeVideo').src = "";
        }

        const texts = ["Rapi & Terkontrol.", "Profit Berlipat.","Penghilang Antrian.", "Anti Bocor Stok."];
        let count = 0; let index = 0; let currentText = ""; let letter = "";
        (function type() {
            if (count === texts.length) count = 0;
            currentText = texts[count]; letter = currentText.slice(0, ++index);
            document.getElementById("typewriter").textContent = letter;
            if (letter.length === currentText.length) { count++; index = 0; setTimeout(type, 2000); } 
            else { setTimeout(type, 100); }
        }());

        function calcROI() {
            let tx = document.getElementById('txRange').value;
            document.getElementById('txVal').innerText = tx + " TRX";
            document.getElementById('roiResult').innerText = (tx * 2 * 30).toLocaleString() + " Menit";
        }
        calcROI();

        const names = ["Andi", "Toko Berkah", "Sinta", "Minimarket Abadi", "Grosir Jaya", "Budi", "Apotek Sehat"];
        const places = ["Jawa Tengah", "Bali", "Bekasi", "Jawa Timur", "Jakarta", "Cikarang"];
        function showFakeNotification() {
            const t = document.getElementById('notification-toast');
            document.getElementById('toast-name').innerText = `${names[Math.floor(Math.random()*names.length)]} di ${places[Math.floor(Math.random()*places.length)]}`;
            t.classList.remove('hidden'); t.classList.add('show-toast');
            setTimeout(() => { t.classList.remove('show-toast'); t.classList.add('hidden'); }, 6000);
        }
        setTimeout(() => { setInterval(showFakeNotification, 20000); }, 3000);

      const galleries = {
    'paket_lengkap': [
        { url: 'https://down-id.img.susercontent.com/file/id-11134207-822wr-mme8zy2kef4fb6', title: 'Perangkat Lengkap XPOS Pro' }
    ],
    'software_only': [
        { url: 'https://down-id.img.susercontent.com/file/id-11134207-822wr-mme8zy2kef4fb6', title: 'Software XPOS Ultimate' }
    ],
    'album_instalasi': [
        { url: 'img/1.webp', title: 'Minimarket X-POS - Tambun Utara' }, // Tambahkan ini (Index 0)
        { url: 'img/2.webp', title: 'Kedai Kopi Abimanyu - Bekasi' },
        { url: 'img/3.webp', title: 'Apotek Sehat - Tambun' },
        { url: 'img/4.webp', title: 'Grosir Jaya Abadi - Cikarang' },
        { url: 'img/5.webp', title: 'Minimarket Lampung' },
        { url: 'img/6.webp', title: 'Grosir Sembako - Jawa Timur' },
        { url: 'img/7.webp', title: 'Toko Plastik - Medan' }
    ]
};
        
        let cGal = []; let cIdx = 0;
        function openGallery(k, startIndex = 0) { 
            cGal = galleries[k]; cIdx = startIndex; updateModal(); 
            const m = document.getElementById('imageModal');
            m.classList.remove('hidden'); m.classList.add('flex');
        }
        function closeModal() { 
            const m = document.getElementById('imageModal');
            m.classList.remove('flex'); m.classList.add('hidden');
        }
        function updateModal() { document.getElementById('modalImg').src = cGal[cIdx].url; document.getElementById('caption').innerText = cGal[cIdx].title; }
        function nextImage() { cIdx = (cIdx + 1) % cGal.length; updateModal(); }
        function prevImage() { cIdx = (cIdx - 1 + cGal.length) % cGal.length; updateModal(); }

        function toggleDarkMode() { document.body.classList.toggle('dark'); }
        function startCountdown() {
            let t = 172800;
            setInterval(() => {
                let h = Math.floor(t / 3600); let m = Math.floor((t % 3600) / 60); let s = t % 60;
                document.getElementById('countdown').innerText = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                if (t > 0) t--;
            }, 1000);
        }
        startCountdown();
        
       function liveSearch() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    
    // 1. Cari di Produk (Tab Produk)
    let productItems = document.getElementsByClassName('product-item');
    let productFound = false;
    for (let item of productItems) {
        if (item.innerText.toLowerCase().includes(input)) {
            item.style.display = "";
            productFound = true;
        } else {
            item.style.display = "none";
        }
    }

    // 2. Cari di Fitur Unggulan (Kartu-kartu fitur)
    // Kita tambahkan class 'feature-card' pada elemen fitur nanti
    let featureCards = document.querySelectorAll('[data-aos="fade-up"] .grid > div');
    for (let card of featureCards) {
        if (input === "") {
            card.style.opacity = "1";
            card.style.filter = "none";
        } else {
            if (card.innerText.toLowerCase().includes(input)) {
                card.style.opacity = "1";
                card.style.filter = "none";
                card.style.display = "";
            } else {
                card.style.opacity = "0.2"; // Efek meredup untuk yang tidak cocok
                card.style.filter = "blur(1px)";
            }
        }
    }

    // 3. Auto-Switch ke tab Produk jika user mencari nama produk
    if (input.length > 3 && productFound && !document.getElementById('produk').classList.contains('active')) {
        // Opsi: Otomatis pindah tab jika pencarian spesifik ke produk
        // switchTab(null, 'produk'); 
    }
}
        
        function switchTab(evt, tName) {
            const contents = document.getElementsByClassName("tab-content");
            for (let c of contents) c.classList.remove("active");
            const btns = document.getElementsByClassName("nav-btn");
            for (let b of btns) b.classList.remove("nav-active");
            document.getElementById(tName).classList.add("active");
            if (evt) evt.currentTarget.classList.add("nav-active");
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
        
        function orderWA(msg) { window.open(`https://wa.me/6282114975811?text=${encodeURIComponent(msg)}`, '_blank'); }

        function initVisitorCounter() {
            let currentVisitors = localStorage.getItem('xpos_visitor_count');
            if (!currentVisitors) {
                currentVisitors = 15240 + Math.floor(Math.random() * 50);
            } else {
                currentVisitors = parseInt(currentVisitors) + 1;
            }
            localStorage.setItem('xpos_visitor_count', currentVisitors);
            document.getElementById('totalVisitors').innerText = currentVisitors.toLocaleString('id-ID');
        }
        initVisitorCounter();

        function updateLiveViewers() {
            let viewers = Math.floor(Math.random() * (38 - 12 + 1)) + 12;
            let viewerEl = document.getElementById('liveViewers');
            if(viewerEl) {
                viewerEl.innerText = viewers;
            }
        }
        setInterval(updateLiveViewers, 7000);
        updateLiveViewers();

        window.addEventListener('scroll', () => {
            const btn = document.getElementById('scrollToTopBtn');
            if (window.scrollY > 300) {
                btn.classList.remove('hidden');
                btn.classList.add('flex');
            } else {
                btn.classList.add('hidden');
                btn.classList.remove('flex');
            }
        });

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function setDynamicGreeting() {
            const hour = new Date().getHours();
            let greeting = "Selamat Pagi";
            
            if(hour >= 11 && hour < 15) {
                greeting = "Selamat Siang";
            } else if(hour >= 15 && hour < 18) {
                greeting = "Selamat Sore";
            } else if(hour >= 18 || hour < 3) {
                greeting = "Selamat Malam";
            }
            
            const greetEl = document.getElementById('dynamicGreeting');
            if(greetEl) {
                greetEl.innerText = `${greeting}, kakak! 👋`;
            }
        }
        setDynamicGreeting();

        let exitPopupShown = false;
        document.addEventListener('mouseleave', function(e) {
            if(e.clientY < 0 && !exitPopupShown) {
                const popup = document.getElementById('exitPopup');
                if(popup) {
                    popup.classList.remove('hidden');
                    popup.classList.add('flex');
                    exitPopupShown = true;
                }
            }
        });

        function closeExitPopup() {
            const popup = document.getElementById('exitPopup');
            if(popup) {
                popup.classList.remove('flex');
                popup.classList.add('hidden');
            }
        }

        function triggerConfettiAndWA(msg) {
            confetti({
                particleCount: 150,
                spread: 80,
                origin: { y: 0.6 },
                colors: ['#fbbf24', '#1e40af', '#ee4d2d', '#22c55e', '#ffffff'],
                zIndex: 10001
            });
            
            setTimeout(() => {
                orderWA(msg);
            }, 1200); 
        }

        function copyRekening() {
            const rek = document.getElementById('rekBca').innerText;
            navigator.clipboard.writeText(rek).then(() => {
                alert("Nomor Rekening BCA berhasil disalin!");
            });
        }

        setTimeout(() => {
            const bubble = document.getElementById('csBubble');
            if(bubble) {
                bubble.classList.remove('hidden');
                bubble.classList.add('flex');
            }
        }, 5000);

    </script>
</body>
</html>
