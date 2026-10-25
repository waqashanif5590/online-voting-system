<?php
$detailsStmt = $pdo->prepare("
    SELECT
        candidates.*,
        users.name AS candidate_name,
        users.email AS candidate_email,
        users.identity_number,
        users.dob,
        users.gender,
        elections.title AS election_name,
        elections.start_date AS election_start_date,
        elections.end_date AS election_end_date,
        positions.title AS position_name,
        parties.name AS party_name
    FROM candidates
    INNER JOIN users ON candidates.user_id = users.id
    LEFT JOIN elections ON candidates.election_id = elections.id
    LEFT JOIN positions ON candidates.position_id = positions.id
    LEFT JOIN parties ON candidates.party_id = parties.id
    WHERE candidates.id = ?
");
$detailsStmt->execute([$candidate['id']]);
$application = $detailsStmt->fetch(PDO::FETCH_ASSOC);

$escape = static fn($value): string => htmlspecialchars((string) ($value ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$status = $application['status'] ?? 'pending';
$statusStyles = [
    'pending' => 'bg-orange-100 text-orange-700',
    'approved' => 'bg-green-100 text-green-700',
    'rejected' => 'bg-red-100 text-red-700',
];
$statusClass = $statusStyles[$status] ?? 'bg-gray-100 text-gray-700';
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
            <span class="inline-flex rounded-full px-3 py-1.5 text-sm font-semibold <?= $statusClass ?>">
                <?= $escape(ucfirst($status)) ?>
            </span>
        </div>
        <p class="mt-4 text-sm leading-6 text-slate-600">
            Your application details are read-only after submission. Contact the election administrator if anything needs to be corrected.
        </p>
    </section>

    <div class="space-y-6">
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
                    <dd class="mt-1 text-sm text-slate-800"><?= $escape($application['party_name'] ?? 'Independent / not specified') ?></dd>
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
