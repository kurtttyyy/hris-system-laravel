<style>
    .nc-chatbot {
        position: fixed;
        right: 5.2rem;
        bottom: 2.4rem;
        z-index: 1085;
        width: 5.8rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .nc-chatbot-launcher {
        width: 6.2rem;
        height: 6.2rem;
        border: 0;
        border-radius: 50%;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: radial-gradient(circle at 26% 22%, #ffffff 0%, #f8fafc 42%, #e5e7eb 100%);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.28), inset 0 2px 5px rgba(255, 255, 255, 0.85), inset 0 -8px 14px rgba(148, 163, 184, 0.22), 0 0 0 4px rgba(255, 255, 255, 0.88);
        color: #0f172a;
        cursor: pointer;
        animation: nc-chatbot-float 2.8s ease-in-out infinite;
        overflow: hidden;
        transform-style: preserve-3d;
    }

    .nc-chatbot-launcher::before {
        content: "";
        position: absolute;
        inset: 0.4rem;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 24%, rgba(255, 255, 255, 0.9), rgba(241, 245, 249, 0.45) 70%, rgba(203, 213, 225, 0.28));
        box-shadow: inset 0 -6px 10px rgba(148, 163, 184, 0.22);
        z-index: 0;
    }

    .nc-chatbot-launcher::after {
        content: "";
        position: absolute;
        top: 0.6rem;
        left: 0.9rem;
        width: 2.15rem;
        height: 1.1rem;
        border-radius: 999px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0));
        transform: rotate(-18deg) translateZ(12px);
        z-index: 2;
    }

    .nc-robot {
        position: relative;
        width: 4.15rem;
        height: 2.95rem;
        border-radius: 1.45rem;
        background: linear-gradient(155deg, #1f2937 0%, #0f172a 58%, #020617 100%);
        border: 2px solid #0f172a;
        box-shadow: inset 0 0 0 2px rgba(74, 222, 128, 0.12), inset 0 8px 10px rgba(255, 255, 255, 0.08), 0 8px 14px rgba(2, 6, 23, 0.45);
        transform-origin: center;
        transition: transform 0.15s ease-out;
        will-change: transform;
        z-index: 1;
    }

    .nc-robot::before {
        content: "";
        position: absolute;
        left: 0.42rem;
        right: 0.42rem;
        top: 0.28rem;
        height: 0.42rem;
        border-radius: 999px;
        background: linear-gradient(180deg, rgba(148, 163, 184, 0.38), rgba(148, 163, 184, 0));
    }

    .nc-robot-eye {
        position: absolute;
        top: 0.74rem;
        width: 0.66rem;
        height: 0.66rem;
        border-radius: 50%;
        transform: translate(var(--eye-x, 0px), var(--eye-y, 0px));
        transition: transform 0.15s ease-out;
        will-change: transform;
        border: 1px solid rgba(134, 239, 172, 0.8);
        background: rgba(2, 6, 23, 0.5);
        overflow: hidden;
    }

    .nc-robot-eye.left { left: 0.7rem; }
    .nc-robot-eye.right { right: 0.7rem; }

    .nc-robot-eye-core {
        position: absolute;
        inset: 0.06rem;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 30%, #86efac, #4ade80 60%, #16a34a 100%);
        box-shadow: 0 0 10px rgba(74, 222, 128, 0.75);
        transform-origin: center;
        animation: nc-eye-blink 3.6s infinite;
    }

    .nc-robot-mouth {
        position: absolute;
        left: 50%;
        bottom: 0.6rem;
        transform: translateX(-50%);
        width: 1.25rem;
        height: 0.28rem;
        border-radius: 999px;
        background: linear-gradient(90deg, #22c55e, #4ade80);
        box-shadow: 0 0 8px rgba(74, 222, 128, 0.65), inset 0 -1px 2px rgba(15, 23, 42, 0.45);
        transition: all 0.22s ease;
    }

    .nc-chatbot-launcher:hover .nc-robot-mouth {
        width: 1.32rem;
        height: 0.46rem;
        bottom: 0.33rem;
        background: transparent;
        border-bottom: 0.18rem solid #4ade80;
        border-radius: 0 0 999px 999px;
    }

    .nc-robot.is-sad .nc-robot-mouth {
        width: 1.12rem;
        height: 0.32rem;
        bottom: 0.5rem;
        background: transparent;
        border-top: 0.14rem solid #4ade80;
        border-radius: 999px 999px 0 0;
        box-shadow: 0 0 6px rgba(74, 222, 128, 0.55);
    }

    .nc-chatbot-launcher-label {
        position: absolute;
        left: 50%;
        top: 6.9rem;
        transform: translateX(-50%);
        white-space: nowrap;
        color: #047857;
        font-size: 0.65rem;
        font-family: "Trebuchet MS", "Gill Sans", "Segoe UI", sans-serif;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .nc-chatbot-launcher,
    .nc-chatbot-launcher-label {
        transition: opacity 0.2s ease, transform 0.28s ease;
    }

    .nc-chatbot-help-hint {
        position: absolute;
        left: 50%;
        top: -3.05rem;
        transform: translate(-50%, 6px);
        max-width: 14rem;
        padding: 0.45rem 0.65rem;
        border-radius: 0.75rem;
        background: rgba(15, 23, 42, 0.94);
        color: #ecfdf5;
        border: 1px solid rgba(52, 211, 153, 0.45);
        box-shadow: 0 12px 25px rgba(2, 6, 23, 0.35);
        font-size: 0.72rem;
        line-height: 1.3;
        font-weight: 600;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
        pointer-events: none;
        text-align: center;
        white-space: nowrap;
        z-index: 3;
    }

    .nc-chatbot-help-hint.is-visible {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, 0);
    }

    .nc-chatbot-panel {
        position: absolute;
        right: 0;
        bottom: 5.2rem;
        width: min(24rem, calc(100vw - 1.5rem));
        max-height: min(34rem, calc(100vh - 8.5rem));
        display: grid;
        grid-template-rows: auto 1fr auto auto;
        border-radius: 1.15rem;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.35);
        box-shadow: 0 28px 50px rgba(15, 23, 42, 0.26);
        transform-origin: 88% 100%;
        will-change: transform, opacity;
    }

    .nc-chatbot-panel[hidden] { display: none; }
    .nc-chatbot-panel.pop-in { animation: nc-bubble-pop 0.34s cubic-bezier(0.2, 0.85, 0.25, 1.15); }

    .nc-chatbot.is-open .nc-chatbot-launcher,
    .nc-chatbot.is-open .nc-chatbot-launcher-label,
    .nc-chatbot.is-open .nc-chatbot-help-hint {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .nc-chatbot.rolling-back .nc-chatbot-launcher {
        animation: nc-assistant-roll-3x 1.05s cubic-bezier(0.25, 0.8, 0.3, 1.02);
    }

    .nc-chatbot.rolling-back .nc-chatbot-launcher-label {
        animation: nc-assistant-label-return 1.05s ease;
    }

    .nc-chatbot.dizzy .nc-robot {
        animation: nc-assistant-dizzy 0.9s ease-in-out;
    }

    .nc-chatbot.dizzy .nc-robot-eye-core {
        animation: nc-eye-dizzy 0.9s linear;
    }

    .nc-chatbot-header {
        padding: 0.95rem 1rem;
        display: flex;
        justify-content: space-between;
        background: linear-gradient(130deg, #0f5132, #157347);
        color: #fff;
    }

    .nc-chatbot-title { margin: 0; font-size: 0.95rem; font-weight: 700; }
    .nc-chatbot-subtitle { margin: 0.1rem 0 0; font-size: 0.75rem; color: rgba(226, 232, 240, 0.95); }
    .nc-chatbot-close { border: 0; border-radius: 0.55rem; width: 2rem; height: 2rem; color: #fff; background: rgba(255, 255, 255, 0.16); }
    .nc-chatbot-messages { padding: 0.95rem; overflow-y: auto; background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%); }
    .nc-bubble { max-width: 88%; border-radius: 0.9rem; padding: 0.65rem 0.75rem; margin-bottom: 0.65rem; line-height: 1.45; font-size: 0.89rem; word-break: break-word; }
    .nc-bubble.user { margin-left: auto; background: #157347; color: #fff; }
    .nc-bubble.bot { background: #fff; color: #0f172a; border: 1px solid #dbe2ea; }
    .nc-chatbot-chips { display: flex; flex-wrap: wrap; gap: 0.45rem; padding: 0.65rem 0.85rem 0.4rem; background: #f1f5f9; border-top: 1px solid #e2e8f0; }
    .nc-chatbot-chip { border: 1px solid #cbd5e1; background: #fff; border-radius: 999px; font-size: 0.73rem; font-weight: 600; padding: 0.42rem 0.6rem; color: #334155; }
    .nc-chatbot-form { padding: 0.75rem; display: flex; align-items: flex-end; gap: 0.6rem; background: #fff; border-top: 1px solid #e2e8f0; }
    .nc-chatbot-input { flex: 1; resize: none; max-height: 8rem; border: 1px solid #cbd5e1; border-radius: 0.85rem; padding: 0.6rem 0.7rem; font-size: 0.9rem; }
    .nc-chatbot-send { border: 0; border-radius: 0.85rem; min-width: 3.2rem; height: 2.9rem; background: linear-gradient(135deg, #157347, #1ea55d); color: #fff; font-weight: 700; font-size: 0.84rem; }

    @keyframes nc-dot {
        0%, 80%, 100% { transform: translateY(0); opacity: 0.45; }
        40% { transform: translateY(-4px); opacity: 1; }
    }

    @keyframes nc-chatbot-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    @keyframes nc-bubble-pop {
        0% { opacity: 0; transform: translateY(12px) scale(0.72); }
        68% { opacity: 1; transform: translateY(-2px) scale(1.04); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes nc-assistant-roll-3x {
        0% { transform: translateY(14px) scale(0.8) rotate(0deg); opacity: 0.1; }
        70% { transform: translateY(-2px) scale(1.02) rotate(990deg); opacity: 1; }
        100% { transform: translateY(0) scale(1) rotate(1080deg); opacity: 1; }
    }

    @keyframes nc-assistant-label-return {
        0% { transform: translateX(-50%) translateY(12px); opacity: 0; }
        70% { transform: translateX(-50%) translateY(-2px); opacity: 1; }
        100% { transform: translateX(-50%) translateY(0); opacity: 1; }
    }

    @keyframes nc-assistant-dizzy {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        20% { transform: translate(-2px, 0) rotate(-7deg); }
        40% { transform: translate(2px, 0) rotate(7deg); }
        60% { transform: translate(-1px, 0) rotate(-5deg); }
        80% { transform: translate(1px, 0) rotate(5deg); }
    }

    @keyframes nc-eye-dizzy {
        0% { opacity: 1; transform: scale(1) rotate(0deg); }
        50% { opacity: 0.65; transform: scale(0.9) rotate(180deg); }
        100% { opacity: 1; transform: scale(1) rotate(360deg); }
    }

    @keyframes nc-eye-blink {
        0%, 44%, 100% { transform: scaleY(1); opacity: 1; }
        46%, 50% { transform: scaleY(0.1); opacity: 0.9; }
        52%, 56% { transform: scaleY(1); opacity: 1; }
    }

    @media (max-width: 767.98px) {
        .nc-chatbot { right: 2.7rem; bottom: 1.8rem; width: 5.1rem; }
        .nc-chatbot-launcher { width: 5.2rem; height: 5.2rem; }
        .nc-chatbot-launcher-label { top: 5.6rem; font-size: 0.6rem; }
        .nc-chatbot-help-hint { top: -2.7rem; font-size: 0.67rem; }
        .nc-chatbot-panel { right: -0.2rem; width: min(23rem, calc(100vw - 1rem)); bottom: 4.8rem; }
    }
</style>

<div class="nc-chatbot" id="ncChatbot" data-endpoint="{{ route('guest.chat.reply') }}">
    <span class="nc-chatbot-help-hint" id="ncChatHelpHint">Click me if you need help</span>
    <button class="nc-chatbot-launcher" id="ncChatLauncher" type="button" aria-expanded="false" aria-controls="ncChatPanel" aria-label="Open chat assistant">
        <span class="nc-robot" aria-hidden="true">
            <span class="nc-robot-eye left"><span class="nc-robot-eye-core"></span></span>
            <span class="nc-robot-eye right"><span class="nc-robot-eye-core"></span></span>
            <span class="nc-robot-mouth"></span>
        </span>
    </button>
    <span class="nc-chatbot-launcher-label">Click here to chat</span>

    <section class="nc-chatbot-panel" id="ncChatPanel" hidden>
        <header class="nc-chatbot-header">
            <div>
                <p class="nc-chatbot-title">NC Career Assistant</p>
                <p class="nc-chatbot-subtitle">Ask about jobs, requirements, and policies</p>
            </div>
            <button class="nc-chatbot-close" id="ncChatClose" type="button" aria-label="Close chat">X</button>
        </header>
        <div class="nc-chatbot-messages" id="ncChatMessages"></div>
        <div class="nc-chatbot-chips" id="ncChatChips">
            <button class="nc-chatbot-chip" type="button" data-msg="Explain this website">Website guide</button>
            <button class="nc-chatbot-chip" type="button" data-msg="Show available jobs">Show available jobs</button>
            <button class="nc-chatbot-chip" type="button" data-msg="How to apply">How to apply</button>
        </div>
        <form class="nc-chatbot-form" id="ncChatForm">
            <textarea class="nc-chatbot-input" id="ncChatInput" rows="1" maxlength="500" placeholder="Type your message..."></textarea>
            <button class="nc-chatbot-send" id="ncChatSend" type="submit">Send</button>
        </form>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatbotRoot = document.getElementById('ncChatbot');
        if (!chatbotRoot) return;

        const endpoint = chatbotRoot.dataset.endpoint;
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const launcher = document.getElementById('ncChatLauncher');
        const panel = document.getElementById('ncChatPanel');
        const closeBtn = document.getElementById('ncChatClose');
        const messagesEl = document.getElementById('ncChatMessages');
        const chipsEl = document.getElementById('ncChatChips');
        const form = document.getElementById('ncChatForm');
        const input = document.getElementById('ncChatInput');
        const sendBtn = document.getElementById('ncChatSend');
        const helpHint = document.getElementById('ncChatHelpHint');
        const robotHead = chatbotRoot.querySelector('.nc-robot');
        const robotEyes = Array.from(chatbotRoot.querySelectorAll('.nc-robot-eye'));
        let hintHideTimeout = null;
        let sadTimeout = null;
        let rollReturnTimeout = null;
        let dizzyReturnTimeout = null;

        function addBubble(role, text) {
            const el = document.createElement('div');
            el.className = 'nc-bubble ' + role;
            el.textContent = text;
            messagesEl.appendChild(el);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function showHint(text, hideMs) {
            if (!helpHint || !panel.hidden) return;
            helpHint.textContent = text;
            helpHint.classList.add('is-visible');
            if (hintHideTimeout) clearTimeout(hintHideTimeout);
            if (hideMs) {
                hintHideTimeout = setTimeout(() => helpHint.classList.remove('is-visible'), hideMs);
            }
        }

        function openPanel() {
            panel.hidden = false;
            panel.classList.remove('pop-in');
            void panel.offsetWidth;
            panel.classList.add('pop-in');
            if (rollReturnTimeout) clearTimeout(rollReturnTimeout);
            if (dizzyReturnTimeout) clearTimeout(dizzyReturnTimeout);
            chatbotRoot.classList.remove('rolling-back', 'dizzy');
            chatbotRoot.classList.add('is-open');
            if (messagesEl.children.length === 0) {
                addBubble('bot', 'Hi. I can explain everything on this website and help you apply.');
            }
            input.focus();
        }

        function closePanel() {
            panel.hidden = true;
            panel.classList.remove('pop-in');
            chatbotRoot.classList.remove('is-open');
            if (rollReturnTimeout) clearTimeout(rollReturnTimeout);
            if (dizzyReturnTimeout) clearTimeout(dizzyReturnTimeout);
            chatbotRoot.classList.remove('rolling-back', 'dizzy');
            void chatbotRoot.offsetWidth;
            chatbotRoot.classList.add('rolling-back');
            rollReturnTimeout = setTimeout(() => {
                chatbotRoot.classList.remove('rolling-back');
                chatbotRoot.classList.add('dizzy');
                dizzyReturnTimeout = setTimeout(() => {
                    chatbotRoot.classList.remove('dizzy');
                    showHint("I'm fine", 1400);
                }, 900);
            }, 1050);
        }

        async function sendMessage(text) {
            const message = (text || '').trim();
            if (!message) return;
            addBubble('user', message);
            input.value = '';
            sendBtn.disabled = true;
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ message })
                });
                const data = await res.json();
                addBubble('bot', data.reply || 'I can help with this page.');
            } catch (_) {
                addBubble('bot', 'I could not connect right now. Please try again.');
            } finally {
                sendBtn.disabled = false;
            }
        }

        function resetFace() {
            if (!robotHead) return;
            robotHead.style.transform = 'translate(0px,0px) rotate(0deg)';
            robotHead.classList.remove('is-sad');
            robotEyes.forEach(eye => {
                eye.style.setProperty('--eye-x', '0px');
                eye.style.setProperty('--eye-y', '0px');
            });
        }

        launcher.addEventListener('mouseenter', function () {
            if (sadTimeout) clearTimeout(sadTimeout);
            robotHead.classList.remove('is-sad');
            showHint('Need help?', 1200);
        });

        launcher.addEventListener('mouseleave', function () {
            robotHead.classList.add('is-sad');
            showHint('Ohh.. okay', 1200);
            sadTimeout = setTimeout(() => robotHead.classList.remove('is-sad'), 1000);
            robotHead.style.transform = 'translate(0px,0px) rotate(0deg)';
            robotEyes.forEach(eye => {
                eye.style.setProperty('--eye-x', '0px');
                eye.style.setProperty('--eye-y', '0px');
            });
        });

        document.addEventListener('mousemove', function (event) {
            if (!robotHead || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            const rect = launcher.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = event.clientX - cx;
            const dy = event.clientY - cy;
            const len = Math.sqrt(dx * dx + dy * dy) || 1;
            const sx = Math.max(-4, Math.min(4, (dx / len) * 4));
            const sy = Math.max(-4, Math.min(4, (dy / len) * 4));
            const eyeX = Math.max(-4.2, Math.min(4.2, (dx / len) * 4.2));
            const eyeY = Math.max(-4.2, Math.min(4.2, (dy / len) * 4.2));
            robotHead.style.transform = `translate(${sx}px, ${sy}px) rotate(${Math.max(-8, Math.min(8, dx / 18))}deg)`;
            robotEyes.forEach(eye => {
                eye.style.setProperty('--eye-x', `${eyeX}px`);
                eye.style.setProperty('--eye-y', `${eyeY}px`);
            });
        });

        launcher.addEventListener('click', () => panel.hidden ? openPanel() : closePanel());
        closeBtn.addEventListener('click', closePanel);
        form.addEventListener('submit', (e) => { e.preventDefault(); sendMessage(input.value); });
        chipsEl.addEventListener('click', (e) => {
            const btn = e.target.closest('.nc-chatbot-chip');
            if (btn) sendMessage(btn.dataset.msg || btn.textContent || '');
        });

        showHint('Click me if you need help', 5000);
        setInterval(() => showHint('Click me if you need help', 5000), 40000);
    });
</script>
