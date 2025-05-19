<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Rooms Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Rooms</li>
    </ol>

    <!-- Room Management Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0" id="totalRooms">0</h4>
                            <div>Total Rooms</div>
                        </div>
                        <i class="fas fa-door-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0" id="availableRooms">0</h4>
                            <div>Available</div>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0" id="occupiedRooms">0</h4>
                            <div>Occupied</div>
                        </div>
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0" id="maintenanceRooms">0</h4>
                            <div>Maintenance</div>
                        </div>
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Management Actions -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Room Management
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="fas fa-plus"></i> Add New Room
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="roomsTable">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Floor</th>
                            <th>Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Room data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <div class="mb-3">
                        <label class="form-label">Room Number</label>
                        <input type="text" class="form-control" name="roomNumber" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Room Type</label>
                        <select class="form-select" name="roomType" required>
                            <option value="">Select Type</option>
                            <option value="standard">Standard</option>
                            <option value="deluxe">Deluxe</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Floor</label>
                        <input type="number" class="form-control" name="floor" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rate per Night</label>
                        <input type="number" class="form-control" name="rate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amenities</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi">
                            <label class="form-check-label">WiFi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="amenities[]" value="tv">
                            <label class="form-check-label">TV</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="amenities[]" value="minibar">
                            <label class="form-check-label">Minibar</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveRoomBtn">Save Room</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRoomForm">
                    <input type="hidden" name="roomId">
                    <!-- Similar fields as add room form -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateRoomBtn">Update Room</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    const roomsTable = new DataTable('#roomsTable', {
        order: [[0, 'asc']],
        pageLength: 10
    });

    // Load rooms data
    loadRooms();

    // Save room event listener
    document.getElementById('saveRoomBtn').addEventListener('click', saveRoom);
    
    // Update room event listener
    document.getElementById('updateRoomBtn').addEventListener('click', updateRoom);
});

function loadRooms() {
    // Fetch rooms data from the server
    fetch('../api/rooms.php')
        .then(response => response.json())
        .then(data => {
            updateRoomStats(data);
            populateRoomsTable(data);
        })
        .catch(error => console.error('Error loading rooms:', error));
}

function updateRoomStats(data) {
    document.getElementById('totalRooms').textContent = data.length;
    document.getElementById('availableRooms').textContent = data.filter(room => room.status === 'available').length;
    document.getElementById('occupiedRooms').textContent = data.filter(room => room.status === 'occupied').length;
    document.getElementById('maintenanceRooms').textContent = data.filter(room => room.status === 'maintenance').length;
}

function populateRoomsTable(data) {
    const tbody = document.querySelector('#roomsTable tbody');
    tbody.innerHTML = '';

    data.forEach(room => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${room.roomNumber}</td>
            <td>${room.type}</td>
            <td><span class="badge bg-${getStatusColor(room.status)}">${room.status}</span></td>
            <td>${room.floor}</td>
            <td>$${room.rate}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editRoom(${room.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteRoom(${room.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function getStatusColor(status) {
    switch(status) {
        case 'available': return 'success';
        case 'occupied': return 'warning';
        case 'maintenance': return 'danger';
        default: return 'secondary';
    }
}

function saveRoom() {
    const form = document.getElementById('addRoomForm');
    const formData = new FormData(form);

    fetch('../api/rooms.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            $('#addRoomModal').modal('hide');
            loadRooms();
            form.reset();
        } else {
            alert('Error saving room: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function editRoom(roomId) {
    fetch(`../api/rooms.php?id=${roomId}`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('editRoomForm');
            // Populate form fields
            form.roomId.value = data.id;
            // ... populate other fields
            $('#editRoomModal').modal('show');
        })
        .catch(error => console.error('Error:', error));
}

function updateRoom() {
    const form = document.getElementById('editRoomForm');
    const formData = new FormData(form);

    fetch('../api/rooms.php', {
        method: 'PUT',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            $('#editRoomModal').modal('hide');
            loadRooms();
        } else {
            alert('Error updating room: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteRoom(roomId) {
    if(confirm('Are you sure you want to delete this room?')) {
        fetch(`../api/rooms.php?id=${roomId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadRooms();
            } else {
                alert('Error deleting room: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>

<!-- <?php require_once '../includes/footer.php'; ?> --> 