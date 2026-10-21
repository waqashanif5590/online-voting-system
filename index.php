
<?php
require_once __DIR__ . '/app/paths.php';

$pageTitle = 'Online Voting System | Your Vote, Your Voice';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <meta name="description"
          content="Register as a voter or candidate, participate in elections, and follow a transparent voting process.">

    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="bg-white text-slate-800 antialiased">

<!-- Navigation -->
<header class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

        <a href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-700 text-2xl text-white">
                ✓
            </span>
            <span class="text-lg font-extrabold tracking-tight text-slate-900 sm:text-xl">
                Online <span class="text-purple-700">Voting</span>
            </span>
        </a>

        <div class="hidden items-center gap-8 text-sm font-semibold md:flex">
            <a href="#home" class="text-purple-700 hover:text-purple-500">Home</a>
            <a href="#about" class="hover:text-purple-700">About</a>
            <a href="#how-it-works" class="hover:text-purple-700">How It Works</a>
            <a href="#contact" class="hover:text-purple-700">Contact</a>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>"
               class="hidden rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold hover:border-purple-600 hover:text-purple-700 sm:inline-block">
                Login
            </a>

            <a href="<?= htmlspecialchars(app_url('views/auth/register.php'), ENT_QUOTES, 'UTF-8') ?>"
               class="rounded-full bg-purple-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-purple-200 transition hover:bg-purple-800">
                Get Started
            </a>
        </div>

    </nav>
</header>


<!-- Hero Section -->
<main>

<section id="home" class="relative overflow-hidden bg-gradient-to-br from-purple-50 via-white to-blue-50">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 py-16 md:grid-cols-2 md:py-24 lg:px-8">

        <div>
            <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                Secure · Transparent · Accessible
            </div>

            <h1 class="max-w-xl text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                Your Vote
                <span class="block text-purple-700">Your Voice.</span>
                <span class="block">
                    A Better
                    <span class="text-orange-500">Tomorrow.</span>
                </span>
            </h1>

            <p class="mt-6 max-w-lg text-base leading-8 text-slate-600 sm:text-lg">
                Take part in the decisions that matter. Register as a voter,
                explore approved candidates, and cast your vote through a
                simple, organized election process.
            </p>

            <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                <a href="<?= htmlspecialchars(app_url('views/voter/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-purple-700 px-6 py-3.5 font-bold text-white shadow-lg shadow-purple-200 transition hover:-translate-y-0.5 hover:bg-purple-800">
                    Go to Voter Portal
                    <span aria-hidden="true">→</span>
                </a>

                <a href="<?= htmlspecialchars(app_url('views/candidate/registration.php'), ENT_QUOTES, 'UTF-8') ?>"
                   class="inline-flex items-center justify-center rounded-xl border-2 border-purple-200 bg-white px-6 py-3.5 font-bold text-purple-800 transition hover:border-purple-600 hover:bg-purple-50">
                    Register as Candidate
                </a>
            </div>

            <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-600">
                <span class="flex items-center gap-2">
                    <span class="font-bold text-green-600">✓</span>
                    Simple registration
                </span>
                <span class="flex items-center gap-2">
                    <span class="font-bold text-green-600">✓</span>
                    One vote per election
                </span>
            </div>
        </div>


        <!-- Voting Illustration -->
        <div class="relative mx-auto w-full max-w-lg">

            <div class="absolute -left-5 top-12 h-28 w-28 rounded-full bg-orange-200/60 blur-2xl"></div>
            <div class="absolute -right-5 bottom-8 h-40 w-40 rounded-full bg-blue-200/70 blur-3xl"></div>

            <div class="relative rounded-3xl border border-white bg-white/80 p-5 shadow-2xl shadow-purple-100 sm:p-8">

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Election participation</p>
                        <h2 class="mt-1 text-xl font-extrabold text-slate-900">
                            Make Your Choice
                        </h2>
                    </div>

                    <span class="rounded-xl bg-purple-100 px-3 py-2 text-2xl text-purple-700">
                        ✓
                    </span>
                </div>

                <div class="space-y-4">

                    <div class="flex items-center gap-4 rounded-2xl border border-purple-100 bg-purple-50 p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-700 text-xl font-bold text-white">
                            1
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">Register</p>
                            <p class="mt-1 text-sm text-slate-500">Create your account</p>
                        </div>
                        <span class="font-bold text-purple-700">→</span>
                    </div>

                    <div class="flex items-center gap-4 rounded-2xl border border-orange-100 bg-orange-50 p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-500 text-xl font-bold text-white">
                            2
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">Verify Access</p>
                            <p class="mt-1 text-sm text-slate-500">Use your voting token if required</p>
                        </div>
                        <span class="font-bold text-orange-600">→</span>
                    </div>

                    <div class="flex items-center gap-4 rounded-2xl border border-green-100 bg-green-50 p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-600 text-xl font-bold text-white">
                            3
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">Cast Your Vote</p>
                            <p class="mt-1 text-sm text-slate-500">Choose an approved candidate</p>
                        </div>
                        <span class="font-bold text-green-700">✓</span>
                    </div>

                </div>

                <div class="mt-6 rounded-2xl bg-gradient-to-r from-purple-700 to-blue-600 p-5 text-white">
                    <p class="text-sm text-purple-100">Every vote matters</p>
                    <p class="mt-1 text-xl font-extrabold">Participate. Choose. Make a Difference.</p>
                </div>

            </div>
        </div>

    </div>
