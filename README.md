# nonono-docker

docker ベースの nonono システム

- Laravel 6
- vue

## 入れたらやること

1. 環境変数の設定

```bash
vi .env

DB_NAME=db
DB_USER=username
DB_PASS=passwd
TZ=Asia/Tokyo
```

2. Laravel のインストール

```bash
docker-compose exec app ash

cd /var/www/html/nonono
composer install

cp .env.sample .env
php artisan key:genelate

# あとは Laravel で使う DB の設定とか...
```

3. vue のインストール

```bash
docker-compose exec node ash

cd /var/www/html/nonono
npm run prod
```
