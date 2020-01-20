<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
		 <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bemutatkozás</title>
		<link name="msapplication-square150x150logo" content="https://contentblobs.blob.core.windows.net/assets/Home-icon.png" />
		<link rel="icon" href="https://contentblobs.blob.core.windows.net/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="https://contentblobs.blob.core.windows.net/assets/Home-icon.png" sizes="57x57" />
        <link rel="stylesheet" href="styles/general.css" />		
        <link rel="stylesheet" href="styles/theme1.css" />		
		<link rel="stylesheet" href="styles/albums.css" />
		<link rel="stylesheet" href="styles/articles.css?version=1" />		
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/JavaScript" src="js/contentmanager.js"></script> 

    </head>
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
    <body>
	<?php 	
	include_once 'includes/top_menu.php';
	?>
	
<div id="content">

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
		if(isset($_SESSION['rank']) and htmlentities($_SESSION['rank'])>0){	
				$myRank=htmlentities($_SESSION['rank']);
				}else{
					$myRank=0;
				}
				 if ($stmt = $mysqli->prepare("SELECT value 
				  FROM static_text 
                                  WHERE name = 'welcome' LIMIT 1")) {
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        // get variables from result.
        $stmt->bind_result($value);
        $stmt->fetch();
				
				echo "<div id='welcomeText'>".$value."</div>";
				}
						
$prep_stmt = "SELECT id FROM important";
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
        						
				echo "<h4>Kiemelt Tartalmak</h4>";
		}	
	}	
				if ($stmt = $mysqli->prepare("SELECT type, otherID
FROM important
ORDER BY originalDate DESC")) {
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        // get variables from result.
        $stmt->bind_result($type, $otherID);
         while($stmt->fetch()){
			 
if($type=="article"){
		if ($stmtA = $mysqli->prepare("SELECT id, title, text, date, public, thumbnail
				  FROM articles
				  WHERE id = ?
				  ORDER BY date DESC")){
		$stmtA->bind_param('i', $otherID);
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
        // get variables from result.
        $stmtA->bind_result($id, $title, $text, $date, $public, $thumbnail);
         while($stmtA->fetch()){
			 include 'includes/builder/article.php';		
				}	  
			}		
		}
if($type=="album"){
		if ($stmtA = $mysqli->prepare("SELECT id, name, date, public, dátum
				  FROM albums
				  WHERE id = ?
				  ORDER BY date DESC")){
		$stmtA->bind_param('i', $otherID);
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
        // get variables from result.
        $stmtA->bind_result($id, $name, $date, $public, $datum);
         while($stmtA->fetch()){
			include 'includes/builder/album.php';
				}
			}
		}
	}					  
}
        ?> 
     </div>
	 <?php 	
	include_once 'includes/banner_bar.php';
	mysqli_close($mysqli);
	?>
    </body>
</html>
