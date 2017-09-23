/*
 * Run when the page is ready. 
 */
(function($) {
	/**
	 * Create re-usable utilities. 
	 */
	var	mediaUtilities	=	{
		/**
		 * A function that sets all of the buttons for media usage. 
		 */
		setButtons: function($buttons, $current) {
			//Remove the disabled class for the other buttons.
			$current.parent().parent().find('.a').removeClass('disabled');
			
			//Set the current button to disabled.
			$current.addClass('disabled'); 
			
			//Show the buttons.
			$buttons.removeClass('hide').show(); 
			
			//Set the default video value.
			var	$defVideo	=	null;
			
			//Hide all of the buttons.
			$buttons.find('a').parent().hide();
			
			//Remove the current class from all buttons.
			$buttons.find('a').removeClass('.disabled');
			
			/*
			 * Based upon what data is available, show the appropriate button and set the stream data. 
			 */
			
			if ($current.data('audio')) {
				$defVideo	=	$buttons.find('.audio').find('.load-player');
				$defVideo.attr('data-stream', $current.data('audio')); 
				$defVideo.parent().show();
			}
			
			if ($current.data('rtmp')) {
				$defVideo	=	$buttons.find('.flash').find('.load-player');
				$defVideo.attr('data-stream', $current.data('rtmp')); 
				$defVideo.parent().show();
			}
			
			if ($current.data('ustream')) {
				$defVideo	=	$buttons.find('.ustream').find('.load-player');
				$defVideo.attr('data-stream', $current.data('ustream')); 
				$defVideo.parent().show();
			}
			
			if ($current.data('youtube')) {
				$defVideo	=	$buttons.find('.youtube').find('.load-player');
				$defVideo.attr('data-stream', $current.data('youtube')); 
				$defVideo.parent().show();
			}
			
			if ($current.data('hls')) {
				$defVideo	=	$buttons.find('.video').find('.load-player');
				$defVideo.attr('data-stream', $current.data('hls')); 
				$defVideo.parent().show();
			} 
			
			//Click the video button to load the media player.
			$defVideo.click(); 
		},
		/**
		 * Replaces the player given a choice. 
		 */
		setPlayer: function($video, $audio, $current) {
			//Get the stream.
			var	stream	=	$current.data('stream'); 
			
			//Get the parent.
			var	$parent	=	$current.parent(); 
			
			//Remove the disabled button. 
			$current.parent().parent().find('.load-player').removeClass('disabled');
			
			//Set the disabled button.
			$current.addClass('disabled'); 
			
			//Clear out the video content.
			$video.html('').hide();
			
			//Stop the audio player.
			$audio.find('audio')[0].pause();
			$audio.find('audio')[0].currentTime	=	0;
			
			//Hide the audio player.
			$audio.hide();
			
			//Based on the parent class.
			if ($parent.hasClass('video')) { 
				//Add the html.
				$video.html('<video id="hls" class="video-js" controls preload="auto" poster="' + $video.data('poster') + '">' + 
						'<source src="' + stream + '" type="application/x-mpegURL" />' + 
				'</video>').show();
				
				//Activate the player.
				videojs('hls'); 
			} else if ($parent.hasClass('youtube')) {
				//Add the html.
				$video.html('<iframe width="640" height="360" frameborder="0" src="' + stream + '"></iframe>').show();
			} else if ($parent.hasClass('ustream')) {
				//Add the html.
				$video.html('<iframe width="640" height="360" frameborder="0" src="' + stream + '"></iframe>').show();
			} else if ($parent.hasClass('rtmp')) {
				//Add the html.
				$video.html('<video id="rtmp" class="video-js" controls preload="auto" poster="' + $video.data('poster') + '">' + 
						'<source src="' + stream + '" type="rtmp/mp4" />' + 
				'</video>').show();
				
				//Activate the player.
				videojs('rtmp', {techOrder: ['flash']}); 
			} else if ($parent.hasClass('audio')) {
				//Hide the video player.
				$video.hide();
				
				//Show the audio player.
				$audio.show(); 
			}
		}
	};

	//Get the video player container.
	var	$video		=	$('#video'); 
	
	//Get the audio player container.
	var	$audio		=	$('#audio');
	
	//Get all of the events available.
	var	$events		=	$('.event-chooser').find('.load-event');
	
	//Get all of the buttons available.
	var	$buttons	=	$('.stream-chooser');
	var	$streams	=	$buttons.find('.load-player'); 
	
	//Pick the first event by default.
	var	$current	=	$buttons.find('.disabled'); 
	
	//Set the appropriate class to disabled.
	$current.addClass('disabled');
	
	//Hide the appropriate elements on the page. 
	$video.hide();
	$audio.hide();
	
	//When one of the buttons is clicked.
	$streams.click(function(e) {
		//Prevent default.
		e.preventDefault();
		
		//Set the player. 
		mediaUtilities.setPlayer($video, $audio, $(this)); 
		
		//Return false.
		return false;
	});
	
	//Set the related buttons.
	mediaUtilities.setButtons($buttons, $events.first()); 
}(jQuery));