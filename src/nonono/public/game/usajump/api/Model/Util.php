<?php

namespace nonono\usajump\Model;

class Util
{
    /**
     * 文字列が整数かどうか
     * @param string $val
     * @return bool
     */
    public static function isStringInt($val): bool
    {
        return preg_match('/^-?\d+$/', $val) !== 0;
    }

    /**
     * 文字列が正の整数かどうか
     * @param string $val
     * @return bool
     */
    public static function isStringUint($val): bool
    {
        return preg_match('/^\d+$/', $val) !== 0;
    }

    /**
     * コンマ区切りのパラメータの数と数値をバリデーションするやつ
     * @param string $params
     * @param int $length
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function isCommaParams($params, $length, $min, $max): bool
    {
        $array = static::_splitStringParams($params);

        if (count($array) !== $length) {
            return false;
        }

        $filtered = array_filter($array, function ($num) use ($min, $max) {
            return static::isStringInt($num) && $min <= $num && $num <= $max;
        });

        return count($filtered) === $length;
    }

    /**
     * 配列から一定数をランダムでピックアップする
     * @param array $array
     * @param int $length
     * @return array
     */
    public static function pickupArray($array, $length): array
    {
        shuffle($array);

        if ($length <= 0) {
            return $array;
        }

        return array_slice($array, 0, $length);
    }

    /**
     * レスポンスを生成する
     * @param int $code
     * @param string $message
     * @return void
     */
    public static function response($code, $message)
    {
        http_response_code($code);
        echo $message;

        return;
    }

    /**
     * パラメータを分割して配列にするやつ
     * @param string $param
     * @return bool
     */
    public static function _splitStringParams($param): array
    {
        return preg_split('/,/', $param);
    }
}
