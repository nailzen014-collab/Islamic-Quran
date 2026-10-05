@extends('layouts.app')

@section('title', 'Rencana Makan')
@section('description', 'Susun rencana makan mingguan per hari dan jam makan.')

@section('content')
    @php
        $prevWeek = $weekStart->copy()->subWeek()->toDateString();
        $nextWeek = $weekStart->copy()->addWeek()->toDateString();
        $weekLabel = $weekStart->translatedFormat('d M').' - '.$weekStart->copy()->addDays(6)->translatedFormat('d M Y');
        $plannedCount = $plans->flatten()->count();
    @endphp

    <section class="mx-auto w-full max-w-7xl px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
            <div>
                <p class="section-eyebrow">Perencanaan</p>
                <h1 class="display-title mt-2 text-4xl">Rencana makan</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $weekLabel }} &middot; {{ $plannedCount }} menu terjadwal.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('rencana', ['week' => $prevWeek]) }}" class="btn-ghost" aria-label="Minggu sebelumnya">&larr;</a>
                <a href="{{ route('rencana') }}" class="btn-ghost">Minggu ini</a>
                <a href="{{ route('rencana', ['week' => $nextWeek]) }}" class="btn-ghost" aria-label="Minggu berikutnya">&rarr;</a>

                @if ($plannedCount > 0)
                    <form action="{{ route('rencana.clear', ['week' => $weekStart->toDateString()]) }}" method="POST"
                          onsubmit="return confirm('Kosongkan seluruh rencana minggu ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Kosongkan minggu ini</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="mt-8 grid gap-4 lg:grid-cols-7">
            @foreach ($days as $day)
                @php
                    $dayPlans = $plans->get($day['date']->toDateString(), collect())->groupBy('slot');
                @endphp

                <div data-reveal style="--reveal-delay: {{ $loop->index * 60 }}ms"
                     class="surface rounded-[1.5rem] p-4 {{ $day['is_today'] ? 'ring-2 ring-ember-500/50' : '' }}">
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">{{ $day['label'] }}</p>
                        <p class="display-title text-lg">{{ $day['day'] }}</p>
                    </div>

                    <div class="mt-3 space-y-2">
                        @foreach ($slots as $slot)
                            @php $plan = $dayPlans->get($slot)->first(); @endphp

                            <div class="meal-slot p-2.5">
                                <p class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">{{ $slot }}</p>

                                @if ($plan)
                                    <div class="mt-1.5 group relative">
                                        <a href="{{ route('recipes.show', $plan->recipe) }}" class="block">
                                            <img src="{{ $plan->recipe->image }}" alt=""
                                                 class="h-20 w-full rounded-lg object-cover transition duration-500 group-hover:brightness-110" loading="lazy">
                                            <span class="mt-1.5 block line-clamp-2 text-xs font-medium leading-snug">{{ $plan->recipe->name }}</span>
                                            <span class="mt-0.5 block text-[10px] text-slate-500">{{ $plan->servings }} porsi</span>
                                        </a>

                                        <form action="{{ route('rencana.destroy', $plan) }}" method="POST" class="absolute right-1 top-1">
                                            @csrf @method('DELETE')
                                            <button type="submit" aria-label="Hapus dari rencana"
                                                    class="flex size-6 items-center justify-center rounded-full bg-slate-950/60 text-white opacity-0 backdrop-blur transition group-hover:opacity-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <label class="mt-1 flex cursor-pointer items-center gap-1.5 text-[11px] text-slate-400 hover:text-ember-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                        Tambah
                                    </label>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <details class="mt-3">
                        <summary class="btn-ghost w-full cursor-pointer list-none text-center text-xs">Isi hari ini</summary>

                        <form action="{{ route('rencana.store') }}" method="POST" class="mt-3 space-y-2">
                            @csrf
                            <input type="hidden" name="planned_for" value="{{ $day['date']->toDateString() }}">

                            <select name="recipe_id" required class="field !py-1.5 text-xs">
                                <option value="">Pilih resep</option>
                                @foreach ($recipes as $recipe)
                                    <option value="{{ $recipe->id }}">{{ Str::limit($recipe->name, 28) }} ({{ $recipe->total_minutes }} mnt)</option>
                                @endforeach
                            </select>

                            <select name="slot" required class="field !py-1.5 text-xs">
                                @foreach ($slots as $slot)
                                    <option value="{{ $slot }}">{{ $slot }}</option>
                                @endforeach
                            </select>

                            <input type="number" name="servings" value="2" min="1" max="20" class="field !py-1.5 text-xs" aria-label="Jumlah porsi">

                            <button type="submit" class="btn-primary w-full !py-1.5 text-xs">Simpan</button>
                        </form>
                    </details>
                </div>
            @endforeach
        </div>
    </section>
@endsection