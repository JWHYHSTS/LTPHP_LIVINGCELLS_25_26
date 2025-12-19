(function () {
  const stack = () => document.getElementById('toastStack');

  function iconHtml(type) {
    if (type === 'success') return '✓';
    if (type === 'error') return '!';
    return 'i';
  }

  function toast(type, title, message, timeout = 2600) {
    const host = stack();
    if (!host) return;

    const el = document.createElement('div');
    el.className = `toast-notification sv-toast sv-toast-${type}`;
    el.setAttribute('role', 'status');
    el.innerHTML = `
      <div class="toast-icon">${iconHtml(type)}</div>
      <div style="flex:1; min-width:0">
        ${title ? `<div class="sv-toast-title" style="font-weight:700; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">${title}</div>` : ''}
        <div class="sv-toast-msg" style="white-space:normal; overflow-wrap:anywhere">${message ?? ''}</div>
      </div>
      <button type="button" class="sv-toast-close" aria-label="Đóng"
              style="border:0; background:transparent; font-size:18px; line-height:1; cursor:pointer; padding:0 2px;">×</button>
    `;

    // close
    el.querySelector('.sv-toast-close')?.addEventListener('click', () => hide(el));

    host.appendChild(el);

    let timer = setTimeout(() => hide(el), timeout);
    el.addEventListener('mouseenter', () => clearTimeout(timer));
    el.addEventListener('mouseleave', () => (timer = setTimeout(() => hide(el), 1200)));

    function hide(node) {
      node.classList.add('toast-hide');
      setTimeout(() => node.remove(), 520);
    }
  }

  // expose global
  window.SVToast = { toast };
})();
