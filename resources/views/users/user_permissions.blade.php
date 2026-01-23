@extends('layouts.app')

@section('title')
    User Permissions
@endsection

@push('css')
    <style>
        .permission-label {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 5px 10px;
            border-radius: 6px;
            background: #eee;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')

<x-page-title title="{{ $user->name }} Permissions" subtitle="Manage individual permissions" />

<div class="user-permissions-box">
    @foreach($permissions->unique('name') as $permission)
        <label class="permission-label">
            <input type="checkbox"
                   data-user="{{ $user->id }}"
                   data-permission-id="{{ $permission->id }}"
                   {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
            {{ $permission->name }}
        </label>
    @endforeach
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.querySelectorAll('input[type=checkbox]').forEach(cb => {
        cb.addEventListener('change', function() {
            const userId = this.dataset.user;
            const permissionId = this.dataset.permissionId;
            const hasPermission = this.checked ? 1 : 0;

            axios.post("{{ route('user.permissions.update') }}", {
                user_id: userId,
                permission_id: permissionId,
                has_permission: hasPermission
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            })
            .then(res => {
                console.log('Permission updated:', res.data);
            })
            .catch(err => {
                console.error('Error updating permission:', err);
                // agar error aaya to checkbox revert
                this.checked = !this.checked;
            });
        });
    });
</script>
@endpush
