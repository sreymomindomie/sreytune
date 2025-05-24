<?php
// Function to generate a pretty-printed XML string
function prettify_xml($xml) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    return $dom->saveXML();
}

// Function to create a combined sitemap
function create_combined_sitemap($brands) {
    // Create the root element 'urlset' with a namespace
    $urlset = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

    // Loop through the brands and add each one to the sitemap
    foreach ($brands as $brand) {
        $url = "https://cursos.fundacionisys.org/my/-/$brand/";

        // Create 'url' element
        $url_element = $urlset->addChild('url');
        $url_element->addChild('loc', $url);
        $url_element->addChild('lastmod', date('Y-m-d'));
        $url_element->addChild('changefreq', 'daily');
        $url_element->addChild('priority', '0.8');
    }

    // Convert the XML to a formatted string
    $pretty_xml = prettify_xml($urlset);
    
    // Write the formatted XML to a file
    $sitemap_file = 'sitemap.xml';
    file_put_contents($sitemap_file, $pretty_xml);
    
    return $sitemap_file;
}

// Function to read the brands from a text file
function read_brands($file_path) {
    if (!file_exists($file_path)) {
        return [];
    }
    // Read file contents into an array, each line is an array element
    return file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

// Main function
function main() {
    $brand_file = 'dir.txt';
    
    // Check if the brand file exists
    if (!file_exists($brand_file)) {
        echo "File $brand_file tidak ditemukan.\n";
        return;
    }

    // Read the brands from the file
    $brands = read_brands($brand_file);
    
    // Create the combined sitemap
    $sitemap_file = create_combined_sitemap($brands);
    echo "Sitemap gabungan dibuat: $sitemap_file\n";
}

// Call the main function
main();
?>
