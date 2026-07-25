@extends('admin.layouts.app')

@section('title', 'Yeni Olanak Ekle')
@section('breadcrumb_parent', 'Olanaklar')
@section('breadcrumb_title', 'Yeni Olanak Ekle')

@section('content')
<div class="flex-grow-1">

    <!-- Header & Action Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Listeye Dön
        </a>
    </div>

    {{-- Genel Hata Uyarısı --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-error-circle fs-4 me-2"></i>
                <h5 class="alert-heading mb-0 fw-bold">Lütfen Formdaki Hataları Düzeltiniz</h5>
            </div>
            <hr class="my-2">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Alanı -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Olanak Bilgileri</h5>
                    <small class="text-muted float-end">* Zorunlu alanlar</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.amenities.store') }}" method="POST" id="amenityForm">
                        @csrf

                        <!-- Olanak Adı -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Olanak Adı <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-list-check"></i></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Örn: Kablosuz İnternet (Wi-Fi)" required autofocus />
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- İkon Kodu -->
                        <div class="mb-3">
                            <label class="form-label" for="icon">İkon Class Kodu (Boxicons)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-code-alt"></i></span>
                                <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', 'bx-wifi') }}" placeholder="Örn: bx-wifi veya bx-pool" />
                            </div>
                            <small class="text-muted">Sistemde <code>Boxicons</code> kütüphanesi kullanılmaktadır. Örn: <code>bx-wifi</code>, <code>bx-swim</code>, <code>bx-car</code></small>
                            @error('icon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf: Önizleme & Hızlı İkon Seçici -->
        <div class="col-md-4">
            <!-- Canlı İkon Önizleme Kartı -->
            <div class="card mb-4 text-center">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3">İkon Önizleme</h5>
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary fs-1" id="icon-preview-box">
                            <i class="bx {{ old('icon', 'bx-wifi') }}" id="icon-preview"></i>
                        </span>
                    </div>
                    <p class="card-text text-muted mb-0" id="icon-name-display">bx-wifi</p>
                </div>
            </div>

            <!-- Hızlı İkon Seçim Rehberi -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 fs-6 fw-bold">Popüler İkonlar (Tıkla-Seç)</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2" id="quick-icon-list">
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-wifi" title="Wi-Fi"><i class="bx bx-wifi"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-swim" title="Havuz"><i class="bx bx-swim"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-car" title="Otopark"><i class="bx bx-car"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-tv" title="TV"><i class="bx bx-tv"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-wind" title="Klima"><i class="bx bx-wind"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-coffee" title="Kahve/Mutfak"><i class="bx bx-coffee"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-dumbbell" title="Spor Salonu"><i class="bx bx-dumbbell"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-restaurant" title="Restoran"><i class="bx bx-restaurant"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-shield-quarter" title="Güvenlik"><i class="bx bx-shield-quarter"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-water" title="Deniz/Manzara"><i class="bx bx-water"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-sun" title="Balkon/Güneş"><i class="bx bx-sun"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-first-aid" title="Sağlık/İlk Yardım"><i class="bx bx-first-aid"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const iconNameDisplay = document.getElementById('icon-name-display');
    const quickIconBtns = document.querySelectorAll('.quick-icon-btn');

    function updateIconPreview(iconClass) {
        let cleanIcon = iconClass.trim();
        if (!cleanIcon.startsWith('bx-') && !cleanIcon.startsWith('bxs-') && !cleanIcon.startsWith('bxl-')) {
            cleanIcon = 'bx-' + cleanIcon;
        }
        iconPreview.className = 'bx ' + cleanIcon;
        iconNameDisplay.textContent = cleanIcon;
    }

    if (iconInput) {
        iconInput.addEventListener('input', function () {
            updateIconPreview(this.value || 'bx-dots-horizontal-rounded');
        });
    }

    quickIconBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedIcon = this.getAttribute('data-icon');
            iconInput.value = selectedIcon;
            updateIconPreview(selectedIcon);
        });
    });
});
</script>
@endpush