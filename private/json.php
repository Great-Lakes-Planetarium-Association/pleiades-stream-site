<?php
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
		if (!file_exists($filename) || !is_readable($filename) || !isset($state -> data) ||
				!isset($state -> expires) || time() > $state -> expires) {
			//Delete the file if it exists.
			if (file_exists($filename)) unlink($filename);

			//Initiate cURL.
			$ch	=	curl_init();

			//Set cURL options.
			curl_setopt($ch, CURLOPT_URL, "http://mcpstars.org/~tdobes/steve/conference_json_mockup.json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
				trigger_error("Unable to retrieve resource information.", E_USER_ERROR);
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
	if (!isset($state -> data)) trigger_error("Resource information missing.", E_USER_ERROR);
?>