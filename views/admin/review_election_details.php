<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Election Details | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <div>
                    <p class="text-sm font-semibold text-purple-700">Administration / Elections</p>
                    <h1 class="mt-1 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Review election details</h1>
                    <p class="mt-1 text-sm text-slate-500">Review and update the election information below.</p>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 16 2 2 4-4" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Election information</h2>
                                <p class="mt-1 text-sm leading-5 text-slate-500">Edit the fields and submit the form to save your changes.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Static form: connect its action and values to your backend later. -->
                    <form action="" method="POST" class="space-y-6 px-5 py-6 sm:px-8 sm:py-8">
                        <div>
                            <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Election title <span class="text-rose-600">*</span></label>
                            <input id="title" name="title" type="text" value="Student Council Election 2026" placeholder="Enter election title" required class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Description <span class="text-rose-600">*</span></label>
                            <textarea id="description" name="description" rows="5" required placeholder="Describe the purpose and details of this election" class="block w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-purple-500 focus:ring-4 focus:ring-purple-100">Vote to elect the student representatives for the 2026 academic year.</textarea>
                            <p class="mt-2 text-xs text-slate-500">Provide a clear summary so voters understand the purpose of this election.</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-semibold text-slate-700">Start date and time <span class="text-rose-600">*</span></label>
                                <input id="start_date" name="start_date" type="datetime-local" value="2026-11-10T09:00" required class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                            </div>
                            <div>
                                <label for="end_date" class="mb-2 block text-sm font-semibold text-slate-700">End date and time <span class="text-rose-600">*</span></label>
                                <input id="end_date" name="end_date" type="datetime-local" value="2026-11-10T17:00" required class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                            </div>
                        </div>

                        <div>
                            <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Election status <span class="text-rose-600">*</span></label>
                            <select id="status" name="status" required class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                <option value="upcoming" selected>Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                            <p class="mt-2 text-xs leading-5 text-slate-500">Validate the selected status and date rules in your backend before saving.</p>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <a href="../common/elections.php" class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Cancel</a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-purple-700 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7" />
                                </svg>
                                Update election
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
                            <h2 class="font-bold text-purple-950">Before you update</h2>
                        </div>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-purple-900">
                            <li class="flex gap-2"><span class="font-bold">•</span><span>Confirm the title and description are accurate.</span></li>
                            <li class="flex gap-2"><span class="font-bold">•</span><span>Make sure the end date and time are later than the start date and time.</span></li>
                            <li class="flex gap-2"><span class="font-bold">•</span><span>Changing an election's status can affect voter access and results.</span></li>
                        </ul>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg>
                            </span>
                            <h2 class="font-bold text-slate-900">Admin review</h2>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">This is a static form layout. Connect the form to your PHP handler and load the selected election's real values before using it for updates.</p>
                    </section>
                </aside>
            </div>

            <footer class="mt-8 border-t border-slate-200 pt-5 text-center text-xs text-slate-500">© 2026 LiveVoting. Online Voting System.</footer>
        </main>
    </div>
</body>

</html>