<?php
session_start();
require_once('/xampp/htdocs/contact_form/php/includes/components/input_generator.php');
// エラーメッセージを取り出す（なければ空の配列）
$errors = $_SESSION['err_msg'] ?? [];
// 入力していた内容を取り出す（なければ空の配列）
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err_msg']);
unset($_SESSION['old']);

//ワンタイムトークンの用意
$is_strong = false;
$token = bin2hex(openssl_random_pseudo_bytes(16, $is_strong));

if ($is_strong === false) {
   die("不具合につき動作を中断します");
}

$_SESSION['token'] = $token;

//データベースに接続
try {
   $dsn = 'mysql:dbname=contact_form; host=localhost; charset=utf8';
   $user = 'root';
   $password = '';
   $dbh = new PDO($dsn, $user, $password);
   $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // 地方と都道府県を結合して取得
   $sql = "SELECT p.id, p.name, r.name AS region_name 
            FROM prefectures p 
            JOIN regions r ON p.region_id = r.id 
            ORDER BY r.id, p.id";
   $prefs = $dbh->query($sql)->fetchAll();

   //接続を切断
   $dbh = null;
} catch (PDOException $e) {
   exit('データの取得に失敗しました。時間をおいて再度お試しください。');
}

//送信された値がある場合は取得（XSS対策でエスケープ）
$selected_id = isset($old['pref']) ? (int)$old['pref'] : 0;
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <link href="../css/style.css?v=3" rel="stylesheet">
   <link rel="icon" href="../favicon.ico" type="image/x-icon">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
   <title>お問い合わせ内容を入力 | お問い合わせフォーム</title>
</head>

<body class="d-flex flex-column min-vh-100">
   <?php include '/xampp/htdocs/contact_form/php/includes/components/header.php'; ?>
   <div class="container my-3 flex-grow-1">
      <h1 class="callout">お問い合わせ内容を入力</h1>
      <p>
         お問い合わせは下記のフォームよりお願いします。<br>
         <span class="badge text-bg-danger fs-6">必須</span>マークは必須入力項目です。
      </p>
      <?php if (isset($errors['err'])): ?>
         <div class="text-danger small fw-bold"><?= $errors['err'] ?></div>
         <?php unset($_SESSION['err_msg']); ?>
      <?php endif; ?>
      <form action="confirm.php" method="post" class="h-adr">
         <span class="p-country-name" style="display:none;">Japan</span>
         <table class="table table-bordered">
            <?php render_form_row('username', 'お名前', true, $old, $errors, ['limit' => 50]); ?>
            <tr class="spam-trap">
               <th>ふりがな</th>
               <td>
                  <input type="text" name="furigana" value="" tabindex="-1" autocomplete="off">
               </td>
            </tr>
            <?php require_once('/xampp/htdocs/contact_form/php/includes/components/address_form.php'); ?>
            <?php render_form_row('email', 'メールアドレス', true, $old, $errors, ['limit' => 256]); ?>
            <?php render_form_row('phone', '電話番号', false, $old, $errors, [
               'limit' => 15,
               'help_text' => '(半角数字と半角ハイフンのみ)',
               'show_count' => false
            ]); ?>
            <?php render_form_row('message', 'お問い合わせ内容', true, $old, $errors, [
               'type' => 'textarea',
               'limit' => 1000,
               'rows' => 5
            ]); ?>
         </table>
         <input type="hidden" name="token" value="<?= $token ?>">
         <button type="submit" class="btn btn-outline-primary btn-lg px-4 fw-bold" id="submit-btn">確認</button>
      </form>
   </div>
   <?php include '/xampp/htdocs/contact_form/php/includes/components/footer.php'; ?>

   <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
   <script src="../js/postal_code.js"></script>
   <script src="../js/char_clear.js"></script>
   <script src="../js/char_count.js"></script>
   <script src="../js/postal_watcher.js"></script>
   <script src="../js/prevent_double_submit.js"></script>
</body>

</html>