<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   $errors = [];
   $errors['err'] = "不正な処理が行われました。";
   $_SESSION['err_msg'] = $errors;
   header('Location: index.php');
   exit;
}

function token_checker(array $data)
{
   $errors = [];

   //トークンを確認
   if (!isset($_SESSION['token']) || !isset($data['token']) || $_SESSION['token'] !== $data['token']) {
      $errors['err'] = "不正な処理が行われました。";
      $_SESSION['err_msg'] = $errors;
      header('Location: index.php');
      exit;
   }

   return $errors;
}

function validation(array $data)
{

   $errors = [];

   //名前
   $val = trim($data['username'] ?? '');
   if (preg_match('/^\s*$/u', $val)) {
      $errors['username'] = "お名前を入力してください。";
   } elseif (mb_strlen($val) > 50) {
      $errors['username'] = "恐れ入りますが、お名前は50文字以内で入力してください。";
   } else {
      $clean['username'] = $val;
   }

   //郵便番号
   $val = trim($data['postal_code'] ?? '');
   if (empty($data['postal_code'])) {
      //空欄の場合
      $errors['postal_code'] = "郵便番号を入力してください。";
   } elseif (!preg_match('/\A\d{3}-\d{4}\z/', $val)) {
      //入力はあるが、正規表現ではない場合
      $errors['postal_code'] = "郵便番号は半角数字と半角ハイフンで入力してください。";
   }

   //都道府県
   if (empty($data['pref'])) {
      $errors['pref'] = "都道府県を選択してください。";
   }

   //市区町村、番地 (共通ルールなのでまとめてチェック)
   foreach (['city' => '市区町村', 'address_line' => '町名番地等'] as $key => $label) {
      $val = trim($data[$key] ?? '');
      if (preg_match('/^\s*$/u', $val)) {
         $errors[$key] = "{$label}を入力してください。";
      } elseif (mb_strlen($val) > 50) {
         $errors[$key] = "恐れ入りますが、{$label}は50文字以内で入力してください。";
      }
   }

   //メールアドレス
   $val = $data['email'] ?? '';
   if ($val === '') {
      $errors['email'] = "メールアドレスを入力してください。";
   } elseif (mb_strlen($val) > 256) {
      $errors['email'] = "恐れ入りますが、メールアドレスは256文字以内で入力してください。";
   } elseif (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "メールアドレスを正しく入力してください。";
   }

   //電話番号（任意だが入力するならチェック）
   $val = $data['phone'] ?? '';
   if (!empty($val) && !preg_match('/\A\d{2,5}-\d{2,5}-\d{4,5}\z/', $val)) {
      $errors['phone'] = "電話番号を正しく入力してください。";
   }

   //お問い合わせ内容（1000文字制限）
   $val = $data['message'] ?? '';
   if (preg_match('/^\s*$/u', $val)) {
      $errors['message'] = "お問い合わせ内容を入力してください。";
   } elseif (mb_strlen($val) > 1000) {
      $errors['message'] = "恐れ入りますが、お問い合わせ内容は1,000文字以内でご入力ください。";
   }

   //ダミー入力欄
   if (!empty($_POST['furigana'])) {
      //スパム検知
      die("不正な入力が行われました。");
   }
   return $errors;
}
