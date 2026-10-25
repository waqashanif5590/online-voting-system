<?php
if (!isset($pageTitle, $pageDescription)) {
    throw new RuntimeException('Page title and description are required to render the portal header.');
}

$headerRole = ucfirst($_SESSION['user_role'] ?? ($portalRole ?? ''));
$headerName = $_SESSION['user_name'] ?? 'Account';
$headerInitials = '';
foreach (preg_split('/\s+/', trim($headerName)) as $namePart) {
    if ($namePart !== '') {
        $headerInitials .= strtoupper(substr($namePart, 0, 1));
    }
}
$headerInitials = substr($headerInitials, 0, 2);
?>
<header class="border-b border-slate-200 bg-white">
    <div class="flex flex-col justify-between gap-4 px-5 py-5 sm:flex-row sm:items-center sm:px-8 lg:px-10">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500"><?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($headerName, ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-xs text-slate-500"><?= htmlspecialchars($headerRole, ENT_QUOTES, 'UTF-8') ?> account</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 text-sm font-bold text-purple-700" aria-hidden="true">
                <?= htmlspecialchars($headerInitials, ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>
    </div>
</header>
