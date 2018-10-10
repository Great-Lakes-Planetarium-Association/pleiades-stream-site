/*
 * Run when the page is ready.
 */
(function($) {
	//Declare the current stream.
	var	currentStream	=	'hls';

	//Create a set of re-usable utilities.
	var	mediaUtilities	=	{
		/**
		 * A function that sets all of the buttons for media usage.
		 */
		setButtons: function($event) {
			//Set the event if no event is set.
			$event	=	(!$event) ? $events.find('.load-event').first() : $event;

			//If there is an event.
			if ($event) {
				//Find any disabled event buttons and enable them.
				$events.find('.disabled').removeClass('disabled');

				//Set the clicked event as disabled.
				$event.addClass('disabled');

				//Reset the stream buttons and hide them.
				$streams.find('.load-stream').removeClass('disabled').attr('href', '').parent().stop().hide();

				//Declare the default video value to be stored based upon what video is available below.
				var	$currentVideo, $defaultVideo;

				/*
				 * Based upon what data is available, show the appropriate button and set the stream data.
				 */

				if ($event.data('audio')) {
					$currentVideo	=	$streams.find('.audio').find('.load-stream');
					$currentVideo.attr('href', $event.data('audio')).parent().stop().show();

					//If the current stream matches.
					if (currentStream == 'audio') {
						//Set the default video.
						$defaultVideo	=	$currentVideo;
					}
				}

				if ($event.data('rtmp')) {
					$currentVideo	=	$streams.find('.flash').find('.load-stream');
					$currentVideo.attr('href', $event.data('rtmp')).parent().stop().show();

					//If the current stream matches.
					if (currentStream == 'flash') {
						//Set the default video.
						$defaultVideo	=	$currentVideo;
					}
				}

				if ($event.data('ustream')) {
					$currentVideo	=	$streams.find('.ustream').find('.load-stream');
					$currentVideo.attr('href', $event.data('ustream')).parent().stop().show();

					//If the current stream matches.
					if (currentStream == 'ustream') {
						//Set the default video.
						$defaultVideo	=	$currentVideo;
					}
				}

				if ($event.data('youtube')) {
					$currentVideo	=	$streams.find('.youtube').find('.load-stream');
					$currentVideo.attr('href', $event.data('youtube')).parent().stop().show();

					//If the current stream matches.
					if (currentStream == 'youtube') {
						//Set the default video.
						$defaultVideo	=	$currentVideo;
					}
				}

				if ($event.data('hls')) {
					$currentVideo	=	$streams.find('.video').find('.load-stream');
					$currentVideo.attr('href', $event.data('hls')).parent().stop().show();

					//If the current stream matches.
					if (!currentStream || !$defaultVideo || currentStream == 'hls') {
						//Set the default video.
						$defaultVideo	=	$currentVideo;
					}
				}

				//If there is no default video.
				if (!$defaultVideo) {
					//Hide the ongoing streams.
					$('.ongoing-streams').hide();
				} else {
					//Hide the ongoing streams.
					$('.ongoing-streams').show();

					//Set the default player.
					mediaUtilities.setPlayer($defaultVideo);
				}
			}
		},
		/**
		 * Replaces the player given a choice.
		 */
		setPlayer: function($stream) {
			//Remove the disabled flag on each stream button.
			$streams.find('.load-stream').removeClass('disabled');

			//If there is a stream.
			if ($stream) {
				//Set this stream as disabled.
				$stream.addClass('disabled');

				//Hide all applicable elements.
				$video.stop().hide();
				$audio.stop().hide();

				//Dispose of the player.
				if ($('#hls').length) videojs('hls').dispose();
				if ($('#rtmp').length) videojs('rtmp').dispose();

				//Clear out the video content.
				$video.html('');
				$audio.find('.player').html('');

				//Based on the parent class.
				if ($stream.parent().hasClass('video')) {
					//Set the current stream.
					currentStream	=	'hls';

					//Add the html.
					$video.html('<video id="hls" class="video-js" controls autoplay poster="' + $video.data('poster') + '">' +
							'<source src="' + $stream.attr('href') + '" type="application/x-mpegURL" />' +
					'</video>').stop().show();

					//Activate the player.
					player	=	videojs('hls', {
						flash: {swf: $('html').data('path') +'/swf/video-js.swf'}
					}, function() {
						$('.video-js').css({
							'width': $('.responsive-embed.widescreen').width(),
							'height': $('.responsive-embed.widescreen').outerHeight()
						});
					});
				} else if ($stream.parent().hasClass('youtube')) {
					//Set the current stream.
					currentStream	=	'youtube';

					//Add the html.
					$video.html('<iframe width="640" height="360" frameborder="0" src="' + $stream.attr('href') + '"></iframe>')
						.stop().show();
				} else if ($stream.parent().hasClass('ustream')) {
					//Set the current stream.
					currentStream	=	'ustream';

					//Add the html.
					$video.html('<iframe width="640" height="360" frameborder="0" src="' + $stream.attr('href') + '"></iframe>')
						.stop().show();
				} else if ($stream.parent().hasClass('flash')) {
					//Set the current stream.
					currentStream	=	'flash';

					//Add the html.
					$video.html('<video id="rtmp" class="video-js" controls autoplay poster="' + $video.data('poster') + '">' +
							'<source src="' + $stream.attr('href') + '" type="rtmp/mp4" />' +
					'</video>').stop().show();

					//Activate the player.
					player	=	videojs('rtmp', {
						flash: {swf: $('html').data('path') +'/swf/video-js.swf'}
					}, function() {
						$('.video-js').css({
							'width': $('.responsive-embed.widescreen').width(),
							'height': $('.responsive-embed.widescreen').outerHeight()
						});
					});
				} else if ($stream.parent().hasClass('audio')) {
					//Set the current stream.
					currentStream	=	'audio';

					//Hide the video player.
					$video.stop().hide();

					//Add the html.
					$audio.find('.player')
						.html('<audio controls><source src="' + $stream.attr('href') + '" type="audio/mpeg" /></audio>');

					//Show the audio player.
					$audio.stop().show(function() {
						//Force autoplay.
						$audio.find('audio')[0].play();
					});
				}
			}
		}
	};

	//Get all applicable elements.
	var	player;
	var	$video		=	$('#video');
	var	$audio		=	$('#audio');
	var	$events		=	$('.ongoing-events');
	var	$streams	=	$('.ongoing-streams');

	//Hide all applicable elements.
	$video.stop().hide();
	$audio.stop().hide();

	//Setup the buttons.
	mediaUtilities.setButtons();

	//When a stream is clicked.
	$streams.find('.load-stream').click(function(e) {
		//Prevent default.
		e.preventDefault();

		//Load the player.
		mediaUtilities.setPlayer($(this));
	});

	//When an event is clicked.
	$events.find('.load-event').click(function(e) {
		//Prevent default.
		e.preventDefault();

		//Set the buttons.
		mediaUtilities.setButtons($(this));
	});

	//Set an interval to check whether the stream has ended.
	setInterval(function() {
		//Get the stream title.
		var	$title	=	$('.stream-title');

		//When the current stream has expired.
		if ($title.data('refresh') < (new Date().getTime() / 1000)) {
			//Set the stream title.
			$title.html('<div class="callout warning">' +
					'<p>' +
						'<strong>The current live stream has ended.</strong> ' +
						"When you're ready for the next stream, " +
						'<a href="' + $('html').data('path') + '">click here to refresh</a> ' +
						'the page to watch the latest stream.' +
					'</p>' +
			'</div>');
		}
	}, 5000);
}(jQuery));