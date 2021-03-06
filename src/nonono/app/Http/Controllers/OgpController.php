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
        '/products' => [
            'title'       => 'ぷろだくと',
            'description' => null,
            'thumbnail'   => null,
        ],
        '/blog' => [
            'title'       => 'にっき',
            'description' => null,
            'thumbnail'   => null,
        ],
        '/blog/\d+' => [
            'title'       => 'にっき',
            'description' => null,
            'thumbnail'   => null,
        ],
        '/blog/post/{{id}}' => [
            'title'       => 'getPostTitle##{{title}}',
            'description' => 'getPostDescription##{{description}}',
            'thumbnail'   => 'getPostThumbnail##{{thumbnail}}',
        ],
        '/blog/category/{{category}}' => [
            'title'       => '{{category}}のにっき一覧',
            'description' => null,
            'thumbnail'   => null,
        ],
        '/blog/category/{{category}}/\d+' => [
            'title'       => '{{category}}のにっき一覧',
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

        if ($info->hasMethodInTitle()) {
            $method = $info->getMethodInTitle();
            $title  = static::executeStaticMethod($method, $info->getParams());

            if ($title === '') {
                return static::DEFAULT_TITLE;
            }
        }

        return $info->replaceTitleWithParams() ?
               $info->replaceTitleWithParams() . ' - ' . static::DEFAULT_TITLE :
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
        $params['param'] = $info->getParams();

        if ($info->hasMethodInDescription()) {
            $method           = $info->getMethodInDescription();
            $params['value']  = static::executeStaticMethod($method, $params['param']);

            if ($params['value'] === '') {
                return static::DEFAULT_DESCRIPTION;
            }
        }

        $descriptioin = $info->replaceDescriptionWithParams($params) ?: static::DEFAULT_DESCRIPTION;
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
        $params['param'] = $info->getParams();

        if ($info->hasMethodInThumbnail()) {
            $method           = $info->getMethodInThumbnail();
            $params['value']  = static::executeStaticMethod($method, $params['param']);

            if ($params['value'] === '') {
                return static::DEFAULT_THUMBNAIL;
            }
        }

        return $info->replaceThumbnailWithParams($params) ?: static::DEFAULT_THUMBNAIL;
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
     * クエリストリング(URLの?以降)を削除して返す
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
        $list = static::$_path_list;

        foreach ($list as $key => $value) {
            ['result' => $result, 'params' => $params] = static::compareUrl($url, $key);

            if ($result) {
                $value['params'] = $params;
                return new OgpInfo($value);
            }
        }

        return new OgpInfo();
    }

    protected static function getPostTitle($params): string
    {
        if (!isset($params['id'])) {
            return '';
        }

        $post = Post::findOne($params['id']);
        return $post->title ?? '';
    }

    protected static function getPostDescription($params): string
    {
        if (!isset($params['id'])) {
            return '';
        }

        return PostResource::getPostContent($params['id']);
    }

    protected static function getPostThumbnail($params): string
    {
        if (!isset($params['id']) || static::getPostTitle($params) === '') {
            return '';
        }

        $exts        = ['.jpg', '.JPG', '.png', '.PNG'];
        $img_path    = __DIR__ . '/../../../public/img/post/';
        $img_webpath = '/img/post/';

        foreach ($exts as $ext) {
            $filename = $params['id'] . '_01' . $ext;

            if (file_exists($img_path . $filename)) {
                return $img_webpath . $filename;
            }
        }

        return '';
    }

    protected static function getCategoryTitle($params): string
    {
        if (!isset($params['category'])) {
            return '';
        }

        return urldecode($params['category']);
    }

    /**
     * URLを比べる
     * @param string $target
     * @param string $url
     * @return ['result' => bool, 'params' => array]
     */
    protected static function compareUrl($target, $url)
    {
        $splited_target = explode('/', $target);
        $splited_url    = explode('/', $url);

        if (count($splited_target) !== count($splited_url)) {
            return [
                'result' => false,
                'params' => [],
            ];
        }

        $ret    = true;
        $params = [];

        for ($idx = 0; $idx < count($splited_target); $idx++) {
            $piece_url    = $splited_url[$idx];
            $piece_target = $splited_target[$idx];

            if (preg_match("/^{{(.+?)}}$/", $piece_url, $var)) {
                $params[$var[1]] = $piece_target;
                continue;
            }

            if (!preg_match("/^$piece_url$/", $piece_target)) {
                $ret = false;
                break;
            }
        }

        return [
            'result' => $ret,
            'params' => $params,
        ];
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
