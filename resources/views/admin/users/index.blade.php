@extends('layouts.admin')
@section('title', 'Quản Lý Người Dùng')
@section('page-title', 'Quản Lý Người Dùng')

@section('content')
<div class="card table-card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-people me-2"></i>Danh Sách Người Dùng</span>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Tìm kiếm..." value="{{ request('search') }}">
            <select name="role" class="form-select form-select-sm" style="width:130px;">
                <option value="">Tất cả</option>
                <option value="customer" {{ request('role')==='customer' ? 'selected' : '' }}>Khách hàng</option>
                <option value="admin"    {{ request('role')==='admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button class="btn btn-sm btn-dark"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Người dùng</th>
                    <th>Username</th>
                    <th class="text-center">Đơn hàng</th>
                    <th>Vai trò</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->avatar)
                                <img src="{{ str_starts_with($user->avatar,'http') ? $user->avatar : asset('storage/'.$user->avatar) }}"
                                     width="36" height="36" class="rounded-circle object-fit-cover">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center
                                            justify-content-center text-white fw-bold"
                                     style="width:36px; height:36px; font-size:14px;">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="mb-0 fw-semibold small">{{ $user->name }}</p>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $user->username }}</code></td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">{{ $user->orders_count }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.role', $user->id) }}">
                            @csrf
                            <select name="role" class="form-select form-select-sm"
                                    style="width:120px;" onchange="this.form.submit()">
                                <option value="customer" {{ $user->role==='customer' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="admin"    {{ $user->role==='admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->is_active ? 'Hoạt động' : 'Bị khoá' }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($user->id !== auth()->id())
                        <form method="POST"
                              action="{{ route('admin.users.toggle', $user->id) }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                    onclick="return confirm('{{ $user->is_active ? 'Khoá' : 'Mở khoá' }} tài khoản này?')">
                                <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                                {{ $user->is_active ? 'Khoá' : 'Mở khoá' }}
                            </button>
                        </form>
                        @else
                            <span class="text-muted small">Tài khoản bạn</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Không có người dùng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $users->links() }}</div>
</div>
@endsection