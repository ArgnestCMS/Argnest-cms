<div
    id="admin-live-chat-popup"
    data-sessions-url="{{ route('admin.live-chat-popup.sessions') }}"
    data-show-url-template="{{ url('/admin/live-chat-popup/sessions/__SESSION__') }}"
    data-reply-url-template="{{ url('/admin/live-chat-popup/sessions/__SESSION__/reply') }}"
    data-close-url-template="{{ url('/admin/live-chat-popup/sessions/__SESSION__/close') }}"
    data-csrf="{{ csrf_token() }}"
    data-can-reply="{{ auth()->user()?->hasPermission('live_chat_reply') ? '1' : '0' }}"
    data-can-close="{{ auth()->user()?->hasPermission('live_chat_close') ? '1' : '0' }}"
>
    <style>
        #admin-live-chat-popup .hidden { display: none !important; }
        #admin-live-chat-popup, #admin-live-chat-popup * { box-sizing: border-box; }
        #admin-live-chat-popup .alc-button {
            align-items: center; background: #0f172a; border: 0; border-radius: 999px;
            bottom: 20px; box-shadow: 0 18px 45px rgba(15, 23, 42, .28); color: #fff;
            cursor: pointer; display: inline-flex; font-size: 16px; font-weight: 900; gap: 8px;
            line-height: 1; padding: 12px 14px; position: fixed !important; right: 20px; z-index: 9998;
        }
        #admin-live-chat-popup .alc-count {
            background: #f59e0b; border-radius: 999px; color: #111827; font-size: 12px;
            min-width: 22px; padding: 5px 7px; text-align: center;
        }
        #admin-live-chat-popup .alc-attention {
            background: #ef4444; border: 2px solid #fff; border-radius: 999px;
            height: 12px; position: absolute; right: 2px; top: 0; width: 12px;
        }
        #admin-live-chat-popup .alc-panel {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            bottom: 80px; box-shadow: 0 24px 80px rgba(15, 23, 42, .35); color: #0f172a;
            display: flex; flex-direction: column; height: 560px; max-height: calc(100vh - 110px);
            max-width: calc(100vw - 40px); overflow: hidden; position: fixed !important; right: 20px;
            width: 500px; z-index: 9999;
        }
        #admin-live-chat-popup .alc-header {
            align-items: center; background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; justify-content: space-between; padding: 14px 16px;
        }
        #admin-live-chat-popup .alc-title { color: #0f172a; font-size: 15px; font-weight: 900; margin: 0; }
        #admin-live-chat-popup .alc-muted { color: #64748b; }
        #admin-live-chat-popup .alc-subtitle { color: #64748b; font-size: 12px; font-weight: 700; margin: 3px 0 0; }
        #admin-live-chat-popup .alc-close {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; color: #334155;
            cursor: pointer; font-size: 12px; font-weight: 800; padding: 8px 10px;
        }
        #admin-live-chat-popup .alc-grid {
            display: grid; flex: 1; grid-template-columns: 160px minmax(0, 1fr); min-height: 0;
        }
        #admin-live-chat-popup .alc-list-wrap {
            background: #f8fafc; border-right: 1px solid #e2e8f0; min-height: 0; overflow: hidden;
        }
        #admin-live-chat-popup .alc-summary {
            border-bottom: 1px solid #e2e8f0; color: #0f172a; font-size: 12px; font-weight: 900; padding: 10px;
        }
        #admin-live-chat-popup .alc-list { display: grid; gap: 8px; max-height: calc(100% - 37px); overflow: auto; padding: 8px; }
        #admin-live-chat-popup .alc-session {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; color: #0f172a;
            cursor: pointer; display: block; padding: 9px; text-align: left; width: 100%;
        }
        #admin-live-chat-popup .alc-session.is-active { border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245, 158, 11, .18); }
        #admin-live-chat-popup .alc-session-title { color: #0f172a; font-size: 12px; font-weight: 900; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #admin-live-chat-popup .alc-session-message { color: #64748b; display: -webkit-box; font-size: 11px; font-weight: 700; line-height: 1.35; margin-top: 4px; overflow: hidden; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
        #admin-live-chat-popup .alc-new {
            background: #ef4444; border-radius: 999px; color: #fff; display: inline-flex;
            font-size: 10px; font-weight: 900; margin-top: 6px; padding: 2px 6px;
        }
        #admin-live-chat-popup .alc-detail { display: flex; flex-direction: column; min-height: 0; }
        #admin-live-chat-popup .alc-empty {
            align-items: center; color: #64748b; display: flex; flex: 1; font-size: 14px;
            font-weight: 800; justify-content: center; padding: 24px; text-align: center;
        }
        #admin-live-chat-popup .alc-visitor-bar { border-bottom: 1px solid #e2e8f0; padding: 12px; }
        #admin-live-chat-popup .alc-visitor { color: #0f172a; font-size: 14px; font-weight: 900; margin: 0; }
        #admin-live-chat-popup .alc-meta { color: #64748b; display: grid; font-size: 11px; font-weight: 700; gap: 2px; margin-top: 6px; }
        #admin-live-chat-popup .alc-messages {
            background: #f8fafc; display: grid; flex: 1; gap: 10px; min-height: 0; overflow: auto; padding: 10px;
        }
        #admin-live-chat-popup .alc-message { display: flex; }
        #admin-live-chat-popup .alc-message.is-admin { justify-content: flex-end; }
        #admin-live-chat-popup .alc-message.is-system { justify-content: center; }
        #admin-live-chat-popup .alc-bubble {
            background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 14px; color: #0f172a;
            max-width: 84%; padding: 9px 11px; white-space: pre-wrap; word-break: break-word;
        }
        #admin-live-chat-popup .alc-message.is-admin .alc-bubble { background: #eff6ff; border-color: #dbeafe; color: #0f172a; }
        #admin-live-chat-popup .alc-message.is-system .alc-bubble { background: #fffbeb; border-color: #fde68a; color: #78350f; text-align: center; }
        #admin-live-chat-popup .alc-bubble-meta { color: #64748b; font-size: 10px; font-weight: 700; margin-bottom: 4px; }
        #admin-live-chat-popup .alc-bubble-sender { color: #1e293b; font-weight: 800; }
        #admin-live-chat-popup .alc-reply { border-top: 1px solid #e2e8f0; padding: 10px; }
        #admin-live-chat-popup textarea {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; color: #0f172a;
            font-size: 13px; min-height: 72px; outline: none; padding: 10px 12px; resize: vertical; width: 100%;
        }
        #admin-live-chat-popup textarea::placeholder { color: #94a3b8; }
        #admin-live-chat-popup .alc-send {
            background: #d97706; border: 0; border-radius: 12px; color: #fff; cursor: pointer;
            font-size: 13px; font-weight: 900; padding: 10px 14px;
        }
        #admin-live-chat-popup .alc-danger {
            background: #dc2626; border: 0; border-radius: 10px; color: #fff; cursor: pointer;
            font-size: 11px; font-weight: 900; padding: 8px 10px;
        }
        @media (max-width: 640px) {
            #admin-live-chat-popup .alc-button { bottom: 16px; right: 16px; }
            #admin-live-chat-popup .alc-panel {
                bottom: 72px; height: min(560px, calc(100vh - 92px)); left: 10px;
                max-width: none; right: 10px; width: auto;
            }
            #admin-live-chat-popup .alc-grid { grid-template-columns: 1fr; grid-template-rows: 150px minmax(0, 1fr); }
            #admin-live-chat-popup .alc-list-wrap { border-bottom: 1px solid #e2e8f0; border-right: 0; }
        }
    </style>

    <button type="button" data-live-chat-toggle class="alc-button" aria-label="Canli destek">
        <span aria-hidden="true">&#128172;</span>
        <span data-live-chat-count class="alc-count">0</span>
        <span data-live-chat-attention-dot class="alc-attention hidden"></span>
        <span data-live-chat-badge class="hidden"></span>
    </button>

    <div data-live-chat-modal class="alc-panel hidden">
        <div class="alc-header">
            <div>
                <p class="alc-title">Canli Destek</p>
                <p class="alc-subtitle">Aktif sohbetleri hizli yanitlayin.</p>
            </div>
            <button type="button" data-live-chat-close-modal class="alc-close">Kapat</button>
        </div>

        <div class="alc-grid">
            <aside class="alc-list-wrap">
                <div data-live-chat-summary class="alc-summary">Sohbetler yukleniyor...</div>
                <div data-live-chat-list class="alc-list"></div>
            </aside>

            <section class="alc-detail">
                <div data-live-chat-empty class="alc-empty">Aktif sohbet bulunmuyor.</div>

                <div data-live-chat-detail class="alc-detail hidden">
                    <div class="alc-visitor-bar">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <p data-live-chat-visitor class="alc-visitor"></p>
                                <div data-live-chat-meta class="alc-meta"></div>
                            </div>
                            <button type="button" data-live-chat-close-session class="alc-danger hidden">Görüşmeyi Sonlandır</button>
                        </div>
                    </div>

                    <div data-live-chat-messages class="alc-messages"></div>

                    <div class="alc-reply">
                        <textarea data-live-chat-reply rows="3" placeholder="Cevabinizi yazin"></textarea>
                        <div class="mt-3 flex items-center justify-between gap-3">
                            <p data-live-chat-form-status class="alc-muted text-xs font-bold"></p>
                            <button type="button" data-live-chat-send class="alc-send">Gonder</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    (() => {
        const root = document.getElementById('admin-live-chat-popup');
        if (!root) return;

        const sessionsUrl = root.dataset.sessionsUrl;
        const csrf = root.dataset.csrf;
        const canReply = root.dataset.canReply === '1';
        const canClose = root.dataset.canClose === '1';
        const list = root.querySelector('[data-live-chat-list]');
        const modal = root.querySelector('[data-live-chat-modal]');
        const count = root.querySelector('[data-live-chat-count]');
        const badge = root.querySelector('[data-live-chat-badge]');
        const dot = root.querySelector('[data-live-chat-attention-dot]');
        const summary = root.querySelector('[data-live-chat-summary]');
        const empty = root.querySelector('[data-live-chat-empty]');
        const detail = root.querySelector('[data-live-chat-detail]');
        const messagesBox = root.querySelector('[data-live-chat-messages]');
        const visitor = root.querySelector('[data-live-chat-visitor]');
        const meta = root.querySelector('[data-live-chat-meta]');
        const replyInput = root.querySelector('[data-live-chat-reply]');
        const sendButton = root.querySelector('[data-live-chat-send]');
        const closeSessionButton = root.querySelector('[data-live-chat-close-session]');
        const formStatus = root.querySelector('[data-live-chat-form-status]');
        let sessions = [];
        let selectedSessionId = null;
        let pollTimer = null;

        const escapeHtml = (value) => String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const urlFor = (template, id) => template.replace('__SESSION__', id);

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

        const renderShell = (payload) => {
            sessions = payload.sessions || [];
            count.textContent = payload.active_count || 0;
            summary.textContent = `${payload.active_count || 0} aktif sohbet`;

            if ((payload.attention_count || 0) > 0) {
                badge.textContent = payload.attention_count;
                badge.classList.remove('hidden');
                dot.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
                dot.classList.add('hidden');
            }

            list.innerHTML = sessions.length
                ? sessions.map((session) => `
                    <button type="button" data-session-id="${session.id}" class="alc-session ${session.id === selectedSessionId ? 'is-active' : ''}">
                        <div class="alc-session-title">${escapeHtml(session.visitor_name)}</div>
                        <div class="alc-session-message">${escapeHtml(session.last_message || 'Mesaj yok')}</div>
                        ${session.needs_attention ? '<span class="alc-new">Yeni</span>' : ''}
                    </button>
                `).join('')
                : '<div class="alc-empty">Aktif sohbet bulunmuyor.</div>';

            list.querySelectorAll('[data-session-id]').forEach((button) => {
                button.addEventListener('click', () => selectSession(Number(button.dataset.sessionId)));
            });

            if (selectedSessionId && !sessions.some((session) => session.id === selectedSessionId)) {
                selectedSessionId = null;
                showEmpty();
            }
        };

        const showEmpty = () => {
            empty.classList.remove('hidden');
            detail.classList.add('hidden');
        };

        const showDetail = () => {
            empty.classList.add('hidden');
            detail.classList.remove('hidden');
        };

        const renderDetail = (payload) => {
            const session = payload.session;
            selectedSessionId = session.id;
            showDetail();
            visitor.textContent = session.visitor_name;
            meta.innerHTML = [
                session.visitor_email ? `E-posta: ${escapeHtml(session.visitor_email)}` : null,
                session.visitor_phone ? `Telefon: ${escapeHtml(session.visitor_phone)}` : null,
                session.ip_address ? `IP: ${escapeHtml(session.ip_address)}` : null,
                session.assigned_user ? `Atanan: ${escapeHtml(session.assigned_user)}` : null,
                `Durum: ${escapeHtml(session.status_label)}`,
            ].filter(Boolean).map((item) => `<p>${item}</p>`).join('');

            closeSessionButton.classList.toggle('hidden', !canClose || session.status === 'closed');
            replyInput.disabled = !canReply || session.status === 'closed';
            sendButton.disabled = !canReply || session.status === 'closed';
            sendButton.style.opacity = sendButton.disabled ? '.55' : '1';
            formStatus.textContent = session.status === 'closed'
                ? 'Bu görüşme sonlandırıldı.'
                : (canReply ? '' : 'Cevap yazma yetkiniz yok.');

            messagesBox.innerHTML = (payload.messages || []).map((message) => {
                const isAdmin = message.sender_type === 'admin';
                const isSystem = message.sender_type === 'system';
                return `
                    <div class="alc-message ${isAdmin ? 'is-admin' : ''} ${isSystem ? 'is-system' : ''}">
                        <div class="alc-bubble">
                            <div class="alc-bubble-meta"><span class="alc-bubble-sender">${escapeHtml(message.sender_name)}</span> · ${escapeHtml(message.created_at || '')}</div>
                            <div>${escapeHtml(message.message)}</div>
                        </div>
                    </div>
                `;
            }).join('');
            messagesBox.scrollTop = messagesBox.scrollHeight;
        };

        const loadSessions = async () => {
            try {
                const payload = await request(sessionsUrl, { method: 'GET' });
                renderShell(payload);

                if (modal.classList.contains('hidden')) return;

                if (selectedSessionId) {
                    await loadSession(selectedSessionId);
                } else if (sessions[0]) {
                    await selectSession(sessions[0].id);
                } else {
                    showEmpty();
                }
            } catch (error) {
                count.textContent = '0';
                badge.classList.add('hidden');
                dot.classList.add('hidden');
            }
        };

        const loadSession = async (id) => {
            const payload = await request(urlFor(root.dataset.showUrlTemplate, id), { method: 'GET' });
            renderDetail(payload);
        };

        const selectSession = async (id) => {
            selectedSessionId = id;
            await loadSession(id);
            renderShell({ active_count: sessions.length, attention_count: sessions.filter((session) => session.needs_attention).length, sessions });
        };

        const startPolling = () => {
            window.clearInterval(pollTimer);
            pollTimer = window.setInterval(loadSessions, 6000);
        };

        root.querySelector('[data-live-chat-toggle]').addEventListener('click', async () => {
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) await loadSessions();
        });

        root.querySelector('[data-live-chat-close-modal]').addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        sendButton.addEventListener('click', async () => {
            if (!selectedSessionId || !canReply) return;
            const message = replyInput.value.trim();
            if (!message) return;

            const payload = await request(urlFor(root.dataset.replyUrlTemplate, selectedSessionId), {
                method: 'POST',
                body: JSON.stringify({ message }),
            });
            replyInput.value = '';
            renderDetail(payload);
            await loadSessions();
        });

        closeSessionButton.addEventListener('click', async () => {
            if (!selectedSessionId || !canClose) return;

            await request(urlFor(root.dataset.closeUrlTemplate, selectedSessionId), { method: 'POST' });
            selectedSessionId = null;
            showEmpty();
            await loadSessions();
        });

        loadSessions();
        startPolling();
    })();
</script>
