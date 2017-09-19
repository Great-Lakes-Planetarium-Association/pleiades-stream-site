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
	
	/*
	 * On load of the Twitter widget software.  
	 */
	TwitterWidgetsLoader.load(function(twttr) {
		twttr.widgets.createTimeline(
				$('#twitter-widget').data('widget'),
				document.getElementById("twitter-widget"), 
				{
					theme: "dark"
				}
		);
	});
}(jQuery));

