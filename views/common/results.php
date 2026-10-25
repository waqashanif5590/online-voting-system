<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';
$portalRole = $_SESSION['user_role'] ?? '';
$pageTitle = 'Election Results';
$pageDescription = 'Review published election results and compare candidates by position.';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results | LiveVoting</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/shared/portal_header.php'; ?>
            <main class="min-w-0 flex-1 pb-8">
                <div class="mx-auto max-w-7xl space-y-7 px-5 py-7 md:px-10 md:py-10">
                <!-- Page actions -->
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <a href="elections.php" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-purple-200 hover:text-purple-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6M9 12h12" />
                        </svg>
                        Back to Elections
                    </a>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-800" aria-label="Print results (placeholder)">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M7 14h10v7H7z" />
                        </svg>
                        Print Results
                    </button>
                </div>

                <!-- Election information -->
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="election-title">
                    <div class="border-l-4 border-orange-500 p-6 md:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700"><span class="h-2 w-2 rounded-full bg-green-500"></span>Completed</span>
                                <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700">Results Published</span>
                            </div>
                            <span class="text-xs font-medium text-slate-500">Election ID: LV-2026-014 <span class="text-slate-300">(sample)</span></span>
                        </div>
                        <h2 id="election-title" class="mt-5 text-2xl font-bold text-slate-900 md:text-3xl">Community Council Election 2026</h2>
                        <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600 md:text-base">The community election was held to select representatives for council leadership and community services. The results below are grouped by position so candidates are compared only with others contesting the same position.</p>
                        <div class="mt-6 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1Z" />
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-slate-500">Election Period</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">Oct 23 – Oct 30, 2026</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-slate-500">Registered Voters</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">1,250</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-slate-500">Results Published</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">October 31, 2026</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Summary statistics -->
                <section aria-label="Election statistics" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Total Votes Cast</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5.5 10.5 8 16 2.5M7 13h10M7 17h6M5 3h3m8 0h3a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />
                                </svg></span>
                        </div>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">1,000</p>
                        <p class="mt-2 text-xs text-slate-500">Of 1,250 registered voters</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Voter Turnout</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M7 15l4-4 3 2 5-6" />
                                </svg></span>
                        </div>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">80<span class="text-xl">%</span></p>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full w-4/5 rounded-full bg-green-500"></div>
                        </div>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Candidates</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg></span>
                        </div>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">6</p>
                        <p class="mt-2 text-xs text-slate-500">Across 2 contested positions</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Valid Votes</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg></span>
                        </div>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">982</p>
                        <p class="mt-2 text-xs text-slate-500">18 invalid or rejected ballots</p>
                    </article>
                </section>

                <!-- Overall winner / result summary -->
                <section class="grid gap-5 xl:grid-cols-5">
                    <article class="relative overflow-hidden rounded-2xl border border-purple-200 bg-white p-6 shadow-sm xl:col-span-2 md:p-7">
                        <div class="absolute right-0 top-0 h-28 w-28 rounded-bl-full bg-purple-50"></div>
                        <div class="relative">
                            <div class="flex items-center gap-2"><span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="m12 2 2.5 6.2 6.7.5-5.1 4.4 1.6 6.5L12 16.1l-5.7 3.5 1.6-6.5-5.1-4.4 6.7-.5L12 2Z" />
                                    </svg>Featured Winner</span><span class="text-xs text-slate-400">Sample result</span></div>
                            <div class="mt-6 flex items-center gap-4">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-xl font-bold text-purple-700">AM</div>
                                <div>
                                    <h2 class="text-xl font-bold text-slate-900">Ali Muhammad</h2>
                                    <p class="mt-1 text-sm text-slate-500">Unity Party · Council President</p>
                                </div>
                            </div>
                            <div class="mt-6 grid grid-cols-2 gap-4 border-t border-slate-100 pt-5">
                                <div>
                                    <p class="text-xs text-slate-500">Votes received</p>
                                    <p class="mt-1 text-2xl font-bold text-slate-900">420</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Share of valid votes</p>
                                    <p class="mt-1 text-2xl font-bold text-purple-700">42.9%</p>
                                </div>
                            </div>
                            <p class="mt-4 text-xs leading-5 text-slate-500">Featured winner is for the Council President position only. Each position has its own ranking and winner.</p>
                        </div>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-3 md:p-7">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Election snapshot</h2>
                                <p class="mt-1 text-sm text-slate-500">Top candidates by position</p>
                            </div><span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">2 positions</span>
                        </div>
                        <div class="mt-6 space-y-6">
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Council President</p>
                                        <p class="text-xs text-slate-500">Ali Muhammad · Unity Party</p>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900">420 votes <span class="font-medium text-slate-500">(42.9%)</span></p>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-[42.9%] rounded-full bg-purple-600"></div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Community Treasurer</p>
                                        <p class="text-xs text-slate-500">Fatima Khan · People's Alliance</p>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900">510 votes <span class="font-medium text-slate-500">(52.0%)</span></p>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-[52%] rounded-full bg-orange-500"></div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-6 rounded-xl bg-slate-50 p-3 text-xs leading-5 text-slate-600">Vote shares are calculated from valid votes for the relevant position. Candidate totals can differ between positions.</p>
                    </article>
                </section>

                <!-- Results by position -->
                <section aria-labelledby="position-results-title" class="space-y-5">
                    <div>
                        <h2 id="position-results-title" class="text-xl font-bold text-slate-900 md:text-2xl">Results by Position</h2>
                        <p class="mt-1 text-sm text-slate-500">Rankings and vote percentages are calculated separately for each position.</p>
                    </div>

                    <!-- Council President -->
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-5 md:p-6">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-purple-700">Position 01</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-900">Council President</h3>
                                <p class="mt-1 text-sm text-slate-500">3 candidates · 978 valid votes for this position</p>
                            </div><span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700"><span class="h-2 w-2 rounded-full bg-green-500"></span>Winner declared</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-5 py-4 font-semibold">Rank</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Candidate</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Party</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Symbol</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Votes</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Vote share</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Result</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="bg-purple-50/50">
                                        <td class="px-5 py-4"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-purple-700 text-xs font-bold text-white">1</span></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 font-bold text-purple-700">AM</span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Ali Muhammad</p>
                                                    <p class="text-xs text-slate-500">Candidate ID: C-101</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-700">Unity Party</td>
                                        <td class="px-5 py-4"><span class="inline-flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-orange-600" aria-label="Sun symbol"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                        <circle cx="12" cy="12" r="4" />
                                                        <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" />
                                                    </svg></span><span class="text-slate-600">Sun</span></span></td>
                                        <td class="px-5 py-4 font-bold tabular-nums text-slate-900">420</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="w-12 font-semibold text-slate-700">42.9%</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full w-[42.9%] rounded-full bg-purple-600"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4"><span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">Winner</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">2</span></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700">FK</span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Fatima Khan</p>
                                                    <p class="text-xs text-slate-500">Candidate ID: C-102</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-700">People's Alliance</td>
                                        <td class="px-5 py-4"><span class="inline-flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 3 20h18L12 3Z" />
                                                        <path stroke-linecap="round" d="M8 16h8" />
                                                    </svg></span><span class="text-slate-600">Mountain</span></span></td>
                                        <td class="px-5 py-4 font-bold tabular-nums text-slate-900">338</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="w-12 font-semibold text-slate-700">34.6%</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full w-[34.6%] rounded-full bg-blue-500"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4"><span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">Runner-up</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">3</span></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 font-bold text-orange-700">US</span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Usman Shah</p>
                                                    <p class="text-xs text-slate-500">Candidate ID: C-103</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-700">Progress Party</td>
                                        <td class="px-5 py-4"><span class="inline-flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-50 text-green-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18M5.6 5.6l12.8 12.8m0-12.8L5.6 18.4" />
                                                    </svg></span><span class="text-slate-600">Cross</span></span></td>
                                        <td class="px-5 py-4 font-bold tabular-nums text-slate-900">220</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="w-12 font-semibold text-slate-700">22.5%</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full w-[22.5%] rounded-full bg-orange-500"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Participated</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-slate-100 bg-slate-50 px-5 py-3 text-xs text-slate-500">Sample figures: 420 + 338 + 220 = 978 valid votes for this position.</div>
                    </article>

                    <!-- Community Treasurer -->
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-5 md:p-6">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-orange-600">Position 02</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-900">Community Treasurer</h3>
                                <p class="mt-1 text-sm text-slate-500">3 candidates · 981 valid votes for this position</p>
                            </div><span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700"><span class="h-2 w-2 rounded-full bg-green-500"></span>Winner declared</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-5 py-4 font-semibold">Rank</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Candidate</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Party</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Symbol</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Votes</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Vote share</th>
                                        <th scope="col" class="px-5 py-4 font-semibold">Result</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="bg-orange-50/40">
                                        <td class="px-5 py-4"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">1</span></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 font-bold text-orange-700">FK</span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Fatima Khan</p>
                                                    <p class="text-xs text-slate-500">Candidate ID: C-104</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-700">People's Alliance</td>
                                        <td class="px-5 py-4"><span class="inline-flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 3 20h18L12 3Z" />
                                                        <path stroke-linecap="round" d="M8 16h8" />
                                                    </svg></span><span class="text-slate-600">Mountain</span></span></td>
                                        <td class="px-5 py-4 font-bold tabular-nums text-slate-900">510</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="w-12 font-semibold text-slate-700">52.0%</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full w-[52%] rounded-full bg-orange-500"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4"><span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">Winner</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">2</span></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 font-bold text-purple-700">AM</span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Ali Muhammad</p>
                                                    <p class="text-xs text-slate-500">Candidate ID: C-105</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-700">Unity Party</td>
                                        <td class="px-5 py-4"><span class="inline-flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-orange-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                        <circle cx="12" cy="12" r="4" />
                                                        <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2" />
                                                    </svg></span><span class="text-slate-600">Sun</span></span></td>
                                        <td class="px-5 py-4 font-bold tabular-nums text-slate-900">290</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="w-12 font-semibold text-slate-700">29.6%</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full w-[29.6%] rounded-full bg-purple-600"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4"><span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">Runner-up</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">3</span></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 font-bold text-green-700">US</span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Usman Shah</p>
                                                    <p class="text-xs text-slate-500">Candidate ID: C-106</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-700">Progress Party</td>
                                        <td class="px-5 py-4"><span class="inline-flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-50 text-green-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18M5.6 5.6l12.8 12.8m0-12.8L5.6 18.4" />
                                                    </svg></span><span class="text-slate-600">Cross</span></span></td>
                                        <td class="px-5 py-4 font-bold tabular-nums text-slate-900">181</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3"><span class="w-12 font-semibold text-slate-700">18.5%</span>
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full w-[18.5%] rounded-full bg-green-500"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Participated</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-slate-100 bg-slate-50 px-5 py-3 text-xs text-slate-500">Sample figures: 510 + 290 + 181 = 981 valid votes for this position.</div>
                    </article>
                </section>

                <!-- Analytics -->
                <section class="grid gap-5 xl:grid-cols-2" aria-label="Election analytics">
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Ballot summary</h2>
                            <p class="mt-1 text-sm text-slate-500">Overall voter participation</p>
                        </div>
                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Registered voters</p>
                                <p class="mt-2 text-2xl font-bold text-slate-900">1,250</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Votes cast</p>
                                <p class="mt-2 text-2xl font-bold text-slate-900">1,000</p>
                            </div>
                            <div class="rounded-xl bg-green-50 p-4">
                                <p class="text-sm text-green-700">Valid ballots</p>
                                <p class="mt-2 text-2xl font-bold text-green-800">982</p>
                            </div>
                            <div class="rounded-xl bg-orange-50 p-4">
                                <p class="text-sm text-orange-700">Invalid / rejected</p>
                                <p class="mt-2 text-2xl font-bold text-orange-800">18</p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <div class="mb-2 flex justify-between text-xs font-medium text-slate-600"><span>Voter turnout</span><span>80%</span></div>
                            <div class="flex h-3 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full w-4/5 bg-purple-600"></div>
                                <div class="h-full w-1/5 bg-slate-200"></div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">250 registered voters did not cast a ballot.</p>
                        </div>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Leading candidate comparison</h2>
                            <p class="mt-1 text-sm text-slate-500">Vote shares for the leading candidate in each position</p>
                        </div>
                        <div class="mt-6 space-y-6">
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Ali Muhammad</p>
                                        <p class="text-xs text-slate-500">Council President · Unity Party</p>
                                    </div><span class="text-sm font-bold text-purple-700">42.9%</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-[42.9%] rounded-full bg-purple-600"></div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Fatima Khan</p>
                                        <p class="text-xs text-slate-500">Community Treasurer · People's Alliance</p>
                                    </div><span class="text-sm font-bold text-orange-600">52.0%</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-[52%] rounded-full bg-orange-500"></div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-3 text-xs leading-5 text-blue-800">These bars compare each position's leader with the valid votes recorded for that position. They are not a ranking across different positions.</p>
                    </article>
                </section>

                <!-- Official notice -->
                <section class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 md:p-5">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17.1A2 2 0 0 0 4.4 20h15.2a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                    </svg>
                    <div>
                        <h2 class="text-sm font-semibold text-amber-900">Official results notice</h2>
                        <p class="mt-1 text-sm leading-6 text-amber-800">These figures are illustrative sample data for the frontend. In the live system, results should be shown as official only after an authorized election administrator has verified and published them. Results that are pending or not published should not display a winner.</p>
                    </div>
                </section>

                <footer class="border-t border-slate-200 pt-6 text-center text-sm text-slate-500">© 2026 LiveVoting. Online Voting System.</footer>
                </div>
            </main>
        </div>
    </div>
</body>

</html>