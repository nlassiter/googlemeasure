googlemeasure
=============

PHP class to post to google measure protocol

example usage:
```
$ga = new google_measure($_COOKIE['ua_client_id']);
$ga->setCustomDimension(3, $user->username)
	->createEvent('listing', 'new', '', $listing_id);
```
			

$_COOKIE['ua_client_id'] was already set from a login post that included the google client id:
```
ga(function(tracker) {
  var clientId = tracker.get('clientId');
});
```
