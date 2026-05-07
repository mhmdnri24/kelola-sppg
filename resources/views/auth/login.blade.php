{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — Sistem</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">  
   <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">

  <div class="flex w-full max-w-3xl min-h-[520px] rounded-xl overflow-hidden shadow-sm border border-gray-100">

    {{-- Panel Kiri --}}
    <div class="w-[44%] bg-[#1a1a2e] p-12 flex flex-col justify-between relative overflow-hidden">
      <div class="font-serif text-xl text-white tracking-wide">
        Sistem<span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-400 mb-0.5 ml-0.5"></span>
      </div>
      <div>
        <p class="font-serif text-2xl text-white leading-relaxed">
          Selamat datang<br>kembali ke <span class="text-amber-400">dapur</span><br>kendali Anda.
        </p>
        <p class="text-xs text-white/40 mt-3 font-light tracking-wide">Masuk untuk melanjutkan sesi Anda</p>
      </div>
      <p class="text-[11px] text-white/20 tracking-widest uppercase">© 2026 · Laravel App</p>
    </div>

    {{-- Panel Kanan --}}
    <div class="flex-1 bg-white p-14 flex flex-col justify-center">
      <p class="text-[11px] font-medium tracking-[2px] uppercase text-amber-500 mb-2">Autentikasi</p>
      <h1 class="font-serif text-3xl font-medium text-gray-900 mb-1">Masuk Akun</h1>
      <p class="text-sm text-gray-400 font-light mb-6">Gunakan kredensial yang terdaftar</p>
      <div class="w-8 h-0.5 bg-amber-400 rounded mb-8"></div>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-5">
          <label for="email" class="block text-[11px] font-medium uppercase tracking-wider text-gray-400 mb-2">
            Alamat Email
          </label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </span>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
              placeholder="nama@domain.com" required autofocus
              class="w-full h-11 pl-10 pr-4 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-amber-400 focus:outline-none transition-colors @error('email') border-red-400 @enderror">
          </div>
          @error('email')
            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- Password --}}
        <div class="mb-5">
          <label for="password" class="block text-[11px] font-medium uppercase tracking-wider text-gray-400 mb-2">
            Kata Sandi
          </label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </span>
            <input type="password" id="password" name="password" required
              placeholder="••••••••"
              class="w-full h-11 pl-10 pr-4 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-amber-400 focus:outline-none transition-colors">
          </div>
        </div>

        {{-- Remember & Forgot --}}
        <div class="flex items-center justify-between mb-7">
          <label class="flex items-center gap-2 text-sm text-gray-400 font-light cursor-pointer">
            <input type="checkbox" name="remember" class="accent-amber-500 w-3.5 h-3.5">
            Ingat saya
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-amber-500 hover:text-amber-600 transition-colors">
              Lupa sandi?
            </a>
          @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
          class="w-full h-12 bg-[#1a1a2e] hover:bg-[#252547] text-white text-sm font-medium tracking-widest uppercase rounded-lg flex items-center justify-center gap-2.5 transition-colors">
          <span>Masuk</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </button>

      </form>
    </div>
  </div>

</body>
</html>