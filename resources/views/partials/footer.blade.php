@php
    $columns = ['sm:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4'];
@endphp

<footer class="mt-24 border-t border-black/5 py-14 dark:border-white/5" data-parallax-scope>
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6">
        <div class="grid gap-10 md:grid-cols-4">
            <div class="md:col-span-2" data-reveal>
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-3">
                    <span class="logo-mark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 1 0 0 18Zm0 0c2.5-2 4-4.5 4-7a4 4 0 0 0-8 0c0 2.5 1.5 5 4 7Z"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold">Recipes</span>
                        <span class="block text-[10px] tracking-[0.18em] text-slate-500 uppercase">Dapur Digital</span>
                    </span>
                </a>

                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    Platform resep lengkap: cari cepat, filter, koleksi pribadi, rencana makan, dan daftar belanja otomatis.
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="chip">{{ App\Models\Recipe::count() }} resep</span>
                    <span class="chip">{{ App\Models\Recipe::allCuisines()->count() }} cuisine</span>
                    <span class="chip">Data lokal</span>
                </div>
            </div>

            <nav aria-label="Jelajahi" data-reveal style="--reveal-delay:80ms">
                <h2 class="text-sm font-semibold">Jelajahi</h2>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('beranda') }}" class="link-underline hover:text-ember-600">Beranda</a></li>
                    <li><a href="{{ route('jelajah') }}" class="link-underline hover:text-ember-600">Semua resep</a></li>
                    <li><a href="{{ route('kategori') }}" class="link-underline hover:text-ember-600">Kategori</a></li>
                    <li><a href="{{ route('statistik') }}" class="link-underline hover:text-ember-600">Statistik</a></li>
                </ul>
            </nav>

            <nav aria-label="Akun" data-reveal style="--reveal-delay:160ms">
                <h2 class="text-sm font-semibold">Dapur kamu</h2>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                    @auth
                        <li><a href="{{ route('koleksi.index') }}" class="link-underline hover:text-ember-600">Koleksi</a></li>
                        <li><a href="{{ route('belanja') }}" class="link-underline hover:text-ember-600">Daftar belanja</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="link-underline hover:text-ember-600">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="link-underline hover:text-ember-600">Daftar akun</a></li>
                    @endauth
                    <li><a href="{{ route('api.resep.index') }}" class="link-underline hover:text-ember-600">JSON API</a></li>
                </ul>
            </nav>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-black/5 pt-6 text-xs text-slate-500 dark:border-white/5 dark:text-slate-400 sm:flex-row">
            <p>&copy; {{ now()->year }} Recipes &middot; data resep dari DummyJSON</p>
            <form action="{{ route('sync') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-black/10 px-3 py-1.5 font-medium transition hover:border-ember-400 hover:text-ember-600 dark:border-white/15">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356M3.977 14.652H8.97v4.992M4.031 9.348a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m-18 4.99 3.182 3.182a8.25 8.25 0 0 0 13.803-3.7"/>
                    </svg>
                    Segarkan dari API
                </button>
            </form>
        </div>
    </div>
</footer>

@php
    unset($columns);
@endphp