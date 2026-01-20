<!DOCTYPE html>
<html>
<head>
<title>Role Permissions</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.role-box{border:1px solid #ccc;padding:15px;margin-bottom:15px}
.permission-grid{display:flex;flex-wrap:wrap;gap:10px}
.permission-grid label{background:#eee;padding:5px 10px;border-radius:6px}
</style>
</head>
<body>

<h2>Role Permission Management</h2>

@foreach($roles as $role)
<div class="role-box">
    <strong>{{ $role->name }}</strong>

    @php
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
    @endphp

    <div class="permission-grid">
        @foreach($permissions as $permission)
            @if($permission->guard_name == $role->guard_name)
            <label>
                <input type="checkbox"
                    data-role="{{ $role->id }}"
                    data-permission="{{ $permission->name }}"
                    {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                {{ $permission->name }}
            </label>
            @endif
        @endforeach
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.querySelectorAll('input[type=checkbox]').forEach(cb=>{
    cb.addEventListener('change', function(){
        axios.post("{{ route('role.permissions.update') }}", {
            role_id: this.dataset.role,
            permission_name: this.dataset.permission,
            has_permission: this.checked ? 1 : 0
        },{
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
        });
    });
});
</script>

</body>
</html>
