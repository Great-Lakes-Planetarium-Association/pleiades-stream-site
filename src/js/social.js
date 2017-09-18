/*
 * On load of the Twitter widgets 
 */
TwitterWidgetsLoader.load(function(twttr) {
	twttr.widgets.createTimeline(
			"600756918018179072",
			document.getElementById("twitter-widget"), 
			{
				theme: "dark", 
				height: 400
			}
	);
});