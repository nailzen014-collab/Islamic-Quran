<footer class="mt-24 border-t border-black/5 py-14 dark:border-white/5">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6">
        <div class="grid gap-10 md:grid-cols-4">
            <div class="md:col-span-2">
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-3">
                    <span class="logo-mark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 1 0 0 18Zm0 0c2.5-2 4-4.5 4-7a4 4 0 0 0-8 0c0 2.5 1.5 5 4 7Z"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold">Islamic</span>
                        <span class="block text-[10px] tracking-[0.18em] text-slate-500 uppercase">Al-Qur'an & Doa</span>
                    </span>
                </a>

                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    Al-Qur'an digital, kumpulan doa harian, dan jadwal shalat berdasarkan wilayah Indonesia.
                </p>
            </div>

            <nav aria-label="Navigasi">
                <h2 class="text-sm font-semibold">Navigasi</h2>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('beranda') }}" class="link-underline hover:text-emerald-600">Beranda</a></li>
                    <li><a href="{{ route('quran.index') }}" class="link-underline hover:text-emerald-600">Al-Qur'an</a></li>
                    <li><a href="{{ route('doa.index') }}" class="link-underline hover:text-emerald-600">Kumpulan Doa</a></li>
                    <li><a href="{{ route('jadwal.index') }}" class="link-underline hover:text-emerald-600">Jadwal Shalat</a></li>
                </ul>
            </nav>

            <nav aria-label="Tautan">
                <h2 class="text-sm font-semibold">Tautan</h2>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="https://equran.id/" target="_blank" rel="noopener" class="link-underline hover:text-emerald-600">equran.id</a></li>
                </ul>
            </nav>
        </div>

        <div class="mt-12 border-t border-black/5 pt-6 text-xs text-slate-500 dark:border-white/5 dark:text-slate-400">
            <p>&copy; {{ now()->year }} Islamic Platform</p>
        </div>
    </div>
</footer>
