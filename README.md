# CronLock

> Load balanced cron jobs. Backed by Predis.

## install
```
composer require loganhenson/cronlock
```

## sample usage

```php
// 5 minutes after job, it will release the lock
$CronLock = new CronLock(new Client());

// a nice key might be `__CLASS__ . __METHOD__`
$CronLock->cron('some unique key for the job', function () {
	//do some cron stuff
});
```

## License

MIT

## Inspiration / Credit to

> https://github.com/AlexDisler/MutexLock
