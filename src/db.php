<?php
session_start();
if (isset($_GET["mode"]) && in_array($_GET["mode"], ["vuln", "fixed"], true)) {
    $_SESSION["mode"] = $_GET["mode"];
}
$mode = $_SESSION["mode"] ?? getenv("APP_MODE") ?: "vuln";
$db = pg_connect(getenv("DATABASE_URL"));
if (!$db) {
    die("Помилка з'єднання.");
}
$schema =
    "CREATE TABLE users (id SERIAL PRIMARY KEY, login VARCHAR(50) NOT NULL UNIQUE, password_hash CHAR(32) NOT NULL)";
$seed =
    "INSERT INTO users (login, password_hash) VALUES ('admin','0192023a7bbd73250516f069df18b500'),('test','d8578edf8458ce06fbc5bb76a58c5ca4'),('demo','62cc2d8b4bf2d8728120d052163a77df')";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_GET["reset"])) {
        pg_query($db, "DROP TABLE IF EXISTS users");
        pg_query($db, $schema);
        pg_query($db, $seed);
        $msg = "Базу скинуто до початкового стану (3 користувачі).";
        $msg_ok = true;
    } else {
        pg_query(
            $db,
            "CREATE TABLE IF NOT EXISTS users (id SERIAL PRIMARY KEY, login VARCHAR(50) NOT NULL UNIQUE, password_hash CHAR(32) NOT NULL)",
        );
        pg_query($db, $seed . " ON CONFLICT (login) DO NOTHING");
    }
    if (
        !isset($_GET["register"]) &&
        isset($_POST["login"], $_POST["password"])
    ) {
        $login = $_POST["login"];
        $hash = md5($_POST["password"]);
        if ($mode === "fixed") {
            $res = pg_query_params(
                $db,
                "SELECT id, login FROM users WHERE login=$1 AND password_hash=$2",
                [$login, $hash],
            );
        } else {
            $sql = "SELECT id, login FROM users WHERE login='$login' AND password_hash='$hash'";
            $res = pg_query($db, $sql);
        }
        if ($res && pg_num_rows($res) > 0) {
            $u = pg_fetch_assoc($res);
            $msg =
                "ACCESS GRANTED — вітаємо, " .
                htmlspecialchars($u["login"]) .
                " (id=" .
                $u["id"] .
                ").";
            $msg_ok = true;
        } else {
            $msg ??= "ACCESS DENIED — невірний логін або пароль.";
        }
    }
    $rl = $_POST["new_login"] ?? ($_POST["login"] ?? null);
    $rp = $_POST["new_password"] ?? ($_POST["password"] ?? null);
    if (isset($_GET["register"]) && $rl !== null && $rp !== null) {
        $nh = md5($rp);
        if ($mode === "fixed") {
            $res = pg_query_params(
                $db,
                "INSERT INTO users (login, password_hash) VALUES ($1,$2) ON CONFLICT DO NOTHING",
                [$rl, $nh],
            );
        } else {
            $res = pg_query(
                $db,
                "INSERT INTO users (login, password_hash) VALUES ('$rl','$nh') ON CONFLICT DO NOTHING",
            );
        }
        $msg = $res ? "Користувача зареєстровано." : "Помилка реєстрації.";
        $msg_ok = (bool) $res;
    }
    if (str_contains($_SERVER["HTTP_ACCEPT"] ?? "", "text/html")) {
        $_SESSION["_flash"] = [$msg ?? "", $msg_ok ?? false];
        header(
            "Location: " . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),
            true,
            303,
        );
        exit();
    }
    $msg ??= "";
    $msg_ok ??= false;
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    pg_query(
        $db,
        "CREATE TABLE IF NOT EXISTS users (id SERIAL PRIMARY KEY, login VARCHAR(50) NOT NULL UNIQUE, password_hash CHAR(32) NOT NULL)",
    );
    pg_query($db, $seed . " ON CONFLICT (login) DO NOTHING");
    [$msg, $msg_ok] = $_SESSION["_flash"] ?? ["", false];
    unset($_SESSION["_flash"]);
}
