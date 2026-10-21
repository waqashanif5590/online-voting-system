<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-[1600px] items-center justify-between px-4 py-3 sm:px-6">
        <a href="<?= htmlspecialchars(app_url('views/candidate/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-700 text-xl font-bold text-white">✓</span>
            <span class="text-lg font-extrabold text-slate-900">
                Online <span class="text-purple-700">Voting</span>
            </span>
        </a>
        <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-purple-700 sm:text-sm">
            Candidate Portal
        </span>
    </nav>
</header>
