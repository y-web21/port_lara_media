(() => {
  /**
   * フォームに変更を加えた状態で、ページから離脱しようとした際に確認メッセージを出します。
   */
  const formChangesWathcer = () => {
    /** @type {Element} */
    const formInputs = document.querySelectorAll('form input[name], form textarea[name]')
    /** @type {boolean} */
    let hasChange = false;

    // なにかしらの変更があったことを記憶する
    for (const el of formInputs) {
      el.addEventListener('change', () => hasChange = true)
    }

    // 特定のボタンは離脱警告対象から除外する
    const exceptIds = ['btn_edit', 'btn_edit']
    for (const el of exceptIds) {
      document.getElementById(el).addEventListener('click', () => hasChange = false)
    }

    // 離脱時に確認する
    window.addEventListener('beforeunload', ev => {
      if (hasChange) ev.returnValue = ''
    }, false)
  }

  formChangesWathcer()
})();
