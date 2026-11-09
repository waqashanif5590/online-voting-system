<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    $action = $_POST['action'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($action === 'delete' && $id) {
        $relationshipFound = false;

        $checks = [
            "SELECT COUNT(*) FROM applications WHERE position_id = ?",
            "SELECT COUNT(*) FROM votes WHERE position_id = ?",
            "SELECT COUNT(*) FROM voter_participation WHERE position_id = ?",
            "SELECT COUNT(*) FROM election_positions WHERE position_id = ?"
        ];

        foreach ($checks as $sql) {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            if ((int) $stmt->fetchColumn() > 0) {
                $relationshipFound = true;
                break;
            }
        }

        if ($relationshipFound) {
            $_SESSION['message'] = 'This position cannot be deleted because it is used by an election or application.';
            $_SESSION['message_type'] = 'error';
        } else {
            $stmt = $pdo->prepare("DELETE FROM positions WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = 'Position deleted successfully.';
            $_SESSION['message_type'] = 'success';
        }
    } elseif ($title === '' || $description === '') {
        $_SESSION['message'] = 'Position title and description are required.';
        $_SESSION['message_type'] = 'error';
    } elseif ($action === 'add') {
        $stmt = $pdo->prepare("SELECT id FROM positions WHERE title = ? LIMIT 1");
        $stmt->execute([$title]);

        if ($stmt->fetch()) {
            $_SESSION['message'] = 'A position with this title already exists.';
            $_SESSION['message_type'] = 'error';
        } else {
            $stmt = $pdo->prepare("INSERT INTO positions (title, description) VALUES (?, ?)");
            $stmt->execute([$title, $description]);
            $_SESSION['message'] = 'Position added successfully.';
            $_SESSION['message_type'] = 'success';
        }
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("SELECT id FROM positions WHERE title = ? AND id <> ? LIMIT 1");
        $stmt->execute([$title, $id]);

        if ($stmt->fetch()) {
            $_SESSION['message'] = 'A position with this title already exists.';
            $_SESSION['message_type'] = 'error';
        } else {
            $stmt = $pdo->prepare("UPDATE positions SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $id]);
            $_SESSION['message'] = 'Position updated successfully.';
            $_SESSION['message_type'] = 'success';
        }
    }

    header('Location: ' . app_url('views/admin/positions.php'));
    exit;
}

$stmt = $pdo->query("SELECT id, title, description FROM positions ORDER BY created_at DESC");
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage election positions in the Online Voting System">
    <title>Election Positions | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php $portalRole = 'admin';
        include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php
            $pageTitle = 'Election Positions';
            $pageDescription = 'Create and manage the positions available in elections.';
            include APP_ROOT . '/includes/shared/portal_header.php';
            ?>
            <main id="dashboard" class="min-w-0 flex-1">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-col justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                            <div>
                                <h2 class="font-bold text-slate-900">Election Positions</h2>
                                <p class="mt-1 text-sm text-slate-500"><span id="positionCount"><?= count($positions) ?></span> positions configured</p>
                            </div>
                            <button type="button" data-open-modal="positionModal" class="inline-flex items-center justify-center rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-800">
                                Add Position
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">Position</th>
                                        <th class="px-6 py-3 font-semibold">Description</th>
                                        <th class="px-6 py-3 text-right font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="positionRows" class="divide-y divide-slate-100">
                                    <?php foreach ($positions as $position) : ?>
                                        <tr data-record data-id="<?= (int) $position['id'] ?>">
                                            <td class="px-6 py-4 font-semibold text-slate-900" data-title><?= htmlspecialchars($position['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="max-w-xl px-6 py-4 text-slate-600" data-description><?= htmlspecialchars($position['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" data-edit class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</button>
                                                    <form method="post" action="<?= htmlspecialchars(app_url('views/admin/positions.php'), ENT_QUOTES, 'UTF-8') ?>" data-delete-form>
                                                        <?= csrfInput() ?>
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= (int) $position['id'] ?>">
                                                        <button type="submit" data-delete class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <?php include_once APP_ROOT . '/includes/admin/footer.php' ?>
                </div>
            </main>
        </div>
    </div>
    <?php include_once APP_ROOT . '/includes/modals/positionModal.php'; ?>
    <script src="../../public/js/openModal.js"></script>
</body>
</html>
