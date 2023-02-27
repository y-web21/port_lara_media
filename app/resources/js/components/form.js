(() => {
  /**
   * フォームに変更を加えた状態で、ページから離脱しようとした際に確認メッセージを出します。
   */
  const formChangesWathcer = () => {
    /** @type {Element} */
    const formInputs = document.querySelectorAll('input[name], textarea[name]')
    /** @type {boolean} */
    let hasChange = false;
    /** @type {Element} */
    const exceptIds = document.querySelectorAll('#btn_post, #btn_edit')

    // なにかしらの変更があったことを記憶する
    for (const el of formInputs) {
      el.addEventListener('change', () => hasChange = true)
    }

    // 特定の要素は離脱警告対象から除外する
    for (const el of exceptIds) {
      el.addEventListener('click', () => hasChange = false)
    }

    // 離脱時に確認する
    window.addEventListener('beforeunload', ev => {
      if (hasChange) ev.returnValue = ''
    }, false)
  }

   /**
   * @param {string} id input type=file で選択したファイル名の表示先
   */
  const refreshFilename = id => {
    const file = document.querySelector('input[type=file]')
    const disp = document.getElementById(id)
    file.addEventListener('change', () => disp.textContent = file.value.split(/.*\\/)[1] ?? '選択されていません')
  }

  formChangesWathcer()
  refreshFilename('disp_filename')
})();
