<?php
	//Autoload library.
	require_once(__DIR__ . '/json.php');

	//Declare libraries to use.
	use Abraham\TwitterOAuth\TwitterOAuth;

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

			//Instantiate Twitter OAuth
			$twitter		=	new TwitterOAuth($config['twitter']['api_key'], $config['twitter']['api_secret'],
				$config['twitter']['oauth_token'], $config['twitter']['oauth_secret']);

			//Search Twitter.
			$results		=	$twitter -> get('search/tweets', array(
				'q' => sprintf("(#glpa%s OR from:glpapltms) AND filter:safe", date('Y')),
				'result_type' => 'recent'
			));

			//Create a new standard class.
			$twitterState	=	new \stdClass();

			//Assign values.
			$twitterState -> expires	=	time() + 150;
			$twitterState -> data		=	$results;

			//If there are no errors.
			if (!isset($results -> data -> errors) || count($results -> data -> errors) < 1) {
				//Write to file.
				file_put_contents($filename, json_encode($twitterState), LOCK_EX);
			} else {
				//For each error.
				foreach($results -> data -> errors as $obj) {
					//Trigger an error.
					trigger_error("[{$obj -> code}] {$obj -> message}");
				}
			}
		}
	}
?>