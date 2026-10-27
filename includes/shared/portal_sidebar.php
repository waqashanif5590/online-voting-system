<?php
$portalRole = $_SESSION['user_role'] ?? ($portalRole ?? '');
$portalRole = (string) $portalRole;
if ($portalRole === '') {
    $roleStatement = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $roleStatement->execute([$_SESSION['user_id']]);
    $portalRole = (string) $roleStatement->fetchColumn();
    $_SESSION['user_role'] = $portalRole;
}

$portalNavigation = [
    'admin' => [
        ['label' => 'Dashboard', 'href' => 'views/admin/dashboard.php', 'pages' => ['dashboard.php']],
        ['label' => 'Elections', 'href' => 'views/common/elections.php', 'pages' => ['elections.php']],
        ['label' => 'Voters', 'href' => 'views/admin/voters.php', 'pages' => ['voters.php']],
        ['label' => 'Candidates', 'href' => 'views/admin/candidates.php', 'pages' => ['candidates.php']],
        ['label' => 'Applications', 'href' => 'views/admin/candidates.php', 'pages' => ['candidates.php']],
        ['label' => 'Positions', 'href' => 'views/admin/positions.php', 'pages' => ['positions.php']],
        ['label' => 'Political Parties', 'href' => 'views/admin/parties.php', 'pages' => ['parties.php']],
        ['label' => 'Election Results', 'href' => 'views/admin/results.php', 'pages' => ['results.php']],
    ],
    'candidate' => [
        ['label' => 'Dashboard', 'href' => 'views/candidate/dashboard.php', 'pages' => ['dashboard.php']],
        ['label' => 'My Profile', 'href' => 'views/candidate/profile.php', 'pages' => ['profile.php']],
        ['label' => 'My Applications', 'href' => 'views/candidate/registration.php', 'pages' => ['registration.php']],
        ['label' => 'Elections', 'href' => 'views/common/elections.php', 'pages' => ['elections.php']],
        ['label' => 'Results', 'href' => 'views/common/results.php', 'pages' => ['results.php']],
    ],
    'voter' => [
        ['label' => 'Dashboard', 'href' => 'views/voter/dashboard.php', 'pages' => ['dashboard.php']],
        ['label' => 'Elections', 'href' => 'views/common/elections.php', 'pages' => ['elections.php']],
        ['label' => 'Voting', 'href' => 'views/voter/dashboard.php#elections', 'pages' => ['cast_vote.php']],
        ['label' => 'My Profile', 'href' => 'views/voter/profile.php', 'pages' => ['profile.php']],
        ['label' => 'Election Results', 'href' => 'views/common/results.php', 'pages' => ['results.php']],
    ],
];

if (!isset($portalNavigation[$portalRole])) {
    throw new RuntimeException('A valid signed-in portal role is required to render navigation.');
}

$currentPage = basename($_SERVER['PHP_SELF']);
$portalLabel = ucfirst($portalRole);
$portalHome = $portalNavigation[$portalRole][0]['href'];
?>
<aside class="w-full shrink-0 border-b border-slate-200 bg-white p-4 text-slate-700 md:w-64 md:border-b-0 md:border-r md:p-6">
    <a href="<?= htmlspecialchars(app_url($portalHome), ENT_QUOTES, 'UTF-8') ?>" class="mb-5 flex items-center gap-3 px-2 md:mb-8">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-700 text-xl font-bold text-white" aria-hidden="true">V</span>
        <span class="text-lg font-extrabold text-slate-900">Online <span class="text-purple-700">Voting</span></span>
    </a>

    <p class="mb-3 hidden px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 md:block"><?= htmlspecialchars($portalLabel, ENT_QUOTES, 'UTF-8') ?> menu</p>
    <nav aria-label="<?= htmlspecialchars($portalLabel, ENT_QUOTES, 'UTF-8') ?> navigation" class="grid grid-cols-2 gap-2 md:grid-cols-1">
        <?php foreach ($portalNavigation[$portalRole] as $item):
            $isActive = in_array($currentPage, $item['pages'], true);
        ?>
            <a href="<?= htmlspecialchars(app_url($item['href']), ENT_QUOTES, 'UTF-8') ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>
                class="rounded-xl px-4 py-3 text-sm font-semibold transition <?= $isActive
                                                                                    ? 'bg-purple-50 text-purple-800 ring-1 ring-inset ring-purple-100'
                                                                                    : 'text-slate-600 hover:bg-slate-50 hover:text-purple-700' ?>">
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>

        <a href="<?= htmlspecialchars(app_url('views/auth/logout.php'), ENT_QUOTES, 'UTF-8') ?>"
            class="rounded-xl px-4 py-3 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 md:mt-4">
            Logout
        </a>
    </nav>
</aside>