/*
 * On load of the Twitter widget software.  
 */
TwitterWidgetsLoader.load(function(twttr) {
	twttr.widgets.createTimeline(
			"909592127721656321",
			document.getElementById("twitter-widget"), 
			{
				theme: "dark"
			}
	);
});