<?php
	//Set the timezone to UTC.
	date_default_timezone_set('America/Chicago');

	//Get the offset in hours.
	$timezone	=	filter_input(INPUT_GET, 'tz', FILTER_SANITIZE_NUMBER_INT);

	//Get the timestamp.
	$timestamp	=	(!$timezone) ? time() : strtotime("$timezone hours");

	//Output the date.
	printf("%s %s %s<sup>%s</sup> %s:%s:%s %s %s %s",
			date('D', $timestamp),
			date('M', $timestamp),
			date('j', $timestamp),
			date('S', $timestamp),
			date('h', $timestamp),
			date('i', $timestamp),
			date('s', $timestamp),
			date('A', $timestamp),
			date('T', $timestamp),
			(!$timezone) ? null : "$timezone:00"
	);
?>