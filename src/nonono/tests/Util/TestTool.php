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
     * 非公開メソッドを引っ張ってくる
     * `$method->invoke(class, $args);`
     *
     * @param \class  $class
     * @param string $method_name
     * @return \ReflectionMethod
     */
    public static function getProtectedMethod($class, $method_name): \ReflectionMethod
    {
        $ref    = new \ReflectionClass($class);
        $method = $ref->getMethod($method_name);

        $method->setAccessible(true);

        return $method;
    }

    /**
     * 非公開プロパティを引っ張ってくる
     * $property->getValue(class);
     * $property->setValue(class, $value);
     *
     * @param \class  $class
     * @param string $property_name
     * @return \ReflectionProperty
     */
    public static function getProtectedProperty($class, $property_name): \ReflectionProperty
    {
        $ref      = new \ReflectionClass($class);
        $property = $ref->getProperty($property_name);

        $property->setAccessible(true);

        return $property;
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
