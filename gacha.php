<?php

// Nama file input
$dirFile = 'keyboard.txt';
$titleFile = 'title.txt';
$descFile = 'deskripsi.txt';

// Delimiter baru untuk output
$delimiter1 = '[SREYMOM]';
$delimiter2 = '[SREYMOMINDOMIE]';

// --- Membaca isi file ke dalam array ---
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

// Periksa apakah file direktori kosong
if ($num_dirs == 0) {
    echo "Tidak ada data direktori di '$dirFile' untuk diproses.\n";
    exit;
}

// Periksa apakah file template judul atau deskripsi kosong, karena akan digunakan untuk pemilihan acak
if ($num_titles == 0) {
    die("Error: File '$titleFile' kosong. Tidak ada template judul untuk dipilih secara acak.\n");
}
if ($num_descs == 0) {
    die("Error: File '$descFile' kosong. Tidak ada template deskripsi untuk dipilih secara acak.\n");
}

echo "Info: Akan memproses $num_dirs item berdasarkan jumlah direktori di '$dirFile'.\n";
echo " - '$titleFile' memiliki $num_titles template judul (akan dipilih secara acak).\n";
echo " - '$descFile' memiliki $num_descs template deskripsi (akan dipilih secara acak).\n\n";

$all_output_lines = []; // Array untuk menyimpan semua hasil jika ingin disimpan ke file

// --- Melakukan perulangan untuk setiap direktori dari keyboard.txt ---
for ($i = 0; $i < $num_dirs; $i++) {
    $current_dir = trim($dirs[$i]);

    // Lewati jika nama direktori kosong setelah di-trim
    if (empty($current_dir)) {
        echo "Info: Baris direktori ke-" . ($i + 1) . " pada '$dirFile' kosong. Item ini dilewati.\n";
        continue;
    }

    // Pilih template judul secara acak dari $title_templates
    $random_title_key = array_rand($title_templates);
    $current_title_template = trim($title_templates[$random_title_key]);

    // Pilih template deskripsi secara acak dari $description_templates
    $random_desc_key = array_rand($description_templates);
    $current_description_template = trim($description_templates[$random_desc_key]);

    // Mengganti placeholder '$dir' di judul dan deskripsi dengan nama direktori saat ini
    $processed_title = str_replace('$dir', $current_dir, $current_title_template);
    $processed_description = str_replace('$dir', $current_dir, $current_description_template);

    // Membuat baris output sesuai format yang diinginkan
    $output_line = $current_dir . $delimiter1 . $processed_title . $delimiter2 . $processed_description;

    // Mencetak baris output ke layar
    echo $output_line . PHP_EOL;
    
    // Tambahkan juga ke array $all_output_lines jika ingin disimpan ke file
    $all_output_lines[] = $output_line;
}

echo "\nSelesai memproses $num_dirs item.\n";

// Opsional: Jika kamu ingin menyimpan hasilnya ke file baru, misalnya 'rendang.txt'
// Bagian ini sekarang menggunakan $all_output_lines yang sudah diisi dari loop utama.
if (!empty($all_output_lines)) {
    $output_filename = 'rendang.txt'; // Kamu bisa ganti nama file output jika perlu
    if (file_put_contents($output_filename, implode(PHP_EOL, $all_output_lines) . PHP_EOL) !== false) {
        echo "Hasil juga berhasil disimpan ke file '$output_filename'.\n";
    } else {
        echo "Error: Gagal menyimpan hasil ke file '$output_filename'.\n";
    }
} else {
    // Ini seharusnya tidak terjadi jika $num_dirs > 0 dan tidak semua direktori kosong
    echo "Tidak ada output yang dihasilkan untuk disimpan ke file.\n";
}

?>