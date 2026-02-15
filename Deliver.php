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
    <title>PRIME SHOP Driver</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <style>
        body { margin:0; font-family: 'Poppins', sans-serif; background: #f3f4f6; color: #333; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; position: sticky; top:0; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .container { padding: 20px; max-width: 600px; margin: 0 auto; padding-bottom: 80px; }
        
        /* Order Card */
        .order-card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .status-badge { background: #f39c12; color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.75em; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Product Items List */
        .item-list { background: #fafafa; border-radius: 10px; padding: 10px; margin: 15px 0; }
        .item-row { display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee; }
        .item-row:last-child { border-bottom: none; }
        .item-img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; margin-right: 15px; border: 1px solid #ddd; }
        .item-details { flex: 1; }
        .item-name { font-weight: 600; font-size: 0.95em; color: #2c3e50; }
        .item-meta { font-size: 0.85em; color: #7f8c8d; }
        .item-price { font-weight: 600; color: #27ae60; }

        /* Buttons */
        .btn { width: 100%; padding: 14px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 1em; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.1s; }
        .btn:active { transform: scale(0.98); }
        .btn-accept { background: #27ae60; color: white; box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3); }
        .btn-nav { background: #2980b9; color: white; }
        .btn-street { background: #8e44ad; color: white; }
        .btn-deliver { background: #c0392b; color: white; margin-top: 20px; }
        
        /* Map & Hidden Elements */
        #activeMap { height: 250px; width: 100%; border-radius: 10px; margin-top: 15px; display: none; border: 2px solid #2c3e50; }
        .hidden { display: none; }
        .info-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; color: #555; }

        /* Street View Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: black; z-index: 9999; flex-direction: column; }
        .modal-header { padding: 15px; background: rgba(0,0,0,0.9); color: white; display: flex; justify-content: space-between; align-items: center; }
        .close-btn { background: none; border:none; color: white; font-size: 1.5em; cursor: pointer; }
        .modal-body { flex: 1; width: 100%; background: #222; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

<div class="header">
    <h2>☕ PRIME SHOP Driver</h2>
    <p id="driverStatus" style="font-size: 0.9em; opacity: 0.9; margin-top: 5px;">Waiting for orders...</p>
</div>

<div class="container" id="ordersList">
    <p style="text-align:center; color:#777; margin-top:50px;">Connecting to server...</p>
</div>

<!-- Active Delivery View -->
<div class="container hidden" id="activeDelivery">
    <div class="order-card">
        <div class="card-header">
            <h3 style="margin:0">Current Delivery</h3>
            <span class="status-badge" style="background:#2980b9">IN PROGRESS</span>
        </div>
        
        <div class="info-row"><i class="fas fa-map-marker-alt" style="color:#e74c3c"></i> <span id="custAddress"></span></div>
        <div class="info-row"><i class="fas fa-phone" style="color:#27ae60"></i> <span id="custPhone"></span></div>
        
        <!-- Product List in Active View -->
        <div class="item-list" id="activeItemsList"></div>

        <div id="activeMap"></div>
        
        <button class="btn btn-street" onclick="openStreetViewModal()">
            <i class="fas fa-street-view"></i> See House (Street View)
        </button>

        <button class="btn btn-nav" onclick="openGoogleMapsNav()">
            <i class="fas fa-location-arrow"></i> Google Maps Navigation
        </button>
        
        <button class="btn btn-deliver" onclick="markDelivered()">
            <i class="fas fa-check-circle"></i> Complete Delivery
        </button>
    </div>
</div>

<!-- Street View Modal -->
<div class="modal" id="svModal">
    <div class="modal-header">
        <span><i class="fas fa-home"></i> House Preview</span>
        <button class="close-btn" onclick="closeStreetView()"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body" id="iframe-holder"></div>
</div>

<!-- Firebase Scripts -->
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

<script>
    // 1. CONFIGURATION (Injected from PHP)
    const firebaseConfig = <?php echo json_encode($firebaseConfig); ?>;

    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    let currentOrderId = null;
    let watchId = null;
    let customerLoc = null;
    let map = null; 
    let driverMarker = null;

    const driverIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/741/741407.png', // Car Icon
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    });

    // 2. Load Pending Orders
    db.ref('orders').orderByChild('status').equalTo('Placed').on('value', snap => {
        const list = document.getElementById('ordersList');
        if(currentOrderId) return; // Don't refresh if busy
        
        list.innerHTML = '';
        if(!snap.exists()) { 
            list.innerHTML = '<div style="text-align:center; margin-top:50px;"><i class="fas fa-mug-hot" style="font-size:3em; color:#ddd;"></i><p style="color:#777; margin-top:15px;">No new orders.</p></div>'; 
            return; 
        }

        snap.forEach(child => {
            const o = child.val();
            const id = child.key;
            
            // Build Item HTML
            let itemsHtml = buildItemsHtml(o.items);

            const card = document.createElement('div');
            card.className = 'order-card';
            card.innerHTML = `
                <div class="card-header">
                    <h4 style="margin:0">Order #${id.substring(1,6)}</h4>
                    <span class="status-badge">${o.status}</span>
                </div>
                
                <div class="item-list">${itemsHtml}</div>
                
                <div style="display:flex; justify-content:space-between; font-weight:bold; margin-bottom:10px;">
                    <span>Total Bill:</span>
                    <span style="color:#2c3e50">Rs${o.total}</span>
                </div>

                <div class="info-row"><i class="fas fa-map-marker-alt" style="color:#e74c3c"></i> ${o.address}</div>
                
                <button class="btn btn-accept" onclick="acceptOrder('${id}')">
                    <i class="fas fa-shipping-fast"></i> Accept & Deliver
                </button>
            `;
            list.appendChild(card);
        });
    });

    // Helper: Build HTML for Product List (Image + Name)
    function buildItemsHtml(items) {
        if(!items || items.length === 0) return '<p>No items data</p>';
        
        return items.map(i => `
            <div class="item-row">
                <img src="${i.img || 'https://via.placeholder.com/50'}" class="item-img" alt="Food">
                <div class="item-details">
                    <div class="item-name">${i.name}</div>
                    <div class="item-meta">Qty: ${i.quantity}</div>
                </div>
                <div class="item-price">Rs${(i.price * i.quantity).toFixed(2)}</div>
            </div>
        `).join('');
    }

    // 3. Accept Order Logic
    window.acceptOrder = (id) => {
        currentOrderId = id;
        
        db.ref('orders/' + id).once('value', s => {
            const o = s.val();
            if(!o) return;

            customerLoc = o.location;
            
            // UI Switch
            document.getElementById('ordersList').classList.add('hidden');
            document.getElementById('activeDelivery').classList.remove('hidden');
            
            // Fill Details
            document.getElementById('custAddress').textContent = o.address;
            document.getElementById('custPhone').textContent = o.phone;
            document.getElementById('activeItemsList').innerHTML = buildItemsHtml(o.items);
            document.getElementById('driverStatus').textContent = "🚚 Driving to Customer...";

            // Update DB Status
            db.ref('orders/' + id).update({ status: 'Out for Delivery' });

            // Start Map & Tracking
            initMap(customerLoc);
            startTracking();
        });
    };

    // 4. GPS Tracking (Sends data to User's Map)
    function startTracking() {
        if(navigator.geolocation) {
            watchId = navigator.geolocation.watchPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                // Update Driver's Local Map
                if(map) {
                    if(driverMarker) driverMarker.setLatLng([lat, lng]);
                    else driverMarker = L.marker([lat, lng], {icon: driverIcon}).addTo(map);
                    
                    // Keep map centered on driver mostly
                    // map.setView([lat, lng], 16); 
                }

                // --- KEY PART FOR USER TRACKING ---
                // Writes to the path 'driverLocation' inside the specific order
                if(currentOrderId) {
                    db.ref(`orders/${currentOrderId}/driverLocation`).set({
                        lat: lat,
                        lng: lng,
                        heading: pos.coords.heading || 0,
                        timestamp: Date.now()
                    });
                }
            }, err => console.error("GPS Error", err), { 
                enableHighAccuracy: true, 
                maximumAge: 2000 
            });
        } else {
            alert("GPS not supported.");
        }
    }

    function initMap(dest) {
        const mapDiv = document.getElementById('activeMap');
        mapDiv.style.display = 'block';
        if (map !== null) map.remove();
        
        map = L.map('activeMap').setView([dest.lat, dest.lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        // Marker for Customer
        L.marker([dest.lat, dest.lng]).addTo(map).bindPopup("Customer").openPopup();
    }

    // Street View Modal
    window.openStreetViewModal = () => {
        if (!customerLoc) { alert("No location data found."); return; }
        document.getElementById('svModal').style.display = 'flex';
        document.getElementById('iframe-holder').innerHTML = `
            <iframe src="https://maps.google.com/maps?layer=c&cbll=${customerLoc.lat},${customerLoc.lng}&cbp=12,0,0,0,0&output=svembed"></iframe>
        `;
    }

    window.closeStreetView = () => {
        document.getElementById('svModal').style.display = 'none';
        document.getElementById('iframe-holder').innerHTML = '';
    }

    window.openGoogleMapsNav = () => {
        const url = `https://www.google.com/maps/dir/?api=1&destination=${customerLoc.lat},${customerLoc.lng}&travelmode=driving`;
        window.open(url, '_blank');
    };

    window.markDelivered = () => {
        if(confirm("Confirm delivery complete?")) {
            if(watchId) navigator.geolocation.clearWatch(watchId);
            db.ref('orders/' + currentOrderId).update({ status: 'Delivered' });
            alert("Order Delivered!");
            location.reload(); 
        }
    };
</script>
</body>
</html>
