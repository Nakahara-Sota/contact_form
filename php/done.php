<?php
session_start();
require_once('/xampp/htdocs/contact_form/php/includes/utils/sanitizer.php');
require_once('/xampp/htdocs/contact_form/php/includes/utils/validator.php');

$errors = token_checker($_POST);

if (count($errors) == 0) {
   $errors = [];

   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $errors['err'] = "不正な処理が行われました。";
      $_SESSION['err_msg'] = $errors;
   }
}

if (count($errors) > 0) {
   $_SESSION['old'] = $_POST; //入力内容をそのまま戻す
   header('Location: index.php');
   exit;
}

$raw_data = $_SESSION['submit_data'] ?? [];
$post = sanitize($raw_data);
$username = $post['username'];
$email = $post['email'];
$message = $post['message'];

//データベースに接続
try {

   //.envファイルを解析して環境変数に登録する関数
   function loadEnv($path)
   {
      if (!file_exists($path)) return;
      $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($lines as $line) {
         if (strpos(trim($line), '#') === 0) continue; //コメント行を無視
         list($name, $value) = explode('=', $line, 2);
         $_ENV[trim($name)] = trim($value);
      }
   }

   //実行
   loadEnv(__DIR__ . '/../.env');

   $dsn = "mysql:dbname={$_ENV['DB_NAME']}; host={$_ENV['DB_HOST']}; charset=utf8";
   $user = $_ENV['DB_USER'];
   $password = $_ENV['DB_PASS'];
   $dbh = new PDO($dsn, $user, $password);
   $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   $sql = 'INSERT INTO contacts(username, postal_code, pref_code, city, address_line, email, phone_number, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
   $stmt = $dbh->prepare($sql);

   $stmt->execute([
      $raw_data['username'],
      $raw_data['postal_code'],
      $raw_data['pref'],
      $raw_data['city'],
      $raw_data['address_line'],
      $raw_data['email'],
      $raw_data['phone'],
      $raw_data['message']
   ]);

   //接続を切断
   $dbh = null;
} catch (PDOException $e) {
   exit('データの追加に失敗しました：' . $e->getMessage());
}
//送信がすべて成功したら、トークンを破棄（二重送信防止）
unset($_SESSION['token']);
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <link href="../css/style.css" rel=stylesheet>
   <link rel="icon" href="../favicon.ico" type="image/x-icon">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
   <title>お問い合わせ送信完了 | お問い合わせフォーム</title>
</head>

<body class="d-flex flex-column min-vh-100">
   <?php include '/xampp/htdocs/contact_form/php/includes/components/header.php'; ?>
   <div class="container my-3 flex-grow-1">
      <h1 class="callout">お問い合わせ完了</h1>
      <p>
         お問い合わせいただき、ありがとうございます。<br>
         担当より連絡いたしますので、しばらくお待ちください。
      </p>
      <?php
      $mail_body = '';
      $mail_body .= $username . " 様\n\n";
      $mail_body .= "この度はお問い合わせいただき、誠にありがとうございます。\n";
      $mail_body .= "送信いただいた内容は無事に受け付けいたしました。\n\n";
      $mail_body .= "内容を確認の上、通常3営業日以内にご返信いたします。\n";
      $mail_body .= "恐れ入りますが、今しばらくお待ちいただけますようお願い申し上げます。\n\n";
      $mail_body .= "※本メールはシステムによる自動返信です。\n";
      $mail_body .= "\n\n";
      $mail_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
      $mail_body .= "■ お問い合わせ内容\n";
      $mail_body .= "お名前：" . $username . " 様\n";
      $mail_body .= "メールアドレス：" . $email . "\n";
      $mail_body .= "内容：\n" . $message . "\n";
      $mail_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
      $mail_body .= "■ ご連絡先\n";
      $mail_body .= "ポートフォリオお問い合わせフォーム\n";
      $mail_body .= "〒530-0001 大阪府大阪市北区梅田9-8888\n";
      $mail_body .= "ポートフォリオビルヂング 71階\n";
      $mail_body .= "TEL : 555-123-4567（受付時間：平日 9:00〜18:00）\n";
      $mail_body .= "URL : http://localhost/contact_form/php/index.php\n";
      $mail_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

      $title = '【自動返信】お問い合わせを受け付けました';
      $header = "From: sample@email.jp\r\n";
      $header .= "Reply-To: sample@email.jp\r\n";
      $header .= "Content-Type: text/plain; charset=UTF-8\r\n";

      mb_language('Japanese');
      mb_internal_encoding('UTF-8');

      // 本番環境（サーバー契約後）にコメントアウトを解除
      // mb_send_mail($email, $title, $mail_body, $header);
      ?>
      <a href="index.php" class="btn btn-outline-primary btn-lg fw-bold">戻る</a>
   </div>
   <?php include '/xampp/htdocs/contact_form/php/includes/components/footer.php'; ?>
</body>

</html>