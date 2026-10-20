<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';

$stmt = $pdo->prepare("SELECT * FROM `elections`");
$stmt->execute();
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elections</title>
    <link rel="stylesheet" href="../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <?php include '../includes/candidate/candidate_header.php'; ?>
    <div class="flex min-h-[calc(100vh-65px)] flex-col md:flex-row">
        <?php include '../includes/candidate/candidate_sidebar.php'; ?>

        <main class="min-w-0 flex-1 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">
            <div class="p-5 grid grid-cols-2 gap-2">
                <?php
                foreach ($elections as $election) {
                    echo '<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <p>' . $election['title'] . '</p>
                        <p>' . $election['status'] . '</p>
                        </div>';
                    if ($election['status'] === 'upcoming') {
                        echo '<a href="">Apply Now</a>';
                    } else if ($election['status'] === 'completed') {
                        echo '<a href="results.php">View Result</a>';
                    } else if ($election['status'] === 'ongoing') {
                        echo '<span>Ongoing</span>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </main>
    </div>
</body>

</html>