let activeCategoryKey = null;
let activeCategoryTitle = '';
let calcType = 'nominal';
let zakatSub = 'fitrah';
let amountValue = 0;

function changeStep(stepNum) {
    for (let i = 1; i <= 4; i++) {
        const panel = document.getElementById(`panel-${i}`);
        const st = document.getElementById(`st-${i}`);
        if (panel) panel.classList.add('hidden');
        if (st) st.classList.remove('active');
    }
    
    const targetPanel = document.getElementById(`panel-${stepNum}`);
    const targetSt = document.getElementById(`st-${stepNum}`);
    
    if (targetPanel) targetPanel.classList.remove('hidden');
    if (targetSt) targetSt.classList.add('active');
    
    window.scrollTo({ top: 0, behavior: 'smooth' });

    if (stepNum === 3) {
        const sumCat = document.getElementById('sum-cat');
        const donorNameInput = document.getElementById('donor-name');
        const sumName = document.getElementById('sum-name');
        const sumTotal = document.getElementById('sum-total');

        if (sumCat) sumCat.textContent = activeCategoryTitle;
        const nameInput = donorNameInput ? donorNameInput.value.trim() : '';
        if (sumName) sumName.textContent = nameInput !== '' ? nameInput : 'Hamba Allah';
        if (sumTotal) sumTotal.textContent = formatRp(amountValue);
    }
}

function selectCategory(key, title, type) {
    activeCategoryKey = key;
    activeCategoryTitle = title;
    calcType = type ? type.trim().toLowerCase() : 'nominal';

    const catTitleEl = document.getElementById('selected-cat-title');
    if (catTitleEl) {
        catTitleEl.textContent = title;
    }

    const sectionZakat = document.getElementById('section-zakat');
    const sectionNominal = document.getElementById('section-nominal');

    // Jika tipenya adalah zakat, tampilkan kalkulator zakat dan sembunyikan nominal bebas
    if (calcType === 'zakat') {
        if (sectionZakat) sectionZakat.classList.remove('hidden');
        if (sectionNominal) sectionNominal.classList.add('hidden');
        
        // Reset ke tab fitrah secara default saat kategori zakat dipilih
        zakatSub = 'fitrah';
        document.querySelectorAll('.z-tab').forEach((b, idx) => {
            if (idx === 0) b.classList.add('active');
            else b.classList.remove('active');
        });
        const formFitrah = document.getElementById('form-fitrah');
        const formMal = document.getElementById('form-mal');
        if (formFitrah) formFitrah.classList.remove('hidden');
        if (formMal) formMal.classList.add('hidden');

        calcZakatFitrah();
    } else {
        if (sectionZakat) sectionZakat.classList.add('hidden');
        if (sectionNominal) sectionNominal.classList.remove('hidden');
        amountValue = 0;
        updateAmountUI();
    }
    changeStep(2);
}

function setZakatSub(sub, btn) {
    zakatSub = sub;
    document.querySelectorAll('.z-tab').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const formFitrah = document.getElementById('form-fitrah');
    const formMal = document.getElementById('form-mal');

    if (formFitrah) formFitrah.classList.toggle('hidden', sub !== 'fitrah');
    if (formMal) formMal.classList.toggle('hidden', sub !== 'mal');

    if (sub === 'fitrah') calcZakatFitrah();
    else calcZakatMal();
}

function calcZakatFitrah() {
    const jiwaEl = document.getElementById('f-jiwa');
    const nominalEl = document.getElementById('f-nominal');
    
    const jiwa = jiwaEl ? parseFloat(jiwaEl.value) || 0 : 0;
    const nominal = nominalEl ? parseFloat(nominalEl.value) || 0 : 0;
    
    amountValue = jiwa * nominal;
    updateAmountUI();
}

function calcZakatMal() {
    const hartaEl = document.getElementById('m-harta');
    const harta = hartaEl ? parseFloat(hartaEl.value) || 0 : 0;
    
    // Perhitungan zakat mal 2.5% dibulatkan agar rapi
    amountValue = Math.round(harta * 0.025);
    updateAmountUI();
}

function setAmount(val, btn) {
    document.querySelectorAll('.nominal-chips button').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    
    const customNominal = document.getElementById('custom-nominal');
    if (customNominal) customNominal.value = '';
    
    amountValue = val;
    updateAmountUI();
}

function setCustomAmount() {
    document.querySelectorAll('.nominal-chips button').forEach(b => b.classList.remove('active'));
    
    const customNominal = document.getElementById('custom-nominal');
    amountValue = customNominal ? parseFloat(customNominal.value) || 0 : 0;
    
    updateAmountUI();
}

function updateAmountUI() {
    const finalAmountText = document.getElementById('final-amount-text');
    const toPayBtn = document.getElementById('to-pay-btn');

    if (finalAmountText) finalAmountText.textContent = formatRp(amountValue);
    if (toPayBtn) toPayBtn.disabled = amountValue <= 0;
}

function formatRp(num) {
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

function processDonation() {
    const trxCode = 'TRX-' + Math.floor(100000 + Math.random() * 900000);
    
    const resCode = document.getElementById('res-code');
    const resCat = document.getElementById('res-cat');
    const resTotal = document.getElementById('res-total');

    if (resCode) resCode.textContent = trxCode;
    if (resCat) resCat.textContent = activeCategoryTitle;
    if (resTotal) resTotal.textContent = formatRp(amountValue);
    
    changeStep(4);
}

function resetAll() {
    activeCategoryKey = null;
    amountValue = 0;
    
    const donorName = document.getElementById('donor-name');
    const customNominal = document.getElementById('custom-nominal');

    if (donorName) donorName.value = '';
    if (customNominal) customNominal.value = '';
    
    changeStep(1);
}