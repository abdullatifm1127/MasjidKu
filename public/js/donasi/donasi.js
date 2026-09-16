(function () {
    'use strict';

    const app = document.getElementById('app');
    const submitUrl = app ? app.dataset.submitUrl : null;
    const nisab = app ? parseInt(app.dataset.nisab, 10) || 85000000 : 85000000;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const MIN_AMOUNT = 5000; // catatan: server (DonasiController) hanya mewajibkan minimal 1.000
    const MAX_AMOUNT = 500000000; // batas wajar sisi klien saja, server tidak membatasi ini

    let state = {
        categoryKey: null,   // dikirim sebagai field "jenis" -> harus persis salah satu key kategori aktif
        categoryTitle: '',
        calcType: 'nominal', // 'nominal' | 'zakat'
        zakatSub: 'fitrah',  // 'fitrah' | 'mal' -> dikirim sebagai "zakat_subtype" hanya jika calcType === 'zakat'
        amount: 0,
    };

    // ---------- helpers ----------

    function formatRp(num) {
        return 'Rp ' + Math.max(0, Math.round(num || 0)).toLocaleString('id-ID');
    }

    function parseDigits(str) {
        return parseInt(String(str || '').replace(/[^\d]/g, ''), 10) || 0;
    }

    /** Live-format a text input as a thousands-separated number, keeping the raw value on dataset.raw */
    function bindThousandsInput(input, onChange) {
        if (!input) return;
        input.addEventListener('input', () => {
            const raw = parseDigits(input.value);
            input.value = raw ? raw.toLocaleString('id-ID') : '';
            input.dataset.raw = String(raw);
            onChange(raw);
        });
    }

    function announce(msg) {
        const el = document.getElementById('step-announcer');
        if (el) el.textContent = msg;
    }

    function setError(el, message) {
        if (!el) return;
        if (message) {
            el.textContent = message;
            el.classList.remove('hidden');
        } else {
            el.textContent = '';
            el.classList.add('hidden');
        }
    }

    // ---------- step navigation ----------
    // Catatan: hanya ada 4 panel (panel-1..panel-4). Tidak ada lagi layar "menunggu
    // pembayaran" dengan polling status, karena backend saat ini (DonasiController@store)
    // tidak terhubung ke payment gateway apa pun -- lihat komentar di donasi.blade.php.

    function changeStep(stepNum) {
        for (let i = 1; i <= 4; i++) {
            document.getElementById(`panel-${i}`)?.classList.add('hidden');
            const st = document.getElementById(`st-${i}`);
            if (st) {
                st.classList.remove('active', 'done');
                st.removeAttribute('aria-current');
                if (i < stepNum) st.classList.add('done');
            }
        }

        document.getElementById(`panel-${stepNum}`)?.classList.remove('hidden');
        const activeSt = document.getElementById(`st-${Math.min(stepNum, 3)}`);
        if (activeSt) {
            activeSt.classList.add('active');
            activeSt.setAttribute('aria-current', 'step');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });

        const titles = { 1: 'Pilih kategori', 2: 'Masukkan nominal', 3: 'Pilih pembayaran', 4: 'Donasi tercatat' };
        announce(`Langkah ${stepNum}: ${titles[stepNum] || ''}`);

        const firstFocusable = document.getElementById(`panel-${stepNum}`)?.querySelector('button, input, a');
        firstFocusable?.focus({ preventScroll: true });

        if (stepNum === 3) {
            document.getElementById('sum-cat').textContent = state.categoryTitle;
            const nameInput = document.getElementById('donor-name');
            const name = nameInput ? nameInput.value.trim() : '';
            document.getElementById('sum-name').textContent = name !== '' ? name : 'Hamba Allah';
            document.getElementById('sum-total').textContent = formatRp(state.amount);
        }
    }

    // ---------- category selection ----------

    function selectCategory(key, title, type) {
        state.categoryKey = key;
        state.categoryTitle = title;
        state.calcType = type || 'nominal';

        const catTitleEl = document.getElementById('panel-2-title') || document.querySelector('[data-role="selected-cat-title"]');
        if (catTitleEl) catTitleEl.textContent = title;

        const sectionZakat = document.getElementById('section-zakat');
        const sectionNominal = document.getElementById('section-nominal');

        if (state.calcType === 'zakat') {
            sectionZakat?.classList.remove('hidden');
            sectionNominal?.classList.add('hidden');

            state.zakatSub = 'fitrah';
            document.querySelectorAll('.z-tab').forEach((b, idx) => {
                const active = idx === 0;
                b.classList.toggle('active', active);
                b.setAttribute('aria-selected', String(active));
            });
            document.getElementById('form-fitrah')?.classList.remove('hidden');
            document.getElementById('form-mal')?.classList.add('hidden');

            calcZakatFitrah();
        } else {
            sectionZakat?.classList.add('hidden');
            sectionNominal?.classList.remove('hidden');
            state.amount = 0;
            document.querySelectorAll('.nominal-chips button').forEach(b => b.classList.remove('selected'));
            document.getElementById('custom-nominal').value = '';
            updateAmountUI();
        }

        changeStep(2);
    }

    function setZakatSub(sub, btn) {
        state.zakatSub = sub;
        document.querySelectorAll('.z-tab').forEach(b => {
            const active = b === btn;
            b.classList.toggle('active', active);
            b.setAttribute('aria-selected', String(active));
        });

        document.getElementById('form-fitrah')?.classList.toggle('hidden', sub !== 'fitrah');
        document.getElementById('form-mal')?.classList.toggle('hidden', sub !== 'mal');

        if (sub === 'fitrah') calcZakatFitrah();
        else calcZakatMal();
    }

    function calcZakatFitrah() {
        const jiwa = parseFloat(document.getElementById('f-jiwa')?.value) || 0;
        const nominal = parseFloat(document.getElementById('f-nominal')?.value) || 0;
        state.amount = jiwa * nominal;
        updateAmountUI();
    }

    function calcZakatMal() {
        const hartaInput = document.getElementById('m-harta');
        const harta = parseDigits(hartaInput?.dataset.raw ?? hartaInput?.value);
        state.amount = Math.round(harta * 0.025);

        const note = document.getElementById('nisab-note');
        if (note) {
            if (harta > 0 && harta < nisab) {
                note.textContent = `Harta ini belum mencapai nisab (${formatRp(nisab)}). Zakat mal biasanya belum wajib, tapi Anda tetap bisa bersedekah.`;
                note.hidden = false;
            } else {
                note.hidden = true;
            }
        }

        updateAmountUI();
    }

    function setAmount(val, btn) {
        document.querySelectorAll('.nominal-chips button').forEach(b => b.classList.remove('selected'));
        btn?.classList.add('selected');
        document.getElementById('custom-nominal').value = '';
        state.amount = val;
        updateAmountUI();
    }

    function updateAmountUI() {
        document.getElementById('final-amount-text').textContent = formatRp(state.amount);
        const toPayBtn = document.getElementById('to-pay-btn');
        const valid = state.amount >= MIN_AMOUNT && state.amount <= MAX_AMOUNT;
        if (toPayBtn) toPayBtn.disabled = !valid;

        let msg = '';
        if (state.amount > 0 && state.amount < MIN_AMOUNT) {
            msg = `Nominal donasi minimal ${formatRp(MIN_AMOUNT)}.`;
        } else if (state.amount > MAX_AMOUNT) {
            msg = 'Nominal donasi terlalu besar untuk dicatat online. Silakan hubungi pengurus masjid langsung.';
        }
        setError(document.getElementById('amount-error'), msg);
    }

    // ---------- copy to clipboard ----------

    async function copyText(text, btn) {
        try {
            await navigator.clipboard.writeText(text);
        } catch (e) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
        if (btn) {
            const original = btn.textContent;
            btn.textContent = 'Tersalin';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.textContent = original;
                btn.classList.remove('copied');
            }, 1800);
        }
    }

    // ---------- submit donation ----------
    //
    // Payload HARUS memakai nama field yang divalidasi DonasiController@store:
    //   jenis (key kategori), zakat_subtype (fitrah|mal, hanya jika calc_type zakat),
    //   nominal, nama_donatur, metode_pembayaran (QRIS|Transfer Bank|Dompet Digital).
    //
    // Server saat ini TIDAK terhubung ke payment gateway apa pun -- ia hanya membuat
    // baris donasi berstatus "pending" dan mengembalikan { success, no_referensi }.
    // Karena itu JS tidak berpura-pura ada konfirmasi pembayaran otomatis: begitu server
    // merespons sukses, kita tampilkan nomor referensi + status "menunggu verifikasi admin".
    // Kalau request ke server gagal (jaringan/validasi), tampilkan error apa adanya —
    // TIDAK ADA fallback yang memalsukan donasi "berhasil".

    async function processDonation() {
        const confirmBtn = document.getElementById('confirm-btn');
        const btnLabel = confirmBtn?.querySelector('.btn-label');
        const btnSpinner = confirmBtn?.querySelector('.btn-spinner');
        const errorEl = document.getElementById('submit-error');
        const payment = document.querySelector('input[name="payment"]:checked')?.value || 'QRIS';
        const donorName = document.getElementById('donor-name')?.value.trim() || '';

        setError(errorEl, '');

        if (!submitUrl) {
            setError(errorEl, 'Sistem donasi belum dikonfigurasi oleh pengurus masjid. Silakan hubungi admin.');
            return;
        }
        if (!state.categoryKey) {
            setError(errorEl, 'Kategori donasi belum dipilih. Silakan ulangi dari awal.');
            return;
        }
        if (state.amount < MIN_AMOUNT) {
            setError(errorEl, `Nominal donasi minimal ${formatRp(MIN_AMOUNT)}.`);
            return;
        }

        if (confirmBtn) confirmBtn.disabled = true;
        btnLabel && (btnLabel.textContent = 'Memproses...');
        btnSpinner?.classList.remove('hidden');

        const payload = {
            jenis: state.categoryKey,
            nominal: state.amount,
            nama_donatur: donorName, // dikosongkan -> server otomatis isi "Hamba Allah"
            metode_pembayaran: payment,
        };
        if (state.calcType === 'zakat') {
            payload.zakat_subtype = state.zakatSub;
        }

        try {
            const res = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            const result = await res.json().catch(() => null);

            if (!res.ok || !result || result.success === false) {
                const message = result?.message
                    || firstValidationError(result)
                    || `Gagal mencatat donasi (kode ${res.status}). Silakan coba lagi.`;
                throw new Error(message);
            }

            showReceipt(result);
        } catch (err) {
            setError(errorEl, err.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.');
        } finally {
            if (confirmBtn) confirmBtn.disabled = false;
            btnLabel && (btnLabel.textContent = 'Catat Donasi Saya');
            btnSpinner?.classList.add('hidden');
        }
    }

    /** Laravel mengembalikan { message, errors: { field: [msg,...] } } saat validasi gagal (422). */
    function firstValidationError(result) {
        if (!result || !result.errors) return null;
        const firstKey = Object.keys(result.errors)[0];
        return firstKey ? result.errors[firstKey][0] : null;
    }

    function showReceipt(result) {
        document.getElementById('res-code').textContent = result.no_referensi || '-';
        document.getElementById('res-cat').textContent = state.categoryTitle;
        document.getElementById('res-total').textContent = formatRp(state.amount);

        const waText = encodeURIComponent(
            `Alhamdulillah, saya baru saja berdonasi ${formatRp(state.amount)} untuk "${state.categoryTitle}" di Masjid. No. Referensi: ${result.no_referensi}.`
        );
        const shareLink = document.getElementById('share-wa');
        if (shareLink) shareLink.href = `https://wa.me/?text=${waText}`;

        changeStep(4);
    }

    function resetAll() {
        state = { categoryKey: null, categoryTitle: '', calcType: 'nominal', zakatSub: 'fitrah', amount: 0 };

        const donorName = document.getElementById('donor-name');
        const customNominal = document.getElementById('custom-nominal');
        const harta = document.getElementById('m-harta');

        if (donorName) donorName.value = '';
        if (customNominal) { customNominal.value = ''; customNominal.dataset.raw = '0'; }
        if (harta) { harta.value = ''; harta.dataset.raw = '0'; }
        document.getElementById('nisab-note')?.setAttribute('hidden', '');
        setError(document.getElementById('submit-error'), '');

        changeStep(1);
    }

    // ---------- modal detail dokumentasi penyaluran ----------

    function openGalleryModal(item) {
        const modal = document.getElementById('gallery-modal');
        if (!modal) return;

        document.getElementById('gm-img').style.backgroundImage = item.photo ? `url('${item.photo}')` : 'none';
        document.getElementById('gm-category').textContent = item.category || 'Penyaluran';
        document.getElementById('gm-title').textContent = item.title || '';
        document.getElementById('gm-desc').textContent = item.desc || 'Tidak ada deskripsi tambahan.';
        document.getElementById('gm-amount').textContent = item.amount || 'Rp 0';
        document.getElementById('gm-date').textContent = item.date || '-';

        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        modal.querySelector('.gallery-modal-close')?.focus();
    }

    function closeGalleryModal() {
        const modal = document.getElementById('gallery-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    // ---------- wire up events (delegation, no inline handlers) ----------

    document.addEventListener('DOMContentLoaded', () => {
        bindThousandsInput(document.getElementById('custom-nominal'), (raw) => {
            document.querySelectorAll('.nominal-chips button').forEach(b => b.classList.remove('selected'));
            state.amount = raw;
            updateAmountUI();
        });

        bindThousandsInput(document.getElementById('m-harta'), () => calcZakatMal());

        document.getElementById('f-jiwa')?.addEventListener('input', calcZakatFitrah);
        document.getElementById('f-nominal')?.addEventListener('input', calcZakatFitrah);

        document.body.addEventListener('click', (e) => {
            const galItem = e.target.closest('.pub-gal-item');
            if (galItem) {
                openGalleryModal({
                    title: galItem.dataset.title,
                    desc: galItem.dataset.desc,
                    category: galItem.dataset.category,
                    photo: galItem.dataset.photo,
                    amount: galItem.dataset.amount,
                    date: galItem.dataset.date,
                });
                return;
            }

            if (e.target.closest('[data-close-modal]')) {
                closeGalleryModal();
                return;
            }

            const catBtn = e.target.closest('.category-item');
            if (catBtn) {
                selectCategory(catBtn.dataset.key, catBtn.dataset.title, catBtn.dataset.calcType);
                return;
            }

            const gotoBtn = e.target.closest('[data-goto-step]');
            if (gotoBtn) {
                changeStep(parseInt(gotoBtn.dataset.gotoStep, 10));
                return;
            }

            const zTab = e.target.closest('.z-tab');
            if (zTab) {
                setZakatSub(zTab.dataset.zakatSub, zTab);
                return;
            }

            const chip = e.target.closest('.nominal-chips button');
            if (chip) {
                setAmount(parseInt(chip.dataset.amount, 10), chip);
                return;
            }

            const copyBtn = e.target.closest('.btn-copy');
            if (copyBtn) {
                copyText(copyBtn.dataset.copy || document.getElementById('res-code')?.textContent || '', copyBtn);
                return;
            }

            if (e.target.closest('#confirm-btn')) {
                processDonation();
                return;
            }

            if (e.target.closest('#reset-btn')) {
                resetAll();
                return;
            }
        });

        // Kartu galeri pakai role="button" + tabindex, jadi perlu ditangani manual
        // supaya bisa dibuka dengan Enter/Spasi juga (bukan cuma klik mouse).
        document.body.addEventListener('keydown', (e) => {
            const galItem = e.target.closest('.pub-gal-item');
            if (galItem && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                galItem.click();
                return;
            }
            if (e.key === 'Escape') {
                closeGalleryModal();
            }
        });
    });
})();