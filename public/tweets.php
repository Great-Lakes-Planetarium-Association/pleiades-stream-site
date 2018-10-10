<?php
	//Get Twitter integration.
	include_once(__DIR__ . '/../private/twitter.php');

	//If there are results.
	if (isset($twitterState) && isset($twitterState -> data -> statuses)) {
		//If there is a count.
		$count	=	count($twitterState -> data -> statuses);
		$count	=	($count < 5) ? $count : 5;

		//Create a data container.
		$data	=	array();

		//Loop through the statuses.
		for ($i = 0; $i < $count; $i++) {
			//Set the current tweet.
			$tweet	=	$twitterState -> data -> statuses[$i];

			//Parse out the date time.
			$date			=	DateTime::createFromFormat('U', strtotime($tweet -> created_at));
			$now			=	new DateTime('now');
			$diff			=	$now -> diff($date);
			$dateText		=	'';

			//If there are years.
			if ($diff -> y) {
				$dateText	.=	sprintf(" %s %s, ", $diff -> y, pluralize($diff -> y, "year"));
			}

			//If there are months.
			if ($diff -> m) {
				$dateText	.=	sprintf(" %s %s, ", $diff -> m, pluralize($diff -> m, "month"));
			}

			//If there are weeks.
			if (floor($diff -> d / 7) > 0) {
				$dateText	.=	sprintf(" %s %s, ", floor($diff -> d / 7), pluralize(floor($diff -> d / 7), "week"));
			}

			//If there are days.
			if ($diff -> d % 7) {
				$dateText	.=	sprintf(" %s %s, ", $diff -> d % 7, pluralize($diff -> d % 7, "day"));
			}

			//If there are hours.
			if ($diff -> h) {
				$dateText	.=	sprintf(" %s %s, ", $diff -> h, pluralize($diff -> h, "hour"));
			}

			//If there are no years, minutes, or weeks, and there are minutes.
			if (!$diff -> y && !$diff -> m && !$diff -> d && $diff -> i) {
				$dateText	.=	sprintf(" %s %s, ", $diff -> i, pluralize($diff -> i, "minute"));
			}

			//If there are no years, minutes, or weeks, and there are seconds.
			//if (!$diff -> y && !$diff -> m && !$diff -> d && $diff -> s) {
			//	$dateText	.=	sprintf(" %s %s, ", $diff -> s, pluralize($diff -> s, "second"));
			//}

			//Trim datetext.
			$dateText	=	rtrim($dateText, ', ');

			//https://stackoverflow.com/a/1188652
			$rexProtocol = '(https?://)?';
			$rexDomain   = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
			$rexPort     = '(:[0-9]{1,5})?';
			$rexPath     = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
			$rexQuery    = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
			$rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
			$text		=	preg_replace_callback(
				"&\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$))&",
				function($match) {
					$completeUrl = ($match[1]) ? $match[0] : "http://{$match[0]}";
					return sprintf('<a href="%s" target="_blank">%s%s%s</a>', $completeUrl, $match[2], $match[3], $match[4]);
				},
				htmlspecialchars($tweet -> text)
			);

			//Replace hashtags.
			$text	=	preg_replace("/#(\w+)/", '<a href="https://twitter.com/search?q=%23$1" target="_blank">#$1</a>',
				$text);

			//Replace mentions.
			$text	=	preg_replace("/@(\w+)/", '<a href="https://twitter.com/$1" target="_blank">@$1</a>', $text);

			//Increment the data object.
			$data[]			=	array(
				'date' => $dateText,
				'text' => $text,
				'url' => sprintf("https://twitter.com/i/web/status/%s", $tweet -> id),
				'user' => array(
					'name' => $tweet -> user -> name,
					'url' => sprintf("https://twitter.com/", $tweet -> user -> screen_name),
					'image' => $tweet -> user -> profile_image_url_https
				)
			);
		}
	}

	//Output the tweets.
	print(json_encode(array('status' => 'OK', 'data' => $data)));

	function pluralize($n, $s) {
		return ($n > 1) ? "{$s}s" : $s;
	}
?>