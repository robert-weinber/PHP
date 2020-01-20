<?php
if($public<=$myRank){
$ext=pathinfo($generatedname, PATHINFO_EXTENSION);
		$extimgpath="https://contentblobs.blob.core.windows.net/ext/".$ext.".png";
		//if(file_exists($extimgpath))
			$extimg="<img class='downloadImg' src='".$extimgpath."' />";		
		//else
		//	$extimg="<img class='downloadImg' src='https://contentblobs.blob.core.windows.net/ext/default.png' />";
		echo "<form ACTION='download.php' METHOD=POST>
		<input type='hidden' value='".$generatedname."' name='file'>
		<input type='hidden' value='".$name."' name='fileName'>";
		echo "<button type='submit' class='downloadHolder'>";
		//echo "<a href='download.php?file=".$egysor['generatedname']."'>";		
		echo "<div class='downloadLink' >".$extimg."</div>";
		echo "<div class='downloadText'><div class='downloadName'>".$name."</div>";
		//echo "<div class='downloadDesc'>".$description."</div>";
		echo "</div>";
		//echo "</a>";
		echo "</button>";
		echo "</form>";
		
		
} ?>