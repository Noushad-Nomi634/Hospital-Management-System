@extends('layouts.app')

@section('title')
    Role Permissions
@endsection

@push('css')
    <style>
        .role-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            background: #f9f9f9;
        }
        .permission-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .permission-grid label {
            background: #eee;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')

<x-page-title title="Role Permissions" subtitle="Management" />

<div class="row">
    <div class="col-xl-12">
        @foreach($roles as $role)
            <div class="role-box">
                <strong>{{ $role->name }}</strong>

                @php
                    $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                @endphp

                <div class="permission-grid">
                   @foreach($permissions->unique('name') as $permission)
    <label>
        <input type="checkbox"
               data-role="{{ $role->id }}"
               data-permission="{{ $permission->name }}"
               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
        {{ $permission->name }}
    </label>
@endforeach

                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.querySelectorAll('input[type=checkbox]').forEach(cb => {
            cb.addEventListener('change', function() {
                axios.post("{{ route('role.permissions.update') }}", {
                    role_id: this.dataset.role,
                    permission_name: this.dataset.permission,
                    has_permission: this.checked ? 1 : 0
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                })
                .then(response => {
                    console.log('Permission updated');
                })
                .catch(error => {
                    console.error('Error updating permission', error);
                });
            });
        });
    </script>
@endpush
