document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const appContainer = document.querySelector('.app-container');
    
    if (sidebarToggle && appContainer) {
        sidebarToggle.addEventListener('click', function() {
            appContainer.classList.toggle('sidebar-collapsed');
        });
    }
    
    // Dropdown functionality
    const dropdownToggles = document.querySelectorAll('.user-dropdown-btn, .notification-btn');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            
            // Close all other dropdowns
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    const otherDropdown = otherToggle.nextElementSibling;
                    if (otherDropdown) {
                        otherDropdown.classList.remove('active');
                    }
                }
            });
            
            dropdown.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.user-dropdown-menu, .notification-dropdown').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });
    
    // Room status toggle
    const roomStatusButtons = document.querySelectorAll('.room-status-toggle');
    
    roomStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.dataset.room;
            const status = this.dataset.status;
            
            // Here you would typically make an AJAX request to update the room status
            console.log(`Updating room ${roomId} to status: ${status}`);
            
            // For demo purposes, toggle the button state
            const parent = this.closest('.room-card');
            parent.querySelector('.current-status').textContent = status;
            
            // Toggle active status on buttons
            parent.querySelectorAll('.room-status-toggle').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // Date range pickers for reports
    const dateRangePickers = document.querySelectorAll('.date-range-picker');
    
    dateRangePickers.forEach(picker => {
        const startDate = picker.querySelector('.start-date');
        const endDate = picker.querySelector('.end-date');
        
        if (startDate && endDate) {
            startDate.addEventListener('change', function() {
                endDate.min = this.value;
                if (endDate.value && new Date(endDate.value) < new Date(this.value)) {
                    endDate.value = this.value;
                }
            });
            
            endDate.addEventListener('change', function() {
                startDate.max = this.value;
            });
        }
    });
    
    // Global search functionality
    const globalSearch = document.getElementById('global-search');
    
    if (globalSearch) {
        globalSearch.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = `index.php?page=search&term=${encodeURIComponent(searchTerm)}`;
                }
            }
        });
    }
    
    // Invoice print functionality
    const printInvoiceBtn = document.getElementById('print-invoice');
    
    if (printInvoiceBtn) {
        printInvoiceBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('form.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
    
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            document.body.classList.toggle('mobile-menu-open');
        });
    }
    
    // Room type change handler (for reservation form)
    const roomTypeSelect = document.getElementById('room_type');
    const roomSelect = document.getElementById('room_id');
    
    if (roomTypeSelect && roomSelect) {
        roomTypeSelect.addEventListener('change', function() {
            const categoryId = this.value;
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            
            if (categoryId && checkIn && checkOut) {
                // Here you would typically make an AJAX request to get available rooms
                fetch(`api/available_rooms.php?category=${categoryId}&check_in=${checkIn}&check_out=${checkOut}`)
                    .then(response => response.json())
                    .then(rooms => {
                        roomSelect.innerHTML = '<option value="">Select a room</option>';
                        
                        rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = `${room.room_number} - ${room.category_name} - ${room.base_price}`;
                            roomSelect.appendChild(option);
                        });
                        
                        roomSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching rooms:', error);
                    });
            } else {
                roomSelect.innerHTML = '<option value="">Select a room</option>';
                roomSelect.disabled = true;
            }
        });
    }
    
    // Guest search functionality
    const guestSearch = document.getElementById('guest-search');
    const guestResults = document.getElementById('guest-search-results');
    
    if (guestSearch && guestResults) {
        guestSearch.addEventListener('keyup', function() {
            const searchTerm = this.value.trim();
            
            if (searchTerm.length > 2) {
                // Here you would typically make an AJAX request to search for guests
                fetch(`api/search_guests.php?term=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(guests => {
                        guestResults.innerHTML = '';
                        
                        if (guests.length > 0) {
                            guests.forEach(guest => {
                                const div = document.createElement('div');
                                div.classList.add('guest-result');
                                div.innerHTML = `
                                    <div>${guest.first_name} ${guest.last_name}</div>
                                    <div>${guest.email}</div>
                                    <div>${guest.phone}</div>
                                    <button type="button" class="btn btn-sm btn-primary select-guest" data-id="${guest.id}">Select</button>
                                `;
                                guestResults.appendChild(div);
                            });
                            
                            guestResults.style.display = 'block';
                            
                            // Add event listeners to select guest buttons
                            document.querySelectorAll('.select-guest').forEach(button => {
                                button.addEventListener('click', function() {
                                    const guestId = this.dataset.id;
                                    document.getElementById('guest_id').value = guestId;
                                    
                                    // Here you would typically make an AJAX request to get guest details
                                    fetch(`api/guest_details.php?id=${guestId}`)
                                        .then(response => response.json())
                                        .then(guest => {
                                            document.getElementById('guest_name').value = `${guest.first_name} ${guest.last_name}`;
                                            document.getElementById('guest_email').value = guest.email;
                                            document.getElementById('guest_phone').value = guest.phone;
                                            
                                            guestResults.style.display = 'none';
                                            guestSearch.value = '';
                                        })
                                        .catch(error => {
                                            console.error('Error fetching guest details:', error);
                                        });
                                });
                            });
                        } else {
                            guestResults.innerHTML = '<div class="no-results">No guests found. <a href="index.php?page=new_guest">Add new guest</a></div>';
                            guestResults.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error searching guests:', error);
                    });
            } else {
                guestResults.style.display = 'none';
            }
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!guestSearch.contains(e.target) && !guestResults.contains(e.target)) {
                guestResults.style.display = 'none';
            }
        });
    }
});