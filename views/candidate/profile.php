<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/views/auth/check_auth.php';

if (empty($_SESSION['login']) || $_SESSION['login'] !== true || empty($_SESSION['user_id'])) {
    header('Location: ' . app_url('views/auth/login.php'));
    exit;
}

require_once APP_ROOT . '/app/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $identityType = $_POST['identity_type'] ?? '';
    $identityNumber = $_POST['identity_number'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $stmt = $pdo->prepare(
        'UPDATE users
         SET name = ?, email = ?, identity_type = ?, identity_number = ?, dob = ?, gender = ?
         WHERE id = ?'
    );
    $stmt->execute([$username, $email, $identityType, $identityNumber, $dob, $gender, $_SESSION['user_id']]);

    header('Location: ' . app_url('views/candidate/profile.php?success=1'));
    exit();
}

$stmt = $pdo->prepare(
    'SELECT name, email, identity_type, identity_number, dob, gender, role
     FROM users
     WHERE id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ' . app_url('views/auth/login.php'));
    exit;
}

if ($user['role'] !== 'candidate') {
    header('Location: ' . app_url('views/auth/login.php'));
    exit;
}

$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$identityType = [
    'cnic' => 'CNIC',
    'passport' => 'Passport',
][$user['identity_type']] ?? ucfirst($user['identity_type']);
$gender = ucfirst($user['gender']);
$dateOfBirth = $user['dob'];
$portalRole = 'candidate';
$pageTitle = 'My Profile';
$pageDescription = 'Review and update the personal details associated with your candidate account.';
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
    <div class="flex min-h-[calc(100vh-65px)] flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/candidate/candidate_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/candidate/candidate_header.php'; ?>
            <main class="min-w-0 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">
            <div class="mx-auto max-w-4xl">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                        <h2 class="text-lg font-bold text-slate-900">Account Information</h2>
                    </div>
                    <form action="<?= htmlspecialchars(app_url('views/candidate/profile.php'), ENT_QUOTES, 'UTF-8') ?>" method="post">
                        <div class="grid gap-x-8 gap-y-6 p-6 sm:grid-cols-2 sm:p-8">
                            <div>
                                <label for="fullName" class="mb-2 block text-sm font-semibold text-slate-700">Full name</label>
                                <input id="fullName" type="text" name="name" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-purple-600 focus:ring-4 focus:ring-purple-100" value="<?= $escape($user['name']) ?>">
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email address</label>
                                <input id="email" type="email" name="email" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-purple-600 focus:ring-4 focus:ring-purple-100" value="<?= $escape($user['email']) ?>">
                            </div>
                            <div>
                                <label for="identityType" class="mb-2 block text-sm font-semibold text-slate-700">Identity document</label>
                                <select id="identityType" name="identity_type" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="cnic" <?= $user['identity_type'] === 'cnic' ? 'selected' : '' ?>>CNIC</option>
                                    <option value="passport" <?= $user['identity_type'] === 'passport' ? 'selected' : '' ?>>Passport</option>
                                </select>
                            </div>
                            <div>
                                <label for="identityNumber" class="mb-2 block text-sm font-semibold text-slate-700">Identity number</label>
                                <input id="identityNumber" type="text" name="identity_number" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-purple-600 focus:ring-4 focus:ring-purple-100" value="<?= $escape($user['identity_number']) ?>">
                            </div>
                            <div>
                                <label for="dateOfBirth" class="mb-2 block text-sm font-semibold text-slate-700">Date of birth</label>
                                <input id="dateOfBirth" type="date" name="dob" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-purple-600 focus:ring-4 focus:ring-purple-100" value="<?= $escape($dateOfBirth) ?>">
                            </div>
                            <div>
                                <label for="gender" class="mb-2 block text-sm font-semibold text-slate-700">Gender</label>
                                <select name="gender" id="gender" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div>
                                <label for="accountType" class="mb-2 block text-sm font-semibold text-slate-700">Account type</label>
                                <div id="accountType" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800">
                                    <?= $escape(ucfirst($user['role'])) ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end border-t border-slate-100 bg-slate-50 px-6 py-4 sm:px-8">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-purple-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                                Save Profile
                            </button>
                        </div>
                    </form>
                </section>
            </div>
            </main>
        </div>
    </div>
</body>

</html>