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
        <title>Hírek</title>
		<link name="msapplication-square150x150logo" content="content/assets/Home-icon.png" />
		<link rel="icon" href="content/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="content/assets/Home-icon.png" sizes="57x57" />
        <link rel="stylesheet" href="styles/general.css?version=1" />
        <link rel="stylesheet" href="styles/theme1.css" />		
		<link rel="stylesheet" href="styles/articles.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/JavaScript" src="js/contentmanager.js"></script> 
		
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
        <?php 
		if(isset($_SESSION['rank']) and htmlentities($_SESSION['rank'])>0){	
				$myRank=htmlentities($_SESSION['rank']);
				}else{
					$myRank=0;
				}
				if ($stmtA = $mysqli->prepare("SELECT id, title, text, date, public, thumbnail
				  FROM articles
				  ORDER BY ordering")){
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
        // get variables from result.
        $stmtA->bind_result($id, $title, $text, $date, $public, $thumbnail);
         while($stmtA->fetch()){
			 
			 include 'includes/builder/article.php';		
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