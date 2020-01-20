var enlargedObject=null;
function showAlbums(){
	$("#vids").hide(300);
	$("#albs").show(300);
}
function showVids(){
	$("#albs").hide(300);
	$("#vids").show(300);
	if(!$('#vids').attr('data-opened')){
	$('#vids').find("iframe").each(function(){
    var src = $(this).attr("data-source");
    $(this).attr("src", src);
});
$('#vids').attr('data-opened','1')
}
}
function albumShow(id){
	$("#small"+id).attr("onclick","albumHide('"+id+"')");
	var target=$("#large"+id);
	if(!$(this).attr('src'))
	target.find(".smallPhoto").each(function(){
    var src = $(this).attr("data-source");
    $(this).attr("src", src);
});
	//target.css({display: 'block'});
	//var he=target.height()/$(window).height()*100;
	//console.log(he);
	//target.css({display: 'none'});
	//target.animate({height: '200'}, 500);
	target.slideDown(500);
	//target.css({display: 'flex'});
	
	//target.css({display: 'flex'});
	target.animate({left: '0vw', opacity: '1'}, 1000);
	//var count = target.children().length/2;
//alert(count);
}
function albumHide(id){
		$("#small"+id).attr("onclick","albumShow('"+id+"')");
	var target=$("#large"+id);
	target.animate({left: '-100vw', opacity: '0.001'}, 1000);
	target.slideUp("500");
	//animate({height: 'auto'}, 500);
}
function large(type, id){
	jQuery('<div/>', {
    id: "largePhotoHolder"+id,
    class: "photoHolderLarge",
    valign: "center"
}).appendTo('body');
jQuery('<div/>', {
    id: "photoBack"+id,
    class: "photoBack",
    //onclick: "small('PhotoHolder', '"+id+"')"
}).appendTo('#largePhotoHolder'+id);
jQuery('<img/>', {
    id: "img"+id,
    class: "photoLarge",
    src: $('#smallPhoto'+id).attr('src')
}).appendTo('#largePhotoHolder'+id);
jQuery('<img/>', {
    id: "close",
    class: "closeBtn",
    onclick: "small('PhotoHolder', '"+id+"')",
    src: "https://contentblobs.blob.core.windows.net/assets/close.png"
}).appendTo('#largePhotoHolder'+id);
	$("#large"+type+id).fadeToggle();
	if(type=="PhotoHolder"){
		enlargedObject=$("#img"+id);
		setLargePosition();
	}
}
function setLargePosition(){
		if(!mobile){			
		console.log($(window).width());
		console.log(mobile);
		console.log(enlargedObject.width());
		enlargedObject.css({height:'80vh', width:'auto'});
		console.log(($(window).width()-enlargedObject.width())/2);
		enlargedObject.css({top:'10vh', left:($(window).width()-enlargedObject.width())/2});
		$("#close").css({height:'10vh', width:'auto'});
		$("#close").css({top:'5vh', right:($(window).width()-enlargedObject.width())/2-$("#close").width()/2});
		}else{
		console.log($(window).height());
		console.log(mobile);
		console.log(enlargedObject.height());
		enlargedObject.css({width:'80vw', height:'auto'});
			console.log(($(window).height()-enlargedObject.height())/2);
		enlargedObject.css({left:'10vw', top:($(window).height()-enlargedObject.height())/2});
		$("#close").css({height:'10vw', width:'auto'});
		$("#close").css({right:'5vw', top:($(window).height()-enlargedObject.height())/2-$("#close").width()/2});
		}
}
function small(type, id){
		$("#large"+type+id).fadeTo("slow", 0.1);
		$("#large"+type+id).remove();
		enlargedObject=null;
}
$(document).ready(function(){
	$(".albumContent").hide();
	
});
function largeArticle(type, id){
	$("#articleExpander"+id).attr({onclick: 'smallArticle("'+type+'", "'+id+'")'});
	$("#articleExpander"+id).html('...vissza');
	$("#small"+type+id).addClass('expanded'); 
	var el = $("#small"+type+id);
    el
        .data('oHeight',el.height())
        .css('height','auto')
        .data('nHeight',el.height())
        .css('height', el.data('oHeight'))
        .animate({height: el.data('nHeight')}, 1000);
	tiHeight = el.find('.artTitle').height();
	teHeight = el.find('.artText').height();
	dHeight = el.find('.artDate').height();
	curHeight = el.height();
    contentHeight = tiHeight+teHeight+dHeight+10;
el.height(curHeight).css('height', contentHeight);
teHeight = el.find('.artText').css('height');
}
function smallArticle(type, id){
	if(mobile)
		$("#small"+type+id).animate({'height': '13vh'}, 1000);
	if(!mobile)
		$("#small"+type+id).animate({'height': '20vh'}, 1000);
	$("#articleExpander"+id).attr({onclick: 'largeArticle("'+type+'", "'+id+'")'});
	$("#articleExpander"+id).html('...tovább');
	$("#small"+type+id).removeClass('expanded'); 
}