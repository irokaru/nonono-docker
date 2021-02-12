<?php

namespace nonono\usajump\Model;

require_once __DIR__ . '/Util.php';

class Usa
{
    protected static $_name = '';

    protected static $_color = [
        'r' => 0, 'g' => 0, 'b' => 0,
    ];

    protected static $_jump_power = 0;

    protected static $_jump_arc = 0;

    protected static $_jump_start_cnt = 0;

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
            static::$_name, ...static::$_color, static::$_jump_power, static::$_jump_arc, static::$_jump_start_cnt, ...static::$_accessory
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
            'name'           => static::$_name,
            'color'          => static::$_color,
            'jump_power'     => static::$_jump_power,
            'jump_arc'       => static::$_jump_arc,
            'jump_start_cnt' => static::$_jump_start_cnt,
            'accessory'      => static::$_accessory,
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

        static::$_name           = $params['name'];
        static::$_color          = preg_split('/,/', $params['color']);
        static::$_jump_power     = $params['jump_power'];
        static::$_jump_arc       = $params['jump_arc'];
        static::$_jump_start_cnt = $params['jump_start_cnt'];
        static::$_accessory      = preg_split('/,/', $params['accessory']);

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
            'name'           => 'is_string',
            'color'          => 'is_string',
            'jump_power'     => 'nonono\usajump\Model\Util::isStringInt',
            'jump_arc'       => 'nonono\usajump\Model\Util::isStringInt',
            'jump_start_cnt' => 'nonono\usajump\Model\Util::isStringUInt',
            'accessory'      => 'is_string',
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
        if (!Util::isCommaParams($array['color'], self::COLOR_LENGTH, self::COLOR_MIN, self::COLOR_MAX)) {
            return false;
        }

        // accessory
        if (!Util::isCommaParams($array['accessory'], self::ACCESSORY_LENGTH, self::ACCESSORY_MIN, self::ACCESSORY_MAX)) {
            return false;
        }

        return true;
    }
}
