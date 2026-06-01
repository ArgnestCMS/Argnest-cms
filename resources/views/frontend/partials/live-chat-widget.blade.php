<div
    id="live-chat-widget"
    class="fixed bottom-24 right-5 z-50 w-[calc(100vw-2.5rem)] max-w-sm"
    data-start-url="{{ route('frontend.live-chat.start') }}"
    data-messages-url-template="{{ url('/canli-destek/__SESSION__/mesajlar') }}"
    data-send-url-template="{{ url('/canli-destek/__SESSION__/mesaj-gonder') }}"
    data-close-url-template="{{ url('/canli-destek/__SESSION__/sonlandir') }}"
    data-csrf="{{ csrf_token() }}"
>
    <div data-chat-panel class="hidden overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between bg-slate-950 px-5 py-4 text-white">
            <div>
                <p class="text-sm font-black">Canli Destek</p>
                <p class="text-xs text-slate-300">Mesajinizi yazin, ekibimiz yanitlasin.</p>
            </div>
            <button type="button" data-chat-close class="rounded-full border border-white/15 px-3 py-1 text-xs font-bold">Kapat</button>
        </div>

        <div data-chat-start class="p-5">
            <div class="grid gap-3">
                <p data-chat-start-info class="hidden rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm font-bold text-slate-600">Yeni sohbet icin mesajinizi yazin.</p>
                <input data-chat-name type="text" placeholder="Ad soyad" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500">
                <input data-chat-email type="email" placeholder="E-posta (opsiyonel)" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500">
                <input data-chat-phone type="text" placeholder="Telefon (opsiyonel)" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500">
                <textarea data-chat-message rows="4" placeholder="Mesajiniz" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500"></textarea>
                <button type="button" data-chat-start-button class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-black text-white transition hover:bg-blue-700">Mesaji Gonder</button>
            </div>
        </div>

        <div data-chat-conversation class="hidden">
            <div data-chat-messages class="max-h-80 space-y-3 overflow-y-auto bg-slate-50 p-4"></div>
            <div class="border-t border-slate-200 p-4">
                <textarea data-chat-reply rows="3" placeholder="Cevabinizi yazin" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500"></textarea>
                <div class="mt-3 flex items-center justify-between gap-3">
                    <p data-chat-status class="text-xs font-bold text-slate-500"></p>
                    <div class="flex items-center gap-2">
                        <button type="button" data-chat-end-button class="rounded-2xl border border-red-200 bg-white px-4 py-3 text-xs font-black text-red-600 transition hover:bg-red-50">Görüşmeyi Sonlandır</button>
                        <button type="button" data-chat-new-button class="hidden rounded-2xl border border-blue-200 bg-white px-4 py-3 text-xs font-black text-blue-700 transition hover:bg-blue-50">Yeni Sohbet Başlat</button>
                        <button type="button" data-chat-send-button class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-black text-white transition hover:bg-blue-700">Gonder</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" data-chat-toggle class="ml-auto flex items-center gap-2 rounded-full bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-2xl shadow-slate-900/25 transition hover:-translate-y-0.5 hover:bg-blue-700">
        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
        Canli Destek
    </button>
</div>

