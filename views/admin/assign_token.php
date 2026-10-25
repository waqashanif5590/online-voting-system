<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
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

$elections = $pdo->query("SELECT * FROM elections")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            echo "<script>alert('Token Assigned to voter')</script>";
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

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen md:flex">
        <aside class="bg-purple-950 text-purple-100 md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <div class="flex items-center gap-3 border-b border-white/10 px-5 py-5 md:px-6">
                <span
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500 text-xl font-extrabold text-white"
                    aria-hidden="true">V</span>
                <div>
                    <p class="font-extrabold tracking-tight text-white">Vote<span class="text-orange-400">System</span>
                    </p>
                    <p class="text-xs text-purple-300">Administration</p>
                </div>
            </div>

            <nav aria-label="Admin navigation"
                class="overflow-x-auto px-3 py-4 md:flex-1 md:overflow-x-hidden md:overflow-y-auto">
                <p class="hidden px-3 pb-3 text-xs font-bold uppercase tracking-widest text-purple-400 md:block">
                    Workspace</p>
                <ul class="flex w-max gap-1 md:w-auto md:flex-col">
                    <li><a href="dashboard.php#dashboard"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <rect x="3" y="3" width="8" height="8" rx="2" />
                                <rect x="13" y="3" width="8" height="5" rx="2" />
                                <rect x="13" y="10" width="8" height="11" rx="2" />
                                <rect x="3" y="13" width="8" height="8" rx="2" />
                            </svg><span>Dashboard</span></a></li>
                    <li><a href="dashboard.php#elections"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2" />
                                <path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18" />
                            </svg><span>Elections</span></a></li>
                    <li><a href="dashboard.php#voters" aria-current="page"
                            class="flex items-center gap-3 rounded-xl bg-white/10 px-3 py-2.5 text-sm font-semibold text-white ring-1 ring-inset ring-white/10">
                            <svg class="h-5 w-5 shrink-0 text-orange-300" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round"
                                    d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" />
                            </svg><span>Voters</span></a></li>
                    <li><a href="dashboard.php#candidates"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="8" r="4" />
                                <path stroke-linecap="round" d="M5 21v-2a7 7 0 0 1 14 0v2M19 8h3m-1.5-1.5v3" />
                            </svg><span>Candidates</span></a></li>
                    <li><a href="dashboard.php#applications"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path stroke-linecap="round" d="M14 2v6h6M8 13h8M8 17h8" />
                            </svg><span>Candidate Applications</span></a></li>
                    <li><a href="dashboard.php#positions"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 1 1-4 0v-.2A2 2 0 0 0 5.8 17l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a2 2 0 0 0-1.4-3.4h-.3a2 2 0 1 1 0-4h.2A2 2 0 0 0 3 4.2l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a2 2 0 0 0 3.4-1.4v-.3a2 2 0 1 1 4 0v.2A2 2 0 0 0 17.2 1l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a2 2 0 0 0 1.4 3.4h.3a2 2 0 1 1 0 4h-.2a2 2 0 0 0-2.1 3.8Z"
                                    transform="translate(1 2) scale(.9)" />
                            </svg><span>Positions</span></a></li>
                    <li><a href="dashboard.php#parties"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3 3 8l9 5 9-5-9-5ZM3 12l9 5 9-5M3 16l9 5 9-5" />
                            </svg><span>Political Parties</span></a></li>
                    <li><a href="dashboard.php#election-results"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 19V5m0 14h17M8 15v-4m5 4V7m5 8v-6" />
                            </svg><span>Election Results</span></a></li>
                    <li><a href="dashboard.php#settings"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 1 1-4 0v-.2A2 2 0 0 0 5.8 17l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a2 2 0 0 0-1.4-3.4h-.3a2 2 0 1 1 0-4h.2A2 2 0 0 0 3 4.2l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a2 2 0 0 0 3.4-1.4v-.3a2 2 0 1 1 4 0v.2A2 2 0 0 0 17.2 1l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a2 2 0 0 0 1.4 3.4h.3a2 2 0 1 1 0 4h-.2a2 2 0 0 0-1.6 2.3Z"
                                    transform="translate(1 2) scale(.9)" />
                            </svg><span>Settings</span></a></li>
                    <li class="md:hidden"><a href="#"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14 5-5-5-5m5 5H9" />
                            </svg><span>Logout</span></a></li>
                </ul>
            </nav>

            <div class="hidden border-t border-white/10 p-3 md:block">
                <a href="#"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14 5-5-5-5m5 5H9" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <main class="min-w-0 flex-1 md:ml-64">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
                    <nav aria-label="Breadcrumb" class="text-sm">
                        <ol class="flex flex-wrap items-center gap-2 text-slate-500">
                            <li><a href="dashboard.php#dashboard" class="hover:text-purple-700">Dashboard</a></li>
                            <li aria-hidden="true"><span>/</span></li>
                            <li><a href="dashboard.php#voters" class="hover:text-purple-700">Voters</a></li>
                            <li aria-hidden="true"><span>/</span></li>
                            <li class="font-semibold text-slate-800" aria-current="page">Assign Token</li>
                        </ol>
                    </nav>
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Assign Voting
                                Token</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Generate a unique voting token
                                and assign it to a voter for a selected election.</p>
                        </div>
                        <a href="dashboard.php#voters"
                            class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-purple-100 sm:self-auto">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6M9 12h12" />
                            </svg>
                            Back to Voters
                        </a>
                    </div>
                </div>
            </header>

            <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <form action="./assign_token.php?user=<?= $_GET['user'] ?>" method="post" class="grid gap-6 lg:grid-cols-2 lg:items-start">
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
</body>

</html>