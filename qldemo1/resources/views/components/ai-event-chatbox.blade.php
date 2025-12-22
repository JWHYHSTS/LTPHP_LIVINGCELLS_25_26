{{-- resources/views/components/ai-event-chatbox.blade.php --}}
<link rel="stylesheet" href="{{ asset('css/ai-chatbox-modern.css') }}">

<div class="ai-chat-widget" id="aiChatWidget" aria-live="polite">
  <div class="ai-chat-panel" id="aiChatPanel" role="dialog" aria-label="AI Trợ lý hệ thống" aria-modal="false">
    <div class="ai-chat-header">
      <div class="ai-chat-title">
        <div class="ai-chat-badge" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M7.5 19.5c-1.2 0-2.2-1-2.2-2.2V7.2C5.3 6 6.3 5 7.5 5h9c1.2 0 2.2 1 2.2 2.2v10.1c0 1.2-1 2.2-2.2 2.2h-9Z" stroke="rgba(255,255,255,.85)" stroke-width="1.7"/>
            <path d="M8.5 9h7M8.5 12h7M8.5 15h4.5" stroke="rgba(255,255,255,.85)" stroke-width="1.7" stroke-linecap="round"/>
          </svg>
        </div>

        <div class="ai-chat-title-text">
          <h4>AI Trợ lý hệ thống</h4>
          <p>Lưu theo phiên trình duyệt (đổi trang không mất)</p>
        </div>
      </div>

      <div class="ai-chat-actions">
        <button type="button" class="ai-chat-btn" id="aiChatClear">Xóa</button>
        <button type="button" class="ai-chat-close" id="aiChatClose" aria-label="Đóng">×</button>
      </div>
    </div>

    <div class="ai-chat-body" id="aiChatMessages"></div>

    <div class="ai-chat-footer">
      <div class="ai-input-row">
        <input class="ai-input" id="aiChatInput"
               placeholder="Hỏi về sự kiện, đăng ký, điểm danh, danh hiệu, tài khoản..."
               autocomplete="off" />
        <button class="ai-send" id="aiChatSend" type="button">
          <span>Gửi</span>
          <svg class="ai-send-ico" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 12 3.6 20.1c-.7.32-1.4-.31-1.18-1.04l1.7-5.55a1 1 0 0 1 .64-.66L12 10l7.24-2.85a1 1 0 0 1 .64.66l1.7 5.55c.22.73-.48 1.36-1.18 1.04Z" stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      <div class="ai-footer-hint">
        Gợi ý: “Sự kiện mở đăng ký?”, “Chi tiết #ID”, “Điều kiện danh hiệu …”
      </div>
    </div>
  </div>

  <button class="ai-chat-toggle" id="aiChatToggle" type="button" aria-label="Mở AI">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M12 2.5c5.2 0 9.5 3.7 9.5 8.3 0 2.8-1.6 5.2-4.2 6.7-.7.4-1.2 1.1-1.3 1.9l-.1 1.1c-.1.7-.7 1.2-1.4 1.2H9.5c-.7 0-1.3-.5-1.4-1.2l-.1-1.1c-.1-.8-.6-1.5-1.3-1.9C4.1 16 2.5 13.6 2.5 10.8 2.5 6.2 6.8 2.5 12 2.5Z" stroke="white" stroke-width="1.6"/>
      <path d="M9.2 10.4h5.6" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
      <path d="M9.2 13h3.2" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
    </svg>
    <span class="ai-fab-dot" aria-hidden="true"></span>
  </button>
</div>

