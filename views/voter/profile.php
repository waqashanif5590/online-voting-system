<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('voter');

$stmt = $pdo->prepare(
    'SELECT name, email, identity_type, identity_number, dob, gender, role
     FROM users
     WHERE id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] !== 'voter') {
    http_response_code(403);
    exit('Access denied.');
}

$escape = static fn($value): string => htmlspecialchars((string) ($value ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$identityTypeLabels = [
    'cnic' => 'CNIC',
    'passport' => 'Passport',
];
$identityType = $identityTypeLabels[$user['identity_type']] ?? ucfirst((string) $user['identity_type']);
$portalRole = 'voter';
$pageTitle = 'My Profile';
$pageDescription = 'Review the personal information associated with your voter account.';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Online Voting System</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/voter/voter_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/voter/voter_header.php'; ?>
            <main class="px-4 py-8 sm:px-6 sm:py-10 lg:px-10">
                <section class="mx-auto max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                        <h2 class="text-lg font-bold text-slate-900">Account Information</h2>
                        <p class="mt-1 text-sm text-slate-500">Your registered voter details.</p>
                    </div>
                    <dl class="grid gap-x-8 gap-y-6 p-6 sm:grid-cols-2 sm:p-8">
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Full name</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?= $escape($user['name']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Email address</dt>
                            <dd class="mt-1 break-all text-sm text-slate-900"><?= $escape($user['email']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Identity document</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?= $escape($identityType) ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Identity number</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?= $escape($user['identity_number']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Date of birth</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?= $escape($user['dob']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Gender</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?= $escape(ucfirst((string) $user['gender'])) ?></dd>
                        </div>
                    </dl>
                </section>
            </main>
        </div>
    </div>
</body>

</html>