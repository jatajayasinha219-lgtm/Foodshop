<?php
// --- FIREBASE CONFIGURATION (Moved to PHP) ---
$firebaseConfig = [
    "apiKey" => "AIzaSyAYll6c6TdWz8KeaQbuXrJVqfuI25wlieU",
    "authDomain" => "food-436e8.firebaseapp.com",
    "databaseURL" => "https://food-436e8-default-rtdb.firebaseio.com",
    "projectId" => "food-436e8",
    "storageBucket" => "food-436e8.firebasestorage.app",
    "messagingSenderId" => "490409791970",
    "appId" => "1:490409791970:web:fdda04b5db1679687629bb"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIME SHOP Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #F9A826;
            --primary-dark: #d48806;
            --secondary-color: #FFF9F2;
            --text-color: #333;
            --light-text: #777;
            --border-color: #EAEAEA;
            --white: #FFFFFF;
            --danger: #e74c3c;
            --success: #2ecc71;
            --sidebar-width: 250px;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            color: var(--text-color);
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* --- Mobile Header (Hidden on Desktop) --- */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            background: var(--white);
            padding: 0 20px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            z-index: 20;
        }
        .mobile-toggle {
            font-size: 1.5em;
            color: var(--text-color);
            cursor: pointer;
        }
        .mobile-logo {
            font-weight: 700;
            font-size: 1.2em;
        }
        .mobile-logo i { color: var(--primary-color); }

        /* --- Sidebar --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--white);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
            z-index: 30;
            transition: transform 0.3s ease;
        }

        .logo {
            font-size: 1.5em;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
        }
        .logo i { color: var(--primary-color); margin-right: 10px; }

        .nav-links { list-style: none; padding: 0; }
        .nav-links li { margin-bottom: 10px; }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--light-text);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .nav-item i { margin-right: 15px; width: 20px; text-align: center; }
        .nav-item:hover, .nav-item.active {
            background-color: var(--secondary-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        .logout-btn {
            margin-top: auto;
            color: var(--danger);
        }
        .logout-btn:hover { background-color: #fee; color: var(--danger); }

        /* --- Sidebar Overlay (Mobile) --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 25;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* --- Main Content --- */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            position: relative;
            transition: margin-left 0.3s ease;
        }

        /* --- Sections --- */
        .section { display: none; }
        .section.active { display: block; animation: fadeIn 0.4s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .header-title {
            font-size: 1.8em;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* --- Auth Overlay --- */
        #authOverlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #f4f6f8;
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        .login-box h2 { color: var(--primary-color); margin-bottom: 20px; }
        .login-box input {
            width: 100%; padding: 15px; margin-bottom: 15px;
            border: 1px solid var(--border-color); border-radius: 10px;
            box-sizing: border-box;
        }
        .btn-primary {
            width: 100%; padding: 15px;
            background-color: var(--primary-color); color: var(--white);
            border: none; border-radius: 10px;
            font-size: 1.1em; font-weight: 600; cursor: pointer;
        }
        .btn-primary:hover { background-color: var(--primary-dark); }

        /* --- Dashboard Stats --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-info h3 { margin: 0; font-size: 2em; color: var(--text-color); }
        .stat-info p { margin: 5px 0 0; color: var(--light-text); font-size: 0.9em; }
        .stat-icon {
            width: 50px; height: 50px;
            background: var(--secondary-color);
            color: var(--primary-color);
            border-radius: 12px;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.5em;
        }

        /* --- Tables --- */
        .table-container {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { font-weight: 600; color: var(--light-text); font-size: 0.9em; text-transform: uppercase; }
        tr:last-child td { border-bottom: none; }
        .status-select {
            padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-color);
            background: white; font-family: inherit; cursor: pointer;
        }

        /* --- Forms & Inputs --- */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .btn-add {
            background: var(--primary-color); color: white;
            padding: 10px 20px; border-radius: 8px; text-decoration: none;
            cursor: pointer; border: none; font-weight: 500;
            white-space: nowrap;
        }
        
        /* --- Grid for Menu Items --- */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .admin-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: relative;
        }
        .admin-card img { width: 100%; height: 140px; object-fit: cover; }
        .admin-card-body { padding: 15px; }
        .admin-card h4 { margin: 0 0 5px; }
        .admin-card p { color: var(--light-text); margin: 0; font-size: 0.9em; }
        .admin-card .price { color: var(--primary-color); font-weight: 600; margin-top: 5px; display: block;}
        .delete-btn {
            position: absolute; top: 10px; right: 10px;
            background: rgba(255,255,255,0.9); color: var(--danger);
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            cursor: pointer; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* --- Modal --- */
        .modal {
            display: none; 
            position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; 
            background-color: rgba(0,0,0,0.5);
            justify-content: center; align-items: center;
        }
        .modal-content {
            background-color: var(--white); padding: 30px; border-radius: 15px;
            width: 90%; max-width: 500px; position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-height: 90vh; overflow-y: auto;
        }
        .close-modal {
            position: absolute; right: 20px; top: 20px; font-size: 1.5em; cursor: pointer;
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid var(--border-color);
            border-radius: 8px; box-sizing: border-box; font-family: inherit; font-size: 16px;
        }

        /* --- RESPONSIVE / MOBILE STYLES --- */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            /* Show Mobile Header */
            .mobile-header {
                display: flex;
            }

            /* Hide Desktop Sidebar by default */
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: -260px; /* Hide off-canvas */
                height: 100%;
                width: 250px;
            }

            .sidebar.active {
                left: 0;
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }

            /* Main Content Adjustments */
            .main-content {
                padding: 15px;
                padding-top: 80px; /* Space for mobile header */
                width: 100%;
                box-sizing: border-box;
            }

            .header-title {
                font-size: 1.5em;
            }

            /* Dashboard Stack */
            .stats-grid {
                grid-template-columns: 1fr; /* Stack vertically */
            }

            /* Menu Grid */
            .menu-grid {
                grid-template-columns: 1fr 1fr; /* 2 items per row */
            }

            @media (max-width: 480px) {
                .menu-grid {
                    grid-template-columns: 1fr; /* 1 item per row on very small screens */
                }
            }
        }

    </style>
