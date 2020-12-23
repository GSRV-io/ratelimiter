# ratelimiter
Simple and effective rate limiter using PHP and MySQL

### usage
```php
include("ratelimiter.php");
ratelimiter("255.255.255.255", "myservice", 5, "10 MINUTE"); // -> bool
```
If the returned value is `true`, you are not rate limited. Every time that you call this function, the `requests` column in the database will increment for the given service. Example use:
```php
include("ratelimiter.php");
if (ratelimiter($_SERVER["REMOTE_ADDR"], "myservice", 5, "10 MINUTE")) exit("You are being rate limited");
else /* do stuff */;
```
Please make sure to check LICENSE before using.
Commercial use is prohibited.
