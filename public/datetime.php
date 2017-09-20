<?php
	//Get the offset in hours.
	$timezone	=	(!isset($timezone)) ? filter_input(INPUT_GET, 'tz', FILTER_SANITIZE_STRING) : $timezone;

	//Set the timezone.
	date_default_timezone_set($timezone);

	//Output the date.
	printf("%s %s %s<sup>%s</sup> %s %s:%s %s %s",
			date('D'),
			date('M'),
			date('j'),
			date('S'),
			date('Y'),
			date('h'),
			date('i'),
			date('A'),
			date('T')
	);
?>