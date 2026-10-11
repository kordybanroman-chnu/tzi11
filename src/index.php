<?php require __DIR__ . "/db.php"; ?>
<!DOCTYPE html>
<html lang="uk">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>ЛР11 — Вхід</title>
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
        max-width: 420px;
        margin: 40px auto;
        padding: 24px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      }
      h1 {
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 16px;
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
        margin-top: 24px;
        background: #3f51b5;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 11px;
        font: inherit;
        font-size: 14px;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      }
      button:hover {
        background: #3949ab;
      }
      .row {
        display: flex;
        gap: 12px;
      }
      .row button {
        flex: 1;
      }
      .row button.ghost {
        background: #fff;
        color: #3f51b5;
        border: 1px solid #3f51b5;
        box-shadow: none;
      }
      .msg {
        margin-top: 16px;
        font-size: 14px;
      }
      .link {
        text-align: center;
        margin-top: 16px;
        font-size: 14px;
      }
      a {
        color: #3f51b5;
      }
    </style>
  </head>
  <body>
    <div class="bar">ЛР11 — Вхід</div>
    <div class="card">
      <h1>Авторизація</h1>
      <form method="POST" action="/">
        <label>Логін<input name="login" required autocomplete="off" /></label>
        <label>Пароль<input type="password" name="password" required /></label>
        <div class="row">
          <button>Увійти</button>
          <button class="ghost" formaction="/?register=1">Реєстрація</button>
        </div>
      </form>
      <?php if ($msg) {
          echo '<p class="msg">' .
              htmlspecialchars($msg) .
              '</p>
      ';
      } ?>
      <p class="link"><a href="/admin/">Технічна сторінка</a></p>
    </div>
    <?php pg_close($db); ?>
  </body>
</html>
