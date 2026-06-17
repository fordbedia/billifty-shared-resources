# Scheduler

This document covers how to verify and manually test scheduled invoice payment
reminders in the local Docker environment.

## Invoice Payment Reminders

The scheduler registration lives in `backend/routes/console.php`:

```php
Schedule::command('invoice-reminders:send-due')
    ->everyFifteenMinutes()
    ->withoutOverlapping();
```

This scheduled command does not send emails directly. It finds due reminder rows
and dispatches `SendInvoicePaymentReminderJob`. The queue worker sends the email.

A reminder is eligible when all of these are true:

- `invoice_payment_reminders.status = pending`
- `invoice_payment_reminders.scheduled_at <= now()`
- The invoice has `payment_reminders_enabled = true`
- The invoice is unpaid
- The invoice has `amount_due_cents > 0`

The application timezone is UTC in Docker, so use UTC when setting test
timestamps.

## Check The Schedule

List scheduled tasks:

```shell
docker exec invoice-backend-1 sh -lc 'cd /var/www/html && php artisan schedule:list'
```

Run only the reminder schedule entry:

```shell
docker exec invoice-backend-1 sh -lc 'cd /var/www/html && php artisan schedule:test --name="invoice-reminders:send-due"'
```

Check scheduler logs:

```shell
docker logs --tail=120 invoice-scheduler-1
```

## Manual Test

### 1. Make One Reminder Due

Open MySQL:

```shell
docker exec -it billifty_mysql mysql -u billifty_u -p app_db
```

Update one reminder so it is due now:

```sql
UPDATE invoice_payment_reminders
SET scheduled_at = UTC_TIMESTAMP() - INTERVAL 1 MINUTE,
    status = 'pending',
    sent_at = NULL,
    attempts = 0,
    last_error = NULL
WHERE id = 1;
```

Make sure the related invoice is eligible:

```sql
SELECT r.id,
       r.status,
       r.scheduled_at,
       i.invoice_number,
       i.status AS invoice_status,
       i.payment_reminders_enabled,
       i.amount_due_cents,
       i.paid_at
FROM invoice_payment_reminders r
JOIN invoices i ON i.id = r.invoice_id
WHERE r.id = 1;
```

### 2. Dispatch Due Reminder Jobs

Run the command manually:

```shell
docker exec invoice-backend-1 sh -lc 'cd /var/www/html && php artisan invoice-reminders:send-due'
```

Expected output:

```text
Dispatched 1 invoice payment reminder job(s).
```

If it says `Dispatched 0`, the reminder row does not match the eligibility
query.

### 3. Run The Queue Job

Process one queued job:

```shell
docker exec invoice-backend-1 sh -lc 'cd /var/www/html && php artisan queue:work --once -vvv'
```

The normal queue worker container should also pick up the job automatically, but
this command is useful when testing a single reminder.

### 4. Confirm The Result

Check the reminder row:

```sql
SELECT id, status, scheduled_at, sent_at, attempts, last_error
FROM invoice_payment_reminders
WHERE id = 1;
```

After a successful email, expect:

```text
status = sent
sent_at is not null
last_error is null
```

## Troubleshooting

If `invoice-reminders:send-due` dispatches `0` jobs, check the reminder status,
`scheduled_at`, and invoice eligibility fields.

If it dispatches `1` job but no email arrives, the scheduler is working. Check
the queue worker, mail configuration, failed jobs, and application logs:

```shell
docker exec invoice-backend-1 sh -lc 'cd /var/www/html && php artisan queue:failed'
docker logs --tail=120 invoice-queue-worker-1
docker exec invoice-backend-1 sh -lc 'cd /var/www/html && tail -200 storage/logs/laravel.log'
```
