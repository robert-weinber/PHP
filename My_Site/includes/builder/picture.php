<?php
if($public<=$myRank){
	// A Javascript generálja ezt kattintásra
/*
$hiddenAlbums.='<div id="largePhotoHolder'.$id.'" class="photoHolderLarge" valign="center">
<div class="photoBack" id="photoback'.$id.'" onclick="small(\'PhotoHolder\', \''.$id.'\')"  /></div>
<img class="photoLarge" id="img'.$id.'" src="content/pics/'.$name.'" /></div>';*/
echo '<div class="photo" id="smallPhotoHolder'.$id.'" onclick="large(\'PhotoHolder\', \''.$id.'\')" /><img class="smallPhoto" id="smallPhoto'.$id.'" src="" data-source="https://contentblobs.blob.core.windows.net/pictures/'.$name.'" /></div>';
}
									?>