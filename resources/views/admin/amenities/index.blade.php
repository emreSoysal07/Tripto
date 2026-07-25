@extends('admin.layouts.app')

@section('title', 'Olanaklar')
@section('breadcrumb_parent', 'Mülk Yönetimi')
@section('breadcrumb_title', 'Olanaklar')

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
            <h5 class="mb-0">Olanaklar</h5>
            <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Yeni Olanak Ekle
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>İkon</th>
                        <th>Olanak Adı</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($amenities as $amenity)
                        <tr>
                            <td>
                                @if ($amenity->icon)
                                    <i class="bx {{ $amenity->icon }} fs-4 text-primary"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><strong>{{ $amenity->name }}</strong></td>
                            <td>
                                @if ($amenity->is_active)
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
                                        <a class="dropdown-item" href="{{ route('admin.amenities.edit', $amenity) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.amenities.destroy', $amenity) }}" method="POST" onsubmit="return confirm('Bu olanağın durumunu değiştirmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item {{ $amenity->is_active ? 'text-danger' : 'text-success' }}">
                                                <i class="bx {{ $amenity->is_active ? 'bx-power-off' : 'bx-check-circle' }} me-1"></i> 
                                                {{ $amenity->is_active ? 'Pasife Al' : 'Aktife Al' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Henüz hiç olanak eklenmemiş.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-end">
            {{ $amenities->links() }}
        </div>
    </div>

</div>
@endsection