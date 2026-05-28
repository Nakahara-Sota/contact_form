document.addEventListener("DOMContentLoaded", () => {
  const clearButtons = document.querySelectorAll(".count-group .btn-danger");

  clearButtons.forEach((btn) => {
    const container = btn.closest(".count-group");
    if (!container) return;

    const input = container.querySelector("input, textarea");
    if (!input) return;

    // 初期表示の切り替え
    btn.style.display = input.value.length > 0 ? "inline" : "none";

    // 入力時のボタン表示制御
    input.addEventListener("input", () => {
      btn.style.display = input.value.length > 0 ? "inline" : "none";
    });

    // 消去ボタンクリック時の処理
    btn.addEventListener("click", () => {
      input.value = ""; // 値をクリア
      btn.style.display = "none"; // ボタンを非表示

      // 入力イベントを強制発火
      const event = new Event("input");
      input.dispatchEvent(event);

      input.focus(); // フォーカスを戻す
    });
  });
});
