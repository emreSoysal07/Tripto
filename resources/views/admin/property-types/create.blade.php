@extends('admin.layouts.app')

@section('title', 'Yeni Mülk Tipi Ekle')
@section('breadcrumb_parent', 'Mülk Tipleri')
@section('breadcrumb_title', 'Yeni Mülk Tipi Ekle')

@section('content')
<div class="flex-grow-1 container-p-y">

    <!-- Header & Action Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('admin.property-types.index') }}" class="btn btn-outline-secondary">
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
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Mülk Tipi Bilgileri</h5>
                    <small class="text-muted float-end">* Zorunlu alanlar</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.property-types.store') }}" method="POST" id="propertyTypeForm">
                        @csrf

                        <!-- Mülk Tipi Adı -->
                        <div class="mb-4">
                            <label class="form-label" for="name">Mülk Tipi Adı <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-building-house"></i></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Örn: Villa, Otel, Daire, Bungalov" required autofocus />
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- İkon Kodu -->
                        <div class="mb-4">
                            <label class="form-label" for="icon">İkon Class Kodu (Boxicons)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-code-alt"></i></span>
                                <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', 'bx-building-house') }}" placeholder="Örn: bx-building veya bx-home-alt" />
                            </div>
                            <small class="text-muted">Boxicons kodu yazabilirsiniz. Örn: <code>bx-building</code>, <code>bx-home</code>, <code>bx-hotel</code></small>
                            @error('icon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Odalar/Üniteler İçeriyor mu? (Otel Mantığı) -->
                        <div class="mb-4 p-3 bg-lighter rounded border">
                            <div class="form-check form-switch d-flex align-items-center justify-content-between ps-0">
                                <div>
                                    <label class="form-check-label fw-bold mb-1" for="has_rooms">Alt Oda / Ünite Mantığı İçeriyor mu?</label>
                                    <p class="text-muted small mb-0">Örn: Otel veya Yurt gibi alt odaları olan mülkler için bu seçeneği aktif edin.</p>
                                </div>
                                <input class="form-check-input ms-2" type="checkbox" id="has_rooms" name="has_rooms" value="1" {{ old('has_rooms') ? 'checked' : '' }} style="width: 2.5em; height: 1.3em;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('admin.property-types.index') }}" class="btn btn-outline-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf: Önizleme & Hızlı İkon Seçici -->
        <div class="col-md-5">
            <!-- Canlı İkon Önizleme Kartı -->
            <div class="card mb-4 text-center">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3">İkon Önizleme</h5>
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary fs-1" id="icon-preview-box">
                            <i class="bx {{ old('icon', 'bx-building-house') }}" id="icon-preview"></i>
                        </span>
                    </div>
                    <p class="card-text fw-bold text-primary mb-0" id="icon-name-display">{{ old('icon', 'bx-building-house') }}</p>
                </div>
            </div>

            <!-- Hızlı İkon Seçim Rehberi -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 fs-6 fw-bold">Popüler İkonlar (Tıkla-Seç)</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2" id="quick-icon-list">
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-building-house" title="Mülk / Ev"><i class="bx bx-building-house"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-home-alt" title="Müstakil Ev"><i class="bx bx-home-alt"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-hotel" title="Otel"><i class="bx bx-hotel"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-building" title="Apartman / Daire"><i class="bx bx-building"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-landscape" title="Villa / Doğa"><i class="bx bx-landscape"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-key" title="Kiralık Ünite"><i class="bx bx-key"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-store-alt" title="İş Yeri / Dükkan"><i class="bx bx-store-alt"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-secondary quick-icon-btn" data-icon="bx-map-pin" title="Lokasyon"><i class="bx bx-map-pin"></i></button>
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