<?php
// update_pdf.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file']) && isset($_POST['file_path'])) {

    $uploadedFile = $_FILES['pdf_file'];
    $targetPath = $_POST['file_path'];

    // 1. Validasi Ekstensi File Asli (Harus PDF)
    $fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
    if ($fileExtension !== 'pdf') {
        http_response_code(400);
        die("Error: File yang diganti harus berformat PDF.");
    }

    // 2. Validasi MIME Type dari file yang di-upload client
    $mimeType = mime_content_type($uploadedFile['tmp_name']);
    if ($mimeType !== 'application/pdf') {
        http_response_code(400);
        die("Error: Data yang dikirim bukan PDF yang valid.");
    }

    // 3. KEAMANAN PATH (VERSI FIX WINDOWS/XAMPP)
    // Dapatkan path absolut dari folder uploads saat ini
    $baseDirectory = realpath(__DIR__ . '/uploads');

    // Gunakan basename() agar hanya mengambil "JAEDOC251000045.pdf" 
    // dan membuang sisa path duplikat dari frontend
    $fullPath = __DIR__ . '/uploads/' . basename($targetPath);
    $requestedPath = realpath($fullPath);

    // Cek apakah file target valid dan berada di dalam folder uploads
    if ($requestedPath === false || strpos($requestedPath, $baseDirectory) !== 0) {
        http_response_code(403);
        die("Error: Akses path tidak diizinkan atau file tidak ditemukan.");
    }

    // 4. Proses Overwrite (Timpa file lama)
    if (move_uploaded_file($uploadedFile['tmp_name'], $requestedPath)) {
        http_response_code(200);
        echo "File berhasil diperbarui.";
    } else {
        http_response_code(500);
        die("Error: Gagal menimpa file di server. Periksa permission folder (chmod).");
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
