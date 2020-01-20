<?php
if($public<=$myRank){
/*	$filename='content/articles/'.$text.'.txt';
			 $handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);*/
			 //$textString = "".$contents;
			 //file_get_contents('./content/articles/'.$text.'.php');
			 
			 
echo "<div class='article' id='smallArticleHolder".$id."'  tabindex='1'>";
if($thumbnail=="")
	echo "<img class='artImg' src='https://contentblobs.blob.core.windows.net/articlethumbnails/placeholder.jpg'/>";
else
	echo "<img class='artImg' src='https://contentblobs.blob.core.windows.net/articlethumbnails/".$thumbnail."'/>";
	echo "<img class='bottomFade' src='https://contentblobs.blob.core.windows.net/assets/bottom-fade.png'/>";
echo "<div class='artTitle'>".$title."</div>";
echo "<div id='text".$id."' class='artText'>";
//.$contents;
echo $text;
//include(".\content\articles\\".$text.".php");
echo "</div>";
//echo "<script type='text/javascript'>
//    $('#text".$id."').load('content/articles/".$text.".txt');
//</script>";
//echo "<div class='artDate'>".$date."</div>";
echo "<button id='articleExpander".$id."' onclick='largeArticle(\"ArticleHolder\", \"".$id."\")' class='articleExpander'>...tovább</button></div>"; 
} ?>