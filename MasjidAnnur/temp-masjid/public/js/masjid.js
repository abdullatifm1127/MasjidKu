document.addEventListener('DOMContentLoaded',function(){
 const steps=[...document.querySelectorAll('.form-step')], progress=document.getElementById('progress');
 if(!steps.length)return; let current=0;
 const labels=['Informasi Masjid','Data Pengurus','Fasilitas & Program','Konfirmasi'];
 function render(){
   steps.forEach((s,i)=>s.classList.toggle('active',i===current));
   if(progress) progress.innerHTML=labels.map((x,i)=>'<div class="progress-item '+(i<=current?'done':'')+'"><b>'+(i<current?'✓':i+1)+'</b><span>'+x+'</span></div>').join('');
   document.getElementById('prev').disabled=current===0;
   document.getElementById('next').hidden=current===steps.length-1;
   document.getElementById('submit').hidden=current!==steps.length-1;
   window.scrollTo({top:0,behavior:'smooth'});
 }
 document.getElementById('next').onclick=function(){ if(current<steps.length-1){current++;render()} };
 document.getElementById('prev').onclick=function(){if(current>0){current--;render()}};
 render();
});