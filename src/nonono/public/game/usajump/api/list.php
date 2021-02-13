<?php

use nonono\usajump\Model\UsaFile;
use nonono\usajump\Model\Util;

require_once __DIR__ . '/Model/UsaFile.php';
require_once __DIR__ . '/Model/Util.php';

// --------------------------------------------------------------

if (!Util::isWoditor()) {
    Util::response(400, 'bad request');
    return;
}

$length = $_GET['length'] ?? 3;
$name   = $_GET['name'] ?? '';
$unique = isset($_GET['unique']) ? boolval($_GET['unique']) : true;

Util::response(200, UsaFile::getScores($length, $name, $unique));
