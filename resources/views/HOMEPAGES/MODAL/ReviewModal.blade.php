{{-- resources/views/HOMEPAGES/FITUR/Modal/review-modal.blade.php --}}
@php
  $user = auth()->user();
  $mustLogin = !$user || !trim($user->name ?? '');
@endphp

<div class="rf-modal" id="reviewModal" aria-hidden="true" style="display:none">
  <div class="rf-modal__backdrop" data-close></div>

  <div class="rf-modal__panel" role="dialog" aria-modal="true" aria-labelledby="rfModalTitle">
    <button class="rf-modal__close" type="button" data-close aria-label="Tutup">&times;</button>
    <h3 class="rf-modal__title" id="rfModalTitle">Tambah Review</h3>

    <form class="review-form" action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Auto dari user / disembunyikan --}}
      <input type="hidden" name="name"  value="{{ $user->name ?? '' }}">
      <input type="hidden" name="role"  value="{{ $user->role ?? 'Customer' }}">
      <input type="hidden" name="date"  value="{{ now()->toDateString() }}">
      <input type="hidden" name="active" value="1">

      <div class="rf-grid">
        <div class="rf-field">
          <label>Rating <span>(1–5)</span></label>
          <input class="rf-input" type="number" name="rating" placeholder="5" min="1" max="5" required>
        </div>
        <div class="rf-field">
          <label>Foto/Avatar</label>
          <input class="rf-input" type="file" name="avatar" accept="image/*">
        </div>
      </div>

      <div class="rf-field">
        <label>Isi Review</label>
        <textarea class="rf-input" name="content" rows="3" placeholder="Tulis kesan dan pesanmu..."></textarea>
      </div>

      <div class="rf-actions">
        <button class="rf-btn" type="submit">Tambah Review</button>
        <button class="rf-btn rf-btn--ghost" type="button" data-close>Batal</button>
      </div>
    </form>
  </div>
</div>

<style>
/* ===== Modal ===== */
.rf-modal__backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.65); backdrop-filter:blur(2px); }
.rf-modal__panel{
  position:fixed; left:50%; top:8vh; transform:translateX(-50%);
  width:min(720px,92vw); background:#2d2d2d; border:1px solid #404040;
  border-radius:14px; padding:16px; z-index:1001; color:#f0f0f0;
  box-shadow:0 10px 40px rgba(0,0,0,.4); animation:rf-pop .2s ease;
}
.rf-modal__title{ margin:0 0 10px; font-size:1.1rem; font-weight:700; }
.rf-modal__close{
  position:absolute; top:10px; right:12px; background:transparent; border:0;
  color:#fff; font-size:28px; cursor:pointer; line-height:1;
}
@keyframes rf-pop{ from{ transform:translate(-50%,8px); opacity:.6 } to{ transform:translate(-50%,0); opacity:1 } }

/* ===== Form (dark) ===== */
.review-form{ background:#2d2d2d; border:1px solid #404040; border-radius:12px; padding:14px; }
.review-form .rf-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.review-form .rf-field{ display:flex; flex-direction:column; gap:6px; }
.review-form label{ color:#f0f0f0; font-size:.92rem; font-weight:600; }
.review-form label span{ color:#b0b0b0; font-weight:400; }
.review-form .rf-input{
  background:#1f1f1f; color:#f0f0f0; border:1px solid #404040; border-radius:10px;
  padding:.6rem .75rem; outline:none; transition:.18s ease; width:100%;
}
.review-form .rf-input::placeholder{ color:#b0b0b0; }
.review-form .rf-input:focus{ border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.22); }
.review-form input[type="number"]{ -moz-appearance:textfield; text-align:center; }
.review-form input[type="number"]::-webkit-outer-spin-button,
.review-form input[type="number"]::-webkit-inner-spin-button{-webkit-appearance:none;margin:0;}
.review-form .rf-actions{ margin-top:10px; display:flex; justify-content:flex-end; gap:8px; }
.review-form .rf-btn{
  background:#6366f1; color:#fff; border:none; padding:.6rem 1rem; border-radius:10px;
  cursor:pointer; font-weight:700; transition:.18s ease;
}
.review-form .rf-btn:hover{ filter:brightness(1.07); transform:translateY(-1px); }
.review-form .rf-btn:active{ transform:translateY(0); }
.rf-btn--ghost{ background:transparent; border:1px solid #404040; color:#b0b0b0; }
.rf-btn--ghost:hover{ background:rgba(255,255,255,.06); }
@media (max-width:720px){ .review-form .rf-grid{ grid-template-columns:1fr; } }
</style>

<script>
(function(){
  const modal   = document.getElementById('reviewModal');
  const opener  = document.getElementById('openReviewModal');
  if(!modal || !opener) return;

  // dari Blade: true kalau harus login (user null atau name kosong)
  const mustLogin = @json($mustLogin);
  const loginUrl  = "{{ route('login') }}";
  // kembali ke halaman ini setelah login
  const intended  = encodeURIComponent("{{ request()->fullUrl() }}");

  const show = () => { modal.style.display = 'block'; document.body.style.overflow='hidden'; };
  const hide = () => { modal.style.display = 'none';  document.body.style.overflow=''; };

  opener.addEventListener('click', (e) => {
    e.preventDefault();
    if(mustLogin){
      window.location.assign(`${loginUrl}?intended=${intended}`);
      return;
    }
    show();
  });

  modal.querySelectorAll('[data-close]').forEach(el => el.addEventListener('click', hide));
  modal.addEventListener('click', (e)=>{ if(e.target.classList.contains('rf-modal__backdrop')) hide(); });
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') hide(); });
})();
</script>
