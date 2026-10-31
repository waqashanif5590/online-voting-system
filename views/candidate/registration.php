<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('candidate');

$message = '';
$stmt = $pdo->prepare("SELECT * FROM `applications` WHERE user_id=?");
$stmt->execute([$_SESSION['user_id']]);
$candidate = $stmt->fetch(PDO::FETCH_ASSOC);
if ($candidate) {
    $message = "Already Registered. Please contact administrator for you queries.";
} else {

    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Elections for candidate registration
    $stmt = $pdo->prepare("SELECT * FROM `elections` WHERE status=?");
    $stmt->execute(['upcoming']);
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $positions = [];
    $parties = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        checkCsrfToken();
        $election_id = (int) ($_POST['election_id'] ?? 0);

        $stmt = $pdo->prepare("
        SELECT p.id, p.title
        FROM election_positions ep
        INNER JOIN positions p ON ep.position_id = p.id
        WHERE ep.election_id = ?
    ");
        $stmt->execute([$election_id]);
        $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("
        SELECT p.id, p.name
        FROM election_parties ep
        INNER JOIN parties p ON ep.party_id = p.id
        WHERE ep.election_id = ?
    ");
        $stmt->execute([$election_id]);
        $parties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user_id = $_SESSION['user_id'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['province'];
        $country = $_POST['country'];
        $postal_code = $_POST['postal_code'];
        $election_id = $_POST['election_id'];
        $position = $_POST['position'];
        $party = $_POST['party'];
        $constituency_area = $_POST['constituency'];
        $qualification = $_POST['qualification'];
        $experience = $_POST['experience'];
        $manifesto = $_POST['manifesto'];

        $stmt = $pdo->prepare("INSERT INTO applications (user_id, address, city, state, country, postal_code, election_id, position_id, constituency_area, party_id, qualifications, experience, manifesto)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $user_id,
            $address,
            $city,
            $state,
            $country,
            $postal_code,
            $election_id,
            $position,
            $constituency_area,
            $party,
            $qualification,
            $experience,
            $manifesto
        ]);
        $message = "Already Registered. Please contact administrator for you queries.";
        header('Location: ' . app_url('views/candidate/registration.php') . '?message=' . rawurlencode($message));
        exit;
    }
}

?>
<?php
$portalRole = 'candidate';
$pageTitle = $candidate ? 'Submitted Application' : 'Candidate Registration';
$pageDescription = $candidate
    ? 'Review the details you submitted for your candidacy.'
    : 'Complete the information required for your candidate application.';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Candidate Registration | Online Voting System</title>

    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="flex min-h-[calc(100vh-65px)] flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/candidate/candidate_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/candidate/candidate_header.php'; ?>
            <main class="min-w-0 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">

                <?php

                if ($candidate) {
                    $applicationCandidateId = (int) $candidate['id'];
                    $showReviewActions = false;
                    include APP_ROOT . '/includes/shared/submitted_application.php';
                } else {
                    $data;
                    $elections;
                    $positions;
                    $parties;
                    include APP_ROOT . '/includes/candidate/registration_form.php';
                }
                ?>

            </main>
        </div>
    </div>


    <footer class="border-t border-slate-200 bg-white px-5 py-6 text-center text-sm text-slate-500">
        &copy; <span id="year"></span> Online Voting System. All rights reserved.
    </footer>
    <script src="<?= htmlspecialchars(app_url('public/js/candidate-registration.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>