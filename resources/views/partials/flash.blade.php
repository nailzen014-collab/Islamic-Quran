@if (session('status'))
    <div class="mx-auto w-full max-w-7xl px-4 pt-4 sm:px-6">
        <p class="flash" role="status">{{ session('status') }}</p>
    </div>
@endif

@if ($errors->any() && ! isset($hideErrors))
    <div class="mx-auto w-full max-w-7xl px-4 pt-4 sm:px-6">
        <div class="flash border-rose-400/30 bg-rose-500/10 text-rose-600 dark:text-rose-300" role="alert">
            <p class="font-semibold">Ada yang belum beres:</p>
            <ul class="mt-1 list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif