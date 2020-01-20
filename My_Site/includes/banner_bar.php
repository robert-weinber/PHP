<div id='banners'>
<h4 id="bannerTitle">Kapcsolódó oldalak</h4>
<?php 
	if ($stmtA = $mysqli->prepare("SELECT id, image, link
				  FROM banners ORDER BY ordering")){
		$stmtA->execute();    
        $stmtA->store_result();
        $stmtA->bind_result($id, $image, $link);
         while($stmtA->fetch()){
						echo "<div>";
						echo "<a href='http://".$link."' target='_blank'>";
						echo "<img class='bannerImg' src='https://contentblobs.blob.core.windows.net/banners/".$image."'/>";
						echo "</a>";
						echo "</div>";
	}	
		}
?> 
	 </div>
 