<meta name="csrf-token" content="{{ csrf_token() }}">

@foreach($users as $user)
<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
    <strong>{{ $user->name ?? $user->email }}</strong>

    @foreach($permissions as $permission)
        <label style="margin-right:10px;">
            <input type="checkbox"
                data-user="{{ $user->id }}"
                data-permission-id="{{ $permission->id }}"
                {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
            {{ $permission->name }}
        </label>
    @endforeach
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.querySelectorAll('input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', function() {
        axios.post("{{ route('user.permissions.update') }}", {
            user_id: this.dataset.user,
            permission_id: this.dataset.permissionId,
            has_permission: this.checked ? 1 : 0
        }, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        }).then(res => console.log(res.data));
    });
});
</script>
