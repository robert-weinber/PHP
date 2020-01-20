$(document).ready(function(){
	 var searchmap = [];
		var term = $('#searchvalue').val();
    $(".artTitle, .artText, .albumTitle, .downloadName").each(function() {
		var src_str = $(this).html();
term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
var pattern = new RegExp("("+term+")", "gi");

src_str = src_str.replace(pattern, "<mark>$1</mark>");
src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

$(this).html(src_str);
    });
});