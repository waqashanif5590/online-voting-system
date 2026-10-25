<?php
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Authentication failed');
}
