@extends('admin.layouts.app')

@section('title', 'Mülkler')
@section('breadcrumb_parent', 'Mülkler')
@section('breadcrumb_title', 'Tüm Mülkler')

@section('content')
<div class="flex-grow-1">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mülkler</h5>
            <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Yeni Mülk Ekle
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Başlık</th>
                        <th>Tip</th>
                        <th>Şehir</th>
                        <th>Fiyat</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($properties as $property)
                        <tr>
                            <td><strong>{{ $property->title }}</strong></td>
                            <td>{{ $property->propertyType->name }}</td>
                            <td>{{ $property->city }}</td>
                            <td>{{ number_format($property->price_per_night, 0, ',', '.') }} ₺</td>
                            <td>
                                @if ($property->status === 'published')
                                    <span class="badge bg-label-success me-1">Yayında</span>
                                @elseif ($property->status === 'draft')
                                    <span class="badge bg-label-warning me-1">Taslak</span>
                                @else
                                    <span class="badge bg-label-secondary me-1">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.properties.edit', $property) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Bu mülkü silmek istediğine emin misin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Henüz hiç mülk eklenmemiş.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body flex justify-end">
            {{ $properties->links('vendor.pagination.sneat') }}
        </div>
    </div>

</div>
@endsection