<?php
$detailsStmt = $pdo->prepare("
    SELECT
        applications.*,
        users.name AS candidate_name,
        users.email AS candidate_email,
        users.identity_number,
        users.dob,
        users.gender,
        elections.title AS election_name,
        elections.status AS election_status,
        elections.start_date AS election_start_date,
        elections.end_date AS election_end_date,
        positions.title AS position_name,
        parties.name AS party_name
    FROM applications
    INNER JOIN users ON applications.user_id = users.id
    LEFT JOIN elections ON applications.election_id = elections.id
    LEFT JOIN positions ON applications.position_id = positions.id
    LEFT JOIN parties ON applications.party_id = parties.id
    WHERE applications.id = ?
");
$detailsStmt->execute([$applicationCandidateId]);
$application = $detailsStmt->fetch(PDO::FETCH_ASSOC);

$escape = static fn($value): string => htmlspecialchars((string) ($value ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$status = $application['status'] ?? 'pending';
$statusStyles = [
    'pending' => 'bg-orange-100 text-orange-700',
    'approved' => 'bg-green-100 text-green-700',
    'rejected' => 'bg-red-100 text-red-700',
];
$statusClass = $statusStyles[$status] ?? 'bg-gray-100 text-gray-700';
$electionStatus = $application['election_status'] ?? '';
$submittedAt = !empty($application['created_at'])
    ? date('F j, Y \a\t g:i A', strtotime($application['created_at']))
    : 'Not available';
?>

<div class="mx-auto max-w-5xl">
    <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Application status</h2>
                <p class="mt-1 text-sm text-slate-500">Submitted <?= $escape($submittedAt) ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <?php if ($electionStatus === 'completed'): ?>
                    <span class="inline-flex rounded-full bg-slate-800 px-3 py-1.5 text-sm font-semibold text-white">
                        Election completed
                    </span>
                <?php else: ?>
                    <span class="inline-flex rounded-full px-3 py-1.5 text-sm font-semibold <?= $statusClass ?>">
                        <?= $escape(ucfirst($status)) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between gap-4">
            <p class="mt-4 text-sm leading-6 text-slate-600">
                These application details are read-only after submission.
            </p>
            <button type="button"
                class="application-toggle flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-700 transition duration-300 hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700 focus:outline-none focus:ring-4 focus:ring-purple-100"
                aria-expanded="false"
                aria-controls="application-data-<?= $applicationCandidateId ?>">
                <svg class="h-5 w-5 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
                <span class="sr-only">Show application details</span>
            </button>
        </div>
    </section>

    <div id="application-data-<?= $applicationCandidateId ?>"
        class="application-details max-h-0 overflow-hidden opacity-0 transition-all duration-500 ease-in-out"
        style="max-height: 0;">
        <div class="min-h-0 space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="mb-5 text-lg font-bold text-slate-900">Personal information</h2>
            <dl class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Full legal name</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['candidate_name'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Email address</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['candidate_email'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Identity number</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['identity_number'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Date of birth</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['dob'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Gender</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['gender'] ?? null) ?></dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="mb-5 text-lg font-bold text-slate-900">Election information</h2>
            <dl class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Election</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['election_name'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Position</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['position_name'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Party</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['party_name'] ?? 'Independent / no party') ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Constituency</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['constituency_area'] ?? null) ?></dd>
                </div>
                <?php if (!empty($application['election_start_date']) || !empty($application['election_end_date'])): ?>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-semibold text-slate-500">Election dates</dt>
                        <dd class="mt-1 text-sm text-slate-800">
                            <?= $escape($application['election_start_date'] ?? 'Not available') ?>
                            &ndash;
                            <?= $escape($application['election_end_date'] ?? 'Not available') ?>
                        </dd>
                    </div>
                <?php endif; ?>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="mb-5 text-lg font-bold text-slate-900">Address</h2>
            <dl class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-semibold text-slate-500">Street address</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-800"><?= $escape($application['address'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">City</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['city'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">State / province</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['state'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Country</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['country'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Postal code</dt>
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['postal_code'] ?? null) ?></dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="mb-5 text-lg font-bold text-slate-900">Qualifications and platform</h2>
            <dl class="space-y-5">
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Qualifications</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-800"><?= $escape($application['qualifications'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Experience</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-800"><?= $escape($application['experience'] ?? null) ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Manifesto</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-800"><?= $escape($application['manifesto'] ?? null) ?></dd>
                </div>
            </dl>
        </section>
        </div>
    </div>
    <?php if (!empty($showReviewActions)): ?>
        <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                Review application
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Approve this candidate's application or reject it if
                the requirements are not met.
            </p>

            <?php if ($electionStatus === 'completed'): ?>
                <p class="mt-4 text-sm font-semibold text-slate-700">Election completed. This application can no longer be reviewed.</p>
                <?php if ($status !== 'pending'): ?>
                    <p class="mt-1 text-sm text-slate-500">Review status: <?= $escape(ucfirst($status)) ?>.</p>
                <?php endif; ?>
            <?php elseif ($status === 'pending'): ?>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="../../app/controllers/actions.php?candidate=<?= $applicationCandidateId ?>&approve=true&csrf_token=<?= urlencode(getCsrfToken()) ?>"
                        class="rounded-lg bg-green-50 px-2.5 py-1.5 text-xs font-bold text-green-700">Approve</a>
                    <a href="../../app/controllers/actions.php?candidate=<?= $applicationCandidateId ?>&approve=false&csrf_token=<?= urlencode(getCsrfToken()) ?>"
                        class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">Reject</a>
                </div>
            <?php else: ?>
                <p class="mt-4 text-sm font-semibold text-slate-600">
                    This application has already been reviewed.
                </p>
            <?php endif; ?>
            <?php if ($electionStatus === 'completed'): ?>
                <a href="../../app/controllers/actions.php?delete_application=<?= $applicationCandidateId ?>&csrf_token=<?= urlencode(getCsrfToken()) ?>"
                    onclick="return confirm('Delete this completed election application?');"
                    class="mt-5 inline-flex rounded-lg bg-red-100 px-3 py-2 text-sm font-bold text-red-700 hover:bg-red-200">
                    Delete application
                </a>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</div>