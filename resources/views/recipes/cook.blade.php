@extends('layouts.app')

@section('title', 'Masak ' . Str::limit($recipe->name, 24))
@section('description', 'Mode masak langkah demi langkah untuk ' . $recipe->name)

@section('content')
    <div data-cook-mode class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="section-eyebrow">Mode masak</p>
                <h1 class="display-title mt-1 text-2xl sm:text-3xl">{{ $recipe->name }}</h1>
            </div>

            <a href="{{ route('recipes.show', $recipe) }}" class="btn-ghost">Kembali ke resep</a>
        </div>

        <div class="surface mt-6 rounded-[1.75rem] p-5">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <p class="text-sm font-medium" data-cook-progress-label>Langkah 1 dari {{ max(count($recipe->instructions ?? []), 1) }}</p>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="timer-ring" data-cook-timer-ring data-duration="300">
                        <span class="relative z-10 font-mono text-lg font-semibold tabular-nums" data-cook-timer>5:00</span>
                    </span>
                    <button type="button" data-cook-start="300" class="btn-ghost">Mulai timer</button>
                    <button type="button" data-cook-reset="300" class="btn-ghost">Reset</button>
                </div>
            </div>

            <div class="bar-track mt-4">
                <div class="bar-fill" data-cook-progress-bar style="width: 0%"></div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-2">
                @foreach ($recipe->instructions ?? [] as $index => $step)
                    <button type="button" data-cook-jump="{{ $index }}" data-cook-dot class="cook-dot" aria-label="Ke langkah {{ $index + 1 }}"></button>
                @endforeach
                <span class="ml-auto badge badge-ember" data-cook-progress-badge>1 / {{ max(count($recipe->instructions ?? []), 1) }}</span>
            </div>
        </div>

        @php
            $instructions = $recipe->instructions ?? [];
            $timerFor = function (string $step): int {
                preg_match('/(\d+)\s*(minute|minutes|hour|hours|min|mins)/i', $step, $matches);

                if (! isset($matches[1])) {
                    return 300;
                }

                $value = (int) $matches[1];

                return preg_match('/hour/i', $matches[2]) === 1 ? $value * 3600 : $value * 60;
            };
        @endphp

        <ol class="mt-6 space-y-4" tabindex="0" aria-label="Langkah memasak">
            @forelse ($instructions as $index => $step)
                <li data-cook-step class="cook-step surface flex items-start gap-5 rounded-[1.5rem] p-6 {{ $index === 0 ? 'is-active' : '' }}"
                    @if ($index === 0) data-cook-current @endif>
                    <span class="display-title flex size-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-ember-400 to-rose-500 text-2xl text-white shadow-lg shadow-ember-500/25">
                        {{ $index + 1 }}
                    </span>

                    <div class="min-w-0 flex-1">
                        <p class="text-lg leading-relaxed font-medium text-slate-800 dark:text-slate-100">{{ $step }}</p>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <button type="button" data-cook-start="{{ $timerFor($step) }}" class="btn-ghost">
                                Timer {{ intdiv($timerFor($step), 60) >= 1 ? intdiv($timerFor($step), 60).' menit' : $timerFor($step).' detik' }}
                            </button>
                            <button type="button" data-cook-reset="{{ $timerFor($step) }}" class="btn-ghost">Reset timer</button>
                        </div>
                    </div>
                </li>
            @empty
                <li class="surface rounded-[1.5rem] p-6 text-sm text-slate-500">Resep ini belum punya langkah memasak.</li>
            @endforelse
        </ol>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button type="button" data-cook-prev class="btn-ghost">Sebelumnya</button>
            <button type="button" data-cook-next class="btn-primary">Langkah berikutnya</button>
            <p class="ml-auto text-xs text-slate-500">Pakai tombol panah kiri dan kanan untuk navigasi.</p>
        </div>

        <div class="surface mt-8 rounded-[1.75rem] p-6">
            <h2 class="text-sm font-semibold">Bahan untuk {{ $recipe->servings }} porsi</h2>
            <ul class="mt-3 grid gap-1.5 sm:grid-cols-2">
                @foreach ($recipe->ingredients ?? [] as $ingredient)
                    <li class="text-sm text-slate-600 dark:text-slate-400">{{ $ingredient }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection