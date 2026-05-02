<?php
// components/header.php
// Menentukan link "Kembali" sesuai halaman asal atau role
$kembali_link = "index.php";
$kembali_text = "Kembali";

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    // Jika admin melihat detail pesanan, kembali ke pesanan.php
    $kembali_link = "pesanan.php";
} else {
    // Jika user biasa di halaman keranjang, ke index.php, jika di detail ke riwayat.php
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, 'detail_pesanan.php') !== false) {
        $kembali_link = "riwayat.php";
    } elseif (strpos($uri, 'keranjang.php') !== false) {
        $kembali_text = "Kembali Belanja";
    }
}
?>
<style>
    /* Universal Header CSS */
    .wrt-header {
        background-color: #ffffff;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        width: 100%;
        margin-bottom: 2rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .wrt-header-container {
        max-width: 56rem; /* 896px */
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wrt-logo-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.5rem;
        font-weight: 700;
        text-decoration: none !important;
    }
    .wrt-logo-icon {
        background-color: #1a8f50;
        color: #ffffff;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
    }
    .wrt-logo-text {
        color: #1a8f50;
    }
    .wrt-btn-back {
        padding: 0.5rem 1.25rem;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        border-radius: 9999px;
        text-decoration: none !important;
        background-color: transparent;
        transition: all 0.2s;
        font-size: 1rem;
    }
    .wrt-btn-back:hover {
        background-color: #f8fafc;
        color: #1a8f50;
        border-color: #1a8f50;
    }
</style>
<nav class="wrt-header">
    <div class="wrt-header-container">
        <a href="index.php" class="wrt-logo-link">
            <div class="wrt-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-2.886-1.201-3.898C12.544 5.36 11.5 4.584 11.5 3.5c0-.837.299-1.554.8-2.03.116-.11.23-.217.338-.32C12.38 1 12 1 11.5 1 9.5 1 8 3.5 8 3.5S6.5 1 4.5 1C4 1 3.62 1 3.362 1.15c.108.103.222.21.338.32.5.476.8 1.193.8 2.03 0 1.084-1.044 1.86-1.299 3.102C2.5 7.614 2 8.999 2 11.5 2 14 4.686 16 8 16Z"/>
                </svg>
            </div>
            <span class="wrt-logo-text">Wartan.</span>
        </a>
        <a href="<?php echo $kembali_link; ?>" class="wrt-btn-back">
            <?php echo $kembali_text; ?>
        </a>
    </div>
</nav>
