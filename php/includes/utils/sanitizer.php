<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   $errors = [];
   $errors['err'] = "不正な処理が行われました。";
   $_SESSION['err_msg'] = $errors;
   header('Location: index.php');
   exit;
}

/**
 * XSS対策のためのエスケープ処理を再帰的に行います
 *
 * @param array|string $data エスケープ対象のデータ
 * @return array|string エスケープ済みの安全なデータ
 */
function sanitize($data)
{
   if (is_array($data)) {
      //中身が配列なら、さらにsanitize自身を呼び出す
      return array_map('sanitize', $data);
   } else {
      //単なる文字列ならエスケープする
      return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
   }
}