</section>


<!-- Features -->
<section id="about" class="py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-purple-700">
                Why Use Our Platform?
            </p>

            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Everything You Need to Participate
            </h2>

            <p class="mt-4 leading-7 text-slate-600">
                A straightforward platform for managing candidates,
                voters, and election participation.
            </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

            <article class="rounded-2xl border border-purple-100 bg-purple-50/60 p-6 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-700 text-2xl text-white">
                    ✓
                </div>
                <h3 class="mt-5 text-lg font-bold text-slate-900">Secure Access</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Account authentication and voting-token validation help prevent unauthorized voting.
                </p>
            </article>

            <article class="rounded-2xl border border-orange-100 bg-orange-50/60 p-6 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500 text-2xl text-white">
                    +
                </div>
                <h3 class="mt-5 text-lg font-bold text-slate-900">Candidate Registration</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Candidates can submit their applications and track their approval status.
                </p>
            </article>

            <article class="rounded-2xl border border-green-100 bg-green-50/60 p-6 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-600 text-2xl text-white">
                    ✓
                </div>
                <h3 class="mt-5 text-lg font-bold text-slate-900">Clear Process</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Approved candidates and election status are presented clearly to eligible voters.
                </p>
            </article>

            <article class="rounded-2xl border border-blue-100 bg-blue-50/60 p-6 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-2xl text-white">
                    ↗
                </div>
                <h3 class="mt-5 text-lg font-bold text-slate-900">Easy to Use</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    A responsive interface makes the platform convenient on desktop and mobile devices.
                </p>
            </article>

        </div>
    </div>
</section>


<!-- How It Works -->
<section id="how-it-works" class="bg-slate-50 py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-600">
                How It Works
            </p>
            <h2 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Four Simple Steps
            </h2>
            <p class="mx-auto mt-4 max-w-xl leading-7 text-slate-600">
                Follow the process from account registration to vote confirmation.
            </p>
        </div>

        <div class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-purple-100 text-2xl font-extrabold text-purple-700">
                    1
                </div>
                <h3 class="mt-5 font-bold text-slate-900">Create an Account</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Register or log in using your assigned account.
                </p>
            </div>

            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 text-2xl font-extrabold text-orange-600">
                    2
                </div>
                <h3 class="mt-5 font-bold text-slate-900">Verify Eligibility</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Confirm your eligibility and enter your token when required.
                </p>
            </div>

            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-2xl font-extrabold text-green-700">
                    3
                </div>
                <h3 class="mt-5 font-bold text-slate-900">Select a Candidate</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Review the approved candidates and submit your choice.
                </p>
            </div>

            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-2xl font-extrabold text-blue-700">
                    4
                </div>
                <h3 class="mt-5 font-bold text-slate-900">Get Confirmation</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Receive confirmation that your ballot was recorded.
                </p>
            </div>

        </div>

    </div>
