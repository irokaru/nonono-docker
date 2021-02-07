<?php

use nonono\usajump\Model\Usa;
use nonono\usajump\Model\UsaFile;
use nonono\usajump\Model\Util;

require_once __DIR__ . '/Model/Usa.php';
require_once __DIR__ . '/Model/UsaFile.php';
require_once __DIR__ . '/Model/Util.php';

// --------------------------------------------------------------

$usa = new Usa();

if (!$usa->init($_GET)) {
    Util::response(422, 'validate error');
    return;
}

if (UsaFile::putScore($usa)) {
    Util::response(200, 'success');
} else {
    Util::response(400, 'error');
}
