<!DOCTYPE html>
<html lang="hu">
<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
 
if (login_check($mysqli) == true) {
    $logged = 'Be';
} else {
    $logged = 'Ki';
}
?>
    <head>
        <meta charset="UTF-8">
        <title>Média</title>
		<link name="msapplication-square150x150logo" content="https://contentblobs.blob.core.windows.net/assets/Home-icon.png" />
		<link rel="icon" href="https://contentblobs.blob.core.windows.net/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="https://contentblobs.blob.core.windows.net/assets/Home-icon.png" sizes="57x57" />
        <link rel="stylesheet" href="styles/general.css?version=1" />
        <link rel="stylesheet" href="styles/theme1.css" />	
		<link rel="stylesheet" href="styles/albums.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/JavaScript" src="js/contentmanager.js"></script> 
    <style>
	
	</style>
	</head>
    <body>
	<?php 	
	include_once 'includes/top_menu.php';
	?>	
<div id="content">
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
		?>	
<div id="switcher">
<div id="alb">
<p class="switchtitle" onclick='showAlbums()'>Képek</p>
</div>
<div id="vid" data-opened="">
<p class="switchtitle" onclick='showVids()'>Videók</p>
</div>
</div>
        <?php
				if(isset($_SESSION['rank']) and htmlentities($_SESSION['rank'])>0){	
				$myRank=htmlentities($_SESSION['rank']);
				}else{
					$myRank=0;
				}
				
		echo "<div id='albs'>";
				if ($stmtA = $mysqli->prepare("SELECT id, name, date, public, dátum
				  FROM albums WHERE public<2 
				  ORDER BY ordering")){
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
        // get variables from result.
        $stmtA->bind_result($id, $name, $date, $public, $datum);
         while($stmtA->fetch()){
								include 'includes/builder/album.php';
	}	
		}				
		echo "</div><div id='vids' style='display: none;'>";
		
		
		if ($stmtA = $mysqli->prepare("SELECT id, name, public, link
				  FROM vids
				  ORDER BY ordering DESC")){
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
        // get variables from result.
        $stmtA->bind_result($id, $name, $public, $link);
         while($stmtA->fetch()){
								include 'includes/builder/vid.php';
	}	
		}				
		echo "</div>";
				?>
            
          </div>
		  <?php 	
	include_once 'includes/banner_bar.php';
	mysqli_close($mysqli);
	?>
    </body>
</html>
