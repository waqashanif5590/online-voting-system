<?php

date_default_timezone_set('Asia/Karachi');

$sql = "
    UPDATE elections
    SET status = 'completed'
    WHERE status IN ('upcoming', 'ongoing')
      AND end_date <= NOW()
";
$pdo->exec($sql);

$pdo->exec("
    UPDATE elections
    SET status = 'ongoing'
    WHERE status = 'upcoming'
      AND start_date <= NOW()
      AND end_date > NOW()
");
