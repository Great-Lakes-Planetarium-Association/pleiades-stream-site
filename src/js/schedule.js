/*
 * Run when the page is ready. 
 */
(function($) {
	//Check flicker.
	var	flicker		=	0;
	
	//Create a function for the time interval.
	var	dateTime	=	function() {
		//If the element exists.
		if ($('.time').length > 0) {
			//Set the html.
			var html	=	'';
			
			//Update the flicker.
			flicker		=	(!flicker) ? 1 : 0; 
			
			//Make the ajax request. 
			$.ajax({
				url: $('html').data('path') + '/datetime.php?tz=' + $('.time').data('timezone') + '&flicker=' + flicker, 
				success: function(data) {
					//Set the data.
					html	=	data;
				},
				error:		function(xhr, textStatus, thrownError) {
					//Error handling.
					console.error('There was an error processing the request.');
					console.error('XHR/Error: [' + textStatus + '] ' + xhr.responseText);
					console.error(thrownError);
				}, 
				complete: function() {
					//Set the html in element.
					$('.time').stop().html(html);
				}
			});
		}
	}.bind($);
	
	var	timeInterval	=	setInterval(function() { dateTime(); }, 1000);
}(jQuery));