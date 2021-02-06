<?php

namespace nonono\usajump\Model;

class UsaFile
{
    const FILE_PATH = __DIR__ . '/../../score/';

    const SCORE_FILE_NAME = 'score.json';

    /**
     * プレイデータをファイルに反映するやつ
     * @param Usa $usa
     * @return bool
     */
    public static function putScore($usa): bool
    {
        $usa_array = $usa->toArray();

        $file_path = self::FILE_PATH . self::SCORE_FILE_NAME;

        if (!file_exists($file_path)) {
            file_put_contents($file_path, '{}');
        }

        $datas = json_decode(file_get_contents($file_path), true);

        if (static::_hasDublicateData($usa_array, $datas)) {
            return true;
        }

        array_push($datas, $usa_array);

        if (!file_put_contents($file_path, json_encode($datas))) {
            return false;
        }

        return true;
    }

    /**
     * スコアをファイルから読み込むやつ
     * @param int $length
     * @param bool $unique
     * @return string
     */
    public static function getScores($length, $unique = true): string
    {
        return '';
    }

    /**
     * OGP用のファイルを生成した上でファイルパスを返すやつ
     * @param array $params
     * @return string
     */
    public static function generateOgpImage($params): string
    {
        return '';
    }

    // --------------------------------------------------------------

    /**
     * 配列内に対象値と同一のデータが有るかを確認するやつ
     * @param mixed $target
     * @param array $array
     * @return bool
     */
    protected static function _hasDublicateData($target, $array): bool
    {
        foreach ($array as $data) {
            if ($target === $data) {
                return true;
            }
        }

        return false;
    }
}
