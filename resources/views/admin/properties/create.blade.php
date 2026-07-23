@extends('admin.layouts.app')

@section('title', 'Yeni Mülk Ekle')
@section('breadcrumb_parent', 'Mülkler')
@section('breadcrumb_title', 'Yeni Mülk Ekle')

@section('content')
<div class="flex-grow-1">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Listeye Dön
        </a>
    </div>

    <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Sneat Filled Tabs Bileşeni -->
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills nav-fill mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-general">
                        <i class="tf-icons bx bx-home me-1"></i> Genel Bilgiler
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-images">
                        <i class="tf-icons bx bx-image-add me-1"></i> Resimler
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-amenities">
                        <i class="tf-icons bx bx-list-check me-1"></i> Olanaklar
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-policies">
                        <i class="tf-icons bx bx-shield-quarter me-1"></i> Politikalar
                    </button>
                </li>
            </ul>

            <div class="tab-content card p-4">

                <!-- 1. TAB: GENEL BİLGİLER -->
                <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                    
                    {{-- Otel Seçilince Çıkacak Bilgilendirme Kutusu --}}
                    <div class="col-12 mb-3" id="hotel-info-alert" style="display: none;">
                        <div class="alert alert-info d-flex align-items-center mb-0" role="alert">
                            <i class="bx bx-info-circle fs-4 me-2"></i>
                            <div>
                                <strong>Otel / Yurt Tipi Seçildi:</strong> Mülkü kaydettikten sonra detayı üzerinden özel oda türleri (Deluxe, Suite vb.) ve odalar tanımlayabilirsiniz.
                            </div>
                        </div>
                    </div>

                    {{-- Satır 1: Başlık + Mülk Tipi --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label" for="title">Başlık</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Bodrum Deniz Manzaralı Villa" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="property_type_id">Mülk Tipi</label>
                            <select id="property_type_id" name="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror">
                                <option value="">Seçiniz</option>
                                @foreach ($propertyTypes as $type)
                                    <option value="{{ $type->id }}" 
                                            data-slug="{{ $type->slug }}" 
                                            data-has-rooms="{{ $type->has_rooms ? 'true' : 'false' }}"
                                            {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('property_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Satır 2: Fiyat + Durum --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="price_per_night">Gecelik Fiyat</label>
                            <div class="input-group @error('price_per_night') is-invalid @enderror">
                                <span class="input-group-text">₺</span>
                                <input type="number" step="0.01" id="price_per_night" name="price_per_night" class="form-control @error('price_per_night') is-invalid @enderror" value="{{ old('price_per_night') }}" placeholder="1500" />
                            </div>
                            @error('price_per_night')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="status">Durum</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Satır 3: Kapasite + Yatak Odası + Banyo (DÜZELTME: id="single-unit-fields" EKLENDİ) --}}
                    <div class="row g-3 mb-3" id="single-unit-fields">
                        <div class="col-md-4">
                            <label class="form-label" for="capacity">Kapasite (Kişi)</label>
                            <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', 1) }}" placeholder="4" />
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="bedrooms">Yatak Odası Sayısı</label>
                            <input type="number" id="bedrooms" name="bedrooms" class="form-control" value="{{ old('bedrooms', 1) }}" placeholder="2" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="bathrooms">Banyo Sayısı</label>
                            <input type="number" id="bathrooms" name="bathrooms" class="form-control" value="{{ old('bathrooms', 1) }}" placeholder="1" />
                        </div>
                    </div>

                    {{-- Satır 4: Adres + Şehir + Ülke --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="address">Adres</label>
                            <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Mahalle, cadde, no..." />
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="city">Şehir</label>
                            <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="Muğla" />
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="country">Ülke</label>
                            <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', 'Türkiye') }}" />
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Satır 5: Açıklama --}}
                    <div class="mb-3">
                        <label class="form-label" for="description">Açıklama</label>
                        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Mülk hakkında detaylı açıklama...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 2. TAB: RESİMLER -->
                <div class="tab-pane fade" id="tab-images" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label" for="images">Mülk Görselleri Seçin</label>
                        <input type="file" id="images" name="images[]" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" multiple accept="image/*">
                        <small class="text-muted d-block mt-1">Birden fazla görsel seçebilirsiniz. Desteklenenler: JPG, PNG, WEBP (Max: 2MB)</small>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 3. TAB: OLANAKLAR (DÜZELTME: Temiz CSS & Flex Hizalaması) -->
                <div class="tab-pane fade" id="tab-amenities" role="tabpanel">
                    <p class="text-muted mb-3">Bu mülkte sunulan olanakları işaretleyin:</p>
                    <div class="row g-3">
                        @if(isset($amenities) && $amenities->count() > 0)
                            @foreach($amenities as $amenity)
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check custom-option custom-option-basic p-0">
                                        <label class="form-check-label custom-option-content p-3 border rounded cursor-pointer d-flex align-items-center w-100" for="amenity_{{ $amenity->id }}">
                                            <input class="form-check-input mt-0 me-2" type="checkbox" style="float: none; margin-left: 0;" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" {{ is_array(old('amenities')) && in_array($amenity->id, old('amenities')) ? 'checked' : '' }}>
                                            <span class="d-flex align-items-center gap-1">
                                                <i class="bx {{ $amenity->icon ?? 'bx-check-circle' }} fs-5"></i>
                                                <span class="fw-semibold">{{ $amenity->name }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">Sistemde henüz kayıtlı bir olanak bulunamadı.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 4. TAB: POLİTİKALAR (Dinamik Todo List) -->
                <div class="tab-pane fade" id="tab-policies" role="tabpanel">
                    <div class="card p-3 mb-4 bg-lighter border">
                        <h6 class="mb-3">Yeni Politika / Kural Ekle</h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" id="policy_icon_input" class="form-control" placeholder="İkon (Örn: bx-time)">
                                <small class="text-muted">Örn: bx-time, bx-no-entry, bx-x-circle</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="policy_title_input" class="form-control" placeholder="Başlık (Örn: Giriş Saati)">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="policy_desc_input" class="form-control" placeholder="Açıklama (Örn: 14:00 sonrası)">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary w-100" id="add-policy-btn">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Eklenen Politikaların Liste Alanı -->
                    <ul class="list-group" id="policy-list">
                        {{-- JS ile buraya dinamik li ve hidden input'lar basılacak --}}
                    </ul>
                </div>

            </div>
        </div>

        {{-- Alt Butonlar --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">İptal</a>
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-1"></i> Kaydet
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. Otel Tipi Seçimi Kontrolü ---
    const propertyTypeSelect = document.getElementById('property_type_id');
    const singleUnitFields = document.getElementById('single-unit-fields');
    const hotelInfoAlert = document.getElementById('hotel-info-alert');

    function toggleFieldBasedOnType() {
        if (!propertyTypeSelect) return;

        const selectedOption = propertyTypeSelect.options[propertyTypeSelect.selectedIndex];
        
        // Seçim yapılmamışsa varsayılan alanları göster, uyarınızı gizle
        if (!selectedOption || !selectedOption.value) {
            if (singleUnitFields) singleUnitFields.style.display = 'flex';
            if (hotelInfoAlert) hotelInfoAlert.style.display = 'none';
            return;
        }

        // Option üzerindeki data-has-rooms bilgisini okuyoruz
        const hasRooms = selectedOption.getAttribute('data-has-rooms') === 'true';

        if (hasRooms) {
            // Otel / Dorm seçildi: Mülk detayındaki yatak/banyo alanlarını gizle
            if (singleUnitFields) singleUnitFields.style.display = 'none';
            if (hotelInfoAlert) hotelInfoAlert.style.display = 'block';
        } else {
            // Villa / Ev / Kabin seçildi: Yatak/Banyo/Kapasite alanlarını göster
            if (singleUnitFields) singleUnitFields.style.display = 'flex';
            if (hotelInfoAlert) hotelInfoAlert.style.display = 'none';
        }
    }

    if(propertyTypeSelect) {
        propertyTypeSelect.addEventListener('change', toggleFieldBasedOnType);
        toggleFieldBasedOnType(); // Sayfa yüklendiğinde eski veriye/seçime göre çalıştır
    }

    // --- 2. Dinamik Politika (Todo List) Ekleme & Silme ---
    const addPolicyBtn = document.getElementById('add-policy-btn');
    const policyList = document.getElementById('policy-list');
    let policyIndex = 0;

    if(addPolicyBtn) {
        addPolicyBtn.addEventListener('click', function () {
            const iconInput = document.getElementById('policy_icon_input');
            const titleInput = document.getElementById('policy_title_input');
            const descInput = document.getElementById('policy_desc_input');

            const iconVal = iconInput.value.trim() || 'bx-info-circle';
            const titleVal = titleInput.value.trim();
            const descVal = descInput.value.trim();

            if (!titleVal) {
                alert('Lütfen en azından bir politika başlığı girin.');
                return;
            }

            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center mb-2 border rounded shadow-sm';
            
            li.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bx ${iconVal} fs-3 me-3 text-primary"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">${titleVal}</h6>
                        <small class="text-muted">${descVal}</small>
                    </div>
                </div>
                
                <!-- Controller'a Gidecek Gizli Inputlar -->
                <input type="hidden" name="policies[${policyIndex}][icon]" value="${iconVal}">
                <input type="hidden" name="policies[${policyIndex}][title]" value="${titleVal}">
                <input type="hidden" name="policies[${policyIndex}][description]" value="${descVal}">
                
                <button type="button" class="btn btn-sm btn-outline-danger remove-policy-btn">
                    <i class="bx bx-trash"></i>
                </button>
            `;

            policyList.appendChild(li);

            // Inputları temizle
            iconInput.value = '';
            titleInput.value = '';
            descInput.value = '';

            policyIndex++;
        });
    }

    // Liste elemanını kaldırma
    if(policyList) {
        policyList.addEventListener('click', function (e) {
            if (e.target.closest('.remove-policy-btn')) {
                e.target.closest('li').remove();
            }
        });
    }

});
</script>
@endpush