<script>
    (() => {
        const root = document.getElementById('live-chat-widget');
        if (!root) return;

        const panel = root.querySelector('[data-chat-panel]');
        const startView = root.querySelector('[data-chat-start]');
        const conversationView = root.querySelector('[data-chat-conversation]');
        const messagesBox = root.querySelector('[data-chat-messages]');
        const statusText = root.querySelector('[data-chat-status]');
        const replyInput = root.querySelector('[data-chat-reply]');
        const sendButton = root.querySelector('[data-chat-send-button]');
        const endButton = root.querySelector('[data-chat-end-button]');
        const newButton = root.querySelector('[data-chat-new-button]');
        const startInfo = root.querySelector('[data-chat-start-info]');
        const startUrl = root.dataset.startUrl;
        const csrf = root.dataset.csrf;
        let sessionId = window.localStorage.getItem('argnest_live_chat_session_id');
        let pollTimer = null;

        const request = async (url, options = {}) => {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
                ...options,
            });

            if (!response.ok) throw new Error('Canli destek istegi basarisiz oldu.');
            return response.json();
        };

        const urlFor = (template, id) => template.replace('__SESSION__', id);

        const renderMessages = (payload) => {
            const isClosed = payload.status === 'closed';
            statusText.textContent = isClosed ? 'Bu görüşme sonlandırıldı.' : 'Sohbet aktif.';
            replyInput.disabled = isClosed;
            sendButton.disabled = isClosed;
            endButton.disabled = isClosed;
            newButton.classList.toggle('hidden', !isClosed);
            replyInput.classList.toggle('opacity-60', isClosed);
            sendButton.classList.toggle('opacity-60', isClosed);
            endButton.classList.toggle('opacity-60', isClosed);
            messagesBox.innerHTML = payload.messages.map((message) => {
                const isAdmin = message.sender_type === 'admin';
                const isSystem = message.sender_type === 'system';
                return `
                    <div class="${isSystem ? 'text-center' : (isAdmin ? 'text-left' : 'text-right')}">
                        <div class="inline-block max-w-[85%] rounded-2xl ${isSystem ? 'border border-amber-200 bg-amber-50 text-amber-900' : (isAdmin ? 'border border-blue-100 bg-blue-50 text-slate-900' : 'border border-slate-200 bg-slate-100 text-slate-900')} px-4 py-3 text-sm shadow-sm">
                            <div class="mb-1 text-[11px] font-semibold text-slate-500"><span class="font-semibold text-slate-800">${escapeHtml(message.sender_name)}</span> · ${escapeHtml(message.created_at || '')}</div>
                            <div class="whitespace-pre-wrap break-words">${escapeHtml(message.message)}</div>
                        </div>
                    </div>
                `;
            }).join('');
            messagesBox.scrollTop = messagesBox.scrollHeight;
        };

        const escapeHtml = (value) => String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const showConversation = () => {
            startView.classList.add('hidden');
            conversationView.classList.remove('hidden');
        };

        const showStart = (message = null) => {
            conversationView.classList.add('hidden');
            startView.classList.remove('hidden');
            startInfo.classList.toggle('hidden', !message);
            startInfo.textContent = message || '';
        };

        const resetCurrentSession = () => {
            if (sessionId) {
                window.localStorage.removeItem('argnest_live_chat_session_id');
            }

            sessionId = null;
            stopPolling();
            replyInput.value = '';
            root.querySelector('[data-chat-message]').value = '';
        };

        const loadMessages = async () => {
            if (!sessionId) return;

            try {
                const payload = await request(urlFor(root.dataset.messagesUrlTemplate, sessionId), { method: 'GET' });
                showConversation();
                renderMessages(payload);
            } catch (error) {
                window.localStorage.removeItem('argnest_live_chat_session_id');
                sessionId = null;
                stopPolling();
                showStart();
            }
        };

        const startPolling = () => {
            stopPolling();
            pollTimer = window.setInterval(loadMessages, 7000);
        };

        const stopPolling = () => {
            if (pollTimer) window.clearInterval(pollTimer);
        };

        root.querySelector('[data-chat-toggle]').addEventListener('click', () => {
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden') && sessionId) {
                loadMessages();
                startPolling();
            }
        });

        root.querySelector('[data-chat-close]').addEventListener('click', () => {
            panel.classList.add('hidden');
            stopPolling();
        });

        root.querySelector('[data-chat-start-button]').addEventListener('click', async () => {
            const message = root.querySelector('[data-chat-message]').value.trim();
            if (!message) return;

            const payload = await request(startUrl, {
                method: 'POST',
                body: JSON.stringify({
                    visitor_name: root.querySelector('[data-chat-name]').value.trim(),
                    visitor_email: root.querySelector('[data-chat-email]').value.trim(),
                    visitor_phone: root.querySelector('[data-chat-phone]').value.trim(),
                    message,
                }),
            });

            sessionId = payload.session_id;
            window.localStorage.setItem('argnest_live_chat_session_id', sessionId);
            showConversation();
            renderMessages(payload);
            startPolling();
        });

        root.querySelector('[data-chat-send-button]').addEventListener('click', async () => {
            const message = replyInput.value.trim();
            if (!message || !sessionId) return;

            const payload = await request(urlFor(root.dataset.sendUrlTemplate, sessionId), {
                method: 'POST',
                body: JSON.stringify({ message }),
            });

            replyInput.value = '';
            renderMessages(payload);
        });

        endButton.addEventListener('click', async () => {
            if (!sessionId || endButton.disabled) return;

            const payload = await request(urlFor(root.dataset.closeUrlTemplate, sessionId), {
                method: 'POST',
            });

            renderMessages(payload);
        });

        newButton.addEventListener('click', () => {
            resetCurrentSession();
            showStart('Onceki gorusme sonlandirildi. Yeni sohbet icin mesajinizi yazin.');
        });
    })();
</script>