<script>
(function () {
  const panel    = document.getElementById('aiChatPanel');
  const toggle   = document.getElementById('aiChatToggle');
  const closeBtn = document.getElementById('aiChatClose');
  const clearBtn = document.getElementById('aiChatClear');
  const box      = document.getElementById('aiChatMessages');
  const input    = document.getElementById('aiChatInput');
  const sendBtn  = document.getElementById('aiChatSend');

  // Nếu component bị render trùng (do include nhiều lần), tránh bind trùng
  if (!panel || panel.dataset.bound === '1') return;
  panel.dataset.bound = '1';

  const STORAGE_KEY = 'ai_chat_history_v1';
  const OPEN_KEY    = 'ai_chat_open_v1';

  let sending = false;
  let history = loadHistory();

  function loadHistory() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const arr = raw ? JSON.parse(raw) : [];
      return Array.isArray(arr) ? arr.slice(-30) : [];
    } catch (_) { return []; }
  }

  function saveHistory() {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(history.slice(-30))); } catch (_) {}
  }

  function escapeHtml(s) {
    return (s || '').toString()
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;');
  }

  function renderText(text) {
    let s = escapeHtml(text);
    s = s.replace(/`([^`]+)`/g, '<code>$1</code>');
    s = s.replace(/\*\*([^*]+)\*\*/g, '<b>$1</b>');
    s = s.replace(/\*([^*]+)\*/g, '<i>$1</i>');
    s = s.replaceAll('\n', '<br>');
    return s;
  }

  function addMsg(role, text) {
    const wrap = document.createElement('div');
    wrap.className = 'ai-msg ' + role;

    const bubble = document.createElement('div');
    bubble.className = 'ai-bubble';
    bubble.innerHTML = renderText(text);

    wrap.appendChild(bubble);
    box.appendChild(wrap);
    box.scrollTop = box.scrollHeight;
  }

  function renderAll() {
    box.innerHTML = '';
    if (!history.length) {
      addMsg('assistant',
        'Bạn có thể hỏi:\n' +
        '- "Sự kiện nào đang mở đăng ký?"\n' +
        '- "Chi tiết sự kiện #id"\n' +
        '- "Cách đăng ký / hủy đăng ký?"\n' +
        '- "Vai trò Admin/CTCT/KhảoThí/Đoàn/Sinh viên có chức năng gì?"\n' +
        '- "Điều kiện danh hiệu Sinh viên 5 tốt / Đoàn viên ưu tú?"'
      );
      return;
    }
    history.forEach(h => addMsg(h.role, h.content));
  }

  function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  }

  function setSending(state) {
    sending = state;
    sendBtn.disabled = state;
    input.disabled = state;
  }

  function showTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'ai-msg assistant';
    wrap.id = 'aiTypingRow';

    const bubble = document.createElement('div');
    bubble.className = 'ai-bubble';
    bubble.innerHTML =
      '<span class="ai-typing">AI đang trả lời' +
      '<span class="ai-dots">' +
      '<span class="ai-dot"></span><span class="ai-dot"></span><span class="ai-dot"></span>' +
      '</span></span>';

    wrap.appendChild(bubble);
    box.appendChild(wrap);
    box.scrollTop = box.scrollHeight;
  }

  function hideTyping() {
    document.getElementById('aiTypingRow')?.remove();
  }

  async function send() {
    const msg = (input.value || '').trim();
    if (!msg || sending) return;

    const csrf = getCsrfToken();
    if (!csrf) {
      addMsg('assistant', 'Thiếu CSRF token. Hãy kiểm tra layouts/app.blade.php có <meta name="csrf-token"...> trong <head>.');
      return;
    }

    addMsg('user', msg);
    history.push({ role: 'user', content: msg });
    saveHistory();
    input.value = '';

    setSending(true);
    showTyping();

    try {
      const res = await fetch(`{{ route('ai.eventChat') }}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ message: msg, history: history.slice(-10) }),
      });

      const raw = await res.text();
      let data = {};
      try { data = JSON.parse(raw); } catch (_) {}

      hideTyping();

      if (!res.ok) {
        const detail = (data.message || raw || '').toString().trim();
        addMsg('assistant', `Lỗi server (${res.status})${detail ? ': ' + detail : ''}`);
        return;
      }

      const reply = (data.reply || '').toString().trim() || '(AI không trả lời)';
      addMsg('assistant', reply);
      history.push({ role: 'assistant', content: reply });
      saveHistory();

    } catch (e) {
      hideTyping();
      addMsg('assistant', 'Fetch failed – không gọi được server (server down / sai host-port / bị chặn).');
    } finally {
      setSending(false);
      input.focus();
    }
  }

  function openPanel() {
    panel.style.display = 'block';
    toggle.style.display = 'none';
    try { localStorage.setItem(OPEN_KEY, '1'); } catch (_) {}
    renderAll();
    setTimeout(() => input.focus(), 50);
  }

  function closePanel() {
    panel.style.display = 'none';
    toggle.style.display = 'grid';
    try { localStorage.setItem(OPEN_KEY, '0'); } catch (_) {}
  }

  toggle.addEventListener('click', openPanel);
  closeBtn.addEventListener('click', closePanel);

  clearBtn.addEventListener('click', () => {
    history = [];
    saveHistory();
    renderAll();
    input.focus();
  });

  sendBtn.addEventListener('click', send);
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') send();
    if (e.key === 'Escape') closePanel();
  });

  try {
    if (localStorage.getItem(OPEN_KEY) === '1') openPanel();
  } catch (_) {}
})();
</script>
