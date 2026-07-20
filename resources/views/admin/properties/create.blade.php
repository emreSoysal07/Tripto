@extends('admin.layouts.app')

@section('title', 'Yeni Mülk Ekle')
@section('breadcrumb_parent', 'Mülkler')
@section('breadcrumb_title', 'Yeni Mülk Ekle')

@section('content')
<div class="flex-grow-1">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Yeni Mülk Ekle</h5>
            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Listeye Dön
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.properties.store') }}" method="POST">
                @csrf

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
                                <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('property_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Satır 2: Açıklama (tek başına, geniş) --}}
                <div class="mb-3">
                    <label class="form-label" for="description">Açıklama</label>
                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Mülk hakkında detaylı açıklama...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Satır 3: Fiyat + Durum --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="price_per_night">Gecelik Fiyat</label>
                        <div class="input-group @error('price_per_night') is-invalid @enderror">
                            <span class="input-group-text">₺</span>
                            <input type="number" id="price_per_night" name="price_per_night" class="form-control @error('price_per_night') is-invalid @enderror" value="{{ old('price_per_night') }}" placeholder="1500" />
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

                {{-- Satır 4: Kapasite + Yatak Odası + Banyo --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="capacity">Kapasite (Kişi)</label>
                        <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" placeholder="4" />
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="bedrooms">Yatak Odası Sayısı</label>
                        <input type="number" id="bedrooms" name="bedrooms" class="form-control" value="{{ old('bedrooms') }}" placeholder="2" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="bathrooms">Banyo Sayısı</label>
                        <input type="number" id="bathrooms" name="bathrooms" class="form-control" value="{{ old('bathrooms') }}" placeholder="1" />
                    </div>
                </div>

                {{-- Satır 5: Adres + Şehir + Ülke --}}
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

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection