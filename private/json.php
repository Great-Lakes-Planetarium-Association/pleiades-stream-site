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
					$call	=	$call -> $param;

					//Check if the call exists.
					if (!isset($call)) {
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

		//If the cache should be updated.
		if (!file_exists($filename) || !is_readable($filename)
				|| !_vc($state, 'expires') || !_vc($state, 'expires') || time() > $state -> expires) {
			//Delete the file if it exists.
			if (file_exists($filename)) unlink($filename);

			//Initiate cURL.
			$ch	=	curl_init();

			//Set cURL options.
			curl_setopt($ch, CURLOPT_URL, "https://live.pleiades2017.org/conference.json");
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
				file_put_contents($filename, json_encode($state), LOCK_EX);
			}
		}
	}

	//If there is no state data, send an error.
	if (!_vc($state, 'data')) trigger_error('"Resource information missing or invalid."', E_USER_ERROR);

	//Set the timezone.
	if (_vc($state, 'timezone')) date_default_timezone_set(_vc($state, 'timezone'));

	//Get the events.
	$eventSet =	_vc($state, 'events');

	//If there are events.
	if (count($eventSet) > 0) {
		//For each event set.
		foreach($eventSet as $k => $day) {
			//Get the date.
			$ts	=	strtotime(_vc($day, 'date') . " 00:01:00");

			//Set additional event day.
			$day -> dayString	=	sprintf("%s, %s %s<sup>%s</sup>",
					date('D', $ts),
					date('M', $ts),
					date('j', $ts),
					date('S', $ts)
					);

			//If the event is active.
			if (_vc($day, 'date') == date('Y-m-d')) {
				//Set the event day as active.
				$state -> data -> active	=	$k;
			}
		}

		//If there is no active status.
		if (!_vc($state, 'data', 'active')) {
			$state -> data -> active	=	"0:0";
		}
	}
?>