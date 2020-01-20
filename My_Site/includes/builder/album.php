<?php
if($public<=$myRank){	
echo "<div class='albumHolder'>
<div class='albumTitle' id='smallAlbum".$id."' onclick='albumShow(\"Album".$id."\")' tabindex='1'>".$name."<br>".$datum."</div>
<div class='albumContent' id='largeAlbum".$id."'>";
if ($stmtP = $mysqli->prepare("SELECT id, name, album, public 
				  FROM images
				  WHERE album = ?")){
		$stmtP->bind_param('i', $id);
		$stmtP->execute();    // Execute the prepared query.
        $stmtP->store_result();
        // get variables from result.
        $stmtP->bind_result($id, $name, $album, $public);
         while($stmtP->fetch()){
									include 'includes/builder/picture.php';		
											
	}
		echo "</div></div>";	
	}
}
									?>