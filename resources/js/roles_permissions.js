// Modal open/close
window.openRoleModal = () => $('#roleModal').removeClass('hidden');
window.closeRoleModal = () => $('#roleModal').addClass('hidden');
window.openPermissionModal = () => $('#permissionModal').removeClass('hidden');
window.closePermissionModal = () => $('#permissionModal').addClass('hidden');
window.openAssignModal = (roleId, permissions) => {
    $('#assignRoleId').val(roleId);
    $('#assignForm input[type=checkbox]').prop('checked', false); 
    permissions.forEach(id => {
        $('#assignForm input[type=checkbox][value="' + id + '"]').prop('checked', true);
    });

    $('#assignModal').removeClass('hidden');
};

window.closeAssignModal = () => $('#assignModal').addClass('hidden');

$(document).ready(function() {

    const table = $('#roleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: rolesAjaxUrl,
        order: [[0, 'desc']],
        columns: [
            { data: 'name', name: 'name' },
            { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        createdRow: function(row) {
            $(row).find('td').addClass('border-b border-gray-200');
        }
    });

    // Create Role
$('#createRoleForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: roleStoreUrl,
        type: 'POST',
        data: $(this).serialize(),
        success: function() {
            closeRoleModal();
            $('#createRoleForm')[0].reset();
            $('#roleTable').DataTable().ajax.reload();
        },
        error: function(xhr) {
            alert('Error creating role: ' + xhr.responseText);
        }
    });
});


    // Create Permission
    $('#createPermissionForm').on('submit', function(e) {
        e.preventDefault();
        $.post(permissionStoreUrl, $(this).serialize(), function() {
            closePermissionModal();
            table.ajax.reload();
        });
    });

    // Assign Permissions
    $('#assignForm').on('submit', function(e) {
        e.preventDefault();
        let roleId = $('#assignRoleId').val();
        let url = assignUrl.replace(':id', roleId);
        $.post(url, $(this).serialize(), function() {
            closeAssignModal();
            table.ajax.reload();
        });
    });
    // Modal open/close helpers (add these if not present)
window.openEditRoleModal = function(roleId) {
    // fetch role data
    $.get(baseRoleUrl + "/" + roleId + "/edit", function(res) {
        $('#editRoleId').val(res.role.id);
        $('#editRoleName').val(res.role.name);
        // Uncheck all then check those from response.permissions
        $('#editRoleForm input[type=checkbox]').prop('checked', false);
        if(res.permissions && res.permissions.length) {
            res.permissions.forEach(function(id) {
                $('#editRoleForm input[type=checkbox][value="' + id + '"]').prop('checked', true);
            });
        }
        $('#editRoleModal').removeClass('hidden');
    }).fail(function(xhr){
        alert('Failed to fetch role data');
    });
};

window.closeEditRoleModal = function() { $('#editRoleModal').addClass('hidden'); };

// Edit Role submit
$('#editRoleForm').on('submit', function(e) {
    e.preventDefault();

    let roleId = $('#editRoleId').val();
    let url = window.appUrls.roles + '/' + roleId; // ✅ use Blade-injected variable
    let data = $(this).serialize();

    $.ajax({
        url: url,
        type: 'POST',
        data: data + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            closeEditRoleModal();
            $('#roleTable').DataTable().ajax.reload();
        },
        error: function(xhr) {
            alert('Error updating role: ' + xhr.responseText);
        }
    });
});


// Edit Permission modal open
window.openEditPermissionModal = function(permissionId) {
    $.get(basePermissionUrl + "/" + permissionId + "/edit", function(res) {
        $('#editPermissionId').val(res.permission.id);
        $('#editPermissionName').val(res.permission.name);
        $('#editPermissionModal').removeClass('hidden');
    }).fail(function(){ alert('Failed to fetch permission'); });
};
window.closeEditPermissionModal = function(){ $('#editPermissionModal').addClass('hidden'); };

// Edit Permission submit
$('#editPermissionForm').on('submit', function(e){
    e.preventDefault();
    let permissionId = $('#editPermissionId').val();
    let url = "{{ url('permissions') }}/" + permissionId;
    let data = $(this).serialize();

    $.ajax({
        url: url,
        type: 'POST',
        data: data + '&_method=PUT',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(){
            closeEditPermissionModal();
            $('#roleTable').DataTable().ajax.reload();
        },
        error: function(xhr){
            alert('Error updating permission');
        }
    });
});

// Delete modal logic
window.openDeleteModal = function(type, id, name) {
    $('#deleteMessage').text(`Are you sure you want to delete ${type} "${name}"? This action cannot be undone.`);
    $('#deleteModal').data('type', type).data('id', id).removeClass('hidden');
};

window.closeDeleteModal = function() {
    $('#deleteModal').addClass('hidden');
};

$('#confirmDeleteBtn').on('click', function() {
    let type = $('#deleteModal').data('type');
    let id = $('#deleteModal').data('id');

    // ✅ use the Blade-provided variable here
    let url = window.deleteUrls[type] + '/' + id;

    $.ajax({
        url: url,
        type: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            closeDeleteModal();
            if (type === 'role') {
                $('#roleTable').DataTable().ajax.reload();
            } else {
                $('#permissionTable').DataTable().ajax.reload();
            }
        },
        error: function() {
            alert('Delete failed');
        }
    });
});


});
