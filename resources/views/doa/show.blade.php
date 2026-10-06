@extends('layouts.app')

@section('title', $item['nama'] ?? 'Detail Doa')

@section('content')
    <section class="mx-auto w-full max-w-4xl px-4 py-10 sm:px-6 sm:py-14">
        <div class="mb-6">
            <a href="{{ route('doa.index') }}" class="text-sm text-slate-500 hover:text-emerald-600">← Kembali ke daftar doa</a>
        </div>

        <div class="surface p-6 sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="text-xs text-slate-500">{{ $item['grup'] ?? '-' }}</span>
                    <h1 class="display-title mt-1 text-2xl sm:text-3xl">{{ $item['nama'] }}</h1>
                </div>
                <span class="badge">ID: {{ $item['id'] }}</span>
            </div>

            @if (!empty($item['ar']))
                <div class="mt-8 rounded-2xl bg-slate-50 p-6 text-right text-3xl leading-loose tracking-wide dark:bg-slate-900/60" dir="rtl">
                    {!! nl2br(e($item['ar'])) !!}
                </div>
            @endif

            @if (!empty($item['tr']))
                <div class="mt-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Latin</h2>
                    <p class="mt-2 whitespace-pre-line text-base leading-relaxed">{{ $item['tr'] }}</p>
                </div>
            @endif

            @if (!empty($item['idn']))
                <div class="mt-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Terjemah Indonesia</h2>
                    <p class="mt-2 whitespace-pre-line text-base leading-relaxed text-slate-700 dark:text-slate-300">{{ $item['idn'] }}</p>
                </div>
            @endif

            @if (!empty($item['tentang']))
                <div class="mt-8 border-t border-black/5 pt-6 dark:border-white/10">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Keterangan</h2>
                    <div class="prose prose-slate mt-3 max-w-none text-sm leading-relaxed dark:prose-invert">
                        {!! nl2br(e($item['tentang'])) !!}
                    </div>
                </div>
            @endif

            @if (!empty($item['tag']) && is_array($item['tag']))
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($item['tag'] as $tag)
                        <span class="chip">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
