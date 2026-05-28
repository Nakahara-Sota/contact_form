<?php
//CSRF状態をチェック(トークン等あれば有効)
$is_csrf_enabled = !empty($_SESSION['token']) || !empty($_SESSION['csrf_token']) || !empty($_SESSION['submit_data']);

//SSL（HTTPS）接続状態を判定
$is_ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-info bg-gradient shadow-sm">
  <div class="container">
    <!-- ポートフォリオのタイトル -->
    <a class="navbar-brand fw-bold" href="#">
      お問い合わせフォーム v1.0
    </a>

    <!-- ナビゲーションの代わりにバッジを並べる -->
    <div class="d-flex align-items-center gap-2">
      <!-- SSL判定:本番のHTTPS接続なら緑、違えば赤 -->
      <?php if ($is_ssl): ?>
        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">SSL暗号化通信</span>
      <?php else: ?>
        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle">非暗号化通信 (危険)</span>
      <?php endif; ?>

      <!-- PHPバージョン:サーバーの値を自動出力 -->
      <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle">
        PHP <?= htmlspecialchars(phpversion()); ?>
      </span>

      <!-- CSRF判定:プログラムの状態によって色と文字を変える -->
      <?php if ($is_csrf_enabled): ?>
        <span class="badge rounded-pill bg-success-subtle text-success border">CSRF: 有効</span>
      <?php else: ?>
        <span class="badge rounded-pill bg-danger text-white">CSRF: 無効 (脆弱性あり)</span>
      <?php endif; ?>
    </div>
  </div>
</nav>