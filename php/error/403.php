<?php session_start(); ?>
<head>
   <meta charset="UTF-8">
   <link href="/contact_form/css/style.css" rel=stylesheet>
   <link rel="icon" href="/contact_form/favicon.ico" type="image/x-icon">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
   <title>404 Forbidden | お問い合わせフォーム</title>
</head>

<body class="d-flex flex-column min-vh-100">
   <?php include '/xampp/htdocs/contact_form/php/includes/components/header.php'; ?>
   <div class="container my-3 flex-grow-1">
      <h1 class="callout">アクセス拒否</h1>
      <p>
         申し訳ありませんが、お探しのURLにアクセスする権限が確認できません。<br>
      </p>
      <a href="/contact_form/php/index.php">お問い合わせ入力フォームへ</a>
   </div>
   <?php include '/xampp/htdocs/contact_form/php/includes/components/footer.php'; ?>
</body>

</html>