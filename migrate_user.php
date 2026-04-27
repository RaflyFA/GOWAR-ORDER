<?php
include 'config/koneksi.php';

$queries = [
    "ALTER TABLE user ADD COLUMN email VARCHAR(100) NULL",
    "ALTER TABLE user ADD COLUMN whatsapp VARCHAR(20) NULL",
    "ALTER TABLE user ADD COLUMN alamat_lengkap TEXT NULL",
    "ALTER TABLE user ADD COLUMN latitude VARCHAR(50) NULL",
    "ALTER TABLE user ADD COLUMN longitude VARCHAR(50) NULL"
];

foreach ($queries as $q) {
    if ($koneksi->query($q)) {
        echo "Success: $q\n";
    } else {
        echo "Failed or already exists: $q\n";
    }
}
?>
