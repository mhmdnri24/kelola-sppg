<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            jakarta: ['Plus Jakarta Sans', 'sans-serif']
          },
          colors: {
            green: {
              'dark': '#1a5c2a',
              'mid': '#217a34',
              'main': '#28a745',
              'light': '#2ecc52',
              'nav': '#22a83a',
            },
            blue: {
              'dark': '#1a2f5c',
              'mid': '#21367a',
              'main': '#2846a7',
              'light': '#2e46cc',
              'nav': '#2d22a8',
            }
          }
        }
      }
    }
  </script>
  @yield('css')
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .topbar-bg {
      background-image: url('/images/navbar-bg-3.png');
      background-size: cover;
      background-position: center;
    }

    .topbar-pattern::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg stroke='%2328a745' stroke-width='0.4' opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      pointer-events: none;
    }

    .breadcrumb-arrow {
      clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 50%, calc(100% - 10px) 100%, 0 100%);
    }

    input[type="checkbox"] {
      accent-color: #28a745;
    }
  </style>
</head>

<body class="bg-[#f4f6f8] text-[#1a1f2e] text-sm min-h-screen flex flex-col">

  <!-- TOPBAR -->
  <div class="topbar-bg topbar-pattern relative px-7 h-[80px] flex items-center justify-between overflow-hidden">
    <!-- Logo -->
    <div class="flex items-center gap-2 relative z-10">
      <div class="bg-white rounded-md px-3 py-1.5 font-bold text-[13px] text-green-dark leading-tight tracking-wide">
        STACK
        <span class="block text-[9px] font-medium text-gray-500 tracking-widest">ENTERPRISE</span>
      </div>
    </div>

    <!-- Right -->
    <div class="flex items-center gap-4 relative z-10">
      <!-- Notif -->
      <button class="text-white/75 flex items-center bg-transparent border-0 cursor-pointer">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>
      <div class="w-px h-7 bg-white/20"></div>
      <!-- Active Badge -->
      <div class="bg-green-light text-white rounded-full px-3 py-1 text-xs font-semibold flex items-center gap-1">
        ✓ Active
      </div>
      <span class="text-white/85 text-[13px] font-medium">Erha Logistic</span>
      <!-- Avatar -->
      <div class="w-9 h-9 rounde`d-full bg-white text-green-dark font-bold text-[15px] flex items-center justify-center cursor-pointer">E</div>
    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="bg-green-nav flex items-stretch px-5 gap-0.5">
    <a href="{{ route("dashboard") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">Halaman Utama</a>
    @if(auth()->user()->hasAnyRole('supplier'))
    <a href="{{ route("katalog") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">     
      Daftar Bahan     
    </a>
    @endif


    <a href="{{ route("etalase") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">
        @if(auth()->user()->hasAnyRole('supplier'))
     Isi Etalase
      @else
      E-katalog
      @endif
    </a>
   
    

    @if(auth()->user()->hasAnyRole('dapur|admin'))
    <a href="{{ route("keranjang") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">Keranjang
      <span class="bg-white px-3 py-1 text-green-dark rounded-full text-xs font-semibold" id="cartCount">@{{ cartCount }}</span>
    </a>
    <a href="{{ route("anggaran") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">Angaran</a>
    @endif

    <a href="{{ route("daftar-pesanan") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">
      @if(auth()->user()->hasAnyRole('supplier'))
      Pesanan Masuk
      @else
      Daftar Pesanan
      @endif
    </a>

    @if(auth()->user()->hasAnyRole('dapur|admin'))
    <a href="#" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">Daftar Tagihan</a>
    @endif

    <a href="{{ route("logout") }}" class="px-4 h-11 flex items-center gap-1 text-[13.5px] font-medium text-white/90 rounded-t cursor-pointer hover:bg-white/10 transition-colors">Logout</a>
  </nav>

  <!-- BREADCRUMB -->
  @if($breadcrumbs ?? false)
  <div class="bg-white px-6 border-b border-gray-200 py-3">
    <nav class="flex items-center text-sm font-medium text-gray-500 space-x-2">
      @foreach($breadcrumbs as $breadcrumb)
      @if($loop->last)
      <span class="text-gray-900 font-semibold">
        {{ $breadcrumb['name'] }}
      </span>
      @else
      <a href="{{ $breadcrumb['url'] }}" class="flex items-center gap-2 text-green-700 hover:text-green-900">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        {{ $breadcrumb['name'] }}
      </a>
      <span>/</span>
      @endif
      @endforeach
    </nav>
  </div>
  @endif

  <!-- MAIN -->
  <main class="flex-1 px-7 pt-7">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-5">
      <h1 class="text-[22px] font-bold text-[#1a1f2e]">{{ $title ?? 'Dashboard' }}</h1>
      @yield('header_actions')
    </div>

    <!-- Toolbar -->
    <div class="hidden flex items-center justify-between bg-white border border-gray-200 border-b-0 rounded-t-lg px-4 py-3">
      <!-- Left -->
      <div class="flex items-center gap-2.5">
        <!-- Search -->
        <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-1.5 bg-[#f4f6f8] w-56">
          <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <path d="M21 21l-4.35-4.35" />
          </svg>
          <input type="text" placeholder="Search..." class="border-0 bg-transparent outline-none text-[13px] text-[#1a1f2e] placeholder-gray-400 w-full font-[inherit]">
        </div>
        <!-- Filter -->
        <button class="flex items-center gap-1.5 border border-gray-200 bg-white rounded-lg px-3.5 py-1.5 text-[13px] font-medium text-[#1a1f2e] cursor-pointer hover:bg-[#f4f6f8] transition-colors font-[inherit]">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 4h18M7 8h10M11 12h4" />
          </svg>
          Filter
        </button>
      </div>

      <!-- Right icon buttons -->
      <div class=" flex items-center gap-2">
        <div class="w-[34px] h-[34px] border border-gray-200 bg-white rounded-md flex items-center justify-center cursor-pointer text-gray-400 hover:bg-[#f4f6f8] hover:text-[#1a1f2e] transition-colors" title="List">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div class="w-[34px] h-[34px] border border-gray-200 bg-white rounded-md flex items-center justify-center cursor-pointer text-gray-400 hover:bg-[#f4f6f8] hover:text-[#1a1f2e] transition-colors" title="Kanban">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" />
            <rect x="14" y="3" width="7" height="7" />
            <rect x="14" y="14" width="7" height="7" />
            <rect x="3" y="14" width="7" height="7" />
          </svg>
        </div>
        <div class="w-[34px] h-[34px] border border-gray-200 bg-white rounded-md flex items-center justify-center cursor-pointer text-gray-400 hover:bg-[#f4f6f8] hover:text-[#1a1f2e] transition-colors" title="Chart">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M18 20V10M12 20V4M6 20v-6" />
          </svg>
        </div>
        <div class="w-[34px] h-[34px] border border-gray-200 bg-white rounded-md flex items-center justify-center cursor-pointer text-gray-400 hover:bg-[#f4f6f8] hover:text-[#1a1f2e] transition-colors" title="Calendar">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
          </svg>
        </div>
        <div class="w-[34px] h-[34px] border border-gray-200 bg-white rounded-md flex items-center justify-center cursor-pointer text-gray-400 hover:bg-[#f4f6f8] hover:text-[#1a1f2e] transition-colors" title="Download">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" />
          </svg>
        </div>
        <div class="w-[34px] h-[34px] border border-gray-200 bg-white rounded-md flex items-center justify-center cursor-pointer text-gray-400 hover:bg-[#f4f6f8] hover:text-[#1a1f2e] transition-colors" title="Export">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
            <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
          </svg>
        </div>
      </div>
    </div>

    @yield('content')

  </main>

  <!-- FOOTER -->
  <div class="mt-7 px-7 py-4 flex justify-between items-center text-xs text-gray-500 border-t border-gray-200 bg-white">
    <span>© 2026 Erha Logistic. All rights reserved.</span>
    <div class="flex items-center gap-5">
      <a href="#" class="text-gray-500 no-underline hover:text-green-dark transition-colors">Documentation</a>
      <a href="#" class="text-gray-500 no-underline hover:text-green-dark transition-colors">Support</a>
      <span>|</span>
      <span class="font-semibold text-[#1a1f2e]">v1.2.0-ext</span>
    </div>
  </div>
  <script>
    const {
      createApp
    } = Vue;

    createApp({
      data() {
        return {
          cartCount: 0
        }
      },
      mounted() {
        this.getCartCount()
      },
      methods: {
        async getCartCount() {
          let response = await fetch("{{ route('cart.count') }}")
          let data = await response.json()
          this.cartCount = data
        },
      }
    }).mount('#cartCount')
  </script>
  @yield('js')
</body>

</html>