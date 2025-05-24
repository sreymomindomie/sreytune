<?php
$data = file('sreymomdata/rendang.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$dirs = [];

$template = file_get_contents('lp.txt');

foreach ($data as $item) {
    $item = trim($item);
    
    if (empty($item)) {
        continue;
    }
    
    preg_match('/^(.*?)\[SREYMOM\](.*?)\[SREYMOMINDOMIE\](.*)$/', $item, $matches);

    if (count($matches) === 4) {
        $dir = $matches[1];
        $title = $matches[2];
        $description = $matches[3];
        
        if (!is_dir($dir)) {
            mkdir($dir);
            $dirs[] = $dir;
        }

        $htmlContent = str_replace(
            ['$title', '$description', '$dir'], 
            [$title, $description, $dir], 
            $template
        );

        file_put_contents("$dir/index.php", $htmlContent);
    } else {
        echo "Format tidak sesuai pada baris: $item\n";
    }
}

file_put_contents('dor.txt', implode(PHP_EOL, $dirs), FILE_APPEND);

echo "Direktori dan file index.php telah berhasil dibuat dan nama direktori disimpan dalam dor.txt.";
?>
