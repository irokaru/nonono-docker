<?php

namespace nonono\usajump\Model;

class Usa
{
    protected static $_name = '';

    protected static $_color = [
        'r' => 0, 'g' => 0, 'b' => 0,
    ];

    protected static $_jump_power = 0;

    protected static $_jump_arc = 0;

    protected static $_accessory = [];

    const COLOR_LENGTH = 3;
    const COLOR_MIN    = 0;
    const COLOR_MAX    = 255;

    const ACCESSORY_LENGTH = 10;
    const ACCESSORY_MIN    = -1;
    const ACCESSORY_MAX    = 99;

    // --------------------------------------------------------------

    /**
     * 初期化処理
     * @param array $params
     * @return Usa|null
     */
    public function init($params)
    {
        if (!is_array($params)) {
            return null;
        }

        if (!$this->_toParam($params)) {
            return null;
        }

        return $this;
    }

    /**
     * パラメータを文字列の一行に変換する
     * @return string
     */
    public function toStringLine(): string
    {
        $array = [
            static::$_name, ...static::$_color, static::$_jump_power, static::$_jump_arc, ...static::$_accessory
        ];

        return join(',', $array);
    }

    /**
     * パラメータを配列に変換する
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name'       => static::$_name,
            'color'      => static::$_color,
            'jump_power' => static::$_jump_power,
            'jump_arc'   => static::$_jump_arc,
            'accessory'  => static::$_accessory
        ];
    }

    // --------------------------------------------------------------

    /**
     * パラメータを各変数に格納するやつ
     * @param array $params
     * @return bool
     */
    protected function _toParam($params): bool
    {
        if (!static::_validate($params)) {
            return false;
        }

        static::$_name       = $params['name'];
        static::$_color      = preg_split('/,/', $params['color']);
        static::$_jump_power = $params['jump_power'];
        static::$_jump_arc   = $params['jump_arc'];
        static::$_accessory  = preg_split('/,/', $params['accessory']);

        return true;
    }

    /**
     * バリデーション
     * @param array $array
     * @return bool
     */
    protected static function _validate($array): bool
    {
        if (!is_array($array)) {
            return false;
        }

        // 型チェック
        $validate_methods = [
            'name'       => 'is_string',
            'color'      => 'is_string',
            'jump_power' => 'static::_isStringInt',
            'jump_arc'   => 'static::_isStringInt',
            'accessory'  => 'is_string',
        ];

        foreach ($validate_methods as $key => $method) {
            if (!isset($array[$key])) {
                return false;
            }

            if (!call_user_func($method, $array[$key])) {
                return false;
            }
        }

        // color
        if (!static::_validateCommaParams($array['color'], self::COLOR_LENGTH, self::COLOR_MIN, self::COLOR_MAX)) {
            return false;
        }

        // accessory
        if (!static::_validateCommaParams($array['accessory'], self::ACCESSORY_LENGTH, self::ACCESSORY_MIN, self::ACCESSORY_MAX)) {
            return false;
        }

        return true;
    }

    /**
     * 文字列が整数かどうか
     * @param string $val
     * @return bool
     */
    protected static function _isStringInt($val): bool
    {
        return preg_match('/^-?\d+$/', $val) !== 0;
    }

    /**
     * コンマ区切りのパラメータの数と数値をバリデーションするやつ
     * @param string $params
     * @param int $length
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected static function _validateCommaParams($params, $length, $min, $max): bool
    {
        $array = static::_splitParams($params);

        if (count($array) !== $length) {
            return false;
        }

        $filtered = array_filter($array, function ($num) use ($min, $max) {
            return static::_isStringInt($num) && $min <= $num && $num <= $max;
        });

        return count($filtered) === $length;
    }

    /**
     * パラメータを分割して配列にするやつ
     * @param string $param
     * @return bool
     */
    protected static function _splitParams($param): array
    {
        return preg_split('/,/', $param);
    }
}
