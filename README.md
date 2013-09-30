googlemeasure
=============

PHP class to post to google measure protocol

example usage:
		$ga = new google_measure('event', $_COOKIE['ua_client_id']);
		$ga->setEventCategory('listing')
			->setEventAction('new')
			->setEventValue($listing_id)
			->setCustomDimension(3, $user->username)
			->createEvent();
			

$_COOKIE['ua_client_id'] was already set from:
ga(function(tracker) {
  var clientId = tracker.get('clientId');
});
