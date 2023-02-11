(() => {
  /**
   * フォームに変更を加えた状態で、ページから離脱しようとした際に確認メッセージを出します。
   */
  const formChangesWathcer = () => {
    /** @type {Element} */
    const formInputs = document.querySelectorAll('form input[name], form textarea[name]')
    /** @type {boolean} */
    let hasChange = false;
    for (const el of formInputs) {
      el.addEventListener('change', () => hasChange = true)
    }
    window.addEventListener('beforeunload', e => {
      if (hasChange) e.returnValue = ''
    }, false)
  }

  formChangesWathcer()
})();
