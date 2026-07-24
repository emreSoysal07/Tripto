@extends('admin.layouts.app')

@section('title', 'Mülk Düzenle')
@section('breadcrumb_parent', 'Mülkler')
@section('breadcrumb_title', 'Mülk Düzenle: ' . $property->title)

@section('content')

@push('styles')
<style>
    /* Sneat Temasına Özel Stiller & Düzeltmeler */
    .tab-content {
        width: 100%;
    }
    html {
        overflow-y: scroll;
    }
    .custom-alert {
        border-left: 4px solid #ff3e1d;
    }
    /* Sürükle Bırak Resim Yükleme Alanı */
    .image-dropzone {
        border: 2px dashed #d9dee3;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: border-color 0.3s, background-color 0.3s;
    }
    .image-dropzone:hover {
        border-color: #696cff;
        background-color: #f3f4ff;
    }
    .preview-image-wrapper {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    .preview-image-wrapper img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #d9dee3;
    }
    /* Silinmek üzere seçilen resimlerin görsel vurgusu */
    .delete-marked {
        opacity: 0.5;
        border: 2px solid #ff3e1d !important;
    }
</style>
@endpush

<div class="flex-grow-1">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Listeye Dön
        </a>
    </div>

    {{-- GENERAL VALIDATION ALERT --}}
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

    <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data" id="propertyForm">
        @csrf
        @method('PUT')

        <!-- Sneat Filled Tabs Bileşeni -->
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills nav-fill mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active d-flex align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-general">
                        <i class="tf-icons bx bx-home me-1"></i> Genel Bilgiler
                        @if($errors->hasAny(['title', 'property_type_id', 'price_per_night', 'status', 'capacity', 'address', 'city', 'country', 'description']))
                            <span class="badge rounded-pill bg-danger ms-2" title="Bu sekmede hata var">!</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link d-flex align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-images">
                        <i class="tf-icons bx bx-image-add me-1"></i> Resimler
                        @if($errors->has('images') || $errors->has('images.*'))
                            <span class="badge rounded-pill bg-danger ms-2" title="Bu sekmede hata var">!</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link d-flex align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-amenities">
                        <i class="tf-icons bx bx-list-check me-1"></i> Olanaklar
                        @if($errors->has('amenities'))
                            <span class="badge rounded-pill bg-danger ms-2" title="Bu sekmede hata var">!</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link d-flex align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-policies">
                        <i class="tf-icons bx bx-shield-quarter me-1"></i> Politikalar
                        @if($errors->has('policies'))
                            <span class="badge rounded-pill bg-danger ms-2" title="Bu sekmede hata var">!</span>
                        @endif
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
                                <strong>Otel / Yurt Tipi Seçildi:</strong> Oda türleri ve odalar mülk detay sayfasından yönetilmektedir.
                            </div>
                        </div>
                    </div>

                    {{-- Satır 1: Başlık + Mülk Tipi --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label" for="title">Başlık <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $property->title) }}" placeholder="Bodrum Deniz Manzaralı Villa" required />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="property_type_id">Mülk Tipi <span class="text-danger">*</span></label>
                            <select id="property_type_id" name="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror" required>
                                <option value="">Seçiniz</option>
                                @foreach ($propertyTypes as $type)
                                    <option value="{{ $type->id }}" 
                                            data-slug="{{ $type->slug }}" 
                                            data-has-rooms="{{ $type->has_rooms ? 'true' : 'false' }}"
                                            {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>
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
                            <label class="form-label" for="price_per_night">Gecelik Fiyat <span class="text-danger">*</span></label>
                            <div class="input-group @error('price_per_night') is-invalid @enderror">
                                <span class="input-group-text">₺</span>
                                <input type="number" step="0.01" id="price_per_night" name="price_per_night" class="form-control @error('price_per_night') is-invalid @enderror" value="{{ old('price_per_night', $property->price_per_night) }}" placeholder="1500" required />
                            </div>
                            @error('price_per_night')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="status">Durum</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', $property->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status', $property->status) == 'published' ? 'selected' : '' }}>Yayında</option>
                                <option value="inactive" {{ old('status', $property->status) == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Satır 3: Kapasite + Yatak Odası + Banyo --}}
                    <div class="row g-3 mb-3" id="single-unit-fields">
                        <div class="col-md-4">
                            <label class="form-label" for="capacity">Kapasite (Kişi)</label>
                            <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $property->capacity) }}" placeholder="4" min="1" />
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="bedrooms">Yatak Odası Sayısı</label>
                            <input type="number" id="bedrooms" name="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" value="{{ old('bedrooms', $property->bedrooms) }}" placeholder="2" min="0" />
                            @error('bedrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="bathrooms">Banyo Sayısı</label>
                            <input type="number" id="bathrooms" name="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" value="{{ old('bathrooms', $property->bathrooms) }}" placeholder="1" min="0" />
                            @error('bathrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Satır 4: Adres + Şehir + Ülke --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="address">Adres</label>
                            <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $property->address) }}" placeholder="Mahalle, cadde, no..." />
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="city">Şehir</label>
                            <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $property->city) }}" placeholder="Muğla" />
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="country">Ülke</label>
                            <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $property->country ?? 'Türkiye') }}" />
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Satır 5: Açıklama --}}
                    <div class="mb-3">
                        <label class="form-label" for="description">Açıklama</label>
                        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Mülk hakkında detaylı açıklama...">{{ old('description', $property->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 2. TAB: RESİMLER -->
                <div class="tab-pane fade" id="tab-images" role="tabpanel">
                    
                    {{-- Mevcut Görseller --}}
                    @if($property->images && $property->images->count() > 0)
                        <h6 class="mb-3">Mevcut Görseller</h6>
                        <div class="row g-3 mb-4">
                            @foreach($property->images as $img)
                                <div class="col-md-3 col-6">
                                    <div class="card border h-100 position-relative existing-img-card" id="card_img_{{ $img->id }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top" style="height: 140px; object-fit: cover;" alt="Görsel">
                                        <div class="card-body p-2 text-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input delete-img-checkbox" type="checkbox" name="delete_images[]" value="{{ $img->id }}" id="del_img_{{ $img->id }}" data-card="card_img_{{ $img->id }}">
                                                <label class="form-check-label text-danger fw-semibold" for="del_img_{{ $img->id }}">
                                                    Sil
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-4">
                    @endif

                    {{-- Yeni Görsel Yükleme --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="images">Yeni Görsel Ekle</label>
                        
                        <!-- Özel Drag & Drop Kutusu -->
                        <div class="image-dropzone mb-3" onclick="document.getElementById('images').click();">
                            <i class="bx bx-cloud-upload fs-1 text-primary mb-2"></i>
                            <h5>Görselleri buraya tıklayarak seçin</h5>
                            <p class="text-muted mb-0">PNG, JPG, WEBP formatları desteklenir (Maks: 2MB / adet)</p>
                        </div>

                        <input type="file" id="images" name="images[]" class="form-control d-none @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" multiple accept="image/*">
                        
                        <!-- Önizleme Konteynırı -->
                        <div id="image-preview-container" class="d-flex flex-wrap gap-2 mt-3"></div>

                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 3. TAB: OLANAKLAR -->
                <div class="tab-pane fade" id="tab-amenities" role="tabpanel">
                    <p class="text-muted mb-3">Bu mülkte sunulan olanakları güncelleyin:</p>
                    <div class="row g-3">
                        @if(isset($amenities) && $amenities->count() > 0)
                            @php
                                $selectedAmenities = old('amenities', $property->amenities ? $property->amenities->pluck('id')->toArray() : []);
                            @endphp
                            @foreach($amenities as $amenity)
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check custom-option custom-option-basic p-0">
                                        <label class="form-check-label custom-option-content p-3 border rounded cursor-pointer d-flex align-items-center w-100" for="amenity_{{ $amenity->id }}">
                                            <input class="form-check-input mt-0 me-2" style="margin-left: 0;" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}>
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="bx {{ $amenity->icon ?? 'bx-check-circle' }} fs-5 text-primary"></i>
                                                <span class="fw-semibold">{{ $amenity->name }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">Sistemde kayıtlı bir olanak bulunamadı.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 4. TAB: POLİTİKALAR -->
                <div class="tab-pane fade" id="tab-policies" role="tabpanel">
                    <div class="card p-3 mb-4 bg-lighter border">
                        <h6 class="mb-3" id="policy-form-title">Yeni Politika / Kural Ekle</h6>
                        <input type="hidden" id="editing_policy_index" value="-1">
                        
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" id="policy_icon_input" class="form-control" placeholder="İkon (bx-time)">
                                <small class="text-muted">Boxicons kodu (Örn: bx-time)</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="policy_title_input" class="form-control" placeholder="Başlık (Örn: Giriş Saati)">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="policy_desc_input" class="form-control" placeholder="Açıklama (Örn: 14:00 sonrası)">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary w-100" id="save-policy-btn">
                                    <i class="bx bx-plus" id="policy-btn-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group" id="policy-list">
                        {{-- JS Tarafından Dinamik Olarak Doldurulacak --}}
                    </ul>
                </div>

            </div>
        </div>

        {{-- Alt Butonlar --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">İptal</a>
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-1"></i> Değişiklikleri Kaydet
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. Otel Tipi Seçimi Kontrolü ---
    const selectEl = document.getElementById('property_type_id');
    const singleUnitFields = document.getElementById('single-unit-fields');
    const hotelInfoAlert = document.getElementById('hotel-info-alert');

    function runToggle() {
        if (!selectEl) return;

        const selectedOption = selectEl.options[selectEl.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            if (singleUnitFields) singleUnitFields.style.display = 'flex';
            if (hotelInfoAlert) hotelInfoAlert.style.display = 'none';
            return;
        }

        const hasRooms = selectedOption.getAttribute('data-has-rooms') === 'true';

        if (hasRooms) {
            if (singleUnitFields) singleUnitFields.style.display = 'none';
            if (hotelInfoAlert) hotelInfoAlert.style.display = 'block';
        } else {
            if (singleUnitFields) singleUnitFields.style.display = 'flex';
            if (hotelInfoAlert) hotelInfoAlert.style.display = 'none';
        }
    }

    if (selectEl) {
        selectEl.addEventListener('change', runToggle);
        runToggle();
    }

    // --- 2. Görsel Silme İşaretleme Görsel Efekti ---
    const deleteCheckboxes = document.querySelectorAll('.delete-img-checkbox');
    deleteCheckboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            const cardId = this.getAttribute('data-card');
            const cardEl = document.getElementById(cardId);
            if(cardEl) {
                if(this.checked) {
                    cardEl.classList.add('delete-marked');
                } else {
                    cardEl.classList.remove('delete-marked');
                }
            }
        });
    });

    // --- 3. Dinamik Yeni Resim Önizleme UI ---
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');

    if(imageInput && previewContainer) {
        imageInput.addEventListener('change', function(e) {
            previewContainer.innerHTML = '';
            const files = e.target.files;

            if(files) {
                Array.from(files).forEach((file) => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'preview-image-wrapper';
                        wrapper.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }

    // --- 4. Dinamik Politika (Edit/Add/Delete Mantığı) ---
    const savePolicyBtn = document.getElementById('save-policy-btn');
    const policyList = document.getElementById('policy-list');
    let policyIndex = 0;

    function renderPolicyItem(icon, title, desc) {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center mb-2 border rounded shadow-sm policy-item';
        li.setAttribute('data-index', policyIndex);
        
        li.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bx ${icon} fs-3 me-3 text-primary policy-icon-disp"></i>
                <div>
                    <h6 class="mb-0 fw-bold policy-title-disp">${title}</h6>
                    <small class="text-muted policy-desc-disp">${desc}</small>
                </div>
            </div>
            
            <input type="hidden" name="policies[${policyIndex}][icon]" class="input-icon" value="${icon}">
            <input type="hidden" name="policies[${policyIndex}][title]" class="input-title" value="${title}">
            <input type="hidden" name="policies[${policyIndex}][description]" class="input-desc" value="${desc}">
            
            <div>
                <button type="button" class="btn btn-sm btn-outline-warning edit-policy-btn me-1">
                    <i class="bx bx-pencil"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger remove-policy-btn">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;

        policyList.appendChild(li);
        policyIndex++;
    }

    function resetPolicyForm() {
        document.getElementById('policy_icon_input').value = '';
        document.getElementById('policy_title_input').value = '';
        document.getElementById('policy_desc_input').value = '';
        document.getElementById('editing_policy_index').value = '-1';
        document.getElementById('policy-form-title').innerText = 'Yeni Politika / Kural Ekle';
        savePolicyBtn.className = 'btn btn-primary w-100';
        document.getElementById('policy-btn-icon').className = 'bx bx-plus';
    }

    // Başlangıç Yüklemesi: Hata Sonrası (Old) ya da DB'den Gelen Veri
    @if(old('policies'))
        const initialPolicies = @json(old('policies'));
        Object.values(initialPolicies).forEach(p => {
            if(p.title) renderPolicyItem(p.icon || 'bx-info-circle', p.title, p.description || '');
        });
    @elseif(isset($property) && $property->policies)
        const initialPolicies = @json($property->policies);
        initialPolicies.forEach(p => {
            if(p.title) renderPolicyItem(p.icon || 'bx-info-circle', p.title, p.description || '');
        });
    @endif

    // Ekle veya Güncelle Butonu Tıklaması
    if(savePolicyBtn) {
        savePolicyBtn.addEventListener('click', function () {
            const iconVal = document.getElementById('policy_icon_input').value.trim() || 'bx-info-circle';
            const titleVal = document.getElementById('policy_title_input').value.trim();
            const descVal = document.getElementById('policy_desc_input').value.trim();
            const editIndex = document.getElementById('editing_policy_index').value;

            if (!titleVal) {
                alert('Lütfen en azından bir başlık girin.');
                return;
            }

            if (editIndex === "-1") {
                // Yeni Ekleme
                renderPolicyItem(iconVal, titleVal, descVal);
            } else {
                // Düzenleme (Mevcut Elemanı Güncelle)
                const item = policyList.querySelector(`.policy-item[data-index="${editIndex}"]`);
                if(item) {
                    item.querySelector('.policy-icon-disp').className = `bx ${iconVal} fs-3 me-3 text-primary policy-icon-disp`;
                    item.querySelector('.policy-title-disp').innerText = titleVal;
                    item.querySelector('.policy-desc-disp').innerText = descVal;

                    item.querySelector('.input-icon').value = iconVal;
                    item.querySelector('.input-title').value = titleVal;
                    item.querySelector('.input-desc').value = descVal;
                }
            }

            resetPolicyForm();
        });
    }

    // Liste İçi Tıklama Olayları (Düzenle ve Sil)
    if(policyList) {
        policyList.addEventListener('click', function(e) {
            // Düzenleme Butonu
            const editBtn = e.target.closest('.edit-policy-btn');
            if(editBtn) {
                const item = editBtn.closest('.policy-item');
                const idx = item.getAttribute('data-index');

                document.getElementById('policy_icon_input').value = item.querySelector('.input-icon').value;
                document.getElementById('policy_title_input').value = item.querySelector('.input-title').value;
                document.getElementById('policy_desc_input').value = item.querySelector('.input-desc').value;

                document.getElementById('editing_policy_index').value = idx;
                document.getElementById('policy-form-title').innerText = 'Politikayı Düzenle';
                savePolicyBtn.className = 'btn btn-success w-100';
                document.getElementById('policy-btn-icon').className = 'bx bx-check';
                return;
            }

            // Silme Butonu
            const removeBtn = e.target.closest('.remove-policy-btn');
            if(removeBtn) {
                removeBtn.closest('li').remove();
            }
        });
    }

});
</script>
@endpush