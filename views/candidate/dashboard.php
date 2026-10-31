<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';
// Temporary demo values until database integration
$candidateName = $_SESSION['user_name'] ?? 'Candidate Name';

$stmt = $pdo->prepare("
    SELECT
        applications.*,
        elections.title AS election_name,
        positions.title AS position_name
    FROM applications
    INNER JOIN elections
        ON applications.election_id = elections.id
    INNER JOIN positions
        ON applications.position_id = positions.id
        WHERE applications.user_id = ?
");

$stmt->execute([$_SESSION['user_id']]);
$candidate = $stmt->fetch(PDO::FETCH_ASSOC);

if ($candidate) {
    $applicationStatus = $candidate['status'] ?? 'incomplete';
    $electionName = $candidate['election_name'] ?? 'N/A';
    $position = $candidate['position_name'] ?? 'N/A';
} else {
    $applicationStatus = 'incomplete';
    $electionName = 'N/A';
    $position = 'N/A';
}

$statusColors = [
    'incomplete' => 'bg-gray-100 text-gray-700',
    'pending' => 'bg-orange-100 text-orange-700',
    'approved' => 'bg-green-100 text-green-700',
    'rejected' => 'bg-red-100 text-red-700',
];

$statusMessages = [
    'incomplete' => 'Your Application is not completed yet.',
    'pending' => 'Your Application is pending for approval',
    'approved' => 'Congratulations! Your application has approved',
    'rejected' => 'Sorry! Your application was rejected, try next time.',
];

$statusClass = $statusColors[$applicationStatus] ?? 'bg-gray-100 text-gray-700';
$statusMessage = $statusMessages[$applicationStatus] ?? 'Your application status is currently unavailable.';
$portalRole = 'candidate';
$pageTitle = 'Dashboard';
$pageDescription = 'Welcome back, ' . $candidateName . '. Review your candidate application and election information.';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="flex min-h-[calc(100vh-65px)] flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/candidate/candidate_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/candidate/candidate_header.php'; ?>
            <main class="min-w-0 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">
            <div class="mx-auto max-w-5xl">
                <div class="grid gap-5 lg:grid-cols-2">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-bold text-slate-900">Application Status</h2>
                        <span class="inline-flex rounded-full px-3 py-1.5 text-sm font-semibold <?= $statusClass ?>">
                            <?= ucfirst(htmlspecialchars($applicationStatus, ENT_QUOTES, 'UTF-8')) ?>
                        </span>
                        <p class="mt-4 text-sm leading-6 text-slate-600">
                            <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </section>

                    <section class="rounded-2xl border border-slate-200 border-l-4 border-l-orange-400 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-bold text-slate-900">Election Information</h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex flex-wrap gap-2">
                                <dt class="font-semibold text-slate-600">Election:</dt>
                                <dd class="text-slate-800"><?= htmlspecialchars($electionName, ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <dt class="font-semibold text-slate-600">Position:</dt>
                                <dd class="text-slate-800"><?= htmlspecialchars($position, ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                        </dl>
                    </section>
                </div>

                <section class="mt-8">
                    <h2 class="mb-4 text-lg font-bold text-slate-900">Quick Actions</h2>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <a href="<?= htmlspecialchars(app_url('views/candidate/profile.php'), ENT_QUOTES, 'UTF-8') ?>" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-purple-200 hover:shadow-md">
                            <h3 class="font-bold text-purple-800">My Profile</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">View or update your account information.</p>
                        </a>
                        <a href="<?= htmlspecialchars(app_url('views/candidate/registration.php'), ENT_QUOTES, 'UTF-8') ?>" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-purple-200 hover:shadow-md">
                            <h3 class="font-bold text-blue-700">My Applications</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Review your candidate application details.</p>
                        </a>
                        <a href="<?= htmlspecialchars(app_url('views/common/elections.php'), ENT_QUOTES, 'UTF-8') ?>" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-purple-200 hover:shadow-md">
                            <h3 class="font-bold text-green-700">Elections</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">View available election information.</p>
                        </a>
                    </div>
                </section>
            </div>
            </main>
        </div>
    </div>

</body>

</html>