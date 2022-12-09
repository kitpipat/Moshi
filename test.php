
<?php

$filepath = "D:\AdaLinkMoshi\Export\Export\Success\'.$_GET[file].'.zip";
$filename = basename($filepath);
// if (!$new_filename) {
//     $new_filename = $filename;
// }
$mime_type = mime_content_type($filepath);
header('Content-type: '.$mime_type);
header('Content-Disposition: attachment; filename="'.$_GET['file'].'.zip"');
readfile($filepath);

?>