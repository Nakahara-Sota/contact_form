//隠し項目の都道府県名（文字）を監視する
const regionHidden = document.querySelector(".p-region");
const prefSelect = document.querySelector("#pref");

if (regionHidden && prefSelect) {
  //住所が自動入力されたタイミングをキャッチ
  const observer = new MutationObserver(() => {
    const strName = regionHidden.value; //「東京都」などが入る

    //セレクトボックスの選択肢の中から、表示テキストが一致するものを探す
    Array.from(prefSelect.options).forEach((option) => {
      if (option.text === strName) {
        prefSelect.value = option.value; //一致したらそのIDを選択状態にする
      }
    });
  });

  //隠し要素の「value」属性の変化を監視開始
  observer.observe(regionHidden, {
    attributes: true,
    attributeFilter: ["value"],
  });
}
