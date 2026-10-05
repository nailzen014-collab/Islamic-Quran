@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="mx-auto flex w-full max-w-md flex-col justify-center px-4 py-16 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Selamat datang</p>
            <h1 class="display-title mt-2 text-3xl">Masuk ke dapurmu</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Simpan favorit, susun rencana makan, dan kelola daftar belanja.
            </p>
        </div>

        <form action="{{ route('login.store') }}" method="POST" class="surface mt-8 space-y-4 rounded-[1.75rem] p-6" data-reveal style="--reveal-delay:80ms">
            @csrf

            <div>
                <label for="email" class="field-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="field" placeholder="kamu@email.com">
            </div>

            <div>
                <label for="password" class="field-label">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="field">
            </div>

            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                <input type="checkbox" name="remember" value="1" class="checkbox">
                Ingat saya
            </label>

            <button type="submit" class="btn-primary w-full">Masuk</button>

            <p class="text-center text-xs text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="link-underline font-medium text-ember-600 dark:text-ember-400">Daftar gratis</a>
            </p>
        </form>

        <div class="surface mt-5 rounded-[1.5rem] p-5" data-reveal style="--reveal-delay:140ms">
            <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Akun demo</p>
            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-slate-500">Email</dt>
                    <dd class="font-mono text-xs">nadia@resep.id</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-slate-500">Password</dt>
                    <dd class="font-mono text-xs">password</dd>
                </div>
            </dl>
        </div>
    </section>
@endsection