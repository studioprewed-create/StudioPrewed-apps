<div class="page-header">
    <div>
        <h1>DATA PARTNERSHIP</h1>
        <p>Brand partnership & studio management</p>
    </div>
</div>

@if($brands->isEmpty())

    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        Belum ada data partnership.
    </div>

@else

<div class="partnership-grid">

    @foreach($brands as $user)

        @php
            $brand = $user->dataBrand;
        @endphp

        <div class="partnership-card">

            <div class="partnership-top">

                <div class="partnership-logo">

                    @if($brand?->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="">
                    @else
                        <i class="fa fa-image"></i>
                    @endif

                </div>

                <div class="partnership-info">

                    <h3>
                        {{ $brand?->nama_brand ?? $user->name }}
                    </h3>

                    <span class="role-badge
                        @if($user->role === 'BRAND_PARTNERSHIP')
                            role-brand_partnership
                        @else
                            role-studio
                        @endif">

                        {{ $user->role }}

                    </span>

                </div>

            </div>

            <div class="partnership-body">

                <div class="partnership-item">
                    <span>Category</span>
                    <strong>
                        {{ $brand?->category?->name ?? '-' }}
                    </strong>
                </div>

                <div class="partnership-item">
                    <span>Email</span>
                    <strong>
                        {{ $brand?->email ?? $user->email }}
                    </strong>
                </div>

                <div class="partnership-item">
                    <span>Phone</span>
                    <strong>
                        {{ $brand?->phone ?? '-' }}
                    </strong>
                </div>

                <div class="partnership-item">
                    <span>Website</span>
                    <strong>
                        {{ $brand?->website ?? '-' }}
                    </strong>
                </div>

                <div class="partnership-item">
                    <span>Instagram</span>
                    <strong>
                        {{ $brand?->instagram ?? '-' }}
                    </strong>
                </div>

            </div>

        </div>

    @endforeach

</div>

@endif