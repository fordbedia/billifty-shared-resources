# Unit Test

## Invoice PDF totals

Invoice PDF templates must use `invoices.total_cents` as the source of truth for
the displayed invoice total.

`invoices.amount_due_cents` represents the remaining balance after payments. It
can legitimately become `0` when Stripe or PayPal marks an invoice as paid, so it
must not be used as the primary invoice total in generated PDFs.

When invoice financial fields, payment status, or payment-link metadata changes,
the stored PDF snapshot (`pdf_path`, `pdf_status`, `pdf_generated_at`,
`pdf_error`) should be invalidated so a stale PDF is not reused.

```shell
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -T mysql \
  sh -lc "MYSQL_PWD='root' mysqldump -u 'root' app_db --single-transaction --routines --triggers --events --no-tablespaces" \
  > shared-resources/src/TestCase/sqldumps/billifty.mysql.sql
```

## and run the test on specific class

Run every module test class one class at a time:

```shell
composer test
```

Or:

```shell
./bin/phpunit-by-class
```

Run a specific class through PHPUnit:

```shell
./vendor/bin/phpunit --filter=UserSubscriptionTest
```

Or

```shell
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec backend php artisan testdb:snapshot
```
