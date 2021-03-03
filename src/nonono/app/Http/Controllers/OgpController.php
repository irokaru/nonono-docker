<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Lib\OgpInfo;
use App\Models\Post;

class OgpController extends Controller
{
    const DEFAULT_TITLE = 'ののの茶屋';

    const DEFAULT_DESCRIPTION = 'ゲームづくりの甘味処';

    const DEFAULT_THUMBNAIL = '/img/thumbnail.png';

    protected static $_path_list = [
     // 'パス名(regex)' => [
     //     'title' => '表示したいタイトル(二重波括弧で変数焼き込み)',
     // ];
        '\/products' => [
            'title'       => 'ぷろだくと',
            'description' => null,
            'thumbnail'   => null,
        ],
        '\/blog' => [
            'title'       => 'にっき',
            'description' => null,
            'thumbnail'   => null,
        ],
        '\/blog\/(\d+)' => [
            'title'       => 'にっき',
            'description' => null,
            'thumbnail'   => null,
        ],
        '\/blog\/post\/(\d+)' => [
            'title'       => 'getPostTitle##{{value}}',
            'description' => 'getPostDescription##{{value}}',
            'thumbnail'   => 'getPostThumbnail##{{value}}',
        ],
        '\/blog\/category\/(.+?)\/(\d+)' => [
            'title'       => 'getCategoryTitle##{{value}}のにっき一覧',
            'description' => null,
            'thumbnail'   => null,
        ],
        '\/blog\/category\/(.+?)' => [
            'title'       => 'getCategoryTitle##{{value}}のにっき一覧',
            'description' => null,
            'thumbnail'   => null,
        ],
    ];


    /**
     * urlからページタイトルを返す
     * @param string $url
     * @return string
     */
    public static function getTitle(string $url)
    {
        $format_url = static::formatUrl($url);
        $info       = static::makeOgpInfoAsUrl($format_url);

        $params = [];
        $params['param'] = $info->getParam();

        if ($info->hasMethodInTitle()) {
            $method           = $info->getMethodInTitle();
            $params['value']  = static::executeStaticMethod($method, $params['param']);

            if ($params['value'] === '') {
                return static::DEFAULT_TITLE;
            }
        }

        return $info->replaceTitleWithParam($params) ?
               $info->replaceTitleWithParam($params) . ' - ' . static::DEFAULT_TITLE :
               static::DEFAULT_TITLE;
    }

    /**
     * urlからページ説明を返す
     * @param string $url
     * @return string
     */
    public static function getDescription(string $url)
    {
        $format_url = static::formatUrl($url);
        $info       = static::makeOgpInfoAsUrl($format_url);

        $params = [];
        $params['param'] = $info->getParam();

        if ($info->hasMethodInDescription()) {
            $method           = $info->getMethodInDescription();
            $params['value']  = static::executeStaticMethod($method, $params['param']);

            if ($params['value'] === '') {
                return static::DEFAULT_DESCRIPTION;
            }
        }

        $descriptioin = $info->replaceDescriptionWithParam($params) ?: static::DEFAULT_DESCRIPTION;
        $descriptioin = preg_replace('/\\\n|\\\r|\\\r\\\n/', '', $descriptioin);

        return mb_strimwidth($descriptioin, 0, 120, '...');
    }

    /**
     * urlからページサムネイルパスを返す
     * @param string $url
     * @return string
     */
    public static function getThumbnail(string $url)
    {
        $format_url = static::formatUrl($url);
        $info       = static::makeOgpInfoAsUrl($format_url);

        $params = [];
        $params['param'] = $info->getParam();

        if ($info->hasMethodInThumbnail()) {
            $method           = $info->getMethodInThumbnail();
            $params['value']  = static::executeStaticMethod($method, $params['param']);

            if ($params['value'] === '') {
                return static::DEFAULT_THUMBNAIL;
            }
        }

        return $info->replaceThumbnailWithParam($params) ?: static::DEFAULT_THUMBNAIL;
    }

    /**
     * urlからtwitter向けカード設定を返す
     * @param string $url
     * @return string
     */
    public static function getCardTypeForTwitter(string $url)
    {
        $result = static::getThumbnail($url);

        if ($result === static::DEFAULT_THUMBNAIL) {
            return 'summary';
        }

        return 'summary_large_image';
    }

    // ======================================================================

    /**
     * クエリストリング(URLの?以降)と最後のスラッシュを削除して返す
     * @param string $url
     * @return string
     */
    protected static function formatUrl(string $url): string
    {
        if (preg_match('/\?/', $url)) {
            $url = strstr($url,'?', true);
        }

        if (preg_match('/\/$/', $url)) {
            return substr($url, 0, -1);
        }

        return $url;
    }

     /**
     * 入力されたURLに合ったOgpInfoインスタンスを返す
     * @param string $url
     * @return \App\Lib\OgpInfo
     */
    protected static function makeOgpInfoAsUrl($url): \App\Lib\OgpInfo
    {
        $keys = array_keys(static::$_path_list);

        foreach ($keys as $key) {
            if (preg_match("/^$key$/", $url, $result)) {
                $path_data = static::$_path_list[$key];
                $param     = isset($result[1]) ? $result[1] : $result[0];

                return new OgpInfo([
                    'title'       => $path_data['title'],
                    'description' => $path_data['description'],
                    'thumbnail'   => $path_data['thumbnail'],
                    'param'       => $param,
                ]);
            }
        }

        return new OgpInfo();
    }

    protected static function getPostTitle($param): string
    {
        $post = Post::findOne($param);
        return $post->title ?? '';
    }

    protected static function getPostDescription($param): string
    {
        return PostResource::getPostContent($param);
    }

    protected static function getPostThumbnail($param): string
    {
        $exts        = ['.jpg', '.JPG', '.png', '.PNG'];
        $img_path    = __DIR__ . '/../../../public/img/post/';
        $img_webpath = '/img/post/';

        foreach ($exts as $ext) {
            $filename = $param . '_01' . $ext;

            if (file_exists($img_path . $filename)) {
                return $img_webpath . $filename;
            }
        }

        return '';
    }

    protected static function getCategoryTitle($param): string
    {
        return urldecode($param);
    }

    /**
     * メソッド名からstaticなメソッドを呼び出して結果を返す
     * @param callback $method
     * @param mixed $arg
     * @return mixed
     */
    protected static function executeStaticMethod($method, $arg)
    {
        return forward_static_call("static::$method", $arg);
    }
}
