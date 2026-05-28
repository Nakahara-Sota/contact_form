<?php
session_start();
require_once('/xampp/htdocs/contact_form/php/includes/utils/sanitizer.php');
require_once('/xampp/htdocs/contact_form/php/includes/utils/validator.php');

$errors = token_checker($_POST);

if (count($errors) == 0) {
    $errors = validation($_POST);
}

//初期化
$clean = []; //有効なデータを入れる
//判定
if (count($errors) > 0) {
    $_SESSION['err_msg'] = $errors;
    $_SESSION['old'] = $_POST; //入力内容をそのまま戻す
    header('Location: index.php');
    exit;
}

//サニタイズ
$clean = sanitize($_POST);

//データベースに接続
try {
    $dsn = 'mysql:dbname=contact_form; host=localhost; charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //都道府県を取得
    $sql = "SELECT id, name FROM prefectures";
    $prefs = $dbh->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);

    //接続を切断
    $dbh = null;
} catch (PDOException $e) {
    exit('データの取得に失敗しました：' . $e->getMessage());
}

$pref_name = $prefs[$clean['pref']] ?? '県名不明';

//完了画面用に、バリデーションに通った生データをセッションへ保存
$_SESSION['submit_data'] = $_POST;

$next_token = $_SESSION['token'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="../css/style.css" rel=stylesheet>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>お問い合わせ内容の確認 | お問い合わせフォーム</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include '/xampp/htdocs/contact_form/php/includes/components/header.php'; ?>
    <div class="container my-3 flex-grow-1">
        <h1 class="callout">お問い合わせ内容の確認</h1>
        <p class="fs-4 mb-3">下記の内容でよろしければ「送信」ボタンを押してください。</p>
        <table class="table table-bordered">
            <tr>
                <th class="table-light">お名前</th>
                <td><span class="text-break"><?= $clean['username']; ?></span></td>
            </tr>
            <tr>
                <th class="table-light">住所</th>
                <td>
                    <span class="text-break">
                        〒<?= $clean['postal_code']; ?><br>
                        <?= htmlspecialchars($pref_name, ENT_QUOTES, 'UTF-8'); ?><br>
                        <?= $clean['city']; ?><br>
                        <?= $clean['address_line']; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th class="table-light">メールアドレス</th>
                <td><span class="text-break"><?= $clean['email']; ?></span></td>
            </tr>
            <tr>
                <th class="table-light">電話番号</th>
                <td><span class="text-break"><?= $clean['phone']; ?></span></td>
            </tr>
            <tr>
                <th class="table-light">お問い合わせ内容</th>
                <td><span class="text-break"><?= nl2br($clean['message'], false); ?></span></td>
            </tr>
        </table>
        <form action="done.php" method="post">
            <input type="hidden" name="token" value="<?= $next_token ?>">
            <input type="submit" class="btn btn-outline-primary btn-lg px-4 fw-bold" value="送信" id="submit-btn">
            <input type="button" class="btn btn-outline-danger btn-lg px-4 fw-bold" onclick="history.back()" value="戻る">
        </form>
    </div>
    <script src="../js/prevent_double_submit.js"></script>
</body>
<?php include '/xampp/htdocs/contact_form/php/includes/components/footer.php'; ?>

</html>