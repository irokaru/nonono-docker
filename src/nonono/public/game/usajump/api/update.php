<?php

use nonono\usajump\Model\Util;

require_once __DIR__ . '/Model/Util.php';

// --------------------------------------------------------------

const FILE_PATH = __DIR__ . '/../update/';

const FILE_WEBPATH = '/game/usajump/update/';

// --------------------------------------------------------------

if (!Util::isWoditor()) {
    Util::response(400, 'bad request');
    return;
}

// --------------------------------------------------------------
// 更新のチェック

$updater = json_decode(file_get_contents(FILE_PATH . 'versions.json'), true);

$file_pathes = [];

foreach (array_keys($updater) as $key) {
    if (!isset($_GET[$key])) {
        Util::response(422, 'validate error');
        exit;
    }

    $now_version    = $_GET[$key];
    $latest_version = $updater[$key];

    if ($now_version >= $latest_version) {
        continue;
    }

    $path = FILE_PATH . "$latest_version/$key.wolf";

    array_push($file_pathes, $path);
}

if (count($file_pathes) === 0) {
    Util::response(200, 'latest');
    exit;
}

// --------------------------------------------------------------
// 更新用ファイルの作成

$hash = hash('ripemd160', implode(',', $file_pathes));

$zip_path = FILE_PATH . "$hash.zip";

if (!file_exists($zip_path)) {
    $zip = new ZipArchive();

    if (!$zip->open($zip_path, ZipArchive::CREATE)) {
        Util::response(500, 'fail zip create');
        exit;
    }

    foreach ($file_pathes as $path) {
        $zip->addFile($path, Util::cutoutFileName($path));
    }

    $zip->close();
}

$zip_webpath = Util::domain() . FILE_WEBPATH . "$hash.zip";

Util::response(200, $zip_webpath);
