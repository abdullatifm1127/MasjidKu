function dnPreviewFoto(input) {
  const preview = document.getElementById('dn-preview');
  if (input.files && input.files[0]) {
    preview.src = URL.createObjectURL(input.files[0]);
    preview.classList.remove('hidden');
  } else {
    preview.classList.add('hidden');
  }
}