</head>
<body>

    <!-- AUTH OVERLAY -->
    <div id="authOverlay">
        <div class="login-box">
            <h1>PRIME SHOP Admin</h1>
            <h2>Admin Login</h2>
            <p id="authError" style="color: var(--danger); font-size: 0.9em;"></p>
            <input type="email" id="adminEmail" placeholder="admin@javagoat.com">
            <input type="password" id="adminPassword" placeholder="Password">
            <button class="btn-primary" id="loginBtn">Login to Dashboard</button>
        </div>
    </div>

    <!-- MOBILE HEADER -->
    <div class="mobile-header">
        <div class="mobile-logo"><i class="fas fa-leaf"></i> PRIME SHOP</div>
        <div class="mobile-toggle" id="menuToggle"><i class="fas fa-bars"></i></div>
    </div>

    <!-- SIDEBAR OVERLAY (Backdrop) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="logo"><i class="fas fa-leaf"></i> Admin Panel</div>
        <ul class="nav-links">
            <li class="nav-item active" data-target="dashboard">
                <i class="fas fa-chart-pie"></i> Dashboard
            </li>
            <li class="nav-item" data-target="orders">
                <i class="fas fa-receipt"></i> Orders
            </li>
            <li class="nav-item" data-target="menu">
                <i class="fas fa-utensils"></i> Menu Items
            </li>
            <li class="nav-item" data-target="restaurants">
                <i class="fas fa-store"></i> Restaurants
            </li>
            <li class="nav-item logout-btn" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        
        <!-- DASHBOARD SECTION -->
        <div id="dashboard" class="section active">
            <div class="header-title">Overview</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3 id="stat-revenue">₹0</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3 id="stat-orders">0</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3 id="stat-dishes">0</h3>
                        <p>Active Dishes</p>
                    </div>
                    <div class="stat-icon"><i class="fas fa-hamburger"></i></div>
                </div>
            </div>
        </div>

        <!-- ORDERS SECTION -->
        <div id="orders" class="section">
            <div class="header-title">Manage Orders</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <!-- Orders inserted here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MENU SECTION -->
        <div id="menu" class="section">
            <div class="action-bar">
                <div class="header-title" style="margin:0;">Dishes</div>
                <button class="btn-add" id="openDishModal">+ Add Dish</button>
            </div>
            <div class="menu-grid" id="dishesGrid">
                <!-- Dishes inserted here -->
            </div>
        </div>

        <!-- RESTAURANTS SECTION -->
        <div id="restaurants" class="section">
             <div class="action-bar">
                <div class="header-title" style="margin:0;">Restaurants</div>
                <button class="btn-add" id="openRestModal">+ Add Restaurant</button>
            </div>
            <div class="menu-grid" id="restaurantsGrid">
                <!-- Restaurants inserted here -->
            </div>
        </div>

    </div>

    <!-- ADD DISH MODAL -->
    <div id="dishModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" data-modal="dishModal">&times;</span>
            <h2>Add New Dish</h2>
            <div class="form-group">
                <label>Dish Name</label>
                <input type="text" id="dishName">
            </div>
            <div class="form-group">
                <label>Price (₹)</label>
                <input type="number" step="0.01" id="dishPrice">
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" id="dishImage" placeholder="https://...">
            </div>
            <button class="btn-primary" id="saveDishBtn">Save Dish</button>
        </div>
    </div>

    <!-- ADD RESTAURANT MODAL -->
    <div id="restModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" data-modal="restModal">&times;</span>
            <h2>Add Restaurant</h2>
            <div class="form-group">
                <label>Restaurant Name</label>
                <input type="text" id="restName">
            </div>
            <div class="form-group">
                <label>Rating (1-5)</label>
                <input type="number" step="0.1" max="5" id="restRating">
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" id="restImage" placeholder="https://...">
            </div>
            <button class="btn-primary" id="saveRestBtn">Save Restaurant</button>
        </div>
    </div>

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

    <script>
        // --- Firebase Config injected from PHP ---
        const firebaseConfig = <?php echo json_encode($firebaseConfig); ?>;

        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.database();

        // --- Auth Logic ---
        const authOverlay = document.getElementById('authOverlay');
        const loginBtn = document.getElementById('loginBtn');
        const logoutBtn = document.getElementById('logoutBtn');
        
        auth.onAuthStateChanged(user => {
            if (user) {
                authOverlay.style.display = 'none';
                initAdmin();
            } else {
                authOverlay.style.display = 'flex';
            }
        });

        loginBtn.addEventListener('click', () => {
            const email = document.getElementById('adminEmail').value;
            const password = document.getElementById('adminPassword').value;
            auth.signInWithEmailAndPassword(email, password)
                .catch(err => document.getElementById('authError').textContent = err.message);
        });

        logoutBtn.addEventListener('click', () => auth.signOut());

        // --- Mobile Navigation Logic ---
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        }

        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
        }

        menuToggle.addEventListener('click', () => {
            if (sidebar.classList.contains('active')) closeSidebar();
            else openSidebar();
        });

        sidebarOverlay.addEventListener('click', closeSidebar);


        // --- Navigation ---
        const navItems = document.querySelectorAll('.nav-item:not(.logout-btn)');
        const sections = document.querySelectorAll('.section');

        navItems.forEach(item => {
            item.addEventListener('click', () => {
                // Active State
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
                
                // Show Section
                const targetId = item.dataset.target;
                sections.forEach(sec => sec.classList.remove('active'));
                document.getElementById(targetId).classList.add('active');

                // Close sidebar on mobile after clicking
                if(window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // --- Real-time Data Handling ---

        function initAdmin() {
            fetchStats();
            fetchOrders();
            fetchDishes();
            fetchRestaurants();
        }

        // 1. ORDERS
        function fetchOrders() {
            db.ref('orders').on('value', snapshot => {
                const tbody = document.getElementById('ordersTableBody');
                tbody.innerHTML = '';
                
                let totalRev = 0;
                let totalOrders = 0;
                
                const orders = [];
                snapshot.forEach(child => {
                    orders.push({ id: child.key, ...child.val() });
                });
                
                orders.sort((a,b) => new Date(b.timestamp) - new Date(a.timestamp));

                orders.forEach(order => {
                    totalRev += parseFloat(order.total);
                    totalOrders++;

                    const itemsString = order.items ? order.items.map(i => `${i.quantity}x ${i.name}`).join(', ') : 'No items';
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="font-family:monospace; font-size:0.8em;">${order.id.substring(1, 8)}...</td>
                        <td>
                            <strong>${order.userEmail || 'Guest'}</strong><br>
                            <span style="font-size:0.8em; color:var(--light-text);">${order.phone || '-'}</span>
                        </td>
                        <td style="font-size:0.9em;">${itemsString}</td>
                        <td style="font-weight:600;">₹${order.total}</td>
                        <td style="font-size:0.9em;">${order.address || 'Pickup'}</td>
                        <td>
                            <select class="status-select" onchange="updateStatus('${order.id}', this.value)" 
                                style="color: ${order.status === 'Delivered' ? 'var(--success)' : 'var(--primary-color)'}">
                                <option value="Placed" ${order.status === 'Placed' ? 'selected' : ''}>Placed</option>
                                <option value="Preparing" ${order.status === 'Preparing' ? 'selected' : ''}>Preparing</option>
                                <option value="Out for Delivery" ${order.status === 'Out for Delivery' ? 'selected' : ''}>On Way</option>
                                <option value="Delivered" ${order.status === 'Delivered' ? 'selected' : ''}>Delivered</option>
                            </select>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                document.getElementById('stat-revenue').textContent = '₹' + totalRev.toFixed(2);
                document.getElementById('stat-orders').textContent = totalOrders;
            });
        }

        window.updateStatus = (orderId, newStatus) => {
            db.ref('orders/' + orderId).update({ status: newStatus });
        };

        // 2. DISHES
        function fetchDishes() {
            db.ref('dishes').on('value', snapshot => {
                const container = document.getElementById('dishesGrid');
                container.innerHTML = '';
                let count = 0;

                snapshot.forEach(child => {
                    count++;
                    const dish = child.val();
                    const card = document.createElement('div');
                    card.className = 'admin-card';
                    card.innerHTML = `
                        <img src="${dish.imageUrl}" alt="${dish.name}">
                        <button class="delete-btn" onclick="deleteItem('dishes', '${child.key}')">&times;</button>
                        <div class="admin-card-body">
                            <h4>${dish.name}</h4>
                            <span class="price">₹${parseFloat(dish.price).toFixed(2)}</span>
                        </div>
                    `;
                    container.appendChild(card);
                });
                document.getElementById('stat-dishes').textContent = count;
            });
        }

        // 3. RESTAURANTS
        function fetchRestaurants() {
            db.ref('restaurants').on('value', snapshot => {
                const container = document.getElementById('restaurantsGrid');
                container.innerHTML = '';

                snapshot.forEach(child => {
                    const rest = child.val();
                    const card = document.createElement('div');
                    card.className = 'admin-card';
                    card.innerHTML = `
                        <img src="${rest.imageUrl}" alt="${rest.name}">
                        <button class="delete-btn" onclick="deleteItem('restaurants', '${child.key}')">&times;</button>
                        <div class="admin-card-body">
                            <h4>${rest.name}</h4>
                            <p><i class="fas fa-star" style="color:var(--primary-color)"></i> ${rest.rating}</p>
                        </div>
                    `;
                    container.appendChild(card);
                });
            });
        }

        // --- CRUD Operations ---

        window.deleteItem = (collection, id) => {
            if(confirm('Are you sure you want to delete this item?')) {
                db.ref(collection + '/' + id).remove();
            }
        };

        const modalBtns = document.querySelectorAll('.btn-add');
        const closeBtns = document.querySelectorAll('.close-modal');
        const modals = document.querySelectorAll('.modal');

        document.getElementById('openDishModal').onclick = () => document.getElementById('dishModal').style.display = 'flex';
        document.getElementById('openRestModal').onclick = () => document.getElementById('restModal').style.display = 'flex';

        closeBtns.forEach(btn => {
            btn.onclick = () => document.getElementById(btn.dataset.modal).style.display = 'none';
        });

        window.onclick = (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        };

        document.getElementById('saveDishBtn').addEventListener('click', () => {
            const name = document.getElementById('dishName').value;
            const price = parseFloat(document.getElementById('dishPrice').value);
            const image = document.getElementById('dishImage').value;

            if(name && price && image) {
                db.ref('dishes').push({ name, price, imageUrl: image })
                .then(() => {
                    document.getElementById('dishModal').style.display = 'none';
                    document.getElementById('dishName').value = '';
                    document.getElementById('dishPrice').value = '';
                    document.getElementById('dishImage').value = '';
                });
            } else { alert('Please fill all fields'); }
        });

        document.getElementById('saveRestBtn').addEventListener('click', () => {
            const name = document.getElementById('restName').value;
            const rating = document.getElementById('restRating').value;
            const image = document.getElementById('restImage').value;

            if(name && rating && image) {
                db.ref('restaurants').push({ name, rating, imageUrl: image })
                .then(() => {
                    document.getElementById('restModal').style.display = 'none';
                    document.getElementById('restName').value = '';
                    document.getElementById('restRating').value = '';
                    document.getElementById('restImage').value = '';
                });
            } else { alert('Please fill all fields'); }
        });

        function fetchStats() {
            // Stats logic
        }

    </script>
</body>
</html>
