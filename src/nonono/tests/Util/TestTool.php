<?php

namespace Tests\Util;

use JWTAuth;

class TestTool
{
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
     * コレクションを配列に変換する
     * @param \Illuminate\Database\Eloquent\Collection $obj
     * @param array $reject_keys
     * @return array
     */
    public static function collection2array($obj, $reject_keys=[]): array
    {
        $array = $obj->toArray();
        for ($i = 0; $i < count($array); $i++) {
            foreach($reject_keys as $key) {
                unset($array[$i][$key]);
            }
        }

        return $array;
    }
}
