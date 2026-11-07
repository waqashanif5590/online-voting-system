<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    $action = $_POST['action'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $symbol = trim($_POST['symbol'] ?? '');

    if ($action === 'delete' && $id) {
        $relationshipFound = false;
        $checks = [
            "SELECT COUNT(*) FROM applications WHERE party_id = ?",
            "SELECT COUNT(*) FROM election_parties WHERE party_id = ?"
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
            $_SESSION['message'] = 'This party cannot be deleted because it is used by an election or application.';
            $_SESSION['message_type'] = 'error';
        } else {
            $stmt = $pdo->prepare("DELETE FROM parties WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = 'Party deleted successfully.';
            $_SESSION['message_type'] = 'success';
        }
    } elseif ($name === '' || $description === '') {
        $_SESSION['message'] = 'Party name and description are required.';
        $_SESSION['message_type'] = 'error';
    } elseif ($action === 'add') {
        $stmt = $pdo->prepare("SELECT id FROM parties WHERE name = ? LIMIT 1");
        $stmt->execute([$name]);

        if ($stmt->fetch()) {
            $_SESSION['message'] = 'A party with this name already exists.';
            $_SESSION['message_type'] = 'error';
        } else {
            $stmt = $pdo->prepare("INSERT INTO parties (name, description, symbol) VALUES (?, ?, ?)");
            $stmt->execute([$name, $description, $symbol !== '' ? $symbol : null]);
            $_SESSION['message'] = 'Party added successfully.';
            $_SESSION['message_type'] = 'success';
        }
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("SELECT id FROM parties WHERE name = ? AND id <> ? LIMIT 1");
        $stmt->execute([$name, $id]);

        if ($stmt->fetch()) {
            $_SESSION['message'] = 'A party with this name already exists.';
            $_SESSION['message_type'] = 'error';
        } else {
            $stmt = $pdo->prepare("UPDATE parties SET name = ?, description = ?, symbol = ? WHERE id = ?");
            $stmt->execute([$name, $description, $symbol !== '' ? $symbol : null, $id]);
            $_SESSION['message'] = 'Party updated successfully.';
            $_SESSION['message_type'] = 'success';
        }
    }

    header('Location: ' . app_url('views/admin/parties.php'));
    exit;
}

$stmt = $pdo->query("SELECT id, name, description, symbol FROM parties ORDER BY created_at DESC");
$parties = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage political parties in the Online Voting System">
    <title>Political Parties | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
    <script src="https://kit.fontawesome.com/1359e2cfd9.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php $portalRole = 'admin';
        include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php
            $pageTitle = 'Political Parties';
            $pageDescription = 'Create and manage the political parties available in elections.';
            include APP_ROOT . '/includes/shared/portal_header.php';
            ?>
            <main id="dashboard" class="min-w-0 flex-1">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-col justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                            <div>
                                <h2 class="font-bold text-slate-900">Political Parties</h2>
                                <p class="mt-1 text-sm text-slate-500"><span id="partyCount"><?= count($parties) ?></span> parties configured</p>
                            </div>
                            <button type="button" data-open-modal="partyModal" class="inline-flex items-center justify-center rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-800">
                                Add Party
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">Party</th>
                                        <th class="px-6 py-3 font-semibold">Description</th>
                                        <th class="px-6 py-3 text-center font-semibold">Symbol</th>
                                        <th class="px-6 py-3 text-right font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="partyRows" class="divide-y divide-slate-100">
                                    <?php foreach ($parties as $party) : ?>
                                        <tr data-record data-id="<?= (int) $party['id'] ?>">
                                            <td class="px-6 py-4 font-semibold text-slate-900" data-name><?= htmlspecialchars($party['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="max-w-lg px-6 py-4 text-slate-600" data-description><?= htmlspecialchars($party['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="px-6 py-4 text-center text-slate-600" data-symbol>
                                                <i class="fa-solid fa-<?= htmlspecialchars($party['symbol'] ?? '', ENT_QUOTES, 'UTF-8')
                                                                        ?>"></i>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" data-edit class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</button>
                                                    <form method="post" action="<?= htmlspecialchars(app_url('views/admin/parties.php'), ENT_QUOTES, 'UTF-8') ?>" data-delete-form>
                                                        <?= csrfInput() ?>
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= (int) $party['id'] ?>">
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
    <?php include_once APP_ROOT . '/includes/modals/partyModal.php'; ?>
    <script src="../../public/js/openModal.js"></script>
</body>

</html>