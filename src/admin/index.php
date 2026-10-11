<?php
require __DIR__ . "/../db.php";
$users = pg_fetch_all(pg_query($db, "SELECT id, login, password_hash FROM users ORDER BY id"),) ?: [];
?>
<!doctype html>
<html lang="uk">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>ЛР11 — Адмін</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      body {
        background: #f5f5f5;
        color: #212121;
        font-family: Roboto, "Segoe UI", Arial, sans-serif;
      }
      .bar {
        background: #3f51b5;
        color: #fff;
        padding: 14px 20px;
        font-size: 18px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      }
      .card {
        background: #fff;
        max-width: 680px;
        margin: 24px auto;
        padding: 24px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      }
      h1 {
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 4px;
      }
      h2 {
        font-size: 16px;
        font-weight: 500;
        margin: 24px 0 8px;
      }
      p,
      li {
        font-size: 14px;
        line-height: 1.6;
      }
      a {
        color: #3f51b5;
      }
      pre {
        background: #f5f5f5;
        padding: 12px;
        overflow-x: auto;
        font-size: 13px;
        border-radius: 4px;
        margin: 8px 0;
        white-space: pre-wrap;
      }
      table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
        margin-top: 8px;
      }
      th,
      td {
        border-bottom: 1px solid #e0e0e0;
        padding: 8px 12px;
        text-align: left;
      }
      th {
        color: #757575;
        font-weight: 500;
      }
      label {
        display: block;
        font-size: 13px;
        color: #757575;
        margin-top: 12px;
      }
      input {
        width: 100%;
        border: none;
        border-bottom: 1px solid #bdbdbd;
        padding: 8px 2px;
        font: inherit;
        outline: none;
        background: transparent;
      }
      input:focus {
        border-bottom: 2px solid #3f51b5;
      }
      button {
        margin-top: 16px;
        background: #3f51b5;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font: inherit;
        font-size: 14px;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      }
      button:hover {
        background: #3949ab;
      }
      .modes {
        margin: 12px 0;
      }
      .modes a {
        display: inline-block;
        text-decoration: none;
        font-size: 14px;
        text-transform: uppercase;
        padding: 8px 16px;
        margin-right: 8px;
        border-radius: 4px;
        border: 1px solid #3f51b5;
      }
      .modes a.on {
        background: #3f51b5;
        color: #fff;
      }
      .msg {
        font-size: 14px;
        margin-top: 12px;
      }
    </style>
  </head>
  <body>
    <div class="bar">ЛР11 — Адмін (режим: <?= htmlspecialchars($mode) ?>)</div>
    <div class="card">
      <h1>Технічна сторінка</h1>
      <p><a href="/">← Назад до входу</a></p>
      <?php if ($msg) { echo '<p class="msg"><b>' . htmlspecialchars($msg) . "</b></p>";} ?>

      <h2>Режим</h2>
      <div class="modes">
        <a href="/admin/?mode=vuln" class="<?= $mode === "vuln" ? "on" : "" ?>">Vuln</a>
        <a href="/admin/?mode=fixed" class="<?= $mode === "fixed" ? "on" : "" ?>">Fixed</a>
      </div>

      <h2>Код входу</h2>
      <p>Вразливий (конкатенація вводу в SQL):</p>
      <pre>$sql = "SELECT id, login FROM users WHERE login='$login' AND password_hash='$hash'";
$res = pg_query($db, $sql);</pre>
      <p>Захищений (параметри):</p>
      <pre>$res = pg_query_params($db, "SELECT id, login FROM users WHERE login=$1 AND password_hash=$2", [$login, $hash]);</pre>

      <h2>База даних</h2>
      <form method="POST" action="/admin/?reset=1">
        <button>Скинути базу</button>
      </form>
      <table>
        <tr>
          <th>id</th>
          <th>login</th>
          <th>md5(password)</th>
        </tr>
        <?php foreach ($users as $u) {
            echo "<tr><td>{$u["id"]}</td><td>" .
                htmlspecialchars($u["login"]) .
                "</td><td>{$u["password_hash"]}</td></tr>";
        } ?>
      </table>

      <h2>Ін'єкції</h2>
      <pre>' OR '1'='1' -- </pre>
      <pre>' UNION SELECT 1,string_agg(login||':'||password_hash,', ') FROM users -- </pre>
    </div>
    <?php pg_close($db); ?>
  </body>
</html>
