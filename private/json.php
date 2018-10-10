<?php
	//Create a function if it does not exist.
	if (!function_exists('_vc')) {
		/**
		 * Checks if an argument exists and then outputs.
		 *
		 * @param object $var
		 * @param array $p
		 */
		function _vc($var, ...$p) {
			//Declare variables.
			$call		=	$var;

			//If the var exists.
			if (!isset($call)) {
				//Return false.
				return false;
			} else {
				//For each parameter.
				foreach($p as $param) {
					//Add to the parameter.
					$call	=	(!isset($call -> $param)) ? null : $call -> $param;

					//Check if the call exists.
					if (!isset($call) || !$call) {
						//Return false.
						return false;
					}
				}
			}

			//Return the var.
			return $call;
		}
	}

	//If the site state is not set.
	if (!isset($state)) {
		//Set the filename.
		$filename	=	__DIR__ . '/resource.json';

		//If an existing resource exists.
		if (file_exists($filename)) {
			//Get the resource data.
			$state	=	json_decode(file_get_contents($filename));
		}

		//Based on the override url.
		if (!isset($_GET['override'])) {
			//Set the resource url.
			$resourceUrl	=	"https://live.glpa.org/conference.json";
		} else {
			//If there is a state expires.
			if (_vc($state, 'expires')) {
				$state -> expires	=	0;
			}

			//Set the resource url.
			$resourceUrl	=	"https://live.glpa.org/conference-override.json";
		}

		//If the cache should be updated.
		if (!file_exists($filename) || !is_readable($filename) || time() > _vc($state, 'expires')) {
			//Delete the file if it exists.
			if (file_exists($filename)) unlink($filename);

			//Initiate cURL.
			$ch	=	curl_init();

			//Set cURL options.
			curl_setopt($ch, CURLOPT_URL, $resourceUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Connection: Keep-Alive',
				'Keep-Alive: 600'
			));

			//Execute the cURL.
			$r	=	curl_exec($ch);

			//Get info.
			$code	=	curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//If there is no response, or the request failed.
			if (!$r || $code !== 200) {
				//Send an error.
				trigger_error('"Unable to retrieve resource information: ' . curl_error($ch) . '"', E_USER_ERROR);
			} else {
				//Create a new standard class.
				$state	=	new \stdClass();

				//Assign values.
				$state -> expires	=	time() + 600;
				$state -> data		=	json_decode($r);

				//Save the data for later.
				if (!isset($_GET['override'])) file_put_contents($filename, json_encode($state), LOCK_EX);
			}
		}
	}

	//If there is no state data, send an error.
	if (!_vc($state, 'data')) trigger_error('"Resource information missing or invalid."', E_USER_ERROR);

	//Set the timezone.
	if (_vc($state, 'data', 'timezone')) date_default_timezone_set(_vc($state, 'data', 'timezone'));

	//Set the event day as active.
	$state -> data -> active	=	array('day' => null, 'event' => null, 'stream' => false);

	//Get the events per day.
	$eventDays =	_vc($state, 'data', 'event_days');

	//If there are days of events.
	if (count($eventDays) > 0) {
		//For each event set.
		foreach($eventDays as $k => $day) {
			//Get the date.
			$ts	=	strtotime(_vc($day, 'date') . " 00:01:00");

			//Set additional information for the day.
			$state -> data -> event_days[$k] -> dayString	=	sprintf("%s, %s %s<sup>%s</sup>",
					date('D', $ts),
					date('M', $ts),
					date('j', $ts),
					date('S', $ts)
			);

			//Get current event day information.
			$thisDay	=	_vc($day, 'date');

			//If the event is active.
			if ($thisDay == date('Y-m-d')) {
				//Set the event day as active.
				$state -> data -> active['day']	=	$k;

				//Run through each event.
				foreach(_vc($day, 'events') as $l => $event) {
					//Get the two timestamps.
					$startTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($event, 'start_time')));
					$endTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($event, 'end_time')));

					//If the time matches.
					if (time() >= $startTime && time() <= $endTime) {
						//Set the state data.
						$state -> data -> active['event']	=	$l;

						//Get the children.
						$childs	=	_vc($event, 'children');

						//If there are children.
						if (is_array($childs) && count($childs) > 0) {
							//For each child.
							foreach($childs as $m => $child) {
								//Get the two timestamps.
								$startTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($child, 'start_time')));
								$endTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($child, 'end_time')));

								//If the time matches.
								if (time() >= $startTime && time() <= $endTime) {
									//Set the state data.
									$state -> data -> active['stream']	=	$m;
								}
							}
						}
					}
				}
			}
		}
	}
?>