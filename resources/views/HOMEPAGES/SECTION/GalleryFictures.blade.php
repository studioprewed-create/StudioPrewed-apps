<div class="floating-middlebar">
    <div class="middlebar-container">
      <div class="gallery-filter">
        <button class="filter-btn {{ empty($selectedCategory) ? 'active' : '' }}" data-filter="all">
          <i class="fas fa-globe"></i><span class="label">All</span>
        </button>
        <button class="filter-btn {{ $selectedCategory === 'prewed' ? 'active' : '' }}" data-filter="prewed">
          <i class="fas fa-dove"></i><span class="label">Prewed Session</span>
        </button>
        <button class="filter-btn {{ $selectedCategory === 'family' ? 'active' : '' }}" data-filter="family">
          <i class="fas fa-leaf"></i><span class="label">Family Session</span>
        </button>
        <button class="filter-btn {{ $selectedCategory === 'maternity' ? 'active' : '' }}" data-filter="maternity">
          <i class="fas fa-seedling"></i><span class="label">Maternity Shoot</span>
        </button>
        <button class="filter-btn {{ $selectedCategory === 'postwedding' ? 'active' : '' }}" data-filter="postwedding">
          <i class="fas fa-ribbon"></i><span class="label">Post Wedding</span>
        </button>
        <button class="filter-btn {{ $selectedCategory === 'beauty' ? 'active' : '' }}" data-filter="beauty">
          <i class="fas fa-heart"></i><span class="label">Beauty Shoot</span>
        </button>
        <button class="filter-btn {{ $selectedCategory === 'birthday' ? 'active' : '' }}" data-filter="birthday">
          <i class="fas fa-gift"></i><span class="label">Birthday Session</span>
        </button>
      </div>
    </div>
</div>

<section class="gallery-section" id="gallery"
         data-initial-filter="{{ $selectedCategory ?? 'all' }}">
    <div class="dynamic-title active" id="dynamicTitle">
        <h2>All Sessions</h2>
        <p>Explore our complete collection of professional photography sessions.</p>
    </div>

    <div class="gallery-grid">
        @forelse($galleries as $g)
            @php
            $img = $g->image ? asset('storage/'.$g->image) : asset('asset/IMGhome/bg1.jpg');
            $cat = $g->category ?: 'prewed';
            @endphp
            <div class="gallery-item" data-category="{{ $cat }}" data-img="{{ $img }}">
                <img src="{{ $img }}" alt="{{ $g->title ?? 'Gallery Image' }}">
                <div class="gallery-overlay">
                    <h3>{{ $g->title ?? 'Untitled' }}</h3>
                    <p>{{ $g->description ?? '' }}</p>
                </div>
            </div>
        @empty
            <p class="empty-state">Belum ada item galeri.</p>
        @endforelse
    </div>
</section>

<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="expandedImage" alt="Preview">
</div>
