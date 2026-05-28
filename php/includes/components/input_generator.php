<?php

/**
 * 入力項目（input/textarea兼用）を生成する
 */
function render_form_row($name, $label, $is_required, $old, $errors, $options = [])
{
   //オプションのデフォルト値
   $type = $options['type'] ?? 'text'; //通常のテキスト入力
   $limit = $options['limit'] ?? 50;
   $least = $options['least'] ?? ($is_required ? 1 : 0);
   $autocomplete = isset($options['autocomplete']) ? 'autocomplete="' . $options['autocomplete'] . '"' : '';
   $rows = $options['rows'] ?? 5; //textarea用
   $help_text = $options['help_text'] ?? ''; //spanを使った補足説明用
   $described_by = $help_text ? 'aria-describedby="' . $name . 'HelpInline"' : '';
   $show_count = $options['show_count'] ?? true; //文字カウントが要らない項目用
   
   //数値をコンマ区切りに
   $formatted_limit = number_format($limit);

   //エラーメッセージ
   $error_html = '';
   if (isset($errors[$name])) {
      $error_html = '<div class="text-danger small fw-bold">' . htmlspecialchars($errors[$name]) . '</div>';
   }

   //必須バッジ
   $badge_html = $is_required ? '<span class="badge text-bg-danger fs-6">必須</span>' : '';

   //エスケープ済みの値
   $value = htmlspecialchars($old[$name] ?? '', ENT_QUOTES, 'UTF-8');

   //入力タグ部分の切り替え
   if ($type === 'textarea') {
      $input_html = "<textarea name=\"{$name}\" id=\"{$name}\" rows=\"{$rows}\" class=\"form-control\" limit=\"{$limit}\" least=\"{$least}\">{$value}</textarea>";
   } else {
      $input_html = "<input type=\"{$type}\" id=\"{$name}\" name=\"{$name}\" class=\"form-control\" value=\"{$value}\" least=\"{$least}\" limit=\"{$limit}\" {$autocomplete} {$described_by}>";
   }

   //文字カウンターHTML（$show_count が true の時だけ出力する）
   $count_group_class = $show_count ? 'count-group' : '';
   $counter_html = '';
   if ($show_count) {
      $counter_html = "<p class=\"text-muted fw-bold mt-1 mb-0\">現在の文字数: <span class=\"count-view\">0</span> / {$formatted_limit}文字</p>";
   }

   $body_content = '';
   if ($help_text) {
      $body_content = <<<HTML
        <div class="row g-3 align-items-center {$count_group_class}">
           <div class="col-5 d-flex align-items-center gap-2 count-group">
              {$input_html}
              <span class="btn btn-danger btn-sm fw-bold text-nowrap" style="display: none; cursor: pointer;">消去</span>
           </div>
           <div class="col-7">
              <span id="{$name}HelpInline">{$help_text}</span>
           </div>
           <div class="col-12 mt-1">
              {$counter_html}
           </div>
        </div>
        HTML;
   } else {
      //通常の項目（名前やメール、textareaなど）のレイアウト
      $body_content = <<<HTML
        <div class="{$count_group_class}">
           <div class="d-flex align-items-center gap-2">
              {$input_html}
              <div class="flex-grow-1"></div>
              <span class="btn btn-danger btn-sm fw-bold text-nowrap" style="display: none; cursor: pointer;">消去</span>
           </div>
           {$counter_html}
        </div>
        HTML;
   }

   //HTML出力
   echo <<<HTML
    <tr>
       <th class="table-light">
          <div class="d-flex justify-content-between align-items-center">
             <span>{$label}</span>
             {$badge_html}
          </div>
       </th>
       <td>
          {$error_html}
          {$body_content}
       </td>
    </tr>
    HTML;
}
