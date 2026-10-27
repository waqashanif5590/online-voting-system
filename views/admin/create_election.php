<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("INSERT INTO elections (title, description, start_date, end_date, status) VALUES
    (?,?,?,?,?)");
    $isInserted = $stmt->execute([$title, $description, $start_date, $end_date, $status]);
    if ($isInserted) {
        echo '<script>alert("New election was added successfully")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Election | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <div>
                    <p class="text-sm font-semibold text-purple-700">Administration / Elections</p>
                    <h1 class="mt-1 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Create a new election</h1>
                    <p class="mt-1 text-sm text-slate-500">Enter the details to set up an election.</p>
                </div>
                <a href="../common/elections.php" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                    </svg>
                    Back to elections
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-5 sm:px-8">
                        <div class="flex items-start gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v5m-2.5-2.5h5" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Election information</h2>
                                <p class="mt-1 text-sm leading-5 text-slate-500">Complete all required fields to prepare the election.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Static form only. Connect this form to your backend when ready. -->
                    <form action="./create_election.php" method="POST" class="space-y-6 px-5 py-6 sm:px-8 sm:py-8">
                        <div>
                            <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Election title <span class="text-rose-600">*</span></label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                placeholder="e.g. Student Council Election 2026"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                            <p class="mt-2 text-xs text-slate-500">Choose a clear, unique title for this election.</p>
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Description <span class="text-rose-600">*</span></label>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Describe the purpose of this election and who it is for."
                                required
                                class="block w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-purple-500 focus:ring-4 focus:ring-purple-100"></textarea>
                            <p class="mt-2 text-xs text-slate-500">Give voters a short explanation of the election.</p>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="relative">
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Select parties to add
                                    <span class="text-rose-600">*</span>
                                </label>

                                <!-- Dropdown button -->
                                <button
                                    type="button"
                                    id="partyDropdownButton"
                                    class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                    aria-expanded="false"
                                    aria-controls="partyDropdown">
                                    <span id="selectedPartyText">Select parties</span>

                                    <svg
                                        class="h-4 w-4 transition-transform"
                                        id="partyDropdownArrow"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m19 9-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown options -->
                                <div
                                    id="partyDropdown"
                                    class="absolute z-20 mt-2 hidden max-h-60 w-full overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-lg">
                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-purple-50">
                                        <input
                                            type="checkbox"
                                            name="parties[]"
                                            value="1"
                                            class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700">Student Unity Party</span>
                                    </label>

                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-purple-50">
                                        <input
                                            type="checkbox"
                                            name="parties[]"
                                            value="2"
                                            class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700">Student Progressive Party</span>
                                    </label>

                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-purple-50">
                                        <input
                                            type="checkbox"
                                            name="parties[]"
                                            value="3"
                                            class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700">Student Social Activists</span>
                                    </label>
                                </div>

                                <p class="mt-2 text-xs text-slate-500">
                                    Select one or more parties for this election.
                                </p>
                            </div>

                            <div class="relative">
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Select positions to add
                                    <span class="text-rose-600">*</span>
                                </label>

                                <!-- Dropdown button -->
                                <button
                                    type="button"
                                    id="positionDropdownButton"
                                    class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                    aria-expanded="false"
                                    aria-controls="positionDropdown">
                                    <span id="selectedPositionText">Select positions</span>

                                    <svg
                                        class="h-4 w-4 transition-transform"
                                        id="positionDropdownArrow"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m19 9-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown options -->
                                <div
                                    id="positionDropdown"
                                    class="absolute z-20 mt-2 hidden max-h-60 w-full overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-lg">
                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-purple-50">
                                        <input
                                            type="checkbox"
                                            name="positions[]"
                                            value="1"
                                            class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700">President</span>
                                    </label>

                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-purple-50">
                                        <input
                                            type="checkbox"
                                            name="positions[]"
                                            value="2"
                                            class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700">Vice President</span>
                                    </label>

                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-purple-50">
                                        <input
                                            type="checkbox"
                                            name="positions[]"
                                            value="3"
                                            class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700">Secratory</span>
                                    </label>
                                </div>

                                <p class="mt-2 text-xs text-slate-500">
                                    Select one or more positions for this election.
                                </p>
                            </div>

                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-semibold text-slate-700">Start date and time <span class="text-rose-600">*</span></label>
                                <input
                                    id="start_date"
                                    name="start_date"
                                    type="datetime-local"
                                    required
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                            </div>
                            <div>
                                <label for="end_date" class="mb-2 block text-sm font-semibold text-slate-700">End date and time <span class="text-rose-600">*</span></label>
                                <input
                                    id="end_date"
                                    name="end_date"
                                    type="datetime-local"
                                    required
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                            </div>
                        </div>

                        <div>
                            <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Initial status <span class="text-rose-600">*</span></label>
                            <select
                                id="status"
                                name="status"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                <option value="" selected disabled>Select election status</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                            <p class="mt-2 text-xs leading-5 text-slate-500">For a new election, Upcoming is generally the appropriate initial status. Your backend should validate the status against the election dates.</p>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <a href="elections.php" class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-purple-700 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                </svg>
                                Create election
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="space-y-5">
                    <section class="rounded-2xl border border-purple-100 bg-purple-50 p-5 sm:p-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-purple-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17.1A2 2 0 0 0 4.4 20h15.2a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                                </svg>
                            </span>
                            <h2 class="font-bold text-purple-950">Before creating</h2>
                        </div>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-purple-900">
                            <li class="flex gap-2"><span class="font-bold">•</span><span>Use a descriptive title that clearly identifies the election.</span></li>
                            <li class="flex gap-2"><span class="font-bold">•</span><span>Set the end date and time after the start date and time.</span></li>
                            <li class="flex gap-2"><span class="font-bold">•</span><span>Double-check the schedule before making the election available to voters.</span></li>
                        </ul>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg>
                            </span>
                            <h2 class="font-bold text-slate-900">Next step</h2>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">After creating the election, you can connect the backend to add positions, approve candidates, and manage voter access.</p>
                    </section>
                </aside>
            </div>

            <footer class="mt-8 border-t border-slate-200 pt-5 text-center text-xs text-slate-500">
                © 2026 LiveVoting. Online Voting System.
            </footer>
        </main>
    </div>
    <script src="<?= htmlspecialchars(app_url('public/js/elections.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>