document.addEventListener("DOMContentLoaded", () => {
  //input要素用の監視設定
  const inputs = document.querySelectorAll(
    'input[type="text"], input[type="password"]',
  );
  inputs.forEach((el) => {
    const descriptor = Object.getOwnPropertyDescriptor(
      HTMLInputElement.prototype,
      "value",
    );
    if (!descriptor) return;

    Object.defineProperty(el, "value", {
      set: function (val) {
        descriptor.set.call(this, val);
        this.dispatchEvent(new Event("input", { bubbles: true })); // 電波を飛ばす
      },
      get: function () {
        return descriptor.get.call(this);
      },
      configurable: true,
    });
  });

  //textarea要素用の監視設定
  const textareas = document.querySelectorAll("textarea");
  textareas.forEach((el) => {
    const descriptor = Object.getOwnPropertyDescriptor(
      HTMLTextAreaElement.prototype,
      "value",
    );
    if (!descriptor) return;

    Object.defineProperty(el, "value", {
      set: function (val) {
        descriptor.set.call(this, val);
        this.dispatchEvent(new Event("input", { bubbles: true })); // 電波を飛ばす
      },
      get: function () {
        return descriptor.get.call(this);
      },
      configurable: true,
    });
  });
});
