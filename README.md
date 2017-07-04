# Top Domain

Top 1 million domains list. Random domain. Check if your domain is in the top 1 million. Get a list of X domains.

This library utilizes Alexa's `Top 1 Million Domains` data file:

```
http://s3.amazonaws.com/alexa-static/top-1m.csv.zip
```

## Usage

```php
use peterkahl\TopDomain\TopDomain;

$tdomObj = new TopDomain;
$tdomObj->CacheDir = '/srv/cache';

#-----------------------------------------
# Get random domain
$temp = $tdomObj->RandomDomain();
echo $temp['domain'] .' ............. '. $temp['rank'] ."\n";

#-----------------------------------------
# Check if given domain is in the top 1 million.
$temp = $tdomObj->FindDomain('alipay.com');

echo $temp['domain'];
if (empty($temp)) {
  echo ' is not in the top 1 million.' ."\n";
}
else {
  echo ' is in the top 1 million with rank '. $temp['rank'] .'.' ."\n";
}

#-----------------------------------------
# Get a list of domains 1 through 100.
$temp = $tdomObj->GetDomains(1, 100);

foreach ($temp as $val) {
  echo $val['rank'] .' ............. '. $val['domain']  ."\n";
}

```

## Important

You probably want to set up a crontab job to periodically update the Alexa data file, perhaps by using the shell script `top-domain-fetch-file.sh`. Don't forget to edit this file with the correct location of your cache directory!