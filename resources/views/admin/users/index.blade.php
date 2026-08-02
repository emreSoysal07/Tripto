@extends('admin.layouts.app')

@section('title', 'Kullanıcı Listesi - Tripto Admin')

@section('content')
<div class="flex-grow-1">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Yönetim /</span> Kullanıcılar</h4>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Kullanıcı Listesi</h5>
      <span class="badge bg-label-primary">Toplam: {{ $users->total() }} Kullanıcı</span>
    </div>
    
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Ad Soyad</th>
            <th>E-Posta</th>
            <th>Kayıt Tarihi</th>
            <th class="text-end">İşlemler</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($users as $user)
            <tr>
              <td><strong>#{{ $user->id }}</strong></td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-xs me-2">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                      {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                  </div>
                  <span>{{ $user->name }}</span>
                </div>
              </td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->created_at ? $user->created_at->format('d.m.Y H:i') : '-' }}</td>
              <td class="text-end">
                <!-- Silme Formu -->
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-icon btn-label-danger" title="Kullanıcıyı Sil">
                    <i class="bx bx-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4">Henüz kayıtlı kullanıcı bulunmamaktadır.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Sayfalandırma -->
    @if ($users->hasPages())
      <div class="card-footer d-flex justify-content-center">
        {{ $users->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</div>
@endsection