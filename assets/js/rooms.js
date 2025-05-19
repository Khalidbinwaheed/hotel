document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    const roomsTable = new DataTable('#roomsTable', {
        responsive: true,
        order: [[0, 'asc']],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search rooms..."
        }
    });

    // Load rooms data
    loadRooms();

    // Event Listeners
    document.getElementById('saveRoomBtn').addEventListener('click', saveRoom);
    document.getElementById('updateRoomBtn').addEventListener('click', updateRoom);
    document.getElementById('addRoomForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveRoom();
    });
});

// Load all rooms
function loadRooms() {
    fetch('/hotel/api/rooms.php')
        .then(response => response.json())
        .then(data => {
            updateRoomStats(data);
            populateRoomsTable(data);
        })
        .catch(error => {
            console.error('Error loading rooms:', error);
            showNotification('Error loading rooms', 'error');
        });
}

// Update room statistics
function updateRoomStats(data) {
    const stats = {
        total: data.length,
        available: data.filter(room => room.status === 'available').length,
        occupied: data.filter(room => room.status === 'occupied').length,
        maintenance: data.filter(room => room.status === 'maintenance').length
    };

    document.getElementById('totalRooms').textContent = stats.total;
    document.getElementById('availableRooms').textContent = stats.available;
    document.getElementById('occupiedRooms').textContent = stats.occupied;
    document.getElementById('maintenanceRooms').textContent = stats.maintenance;
}

// Populate rooms table
function populateRoomsTable(data) {
    const tbody = document.querySelector('#roomsTable tbody');
    tbody.innerHTML = '';

    data.forEach(room => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${room.room_number}</td>
            <td>${room.category_name}</td>
            <td><span class="badge bg-${getStatusColor(room.status)}">${room.status}</span></td>
            <td>${room.floor}</td>
            <td>$${room.base_price}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary" onclick="editRoom(${room.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRoom(${room.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Get status color
function getStatusColor(status) {
    switch(status) {
        case 'available': return 'success';
        case 'occupied': return 'warning';
        case 'maintenance': return 'danger';
        case 'cleaning': return 'info';
        default: return 'secondary';
    }
}

// Save new room
function saveRoom() {
    const form = document.getElementById('addRoomForm');
    const formData = new FormData(form);
    const data = {
        room_number: formData.get('roomNumber'),
        category_id: formData.get('roomType'),
        floor: formData.get('floor'),
        status: 'available',
        notes: ''
    };

    fetch('/hotel/api/rooms.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            $('#addRoomModal').modal('hide');
            form.reset();
            loadRooms();
            showNotification('Room added successfully', 'success');
        } else {
            showNotification(data.message || 'Error adding room', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding room', 'error');
    });
}

// Edit room
function editRoom(roomId) {
    fetch(`/hotel/api/rooms.php?id=${roomId}`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('editRoomForm');
            form.querySelector('[name="roomId"]').value = data.id;
            form.querySelector('[name="roomNumber"]').value = data.room_number;
            form.querySelector('[name="roomType"]').value = data.category_id;
            form.querySelector('[name="floor"]').value = data.floor;
            $('#editRoomModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading room details', 'error');
        });
}

// Update room
function updateRoom() {
    const form = document.getElementById('editRoomForm');
    const formData = new FormData(form);
    const data = {
        id: formData.get('roomId'),
        room_number: formData.get('roomNumber'),
        category_id: formData.get('roomType'),
        floor: formData.get('floor'),
        status: formData.get('status') || 'available',
        notes: formData.get('notes') || ''
    };

    fetch('/hotel/api/rooms.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            $('#editRoomModal').modal('hide');
            loadRooms();
            showNotification('Room updated successfully', 'success');
        } else {
            showNotification(data.message || 'Error updating room', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating room', 'error');
    });
}

// Delete room
function deleteRoom(roomId) {
    if(confirm('Are you sure you want to delete this room?')) {
        fetch(`/hotel/api/rooms.php?id=${roomId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadRooms();
                showNotification('Room deleted successfully', 'success');
            } else {
                showNotification(data.message || 'Error deleting room', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting room', 'error');
        });
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    const container = document.getElementById('toastContainer');
    container.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
} 