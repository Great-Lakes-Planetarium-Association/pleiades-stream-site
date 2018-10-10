/*
 * Run when the page is ready.
 */
(function($) {
	//If there is no show live chat cookie.
	if (!$.cookie('show_live_chat')) {
		//Toggle the visibility of chat functionality.
		$('#chat .responsive-embed').hide();
	} else {
		//Change the button text.
		$('.launch-live-chat').text('Hide Live Chat Applet');
	}

	//On click.
	$('.launch-live-chat').click(function(e) {
		//Prevent default.
		e.preventDefault();

		//If the element is hidden.
		if ($('#chat .responsive-embed').is(':hidden')) {
			//Change the button text.
			$(this).text('Hide Live Chat Applet');

			//Set the cookie to show.
			$.cookie('show_live_chat', true, {expires: 7});
		} else {
			//Change the button text.
			$(this).text('Launch Live Chat Applet');

			//Delete the cookie.
			$.removeCookie('show_live_chat');
		}

		//Show the chat.
		$('#chat .responsive-embed').slideToggle();
	});

	if ($('#twitter-statuses').length > 0) {
		var	checkTweets	=	function() {
			var $container	=	$('#twitter-statuses .tweets');

			//Ajax for the vote.
			$.ajax({
				url:		$('html').data('path') + '/tweets.php',
				dataType:	'json',
				type:		'GET',
				success:	function (returnData) {
					var	data	=	returnData.data;
					var html	=	'';

					$.each(data, function(k, v) {
						html	+=	'<div class="card tweet"><div class="card-section">';
						html	+=	'<div class="row">';
						html	+=	'<div class="column small-3"><img src="' + v.user.image + '" alt="' + v.user.name +
							'" /></div>';
						html	+=	'<div class="column small-9">';
						html	+=	'<h3><a href="' + v.user.url + '" target="_blank">' + v.user.name + '</a></h3>';
						html	+=	'<span class="social twitter" title="Tweeted">Tweeted</span>' +
							v.date + ' ago';
						html	+=	'</div>';
						html	+=	'</div>';
						html	+=	v.text;
						html	+=	'<p class="text-right"><small><a href="' + v.url +
							'" target="_blank">View Tweet</a></small></p>';
						html	+=	'</div></div>';
					});

					$container.html(html);
				},
				error:		function(xhr, textStatus, thrownError) {
					//Error handling.
					console.error('There was an error processing the request.');
					console.error('XHR/Error: [' + textStatus + '] ' + xhr.responseText);

					$container.text('There are no recent tweets.');
				}
			});
		};

		//Initial check for tweets.
		checkTweets();

		//Set an interval.
		setInterval(function() {
			checkTweets();
		}, 15000);
	}

	/*
	 * On load of the Twitter widget software.
	 */
	TwitterWidgetsLoader.load(function(err, twttr) {
		if ($('#twitter-widget').data('widget')) {
			if (!err) {
				twttr.widgets.createTimeline(
					$('#twitter-widget').data('widget'),
					document.getElementById("twitter-widget"),
					{
						theme: "dark"
					}
				);
			} else {
				$('#twitter-widgets').text("There was an error loading the Twitter timeline.");
			}
		}
	});
}(jQuery));

