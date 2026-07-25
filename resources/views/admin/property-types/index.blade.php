@extends('admin.layouts.app')

@section('title', 'Emlak Tipleri')
@section('breadcrumb_parent', 'Mülk Yönetimi')
@section('breadcrumb_title', 'Emlak Tipleri')

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
            <h5 class="mb-0">Emlak Tipleri</h5>
            <a href="{{ route('admin.property-types.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Yeni Emlak Tipi Ekle
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>İkon</th>
                        <th>Emlak Tipi Adı</th>
                        <th>Slug</th>
                        <th>Oda Durumu</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($propertyTypes as $type)
                        <tr>
                            <td>
                                @if ($type->icon)
                                    <i class="bx {{ $type->icon }} fs-4 text-primary"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><strong>{{ $type->name }}</strong></td>
                            <td><code>{{ $type->slug }}</code></td>
                            <td>
                                @if ($type->has_rooms)
                                    <span class="badge bg-label-info me-1">Oda Sayılı</span>
                                @else
                                    <span class="badge bg-label-secondary me-1">Odasız</span>
                                @endif
                            </td>
                            <td>
                                @if ($type->is_active)
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                @else
                                    <span class="badge bg-label-danger me-1">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.property-types.edit', $type) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.property-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Bu emlak tipinin durumunu değiştirmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item {{ $type->is_active ? 'text-danger' : 'text-success' }}">
                                                <i class="bx {{ $type->is_active ? 'bx-power-off' : 'bx-check-circle' }} me-1"></i> 
                                                {{ $type->is_active ? 'Pasife Al' : 'Aktife Al' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Henüz hiç emlak tipi eklenmemiş.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-end">
            {{ $propertyTypes->links() }}
        </div>
    </div>

</div>
@endsection