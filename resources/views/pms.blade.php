<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpha PMS - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 2rem; }
        .sidebar { height: 100vh; position: fixed; left: 0; top: 0; padding-top: 4rem; width: 200px; background: #f8f9fa; border-right: 1px solid #dee2e6; }
        .main-content { margin-left: 220px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Alpha PMS</a>
        </div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="#" onclick="showSection('dashboard')">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('bookings')">Bookings</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('rooms')">Rooms</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('wholesalers')">Wholesalers</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('accounting')">Accounting</a></li>
        </ul>
    </div>

    <div class="main-content container-fluid">
        <div id="section-dashboard">
            <h2>Dashboard</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Today's Arrivals</h5>
                            <p class="card-text h2" id="count-arrivals">0</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Available Rooms</h5>
                            <p class="card-text h2" id="count-available">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="section-bookings" style="display:none;">
            <h2>Bookings</h2>
            <button class="btn btn-primary mb-3" onclick="openBookingModal()">New Booking</button>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookings-table-body"></tbody>
            </table>
        </div>

        <div id="section-rooms" style="display:none;">
            <h2>Rooms</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Room #</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="rooms-table-body"></tbody>
            </table>
        </div>

        <div id="section-wholesalers" style="display:none;">
            <h2>Wholesalers</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Commission %</th>
                    </tr>
                </thead>
                <tbody id="wholesalers-table-body"></tbody>
            </table>
        </div>

        <div id="section-accounting" style="display:none;">
            <h2>Accounting & Invoices</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Guest</th>
                        <th>Booking Status</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="accounting-table-body"></tbody>
            </table>
        </div>
        
        <!-- More sections can be added here -->
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room Type</label>
                            <select class="form-control" name="room_type_id" id="booking-room-type" required></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wholesaler (Optional)</label>
                            <select class="form-control" name="wholesaler_id" id="booking-wholesaler"></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check In</label>
                            <input type="date" class="form-control" name="check_in" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check Out</label>
                            <input type="date" class="form-control" name="check_out" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Assignment Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="assign-booking-id">
                    <div class="mb-3">
                        <label class="form-label">Available Rooms</label>
                        <select class="form-control" id="assign-room-select"></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submitRoomAssignment()">Assign Room</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.main-content > div').forEach(div => div.style.display = 'none');
            document.getElementById('section-' + sectionId).style.display = 'block';
            loadData(sectionId);
        }

        async function loadData(section) {
            if (section === 'bookings') {
                const res = await fetch('/api/bookings');
                const bookings = await res.json();
                renderBookings(bookings);
            } else if (section === 'rooms') {
                const res = await fetch('/api/rooms');
                const rooms = await res.json();
                renderRooms(rooms);
            } else if (section === 'wholesalers') {
                const res = await fetch('/api/wholesalers');
                const data = await res.json();
                renderWholesalers(data);
            } else if (section === 'accounting') {
                const res = await fetch('/api/invoices');
                const data = await res.json();
                renderAccounting(data);
            }
        }

        function renderBookings(bookings) {
            const body = document.getElementById('bookings-table-body');
            body.innerHTML = bookings.map(b => `
                <tr>
                    <td>${b.guest.first_name} ${b.guest.last_name}</td>
                    <td>${b.check_in}</td>
                    <td>${b.check_out}</td>
                    <td>${b.room ? b.room.room_number : 'Not Assigned'}</td>
                    <td><span class="badge bg-info">${b.status}</span></td>
                    <td>
                        ${b.status === 'pending' ? `<button class="btn btn-sm btn-warning" onclick="assignRoom(${b.id})">Assign</button>` : ''}
                        ${b.status === 'confirmed' ? `<button class="btn btn-sm btn-success" onclick="checkIn(${b.id})">Check In</button>` : ''}
                        ${b.status === 'checked_in' ? `<button class="btn btn-sm btn-danger" onclick="checkOut(${b.id})">Check Out</button>` : ''}
                    </td>
                </tr>
            `).join('');
        }

        function renderRooms(rooms) {
            const body = document.getElementById('rooms-table-body');
            body.innerHTML = rooms.map(r => `
                <tr>
                    <td>${r.room_number}</td>
                    <td>${r.room_type.name}</td>
                    <td><span class="badge ${getStatusClass(r.status)}">${r.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-secondary" onclick="editRoom(${r.id})">Edit</button>
                    </td>
                </tr>
            `).join('');
        }

        function renderWholesalers(wholesalers) {
            const body = document.getElementById('wholesalers-table-body');
            body.innerHTML = wholesalers.map(w => `
                <tr>
                    <td>${w.name}</td>
                    <td>${w.contact_person}</td>
                    <td>${w.email}</td>
                    <td>${w.commission_rate}%</td>
                </tr>
            `).join('');
        }

        function renderAccounting(invoices) {
            const body = document.getElementById('accounting-table-body');
            body.innerHTML = invoices.map(i => `
                <tr>
                    <td>INV-${i.id.toString().padStart(4, '0')}</td>
                    <td>${i.booking.guest.first_name} ${i.booking.guest.last_name}</td>
                    <td>${i.booking.status}</td>
                    <td>$${i.amount}</td>
                    <td><span class="badge ${i.status === 'paid' ? 'bg-success' : 'bg-warning'}">${i.status}</span></td>
                    <td>
                        ${i.status !== 'paid' ? `<button class="btn btn-sm btn-primary" onclick="payInvoice(${i.id})">Mark Paid</button>` : ''}
                    </td>
                </tr>
            `).join('');
        }

        async function payInvoice(invoiceId) {
            const res = await fetch(`/api/invoices/${invoiceId}/payments`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ amount: 0, payment_method: 'Manual' }) // amount 0 in recordPayment logic can mean pay full
            });
            if (res.ok) loadData('accounting');
        }

        async function assignRoom(bookingId) {
            // Find the booking to know the room type
            const resBookings = await fetch('/api/bookings');
            const bookings = await resBookings.json();
            const booking = bookings.find(b => b.id === bookingId);

            // Fetch available rooms of that type
            const resRooms = await fetch('/api/rooms');
            const rooms = await resRooms.json();
            const availableRooms = rooms.filter(r => r.status === 'available' && r.room_type_id === booking.room_type_id);

            const select = document.getElementById('assign-room-select');
            if (availableRooms.length === 0) {
                select.innerHTML = '<option value="">No available rooms of this type</option>';
            } else {
                select.innerHTML = availableRooms.map(r => `<option value="${r.id}">Room ${r.room_number}</option>`).join('');
            }

            document.getElementById('assign-booking-id').value = bookingId;
            const modal = new bootstrap.Modal(document.getElementById('assignModal'));
            modal.show();
        }

        async function submitRoomAssignment() {
            const bookingId = document.getElementById('assign-booking-id').value;
            const roomId = document.getElementById('assign-room-select').value;

            if (!roomId) {
                alert('Please select a room');
                return;
            }

            const res = await fetch(`/api/bookings/${bookingId}/assign-room`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ room_id: roomId })
            });

            if (res.ok) {
                bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
                loadData('bookings');
            } else {
                const error = await res.json();
                alert('Error: ' + (error.error || 'Could not assign room'));
            }
        }

        async function checkIn(bookingId) {
            const res = await fetch(`/api/bookings/${bookingId}/check-in`, { method: 'POST' });
            if (res.ok) loadData('bookings');
        }

        async function checkOut(bookingId) {
            const res = await fetch(`/api/bookings/${bookingId}/check-out`, { method: 'POST' });
            if (res.ok) loadData('bookings');
        }

        function getStatusClass(status) {
            switch(status) {
                case 'available': return 'bg-success';
                case 'occupied': return 'bg-danger';
                case 'dirty': return 'bg-warning';
                default: return 'bg-secondary';
            }
        }

        async function openBookingModal() {
            // Fetch Room Types
            const resTypes = await fetch('/api/room-types');
            const types = await resTypes.json();
            const selectType = document.getElementById('booking-room-type');
            selectType.innerHTML = types.map(t => `<option value="${t.id}">${t.name} ($${t.base_rate})</option>`).join('');
            
            // Fetch Wholesalers
            const resWholesalers = await fetch('/api/wholesalers');
            const wholesalers = await resWholesalers.json();
            const selectWholesaler = document.getElementById('booking-wholesaler');
            selectWholesaler.innerHTML = '<option value="">None (Direct Booking)</option>' + 
                wholesalers.map(w => `<option value="${w.id}">${w.name}</option>`).join('');

            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        document.getElementById('bookingForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            
            // Ensure empty wholesaler_id is null
            if (data.wholesaler_id === "") {
                data.wholesaler_id = null;
            }
            
            const res = await fetch('/api/bookings', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
                showSection('bookings');
            } else {
                const errorData = await res.json();
                let message = 'Error creating booking: ';
                if (errorData.messages) {
                    message += Object.values(errorData.messages).flat().join(', ');
                } else {
                    message += errorData.message || 'Unknown error';
                }
                alert(message);
            }
        });

        // Initial load
        showSection('dashboard');
    </script>
</body>
</html>
