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
if (!ratelimiter($_SERVER["REMOTE_ADDR"], "myservice", 5, "10 MINUTE")) exit("You are being rate limited");
else /* do stuff */;
```
Please make sure to check LICENSE before using.
Commercial use is prohibited.

### installation
Clone the repository in your desired folder.
```
git clone https://github.com/GSRV-io/ratelimiter.git .
```
Run the following SQL query to initialise the table.
```sql
CREATE TABLE `ratelimits` (
  `ip` varchar(256) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `service` varchar(128) NOT NULL DEFAULT 'general',
  `requests` int(11) NOT NULL DEFAULT 1,
  `time` datetime NOT NULL DEFAULT utc_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
The code looks for a variable named `$conn` that defines a MySQL connection. The table must be available within this connection. To use a variable with a different name, replace all occurrances of `$conn` in `ratelimiter.php` with your variable.
