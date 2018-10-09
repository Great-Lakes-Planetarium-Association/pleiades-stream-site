<?php
	//Libraries. @todo Composer
	require_once(__DIR__ . '/TwitterAPIExchange.php');

	//Set the configuration options.
	$config	=	parse_ini_file(__DIR__ . '/twitter.ini', true);

	//If the twitter state is not set.
	if (!isset($twitterState)) {
		//Set the filename.
		$filename	=	__DIR__ . '/twitter.json';

		//If an existing resource exists.
		if (file_exists($filename)) {
			//Get the resource data.
			$twitterState	=	json_decode(file_get_contents($filename));
		}

		//If the cache should be updated.
		if (!file_exists($filename) || !is_readable($filename) || time() > $twitterState -> expires) {
			//Delete the file if it exists.
			if (file_exists($filename)) unlink($filename);

			//Set the request variables.
			$baseUrl		=	"https://api.twitter.com/1.1/";
			$requestUrl		=	"search/tweets.json?q=(%23glpa2018%20OR%20from%3A%40glpapltms)%20AND%20filter%3Asafe%20";
			$method			=	"GET";

			//Create a new request.
			$twitter		=	(new TwitterAPIExchange(array(
				'oauth_access_token' => $config['twitter']['oauth_token'],
				'oauth_access_token_secret' => $config['twitter']['oauth_secret'],
				'consumer_key' => $config['twitter']['api_key'],
				'consumer_secret' => $config['twitter']['api_secret']
			)))
				-> buildOauth($baseUrl . $requestUrl, $method)
				-> performRequest();

			//Create a new standard class.
			$twitterState	=	new \stdClass();

			//Assign values.
			$twitterState -> expires	=	time() + 150;
			$twitterState -> data		=	json_decode($twitter);

			var_dump($twitterState);

			//If there are no errors.
			if (!isset($twitter -> data -> errors) || count($twitter -> data -> errors) < 1) {
				//Write to file.
				file_put_contents($filename, json_encode($twitterState), LOCK_EX);
			} else {
				//For each error.
				foreach($twitter -> data -> errors as $obj) {
					//Trigger an error.
					trigger_error("[{$obj -> code}] {$obj -> message}");
				}
			}
		}
	}
?>