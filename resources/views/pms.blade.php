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
        
        <!-- More sections can be added here -->
    </div>

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

        function getStatusClass(status) {
            switch(status) {
                case 'available': return 'bg-success';
                case 'occupied': return 'bg-danger';
                case 'dirty': return 'bg-warning';
                default: return 'bg-secondary';
            }
        }

        // Initial load
        showSection('dashboard');
    </script>
</body>
</html>
