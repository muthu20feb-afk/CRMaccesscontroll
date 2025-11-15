// ✅ Make global functions
window.openModal = function() {
    document.getElementById('createModal').classList.remove('hidden');
};

window.closeModal = function() {
    document.getElementById('createModal').classList.add('hidden');
};

window.openEditModal = function(id, name, email, roleIds) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;

    document.getElementById('editForm').action = `/user/${id}/assign-roles`;
    $('#editRoles').val(roleIds).trigger('change');
};

window.closeEditModal = function() {
    document.getElementById('editModal').classList.add('hidden');
};

// ✅ Initialize Select2 + DataTable after document ready
$(document).ready(function () {

    $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: usersAjaxUrl,
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td').addClass('border-b border-gray-200');
        }
    });
});
