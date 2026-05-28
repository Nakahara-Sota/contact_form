document.querySelector("form").addEventListener("submit", function (e) {
  const submitBtn = document.getElementById("submit-btn"); //ボタンのID
  submitBtn.disabled = true;
});

window.addEventListener("pageshow", function () {
  const indexBtn = document.getElementById("submit-btn");
  if (indexBtn) {
    indexBtn.disabled = false;
  }
});
