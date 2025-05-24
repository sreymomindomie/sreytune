<?php

// Nama file input
$dirFile = 'dir.txt';
$titleFile = 'title.txt';
$descFile = 'deskripsi.txt';

// Delimiter baru untuk output
$delimiter1 = '[SREYMOM]';
$delimiter2 = '[SREYMOMINDOMIE]';

// --- Membaca isi file ke dalam array ---
// Menggunakan @ untuk menekan warning jika file tidak ada, akan dicek setelahnya
$dirs = @file($dirFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$title_templates = @file($titleFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$description_templates = @file($descFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// --- Pengecekan apakah file berhasil dibaca ---
if ($dirs === false) {
    die("Error: Gagal membaca file '$dirFile'. Pastikan file ada dan bisa diakses.\n");
}
if ($title_templates === false) {
    die("Error: Gagal membaca file '$titleFile'. Pastikan file ada dan bisa diakses.\n");
}
if ($description_templates === false) {
    die("Error: Gagal membaca file '$descFile'. Pastikan file ada dan bisa diakses.\n");
}

// --- Menentukan jumlah item yang akan diproses ---
$num_dirs = count($dirs);
$num_titles = count($title_templates);
$num_descs = count($description_templates);

$items_to_process = min($num_dirs, $num_titles, $num_descs);

if ($items_to_process == 0) {
    echo "Tidak ada data yang bisa diproses. Pastikan file input tidak kosong.\n";
    exit;
}

// Memberikan peringatan jika jumlah baris di file-file input berbeda
if ($num_dirs !== $num_titles || $num_dirs !== $num_descs || $num_titles !== $num_descs) {
    echo "Peringatan: Jumlah baris dalam file input tidak sama. Hanya akan diproses sejumlah $items_to_process item (berdasarkan jumlah baris terkecil).\n";
    echo " - '$dirFile' memiliki $num_dirs baris.\n";
    echo " - '$titleFile' memiliki $num_titles baris.\n";
    echo " - '$descFile' memiliki $num_descs baris.\n\n";
}

// --- Melakukan perulangan untuk setiap item ---
for ($i = 0; $i < $items_to_process; $i++) {
    $current_dir = trim($dirs[$i]);
    $current_title_template = trim($title_templates[$i]);
    $current_description_template = trim($description_templates[$i]);

    // Lewati jika nama direktori kosong setelah di-trim
    if (empty($current_dir)) {
        echo "Info: Baris ke-" . ($i + 1) . " pada '$dirFile' kosong. Item ini dilewati.\n";
        continue;
    }

    // Mengganti placeholder '$dir' di judul dan deskripsi dengan nama direktori saat ini
    $processed_title = str_replace('$dir', $current_dir, $current_title_template);
    $processed_description = str_replace('$dir', $current_dir, $current_description_template);

    // Membuat baris output sesuai format yang diinginkan
    $output_line = $current_dir . $delimiter1 . $processed_title . $delimiter2 . $processed_description;

    // Mencetak baris output ke layar
    echo $output_line . PHP_EOL; // PHP_EOL adalah karakter newline yang sesuai sistem operasi
}

echo "\nSelesai memproses $items_to_process item.\n";


// Opsional: Jika kamu ingin menyimpan hasilnya ke file baru, misalnya 'output_agc.txt'
// Hapus komentar di bawah ini dan pastikan direktori memiliki izin tulis.

$all_output_lines = [];
for ($i = 0; $i < $items_to_process; $i++) {
    $current_dir = trim($dirs[$i]);
    $current_title_template = trim($title_templates[$i]);
    $current_description_template = trim($description_templates[$i]);

    if (empty($current_dir)) {
        continue; // Sudah ada info di atas jika dilewati
    }

    $processed_title = str_replace('$dir', $current_dir, $current_title_template);
    $processed_description = str_replace('$dir', $current_dir, $current_description_template);
    $all_output_lines[] = $current_dir . $delimiter1 . $processed_title . $delimiter2 . $processed_description;
}

if (!empty($all_output_lines)) {
    $output_filename = 'agc.txt';
    if (file_put_contents($output_filename, implode(PHP_EOL, $all_output_lines) . PHP_EOL) !== false) {
        echo "Hasil juga berhasil disimpan ke file '$output_filename'.\n";
    } else {
        echo "Error: Gagal menyimpan hasil ke file '$output_filename'.\n";
    }
}


?>