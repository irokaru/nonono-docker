<?php

namespace Tests\Util;

use JWTAuth;

class TestTool
{
    /**
     * 複数の配列の組み合わせを作成する
     *
     * @param array $arrays 配列の配列
     * @return array
     */
    public static function combine(): array
    {
        $args = func_get_args();

        $a = array_shift($args);
        $b = array_shift($args);

        $result = array();
        foreach ($a as $val1) {
            foreach ($b as $val2) {
                $result[] = array_merge((array)$val1, (array)$val2);
            }
        }

        if (count($args) > 0) {
            foreach ($args as $arg) {
                $result = self::combine($result, $arg);
            }
        }

        return $result;
    }

    /**
     * 認証済みユーザ情報からjwtトークンを取得する
     * @param  \App\Models\Admin $admin
     * @return array
     */
    public static function getJwtAuthorization($admin): array
    {
        $token = JWTAuth::fromUser($admin);
        return ['Authorization' => "Bearer $token"];
    }

    /**
     * モデルを配列に変換する
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $reject_keys
     * @return array
     */
    public static function model2array($model, $reject_keys=[]): array
    {
        $array = $model->toArray();
        foreach($reject_keys as $key) {
            unset($array[$key]);
        }
        return $array;
    }

    /**
     * コレクションを配列に変換する
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param array $reject_keys
     * @return array
     */
    public static function collection2array($collection, $reject_keys=[]): array
    {
        $array = $collection->toArray();
        for ($i = 0; $i < count($array); $i++) {
            foreach($reject_keys as $key) {
                unset($array[$i][$key]);
            }
        }

        return $array;
    }
}
