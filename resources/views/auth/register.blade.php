@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <section class="mx-auto flex w-full max-w-md flex-col justify-center px-4 py-16 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Gratis</p>
            <h1 class="display-title mt-2 text-3xl">Bikin akun dapur</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Akun ini dipakai untuk koleksi, favorit, rencana makan, dan daftar belanja.
            </p>
        </div>

        <form action="{{ route('register.store') }}" method="POST" class="surface mt-8 space-y-4 rounded-[1.75rem] p-6" data-reveal style="--reveal-delay:80ms">
            @csrf

            <div>
                <label for="name" class="field-label">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus maxlength="60" autocomplete="name" class="field" placeholder="Nama kamu">
            </div>

            <div>
                <label for="email" class="field-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="field" placeholder="kamu@email.com">
            </div>

            <div>
                <label for="password" class="field-label">Password</label>
                <input id="password" name="password" type="password" required minlength="6" autocomplete="new-password" class="field">
            </div>

            <div>
                <label for="password_confirmation" class="field-label">Ulangi password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="field">
            </div>

            <button type="submit" class="btn-primary w-full">Daftar sekarang</button>

            <p class="text-center text-xs text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="link-underline font-medium text-ember-600 dark:text-ember-400">Masuk</a>
            </p>
        </form>
    </section>
@endsection