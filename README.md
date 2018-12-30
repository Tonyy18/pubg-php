# php-pubg
PHP wrapper for PUBG Tracker api 

<h2>Functions</h2>
<b>Constructor</b>    
<p>Put your API key in constructor otherwise other functions throws exceptions</p>

```php
require "php-pubg.php";

$api = new pubg("your api key");
```

<b>nickname</b>
<p>Function returns full player object for given nickname</p>

```php
$player = $api->nickname("nickname");
````

<p>Use array as second argument to restricts the player search. Season, region and mode options are allowed</p>
<p>Regions</p>

```
eu, na, as, oc, sa, sea, krjp
```

<p>Modes</p>

```
solo, duo, squad, solo-fpp, duo-fpp, squad-fpp
```

```php
$player = $api->nickname("nickname", array(
  "season" => "2017-pre3",
  "region" => "eu",
  "mode" => "solo"
));
````

<b>steamid</b>
<p>Returns small object including your account id, nickname, steamId and steamName. Used only with 64 bit steam id</p>

```php
$player = $api->steamid("76561198201055225");
```

<b>matches</b>
<p>Returns match list for given account id. You can get your account id from previous functions.<p>

```php
$player = $api->steamid("76561198201055225");

$accountId = $player->accountId

$matches = $api->matches($accountId);
```

<b>disableExceptions</b>
<p>Turn off all exceptions by calling this function. This function takes no arguments.</p>
<p>Call this function again to turn exceptions on.</p>

```php
$api->disableExceptions();
```

