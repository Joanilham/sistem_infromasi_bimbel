<?php

$targetFolder = __DIR__ . '/../storage/app/public';
$linkFolder = __DIR__ . '/storage';

echo "<h3>Memperbaiki Storage Link</h3>";

if(file_exists($linkFolder)){
    echo "Folder 'storage' sudah ada di public directory.<br>";
    if(is_link($linkFolder)){
        echo "Itu adalah sebuah symlink (Shortcut). Menghapus symlink lama...<br>";
        unlink($linkFolder);
    } else {
        echo "<b>Peringatan:</b> 'storage' adalah folder biasa, bukan symlink. Jika folder ini kosong atau isinya tidak penting, hapus folder ini secara manual melalui cPanel File Manager, lalu refresh halaman ini.<br>";
    }
}

if(!file_exists($linkFolder)){
    try {
        symlink($targetFolder, $linkFolder);
        echo "<b style='color:green;'>Sukses! Symlink berhasil dibuat.</b><br>";
    } catch(Exception $e) {
        echo "<b style='color:red;'>Error pembuatan symlink:</b> " . $e->getMessage() . "<br>";
        echo "<br>Catatan: Fitur symlink mungkin dinonaktifkan oleh server hosting Anda. Silakan hubungi tim support hosting Anda untuk mengaktifkan fungsi 'symlink' pada PHP.<br>";
    }
}

// Menghapus cache view secara manual
$viewCacheDir = __DIR__ . '/../storage/framework/views';
if(is_dir($viewCacheDir)){
    $files = glob($viewCacheDir . '/*');
    $count = 0;
    foreach($files as $file){
        if(is_file($file) && basename($file) !== '.gitignore'){
            unlink($file);
            $count++;
        }
    }
    echo "<br><b>Berhasil menghapus $count file cache views!</b><br>";
}

echo "<br>Selesai! Silakan cek kembali website Anda.";
