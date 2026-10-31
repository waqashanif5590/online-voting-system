<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('admin');
if (isset($_GET['user'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_GET['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
$statColors = [
    true => 'bg-green-50 text-green-700',
    false => 'bg-red-50 text-red-500'
];

do {
    $token = (string) random_int(10000000, 99999999);

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM voters WHERE token = ?"
    );

    $stmt->execute([$token]);
} while ($stmt->fetchColumn() > 0);

$stmt = $pdo->prepare("SELECT * FROM elections WHERE status=?");
$stmt->execute(['upcoming']);
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    checkCsrfToken();
    $voter = $_GET['user'];
    $election = $_POST['election'];
    $token = $_POST['token'];

    $stmt = $pdo->prepare("SELECT * FROM voters WHERE user_id=? AND election_id=?");
    $stmt->execute([$voter, $election]);
    $assigned_token = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($assigned_token) {
        echo "<script>alert('Token already assigned')</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO voters (user_id, token, election_id) VALUES (?,?,?)");
        $insertion = $stmt->execute([$voter, $token, $election]);
        if ($insertion) {
            header('Location: ' . app_url('views/admin/voters.php'));
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Assign a voting token to a voter for an election">
    <title>Assign Voting Token | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php $portalRole = 'admin';
        include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php
            $pageTitle = 'Assign Voting Token';
            $pageDescription = 'Generate a unique voting token and assign it to a voter for a selected election.';
            include APP_ROOT . '/includes/shared/portal_header.php';
            ?>
            <main class="min-w-0 flex-1">
                <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                    <a href="dashboard.php#voters" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-700 hover:text-purple-900">&larr; Back to Voters</a>

                    <form action="./assign_token.php?user=<?= $_GET['user'] ?>" method="post" class="grid gap-6 lg:grid-cols-2 lg:items-start">
                        <?= csrfInput() ?>
                        <div class="space-y-6">
                            <section aria-labelledby="voter-heading"
                                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                <div class="mb-5">
                                    <p class="text-xs font-bold uppercase tracking-wider text-purple-700">Voter details</p>
                                    <h2 id="voter-heading" class="mt-1 text-lg font-bold text-slate-900">Selected voter</h2>
                                </div>
                                <div class="flex items-start gap-4">
                                    <span
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-purple-100 text-lg font-extrabold text-purple-800"
                                        aria-label="Ahmed Khan initials">AK</span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-base font-bold text-slate-900"><?= $user['name'] ?></h3>
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold <?= $statColors[$user['status']] ?>">
                                                <span class="h-1.5 w-1.5 rounded-full <?= $user['status'] == true ? 'bg-green-500' : 'bg-red-500' ?>"
                                                    aria-hidden="true"></span>
                                                <?= $user['status'] == true ? 'Active' : 'Blocked' ?>
                                            </span>
                                        </div>
                                        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                                            <div>
                                                <dt class="text-xs font-medium text-slate-500">Identity Number</dt>
                                                <dd class="mt-1 font-semibold text-slate-700"><?= $user['identity_number'] ?></dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs font-medium text-slate-500">Email</dt>
                                                <dd class="mt-1 break-all font-semibold text-blue-700">
                                                    <a href="mailto:ahmed@example.com"
                                                        class="hover:underline"><?= $user['email'] ?></a>
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </section>

                            <section aria-labelledby="election-heading"
                                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                <div class="mb-5">
                                    <h2 id="election-heading" class="text-lg font-bold text-slate-900">Select Election</h2>
                                    <p class="mt-1 text-sm text-slate-500">Choose the election this voting token is for.</p>
                                </div>
                                <label for="election" class="mb-2 block text-sm font-semibold text-slate-700">Election</label>
                                <select id="election" name="election"
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                    <option value="" selected>Select an election</option>
                                    <?php foreach ($elections as $key => $election) {
                                        echo '<option value="' . $election['id'] . '">' . $election['title'] . '</option>';
                                    } ?>
                                </select>
                                <p class="mt-3 flex items-start gap-2 text-xs leading-5 text-blue-700">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path stroke-linecap="round" d="M12 11v5m0-8h.01" />
                                    </svg>
                                    This token will be assigned to the selected election only.
                                </p>
                            </section>
                        </div>

                        <div class="space-y-6">
                            <section aria-labelledby="token-heading"
                                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <h2 id="token-heading" class="text-lg font-bold text-slate-900">Generated Voting
                                            Token</h2>
                                        <p class="mt-1 text-sm text-slate-500">Review the token before assignment.</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-700">Newly
                                        generated</span>
                                </div>

                                <div class="mt-6 rounded-xl border border-purple-100 bg-purple-50/60 px-4 py-6 text-center">
                                    <p class="text-xs font-bold uppercase tracking-widest text-purple-700">Voting token</p>
                                    <p
                                        class="mt-3 break-all font-mono text-2xl font-extrabold tracking-wider text-slate-900 sm:text-3xl">
                                        <?= $token ?></p>
                                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                                </div>

                                <p class="mt-4 flex items-start gap-2 text-sm leading-6 text-slate-500">
                                    <svg class="mt-1 h-4 w-4 shrink-0 text-blue-600" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path stroke-linecap="round" d="M12 11v5m0-8h.01" />
                                    </svg>
                                    This token is intended for one-time voting access in the selected election.
                                </p>

                                <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                                    <button type="button"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-purple-200 bg-white px-4 py-3 text-sm font-bold text-purple-800 transition hover:bg-purple-50 focus:outline-none focus:ring-4 focus:ring-purple-100">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 7v5h-5M4 17v-5h5m-3.5-3A7 7 0 0 1 18 7l2 5M4 12l2 5a7 7 0 0 0 12.5-2" />
                                        </svg>
                                        Regenerate Token
                                    </button>
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-100">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m5 12 4 4L19 6" />
                                        </svg>
                                        Assign Token
                                    </button>
                                </div>
                            </section>

                            <aside aria-labelledby="token-guidance"
                                class="rounded-2xl border border-amber-200 bg-amber-50 p-5 sm:p-6">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700"
                                        aria-hidden="true">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3l-7.6-13.1a2 2 0 0 0-3.4 0Z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h2 id="token-guidance" class="font-bold text-amber-900">Token security</h2>
                                        <ul class="mt-2 space-y-2 text-sm leading-5 text-amber-900/80">
                                            <li>Each token should be unique and used only for its assigned election.</li>
                                            <li>A voter should not vote more than once in the same election.</li>
                                            <li>Share the token securely with the intended voter.</li>
                                        </ul>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>