<?php
	/**
	 * Author: PxO Ink LLC (http://pxoink.net/)
	 */

	//Import all of the relevant content data.
	include(__DIR__ . '/../private/json.php');

	//Parse the URL.
	$parsedUrl	=	parse_url($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="en" data-path="<?php print(rtrim(pathinfo($parsedUrl['path'], PATHINFO_DIRNAME), '/')); ?>">
	<head>
		<title>
			Watch Live at the <?php print(_vc($state, 'data', 'host')); ?> in
			<?php print(_vc($state, 'data', 'location')); ?> | <?php print(_vc($state, 'data', 'conference'));?>
		</title>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="author" content="GLPA" />
		<link rel="group" href="humans.txt" />
		<link rel="stylesheet" href="css/stylesheet.css" />
	<?php if (_vc($state, 'data', 'background')) { ?>
		<style type="text/css">
			body {
				background-image: url('<?php print(_vc($state, 'data', 'background')); ?>');
			}
		</style>
	<?php } ?>
	</head>
	<body>
		<header>
			<div class="row">
				<div class="column small-12 medium-2 large-1 logo">
					<img src="images/logo.png" alt="GLPA logo" />
				</div>
				<div class="column show-for-medium medium-7 large-9">
					<h1><?php print(_vc($state, 'data', 'conference')); ?></h1>
				</div>
				<div class="column show-for-medium medium-3 large-2 text-right">
					<a href="https://twitter.com/Pleiades_NPC" class="sobar" target="_blank" rel="noopener">
						<span class="social micro twitter" title="Twitter">Twitter</span>
					</a>
				</div>
			</div>
		</header>
		<section>
			<section class="row">
				<main class="column small-12 medium-8">
				<?php if (_vc($state, 'data', 'announcement')) { ?>
					<div class="callout alert">
						<?php print(_vc($state, 'data', 'announcement')); ?>
					</div>
				<?php } ?>
				<?php
					//Get the active data.
					$active		=	_vc($state, 'data', 'active');

					//Set active data if necessary.
					$active['day']		=	(!isset($active['day']) || !$active['day']) ? 0 : $active['day'];
					$active['event']	=	(!isset($active['event']) || !$active['event']) ? 0 : $active['event'];

					//Get the event data.
					$eventDays	=	(!isset($eventDays)) ? _vc($state, 'data', 'event_days') : $eventDays;

					//If there is an active day.
					if (isset($eventDays[$active['day']])) {
						//Get the active day.
						$activeDay	=	$eventDays[$active['day']];
						$thisDay	=	_vc($activeDay, 'date');

						//Get the events for the day.
						if ($events = _vc($activeDay, 'events')) {
							//If there is an active event.
							if (isset($events[$active['event']])) {
								//Get the current event.
								$event	=	$events[$active['event']];

								//If there are active streams.
								if (!is_numeric($active['stream'])) {
									//Get the title.
									$title		=	_vc($event, 'title');

									//Get the start and end times.
									$startTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($event, 'start_time')));
									$endTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($event, 'end_time')));
								} else {
									//Get all of the children.
									$streams		=	_vc($event, 'children');

									//If the child exists.
									if (isset($streams[$active['stream']])) {
										//Get the current stream.
										$nowStream	=	$streams[$active['stream']];

										//If there are cStreams.
										$cStreams	=	_vc($nowStream, 'concurrent_streams');

										//Get the title.
										$title		=	(!$cStreams) ? _vc($nowStream, 'title') : _vc($cStreams[0], 'title');

										//Get the start and end times.
										$startTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($nowStream, 'start_time')));
										$endTime	=	strtotime(sprintf("%s %s", $thisDay, _vc($nowStream, 'end_time')));
									}
								}
							}
				?>
					<div class="stream-title" data-refresh="<?php print($endTime); ?>">
					<?php if ($title) { ?>
						<h2>
							Currently showing:
							<?php print($title); ?>
						</h2>
					<?php } ?>
					<?php if ($startTime && $endTime) { ?>
						<p>
							Airing between:
							<?php printf("%s - %s", date('h:i A', $startTime), date('h:i A', $endTime)); ?>
						</p>
					<?php } ?>
					</div>
				<?php
						}
					}
				?>
					<noscript>
						<div class="callout alert">
							You must have JavaScript enabled to choose a stream.
						</div>
					</noscript>
					<nav class="ongoing-events<?php if (!isset($cStreams) || !$cStreams) { ?> hide<?php } ?>">
						<ul class="menu">
							<li>
								<h3>Ongoing Events:</h3>
							<?php
								//If there are concurrent streams.
								if (isset($cStreams) && count($cStreams) > 0) {
									//Get the stream types.
									$streams	=	_vc($state, 'data', 'streams');

									//For each concurrent stream.
									foreach($cStreams as $i => $cStream) {
										//Get the current stream.
										$thisStream		=	(!isset($streams[_vc($cStream, 'stream')])) ? null :
											$streams[_vc($cStream, 'stream')];

										//If there is a stream.
										if ($thisStream) {
											//Get the streams.
											$streamHls		=	(!_vc($thisStream, 'hls', 'enabled')) ? null :
												sprintf("%s%s",
													_vc($state, 'data', 'stream_url_prefixes', 'hls'),
													_vc($thisStream, 'hls', 'url')
											);
											$streamRtmp		=	(!_vc($thisStream, 'rtmp', 'enabled')) ? null :
												sprintf("%s%s",
													_vc($state, 'data', 'stream_url_prefixes', 'rtmp'),
													_vc($thisStream, 'rtmp', 'url')
											);
											$streamYoutube	=	(!_vc($thisStream, 'youtube', 'enabled')) ? null :
												sprintf("%s%s%s",
													_vc($state, 'data', 'stream_url_prefixes', 'youtube'),
													_vc($thisStream, 'youtube', 'url'),
													_vc($state, 'data', 'stream_url_suffixes', 'youtube')
											);
											$streamUstream	=	(!_vc($thisStream, 'ustream', 'enabled')) ? null :
												sprintf("%s%s%s",
													_vc($state, 'data', 'stream_url_prefixes', 'ustream'),
													_vc($thisStream, 'ustream', 'url'),
													_vc($state, 'data', 'stream_url_suffixes', 'ustream')
											);
											$streamAudio	=	(!_vc($thisStream, 'audio', 'enabled')) ? null :
												sprintf("%s%s",
													_vc($state, 'data', 'stream_url_prefixes', 'audio'),
													_vc($thisStream, 'audio', 'url')
											);

											//If this is the first.
											$defaultVideo	=	(!isset($defaultVideo)) ? $streamYoutube : $defaultVideo;
											$defaultAudio	=	(!isset($defaultAudio)) ? $streamAudio : $defaultAudio;
										}
								?>
								<li>
									<a href="#video" class="button load-event<?php if ($i == 0) { ?> current<?php } ?>"
										<?php if ($streamHls) { ?>data-hls="<?php print($streamHls); ?>"<?php } ?>
										<?php if ($streamRtmp) { ?>data-rtmp="<?php print($streamRtmp); ?>"<?php } ?>
										<?php if ($streamYoutube) { ?>data-youtube="<?php print($streamYoutube); ?>"<?php } ?>
										<?php if ($streamUstream) { ?>data-ustream="<?php print($streamUstream); ?>"<?php } ?>
										<?php if ($streamAudio) { ?>data-audio="<?php print($streamAudio); ?>"<?php } ?>>
										<?php print(_vc($cStream, 'title')); ?>
									</a>
								</li>
							<?php
									}
								}
							?>
							</li>
						</ul>
					</nav>
					<div class="media<?php if (!isset($nowStream)) { ?> hide<?php } ?>">
						<div id="video" class="responsive-embed widescreen"
							data-poster="<?php print(_vc($state, 'data', 'background')); ?>">
							<iframe width="640" height="360" frameborder="0"
								src="<?php if (isset($defaultVideo)) print($defaultVideo); ?>"></iframe>
						</div>
						<div id="audio">
						<?php if (isset($streamAudio) && $streamAudio) { ?>
							<div class="row">
								<div class="column float-left medium-2">
									<h3>Audio Player</h3>
								</div>
								<div class="column small-12 medium-10 player">
									<audio controls>
										<source type="audio/mpeg"
											src="<?php if (isset($defaultAudio)) print($defaultAudio); ?>" />
									</audio>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
					<nav class="ongoing-streams">
						<ul class="menu">
							<li>
								<h3>Stream Choice:</h3>
							</li>
							<li class="video">
								<a href="#video" class="button load-stream">
									Video
								</a>
							</li>
							<li class="flash">
								<a href="#video" class="button load-stream">
									Flash
								</a>
							</li>
							<li class="youtube">
								<a href="#video" class="button load-stream">
									Youtube
								</a>
							</li>
							<li class="ustream">
								<a href="#video" class="button load-stream">
									UStream
								</a>
							</li>
							<li class="audio">
								<a href="#audio" class="button load-stream">
									Audio Only
								</a>
							</li>
						</ul>
					</nav>
					<hr />
					<?php include_once(__DIR__ . '/schedule.php'); ?>
				</main>
				<aside class="column small-12 medium-4">
					<div id="chat">
						<h2>Live Chat</h2>
						<div class="responsive-embed">
							<iframe width="200" height="400" scrolling="no" frameborder="0"
								src="<?php print(_vc($state, 'data', 'chat')); ?>"></iframe>
						</div>
						<span class="launch-live-chat button" data-hide="false">Launch Live Chat Applet</span>
					</div>
					<hr />
					<div id="twitter-widget" data-widget="<?php print(_vc($state, 'data', 'twitter', 'id')); ?>"></div>
				</aside>
			</section>
		</section>
		<footer class="text-center">
			<p>Copyright &copy; <?php print(date('Y')); ?> GLPA. All Rights Reserved.</p>
		</footer>
		<script type="text/javascript" src="js/modernizr.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/scripts.js"></script>
	</body>
</html>