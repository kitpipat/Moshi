<?php

    $tFile      = $_GET['pFile'];
    $tFilePath  = 'D:/AdaLinkMoshi/Export/Export/Success/'.$tFile.'.zip';
    $tFileName  = basename($tFilePath);

    $tMimeType = mime_content_type($tFilePath);
    header('Content-type: '.$tMimeType);
    header('Content-Disposition: attachment; filename='.$tFileName);
    readfile($tFilePath);

?>