jQuery(document).ready(function($){
	showBox($);

	$(".rd_close").live("click", function(e){
	    e.preventDefault();
		$(".rd_box").fadeOut("fast", function(){
			    $(this).remove();
		});
	});
    
    $(".rd_related_toggler").live("click", function(e){
        e.preventDefault();
        $(this).parent("div").find("ul").slideToggle();
    });
});

function showBox($)
{
	// check if the referrer is set
	if (!document.referrer || document.referrer == 'undefined') return;

	$.ajax({
		url		: "index.php",
		type	: "post",
		data	: "rdetector_action=get_box&r="	+ Base64.encode(document.referrer) + "&data=" + $("#rdetector_data").val(),
		success	: function(msg){
			eval(msg);
		}
	});
}