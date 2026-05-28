<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   $errors = [];
   $errors['err'] = "不正な処理が行われました。";
   $_SESSION['err_msg'] = $errors;
   header('Location: index.php');
   exit;
}


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

function make_combined_options($start, $end, $selected_value = null)
{
   $options = ''; //最初に空の状態で宣言
   for ($i = $start; $i <= $end; $i++) {
      //%02d = 「10進数(d)で、2桁(2)に満たない場合は0で埋める」
      $value = sprintf('%02d', $i);

      //選択状態の判定（DBから取得した値と一致するかチェック）
      $selected = ($value == $selected_value) ? ' selected' : '';

      $options .= '<option value="' . $value . '"' . $selected . '>' . $value . '</option>';
   }
   return $options;
}
