jQuery( document ).ready(function( $ ) {
	$(".notelist.no-list").addClass("hide");
	$(".notelink.drop").hover(
  	function() {
  		$(this).find(".dropbox").addClass("show");
  	},
  	function() {
  		box = $(this).find(".dropbox");
  		box.delay(200);
  		box.removeClass("show");
  	}
  	);
});