document.addEventListener('DOMContentLoaded', function () {

  let current = null;
  let amount = 0;
  let zakatSubtype = 'fitrah'; // default zakat subtype
  let generatedNoReferensi = ''; // menampung nomor referensi dari server

  function goStep(n) {
    for (let i = 1; i <= 4; i++) document.getElementById('step-' + i).classList.add('hidden');
    document.getElementById('step-' + n).classList.remove('hidden');
    for (let i = 1; i <= 4; i++) {
      const bar = document.getElementById('bar-' + i);
      bar.classList.remove('active', 'done');
      if (i < n) bar.classList.add('done');
      if (i === n) bar.classList.add('active');
    }
    if (n === 3) fillSummary();
    if (n === 4) fillConfirm();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function openDetail(key) {
    current = key;
    amount = 0;
    zakatSubtype = 'fitrah';
    const cat = window.donasiCategories[key];
    document.getElementById('detail-title').textContent = cat.title;
    document.getElementById('detail-desc').textContent = cat.desc;

    document.getElementById('zakat-subtype').classList.add('hidden');
    document.getElementById('calc-fitrah').classList.add('hidden');
    document.getElementById('calc-mal').classList.add('hidden');
    document.getElementById('generic-amount').classList.add('hidden');
    document.getElementById('to-payment-btn').disabled = true;

    if (key === 'zakat') {
      document.getElementById('zakat-subtype').classList.remove('hidden');
      document.getElementById('calc-fitrah').classList.remove('hidden');
      // Set active button UI for zakat subtype
      const buttons = document.querySelectorAll('#zakat-subtype button');
      buttons.forEach((b, idx) => {
        if (idx === 0) b.classList.add('active');
        else b.classList.remove('active');
      });
      calcFitrah();
    } else {
      document.getElementById('generic-amount').classList.remove('hidden');
    }
    goStep(2);
  }

  function setZakatType(type, btn) {
    zakatSubtype = type;
    document.querySelectorAll('#zakat-subtype button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('calc-fitrah').classList.toggle('hidden', type !== 'fitrah');
    document.getElementById('calc-mal').classList.toggle('hidden', type !== 'mal');
    if (type === 'fitrah') calcFitrah(); else calcMal();
  }

  function formatRp(v) { return 'Rp ' + Math.round(v).toLocaleString('id-ID'); }

  function calcFitrah() {
    const jiwa = parseFloat(document.getElementById('fitrah-jiwa').value) || 0;
    const nom = parseFloat(document.getElementById('fitrah-nominal').value) || 0;
    amount = jiwa * nom;
    document.getElementById('fitrah-total').textContent = formatRp(amount);
    document.getElementById('to-payment-btn').disabled = amount <= 0;
  }

  function calcMal() {
    const harta = parseFloat(document.getElementById('mal-harta').value) || 0;
    amount = harta * 0.025;
    document.getElementById('mal-total').textContent = formatRp(amount);
    document.getElementById('to-payment-btn').disabled = amount <= 0;
  }

  function pickAmount(v, btn) {
    document.querySelectorAll('#generic-amount .amount-grid button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('custom-amount').value = '';
    amount = v;
    document.getElementById('to-payment-btn').disabled = false;
  }

  function pickCustom() {
    document.querySelectorAll('#generic-amount .amount-grid button').forEach(b => b.classList.remove('active'));
    amount = parseFloat(document.getElementById('custom-amount').value) || 0;
    document.getElementById('to-payment-btn').disabled = amount <= 0;
  }

  function selectPay(el) {
    document.querySelectorAll('.paymethods div').forEach(d => d.classList.remove('sel'));
    el.classList.add('sel');
  }

  function fillSummary() {
    const name = document.getElementById('donor-name').value || 'Hamba Allah';
    document.getElementById('sum-jenis').textContent = window.donasiCategories[current].title;
    document.getElementById('sum-nama').textContent = name;
    document.getElementById('sum-total').textContent = formatRp(amount);
  }

  // Fungsi untuk mengirim data ke DonasiController@store via AJAX Fetch
  function submitDonation() {
    const name = document.getElementById('donor-name').value || 'Hamba Allah';
    const selMethod = document.querySelector('.paymethods .sel');
    const paymentMethod = selMethod ? selMethod.textContent.trim() : 'QRIS';
    
    // Ambil slug masjid dari URL path (format: /masjid/{slug}/donasi)
    const pathSegments = window.location.pathname.split('/');
    const mosqueSlug = pathSegments[pathSegments.indexOf('masjid') + 1];

    const payload = {
      jenis: current,
      zakat_subtype: current === 'zakat' ? zakatSubtype : null,
      nominal: amount,
      nama_donatur: name,
      metode_pembayaran: paymentMethod
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(`/masjid/${mosqueSlug}/donasi`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        generatedNoReferensi = data.no_referensi;
        goStep(4);
      } else {
        alert('Gagal memproses donasi. Silakan periksa kembali data Anda.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan pada server.');
    });
  }

  function fillConfirm() {
    document.getElementById('conf-jenis').textContent = window.donasiCategories[current].title;
    document.getElementById('conf-total').textContent = formatRp(amount);
    const sel = document.querySelector('.paymethods .sel');
    document.getElementById('conf-method').textContent = sel ? sel.textContent.trim() : 'QRIS';
    
    // Tampilkan nomor referensi asli yang didapat dari server
    const refContainer = document.querySelector('.receipt');
    if (refContainer) {
      refContainer.innerHTML = `
        No. referensi: <b>${generatedNoReferensi || 'DN-PENDING'}</b><br>
        Jenis: <b id="conf-jenis">${window.donasiCategories[current].title}</b><br>
        Nominal: <b id="conf-total">${formatRp(amount)}</b><br>
        Metode: <b id="conf-method">${sel ? sel.textContent.trim() : 'QRIS'}</b>
      `;
    }
  }

  function resetAll() {
    current = null; 
    amount = 0;
    generatedNoReferensi = '';
    document.getElementById('donor-name').value = '';
    const malHarta = document.getElementById('mal-harta');
    if (malHarta) malHarta.value = '';
    const customAmt = document.getElementById('custom-amount');
    if (customAmt) customAmt.value = '';
    const fitrahJiwa = document.getElementById('fitrah-jiwa');
    if (fitrahJiwa) fitrahJiwa.value = 1;
    goStep(1);
  }

  // Override tombol "Selesaikan Donasi" agar memanggil fungsi AJAX submitDonation
  const finishBtn = document.querySelector('#step-3 .cta');
  if (finishBtn) {
    finishBtn.removeAttribute('onclick');
    finishBtn.addEventListener('click', submitDonation);
  }

  // expose functions used by inline onclick= attributes in the Blade view
  window.goStep = goStep;
  window.openDetail = openDetail;
  window.setZakatType = setZakatType;
  window.calcFitrah = calcFitrah;
  window.calcMal = calcMal;
  window.pickAmount = pickAmount;
  window.pickCustom = pickCustom;
  window.selectPay = selectPay;
  window.resetAll = resetAll;

  goStep(1);
});