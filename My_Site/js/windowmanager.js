x = 0;
y = 0;
var mobile= false;
var wide= false;
$(document).ready(function(){
	var ul = $("#menu");
	var listactive = $("#active");
	//console.log(listactive.length);
	var list = ul.children;
	//console.log(list.length);
            var originalIndex=$( "li" ).index( listactive );
//alert( "Index: " + $( "li" ).index( listactive ) );
	var listisdown=false;
var $this = listactive;
listactive.click(function(event) {          
		if(mobile){
			if(!listisdown){
			ul.css('height', '30vh');
			listisdown=true;
			}else{
			ul.css('height', '5vh');
			listisdown=false;
			}
		}
        });
		var searchopen=false;
$('#searchexpand').click(function(event) {  
if(!searchopen){  
if(mobile){
	$('#searchholder').animate({width: '80vw', 'backgroundColor': 'rgba(0, 74, 35, 1)'}, 1000);
	$('#searchbar').css({width: 'calc(80vw - 10vh)'});
	$('#searchbutton').css({'font-size': '1.2'});
}else{     
		$('#searchholder').animate({width: '50vw', 'backgroundColor': 'rgba(0, 74, 35, 1)'}, 1000);
		$('#searchbar').css({width: 'calc(50vw - 10vh)'});
	$('#searchbutton').css({'font-size': '1.5'});
}
		searchopen=true;
}else{
	$('#searchholder').animate({width: '5vh', 'backgroundColor': 'rgba(3, 149, 76, 1)'}, 1000);
	searchopen=false;
}
        });
   // $this.insertBefore($this.siblings(':eq(0)'));
//ul.remove(listactive);
            //ul.insertBefore(listactive, list[2]);

	//$(".active").remove();
	var width = $(window).width();
	var height = $(window).height();
	if(width/height >=1.67){
				$('.menuelem').css({'font-size': '3.5vh'});
			wide=true;
		}else{
				$('.menuelem').css({'font-size': '2vw'});
			wide=false;
		}
	if(width < height){
		mobile=true;
    $this.insertBefore($this.siblings(':eq(0)'));
	$("#menuwrapper").addClass("mobile");
	$("#loginmenu").addClass("mobile");
	$("#content").addClass("mobile");
	$("#banners").addClass("mobile");
		//$("h4").text("mobile");
	}else{
		mobile=false;
			$("#menuwrapper").removeClass("mobile");
			$("#loginmenu").removeClass("mobile");
			$("#content").removeClass("mobile");
			$("#banners").removeClass("mobile");
		//$("h4").text("pc");
	}
    $(window).resize(function(){
		width = $(window).width();
		height = $(window).height();
		if(width/height >=1.67){
			if(!wide){
				$('.menuelem').css({'font-size': '3.5vh'});
			wide=true;
			}
		}else{
			if(wide){
				$('.menuelem').css({'font-size': '2vw'});
			wide=false;
			}
		}		
		if(width < height){
			if(!mobile){
        //$("h4").text("mobile"+(x += 1));
    $this.insertBefore($this.siblings(':eq(0)'));
	$("#menuwrapper").addClass("mobile");
	$("#loginmenu").addClass("mobile");
	$("#content").addClass("mobile");
	$("#banners").addClass("mobile");
			mobile=true;
			}
		}else{
			if(mobile){
			console.log(originalIndex);
		$this.insertBefore($this.siblings(':eq('+originalIndex+')'));
			//$("h4").text("pc"+(y += 1));
			$("#menuwrapper").removeClass("mobile");
			$("#loginmenu").removeClass("mobile");
			$("#content").removeClass("mobile");
			$("#banners").removeClass("mobile");
			ul.css('height', '5vh');
			listisdown=false;
			mobile=false;
			}
		}
	if (typeof enlargedObject !== 'undefined') {

	if(enlargedObject!=null){
		setLargePosition();
	}
	}
    });
	$(".logintext").keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
        formhash(this.form, this.form.password);
    }
});
});
function loginExpand(){
	//$(".logindrop").show();
	$(".logindrop").animate({ 'height': 'toggle'});
	//$(".logindrop").animate({ 'top': '100%'}, "slow");
	//$("#logindropdownIn").show();
	//$("#logindropdownOut").show();
	$("#arrow").attr("onclick","loginCollapse()");
		//$("#arrow").toggleClass('rotated');
		//$("#arrow").css({ 'transform': 'rotate(' + 180 + 'deg)'});
		$("#arrow").rotate({ endDeg:180, duration:0.4, persist:true });
	}
function loginCollapse(){
	$(".logindrop").animate({ 'height': 'toggle'});
	//$(".logindrop").fadeOut();
	//$("#logindropdownIn").fadeOut();
	//$("#logindropdownOut").fadeOut();
	$("#arrow").attr("onclick","loginExpand()");
		//$("#arrow").toggleClass('rotated');
		$("#arrow").rotate({ endDeg:0, duration:0.4, persist:true });
	}