# Unit Test

```shell
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -T mysql \
  sh -lc "MYSQL_PWD='root' mysqldump -u 'root' app_db --single-transaction --routines --triggers --events --no-tablespaces" \
  > shared-resources/src/TestCase/sqldumps/billifty.mysql.sql
```

## and run the test on specific class

```shell
./vendor/bin/phpunit --filter=UserSubscriptionTest
```

Or

```shell
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec backend php artisan testdb:snapshot
```