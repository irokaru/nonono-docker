<?php

namespace nonono\usajump\Model;

require_once __DIR__ . '/Usa.php';

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

        $file_path = static::_scoreFilePath();

        if (!file_exists($file_path)) {
            static::_touchScoreFile();
        }

        $datas = json_decode(file_get_contents($file_path), true);

        if (static::_hasDuplicateData($usa_array, $datas)) {
            return true;
        }

        array_unshift($datas, $usa_array);

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
    public static function getScores($length = 3, $unique = true): string
    {
        if (!Util::isStringUint($length) || $length == 0) {
            $length = 3;
        }

        if (!is_bool($unique)) {
            $unique = true;
        }

        $file_path = static::_scoreFilePath();

        if (!file_exists($file_path)) {
            static::_touchScoreFile();
        }

        $datas = json_decode(file_get_contents($file_path), true);

        if ($unique) {
            $datas = static::_deleteDuplicateItemAsValue($datas, 'name');
        }

        $usa_array = [];

        foreach($datas as $data) {
            $data['color']     = implode(',', $data['color']);
            $data['accessory'] = implode(',', $data['accessory']);

            $usa = new Usa();

            if (!$usa->init($data)) {
                continue;
            }

            array_push($usa_array, $usa->toStringLine());
        }

        return implode("\n", Util::pickupArray($usa_array, $length));
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
    protected static function _hasDuplicateData($target, $array): bool
    {
        foreach ($array as $data) {
            if ($target === $data) {
                return true;
            }
        }

        return false;
    }

    /**
     * 連想配列内で$keyと重複しているデータがあれば削除して返すやつ
     * @param array $array
     * @param string $key
     * @return string
     */
    protected static function _deleteDuplicateItemAsValue($array, $key): array
    {
        $unique_array = [];
        $value_array  = [];

        foreach ($array as $item) {
            $value = $item[$key];

            if (!in_array($value, $value_array)) {
                $value_array[]  = $value;
                $unique_array[] = $item;
            }
        }

        return $unique_array;
    }

    /**
     * スコア記録用ファイルを生成するやつ
     * @return int|false
     */
    protected static function _touchScoreFile(): string
    {
        return file_put_contents(static::_scoreFilePath(), '[]');
    }

    /**
     * スコア記録用ファイルのパス
     * @return string
     */
    protected static function _scoreFilePath(): string
    {
        return self::FILE_PATH . self::SCORE_FILE_NAME;
    }
}