<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if ($uri === "/admin" || str_starts_with($uri, "/admin/")) {
    require __DIR__ . "/admin/index.php";
    return;
}
return false;
