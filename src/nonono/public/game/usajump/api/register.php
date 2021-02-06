<?php

use nonono\usajump\Model\Usa;
use nonono\usajump\Model\UsaFile;

require_once __DIR__ . '/Model/Usa.php';
require_once __DIR__ . '/Model/UsaFile.php';


// --------------------------------------------------------------

$usa = new Usa();

if (!$usa->init($_GET)) {
    http_response_code(422);
    echo 'validate error';
}

if (UsaFile::putScore($usa)) {
    echo 'success';
} else {
    http_response_code(400);
    echo 'fail';
}
