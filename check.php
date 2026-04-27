<?php
include 'config/koneksi.php';
echo "Table user:\n";
$result = $koneksi->query("DESCRIBE user");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
?>
