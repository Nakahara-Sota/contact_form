//文字数表示を制御
document.addEventListener("DOMContentLoaded", () => {
  const targets = document.querySelectorAll(
    'textarea, input[type="text"], input[type="password"]',
  );

  targets.forEach((el) => {
    const container = el.closest(".count-group");
    if (!container) return;

    const viewer = container.querySelector(".count-view");
    //HTMLの数字を読み取る
    const limit = el.getAttribute("limit");
    const least = el.getAttribute("least");

    const limitInt = parseInt(limit);
    const leastInt = parseInt(least);

    if (viewer && limit) {
      //リストに入った入力欄(要素)を一つずつ取り出して、それぞれに専用の処理を設定
      const updateCount = () => {
        const currentLength = el.value.length;
        viewer.textContent = currentLength.toLocaleString();

        //制限文字数オーバーの処理
        if (leastInt > currentLength || currentLength > limitInt) {
          viewer.classList.add("text-danger");
        } else {
          viewer.classList.remove("text-danger");
        }
      };

      //初期表示と入力時の両方で実行
      updateCount();
      el.addEventListener("input", updateCount);
    }
  });
});
