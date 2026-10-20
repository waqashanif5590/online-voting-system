<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$navigation = [
    'dashboard.php' => ['Dashboard', 'dashboard.php'],
    'profile.php' => ['My Profile', 'profile.php'],
    'registration.php' => ['My Applications', 'registration.php'],
    '../common/elections.php' => ['Elections', '../common/elections.php'],
];
?>
<aside class="w-full shrink-0 border-b border-slate-200 bg-white p-4 text-slate-700 md:w-64 md:border-b-0 md:border-r md:p-6">
    <a href="dashboard.php" class="mb-5 flex items-center gap-3 md:mb-8">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-700 text-xl font-bold text-white">
            <img src="" alt="">
        </span>
        <span class="text-lg font-extrabold text-slate-900">
            Vote<span class="text-purple-700">System</span>
        </span>
    </a>

    <nav aria-label="Candidate navigation" class="grid grid-cols-2 gap-2 md:grid-cols-1">
        <?php foreach ($navigation as $page => [$label, $href]):
            $isActive = $currentPage === $page;
        ?>
            <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>
                class="rounded-xl px-4 py-3 text-sm font-semibold transition <?= $isActive
                    ? 'bg-purple-50 text-purple-800 ring-1 ring-inset ring-purple-100'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-purple-700' ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>

        <a href="../public/logout.php"
            class="rounded-xl px-4 py-3 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 md:mt-4">
            Logout
        </a>
    </nav>
</aside>
