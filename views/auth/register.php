<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $identity_type = $_POST['identity_type'];
    $identity_number = $_POST['identity_number'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? '';

    if ($role !== 'voter' && $role !== 'candidate') {
        $role = '';
        echo 'Please choose a valid account role.';
    } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Find email if already exists
    $email_stmt = $pdo->prepare("SELECT email FROM `users` WHERE email=?");
    $email_stmt->execute([$email]);
    $user_email = $email_stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_email) {
        echo "Email already exists.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, identity_type, identity_number, dob, gender, password, role) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $name,
            $email,
            $identity_type,
            $identity_number,
            $dob,
            $gender,
            $hashed_password,
            $role
        ]);
        header('Location: ' . app_url('views/auth/login.php'));
        exit();
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
                <a href="<?= htmlspecialchars(app_url('views/auth/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="font-bold text-purple-700 hover:text-purple-900">
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
                            Create Account
                        </p>

                        <h2 class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">
                            Get Started Today
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Enter your details below to create your account.
                        </p>
                    </div>

                    <form action="<?= htmlspecialchars(app_url('views/auth/register.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" id="registrationForm" class="space-y-5">
                        <?= csrfInput() ?>

                        <!-- Profile Image -->
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-slate-700">
                                Profile Image
                                <span class="font-normal text-slate-400">(Optional)</span>
                            </label>

                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-purple-200 bg-purple-50">
                                    <img id="imagePreview"
                                        src=""
                                        alt="Profile image preview"
                                        class="hidden h-full w-full object-cover">

                                    <svg id="defaultAvatar" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="h-10 w-10 text-purple-300">
                                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <label for="profileImage"
                                        class="inline-flex cursor-pointer items-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-purple-500 hover:bg-purple-50 hover:text-purple-700">
                                        Choose Image
                                    </label>

                                    <input type="file" id="profileImage"
                                        accept="image/png,image/jpeg,image/webp"
                                        class="sr-only">

                                    <p id="imageName" class="mt-2 break-all text-xs text-slate-500">
                                        PNG, JPG or WebP · Maximum 2 MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>

                            <input type="text" id="name" name="name"
                                placeholder="Enter your full name"
                                autocomplete="name" minlength="2" maxlength="100"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                        </div>

                        <!-- CNIC / Passport -->
                        <div>
                            <label for="identityNumber" class="mb-2 block text-sm font-semibold text-slate-700">
                                Identity Document <span class="text-red-500">*</span>
                            </label>

                            <div class="grid gap-3 sm:grid-cols-3">
                                <select id="identityType" name="identity_type"
                                    class="rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="cnic">CNIC</option>
                                    <option value="passport">Passport</option>
                                </select>

                                <input type="text" id="identityNumber" name="identity_number"
                                    placeholder="Identity number"
                                    maxlength="13" autocomplete="off"
                                    class="sm:col-span-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <p id="identityHelp" class="mt-2 text-xs text-slate-500">
                                Enter your 13-digit CNIC number, with or without hyphens.
                            </p>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="dob" class="mb-2 block text-sm font-semibold text-slate-700">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>

                            <input type="date" id="dob" name="dob"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                        </div>
                        <!-- Gender -->
                        <div>
                            <label for="gender" class="mb-2 block text-sm font-semibold text-slate-700">
                                Gender <span class="text-red-500">*</span>
                            </label>

                            <select id="gender" name="gender"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>

                            <input type="email" id="email" name="email"
                                placeholder="you@example.com"
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
                                    placeholder="Create a strong password"
                                    autocomplete="new-password"
                                    minlength="8" maxlength="128"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-20 text-sm outline-none transition placeholder:text-slate-400 focus:border-purple-600 focus:ring-4 focus:ring-purple-100">

                                <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-purple-700 hover:text-purple-900">
                                    Show
                                </button>
                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                Use at least 8 characters. A longer, unique password is recommended.
                            </p>
                        </div>

                        <!-- Role -->
                        <fieldset>
                            <legend class="mb-3 text-sm font-semibold text-slate-700">
                                Register As <span class="text-red-500">*</span>
                            </legend>

                            <div class="grid gap-4 sm:grid-cols-2">

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="role" value="voter"
                                        class="peer sr-only" checked>

                                    <div class="flex h-full items-start gap-3 rounded-xl border-2 border-slate-200 p-4 transition peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-purple-300">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                                                <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-8 9a8 8 0 1 1 16 0H4Z" />
                                            </svg>
                                        </span>

                                        <span>
                                            <span class="block font-bold text-slate-900">Voter</span>
                                            <span class="mt-1 block text-xs leading-5 text-slate-500">
                                                Participate in elections and cast your vote.
                                            </span>
                                        </span>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="role" value="candidate"
                                        class="peer sr-only">

                                    <div class="flex h-full items-start gap-3 rounded-xl border-2 border-slate-200 p-4 transition peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                                                <path d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10ZM3 21a9 9 0 0 1 18 0H3Zm14-15h5v2h-5V6Z" />
                                            </svg>
                                        </span>

                                        <span>
                                            <span class="block font-bold text-slate-900">Candidate</span>
                                            <span class="mt-1 block text-xs leading-5 text-slate-500">
                                                Apply to participate as an election candidate.
                                            </span>
                                        </span>
                                    </div>
                                </label>

                            </div>
                        </fieldset>

                        <!-- Terms -->
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms"
                                class="mt-1 h-4 w-4 rounded border-slate-300 accent-purple-700">

                            <label for="terms" class="text-sm leading-6 text-slate-600">
                                I confirm that the information I have provided is accurate and agree to the platform's
                                <a href="#terms-info" class="font-semibold text-purple-700 hover:underline">registration terms</a>.
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full rounded-xl bg-purple-700 px-5 py-3.5 font-bold text-white shadow-lg shadow-purple-200 transition hover:-translate-y-0.5 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                            Create My Account
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
    <script src="<?= htmlspecialchars(app_url('public/js/register.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>