</section>


<!-- Portal Access -->
<section class="py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="mb-10 text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-purple-700">
                Choose Your Portal
            </p>
            <h2 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Get Started Today
            </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-3">

            <article class="rounded-2xl border border-purple-100 p-7 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-2xl text-purple-700">
                    👤
                </div>
                <h3 class="mt-5 text-xl font-bold">Voter Portal</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Access your eligible elections, verify your token, and cast your vote.
                </p>
                <a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>"
                   class="mt-6 inline-flex items-center gap-2 font-bold text-purple-700 hover:text-purple-900">
                    Voter Login <span aria-hidden="true">→</span>
                </a>
            </article>

            <article class="rounded-2xl border border-orange-100 p-7 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-2xl text-orange-600">
                    ✎
                </div>
                <h3 class="mt-5 text-xl font-bold">Candidate Portal</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Submit your candidacy application and follow its approval progress.
                </p>
                <a href="<?= htmlspecialchars(app_url('views/candidate/registration.php'), ENT_QUOTES, 'UTF-8') ?>"
                   class="mt-6 inline-flex items-center gap-2 font-bold text-orange-600 hover:text-orange-800">
                    Candidate Registration <span aria-hidden="true">→</span>
                </a>
            </article>

            <article class="rounded-2xl border border-blue-100 p-7 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl text-blue-700">
                    ⚙
                </div>
                <h3 class="mt-5 text-xl font-bold">Admin Dashboard</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Authorized administrators can manage elections, applications, voters, and tokens.
                </p>
                <a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>"
                   class="mt-6 inline-flex items-center gap-2 font-bold text-blue-700 hover:text-blue-900">
                    Admin Login <span aria-hidden="true">→</span>
                </a>
            </article>

        </div>
    </div>
</section>


<!-- Call to Action -->
<section class="px-5 pb-20">
    <div class="mx-auto max-w-7xl overflow-hidden rounded-3xl bg-gradient-to-r from-purple-800 via-purple-700 to-blue-700 px-6 py-12 text-center text-white sm:px-12 sm:py-16">

        <h2 class="text-3xl font-extrabold sm:text-4xl">
            Your Participation Makes a Difference
        </h2>

        <p class="mx-auto mt-4 max-w-2xl leading-7 text-purple-100">
            Sign in to access your portal or apply to become a candidate in an available election.
        </p>

        <div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
            <a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>"
               class="rounded-xl bg-white px-6 py-3.5 font-bold text-purple-800 transition hover:bg-purple-50">
                Sign In
            </a>

            <a href="<?= htmlspecialchars(app_url('views/candidate/registration.php'), ENT_QUOTES, 'UTF-8') ?>"
               class="rounded-xl bg-orange-500 px-6 py-3.5 font-bold text-white transition hover:bg-orange-600">
                Apply as Candidate
            </a>
        </div>
    </div>
</section>

</main>


<!-- Footer -->
<footer id="contact" class="bg-slate-950 text-slate-300">
    <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">

        <div class="grid gap-10 md:grid-cols-3">

            <div>
                <a href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>" class="text-xl font-extrabold text-white">
                    Online <span class="text-purple-400">Voting</span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-7 text-slate-400">
                    A simple platform for candidate registration,
                    voter participation, and election management.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-white">Quick Links</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="#home" class="hover:text-white">Home</a></li>
                    <li><a href="#about" class="hover:text-white">About the Platform</a></li>
                    <li><a href="#how-it-works" class="hover:text-white">How It Works</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="hover:text-white">Login</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-white">Portal Access</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="hover:text-white">Voter Login</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('views/candidate/registration.php'), ENT_QUOTES, 'UTF-8') ?>" class="hover:text-white">Candidate Registration</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="hover:text-white">Administrator Login</a></li>
                </ul>
            </div>

        </div>

        <div class="mt-10 border-t border-slate-800 pt-6 text-center text-sm text-slate-500">
            &copy; <?= date('Y') ?> Online Voting System.
            Built for organized and responsible election participation.
        </div>

    </div>
</footer>

</body>
</html>
