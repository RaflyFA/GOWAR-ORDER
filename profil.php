<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM user WHERE id_user = '$id_user'";
$result = $koneksi->query($query);
$user = $result->fetch_assoc();

// Default coordinates if empty (Misal: Jakarta Pusat)
$lat = !empty($user['latitude']) ? $user['latitude'] : '-6.175110';
$lng = !empty($user['longitude']) ? $user['longitude'] : '106.827153';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Wartan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f9f8f3; }
        .bg-wartan { background-color: #3d6a4a; }
        .btn-green { background-color: #3d6a4a; color: white; border-radius: 10px; }
        .btn-green:hover { background-color: #2b4d34; color: white; }
    </style>
</head>
<body>
    <nav class="navbar bg-white shadow-sm py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-dark d-flex align-items-center" href="index.php">
                <div class="bg-wartan text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                    <i class="bi bi-shop fs-5"></i>
                </div>
                Wartan.
            </a>
            <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
        </div>
    </nav>

    <div class="container mb-5" style="max-width: 800px;">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person text-secondary fs-1"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Profil & Alamat</h2>
                        <p class="text-muted mb-0">Atur informasi kontak dan lokasi pengiriman Anda.</p>
                    </div>
                </div>

                <form action="actions/proses_edit_profil.php" method="POST">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control p-3 bg-light" style="border-radius: 10px;" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control p-3 bg-light text-muted" style="border-radius: 10px;" value="<?php echo htmlspecialchars($user['username']); ?>" readonly disabled>
                            <div class="form-text">Username tidak dapat diubah.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control p-3 bg-light" style="border-radius: 10px;" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" placeholder="contoh@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control p-3 bg-light" style="border-radius: 10px;" value="<?php echo htmlspecialchars($user['whatsapp'] ?? ''); ?>" placeholder="081234567890">
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-top pt-4">Lokasi Pengiriman</h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="form-control p-3 bg-light" rows="3" style="border-radius: 10px;" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan..."><?php echo htmlspecialchars($user['alamat_lengkap'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold d-flex justify-content-between">
                            <span>Tandai Lokasi di Peta</span>
                            <span class="text-muted fw-normal" style="font-size: 0.85rem;">Geser pin merah atau klik peta</span>
                        </label>
                        <!-- Peta Leaflet -->
                        <div id="map" style="height: 350px; border-radius: 12px; z-index: 1; border: 1px solid #dee2e6;"></div>
                        <!-- Hidden inputs untuk menyimpan koordinat -->
                        <input type="hidden" id="latitude" name="latitude" value="<?php echo $lat; ?>">
                        <input type="hidden" id="longitude" name="longitude" value="<?php echo $lng; ?>">
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" name="simpan_profil" class="btn btn-green px-5 py-3 fw-bold fs-5">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta
        var lat = <?php echo $lat; ?>;
        var lng = <?php echo $lng; ?>;
        var map = L.map('map').setView([lat, lng], 15);

        // Menambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Menambahkan marker yang bisa digeser (draggable)
        var marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        // Fungsi mengupdate input koordinat saat marker digeser
        marker.on('dragend', function (e) {
            var position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Pindahkan marker saat peta di klik
        map.on('click', function(e) {
            var newLat = e.latlng.lat;
            var newLng = e.latlng.lng;
            marker.setLatLng([newLat, newLng]);
            document.getElementById('latitude').value = newLat;
            document.getElementById('longitude').value = newLng;
        });
    </script>
</body>
</html>
