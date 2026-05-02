<?php
session_start();
session_destroy();
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Anda telah berhasil keluar.',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='index.php';
    });
</script></body>";
?>
