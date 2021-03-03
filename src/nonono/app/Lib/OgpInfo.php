<?php

namespace App\Lib;

class OgpInfo
{
    protected $_title = '';

    protected $_description = '';

    protected $_thumbnail = '';

    protected $_param = '';

    public function __construct(array $params = [])
    {
        if (isset($params['title'])) {
            $this->_title = $params['title'];
        }

        if (isset($params['description'])) {
            $this->_description = $params['description'];
        }

        if (isset($params['thumbnail'])) {
            $this->_thumbnail = $params['thumbnail'];
        }

        if (isset($params['param'])) {
            $this->_param = $params['param'];
        }
    }

    /**
     * もともとのタイトルを返す
     * @return string
     */
    public function getOriginalTitle()
    {
        return $this->_title;
    }

    /**
     * タイトルを返す
     * @return string
     */
    public function getTitle()
    {
        return static::_getValue($this->getOriginalTitle());
    }

    /**
     * タイトルに仕込まれているメソッド名を返す
     * @return string
     */
    public function getMethodInTitle()
    {
        return static::_getMethodName($this->getOriginalTitle());
    }

    /**
     * タイトルにメソッド名が仕込まれているかを返す
     * @return boolean
     */
    public function hasMethodInTitle(): bool
    {
        return $this->getMethodInTitle() !== '';
    }

    /**
     * もともとの説明を返す
     * @return string
     */
    public function getOriginalDescription()
    {
        return $this->_description;
    }

    /**
     * 説明を返す
     * @return string
     */
    public function getDescription()
    {
        return static::_getValue($this->getOriginalDescription());
    }

    /**
     * 説明に仕込まれているメソッド名を返す
     * @return string
     */
    public function getMethodInDescription()
    {
        return static::_getMethodName($this->getOriginalDescription());
    }

    /**
     * 説明にメソッド名が仕込まれているかを返す
     * @return boolean
     */
    public function hasMethodInDescription(): bool
    {
        return $this->getMethodInDescription() !== '';
    }

    /**
     * もともとのサムネイルパスを返す
     * @return string
     */
    public function getOriginalThumbnail()
    {
        return $this->_thumbnail;
    }

    /**
     * サムネイルパスを返す
     * @return string
     */
    public function getThumbnail()
    {
        return static::_getValue($this->getOriginalThumbnail());
    }

    /**
     * サムネイルパスに仕込まれているメソッド名を返す
     * @return string
     */
    public function getMethodInThumbnail()
    {
        return static::_getMethodName($this->getOriginalThumbnail());
    }

    /**
     * サムネイルパスにメソッド名が仕込まれているかを返す
     * @return boolean
     */
    public function hasMethodInThumbnail(): bool
    {
        return $this->getMethodInThumbnail() !== '';
    }

    /**
     * パラメータを返す
     * @return string
     */
    public function getParam()
    {
        return $this->_param;
    }

    /**
     * 各種パラメータのキーを基に各種値に置き換えたタイトルを返す
     * @param array $params
     * @return string
     */
    public function replaceTitleWithParam($params): string
    {
        return static::_replaceValueWithParam($this->getTitle(), $params);
    }

    /**
     * 各種パラメータのキーを基に各種値に置き換えた説明を返す
     * @param array $params
     * @return string
     */
    public function replaceDescriptionWithParam($params): string
    {
        return static::_replaceValueWithParam($this->getDescription(), $params);
    }

    /**
     * 各種パラメータのキーを基に各種値に置き換えたサムネイルパスを返す
     * @param array $params
     * @return string
     */
    public function replaceThumbnailWithParam($params): string
    {
        return static::_replaceValueWithParam($this->getThumbnail(), $params);
    }

    // ======================================================================

    /**
     * 2つの中括弧で囲まれた変数置換用スコープをエスケープする
     * @param string $str
     * @return string
     */
    protected static function escapeReplaceScope($str): string
    {
        return preg_replace('/\{\{(.+?)\}\}/', '\\\{\\\{$1\\\}\\\}', $str);
    }

    /**
     * エスケープされた変数置換用スコープをもとに戻す
     * @param string $str
     * @return string
     */
    protected static function unescapeReplaceScope($str): string
    {
        return preg_replace('/\\\{\\\{(.+?)\\\}\\\}/', '{{$1}}', $str);
    }

    /**
     * 対象から値を抜き出して返す
     * @param string $target
     * @return string
     */
    protected static function _getValue($target)
    {
        if (!preg_match('/##/', $target)) {
            return $target;
        }

        return explode('##', $target)[1];
    }

    /**
     * 対象からメソッドを抜き出して返す
     * @param string $target
     * @return string
     */
    protected static function _getMethodName($target)
    {
        if (!preg_match('/##/', $target)) {
            return '';
        }

        return explode('##', $target)[0];
    }

    /**
     * 値をパラメータを基に書き換えて返す
     * @param string $target
     * @param array $params
     * @param string
     */
    protected static function _replaceValueWithParam($target, $params)
    {
        foreach ($params as $key => $value) {
            $target = preg_replace("/\{\{$key\}\}/", static::escapeReplaceScope($value), $target);
        }

        return static::unescapeReplaceScope($target);
    }
}
