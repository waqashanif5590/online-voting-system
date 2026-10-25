<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user == null) {
        echo 'Account not found, please register first.';
    } else {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            if ($user['role'] === 'voter') {
                header('Location: ' . app_url('views/voter/dashboard.php'));
                exit;
            } else if ($user['role'] === 'candidate') {
                header('Location: ' . app_url('views/candidate/dashboard.php'));
                exit;
            } else if ($user['role'] === 'admin') {
                header('Location: ' . app_url('views/admin/dashboard.php'));
                exit;
            }
        } else {
            echo 'incorrect username or password';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Online Voting System</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">

    <!-- Header -->
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
            <a href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-700 text-2xl font-bold text-white">✓</span>
                <span class="text-lg font-extrabold text-slate-900">
                    Online <span class="text-purple-700">Voting</span>
                </span>
            </a>

            <p class="text-sm text-slate-600">
                Already registered?
                <a href="<?= htmlspecialchars(app_url('views/auth/register.php'), ENT_QUOTES, 'UTF-8') ?>" class="font-bold text-purple-700 hover:text-purple-900">
                    Login
                </a>
            </p>
        </div>
    </header>

    <!-- Main -->
    <main class="px-4 py-10 sm:py-14">
        <div class="mx-auto max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl shadow-slate-200/70">

            <div class="grid md:grid-cols-5">

                <!-- Left Panel -->
                <aside class="relative overflow-hidden bg-gradient-to-br from-purple-800 via-purple-700 to-blue-700 p-7 text-white sm:p-10 md:col-span-2">
                    <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-orange-400/20"></div>

                    <div class="relative z-10">
                        <span class="inline-flex rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold tracking-wide">
                            JOIN THE COMMUNITY
                        </span>

                        <h1 class="mt-7 text-3xl font-extrabold leading-tight sm:text-4xl">
                            Your Voice.
                            <span class="block text-orange-300">Your Choice.</span>
                        </h1>

                        <p class="mt-5 text-sm leading-7 text-purple-100">
                            Create your account to participate in elections or apply to become a candidate.
                        </p>

                        <div class="mt-10 space-y-5">
                            <div class="flex gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-400/20 font-bold text-green-300">✓</span>
                                <div>
                                    <h2 class="font-bold">Simple Registration</h2>
                                    <p class="mt-1 text-sm text-purple-100">Create an account with your basic information.</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-400/20 font-bold text-orange-300">✓</span>
                                <div>
                                    <h2 class="font-bold">Choose Your Role</h2>
                                    <p class="mt-1 text-sm text-purple-100">Register as a voter or a candidate.</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-400/20 font-bold text-blue-200">✓</span>
                                <div>
                                    <h2 class="font-bold">Continue to Your Portal</h2>
                                    <p class="mt-1 text-sm text-purple-100">Access the relevant portal after registration is implemented.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Registration Form -->
                <section class="p-6 sm:p-10 md:col-span-3">
                    <div class="mb-8">
                        <p class="text-sm font-bold uppercase tracking-widest text-purple-700">
                            Login to your account
                        </p>

                        <h2 class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">
                            Get Started Today
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Enter your details below to login to your account.
                        </p>
                    </div>

                    <form action="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" id="registrationForm" class="space-y-5">


                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>

                            <input type="email" id="email" name="email"
                                placeholder="Enter your registered email"
                                autocomplete="email" maxlength="254"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                                Password <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    placeholder="Enter your password"
                                    autocomplete="new-password"
                                    minlength="8" maxlength="128"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-20 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">

                                <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-purple-700 hover:text-purple-900">
                                    Show
                                </button>
                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                Forgot password? <a href="">Reset</a>
                            </p>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full rounded-xl bg-purple-700 px-5 py-3.5 font-bold text-white shadow-lg shadow-purple-200 transition hover:-translate-y-0.5 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                            Login to account
                        </button>

                        <p id="formMessage" role="status" aria-live="polite"
                            class="hidden rounded-xl p-4 text-sm leading-6"></p>

                    </form>
                </section>

            </div>
        </div>

        <p class="mt-8 text-center text-sm text-slate-500">
            &copy; <?= date('Y') ?> Online Voting System. All rights reserved.
            <a href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>" class="ml-1 font-semibold text-purple-700 hover:underline">Back to Home</a>
        </p>
    </main>

</body>

</html>