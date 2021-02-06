<?php

use nonono\usajump\Model\UsaFile;

require_once __DIR__ . '/Model/UsaFile.php';

// --------------------------------------------------------------

$length = $_GET['length'] ?? 3;
$unique = isset($_GET['unique']) ? boolval($_GET['unique']) : true;

echo UsaFile::getScores($length, $unique);
