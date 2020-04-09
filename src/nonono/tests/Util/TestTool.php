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
}
