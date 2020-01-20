<!DOCTYPE html>
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
<html>
    <head>
        <meta charset="UTF-8">
        <title>Kapcsolat</title>  
		<link name="msapplication-square150x150logo" content="content/assets/Home-icon.png" />
		<link rel="icon" href="content/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="content/assets/Home-icon.png" sizes="57x57" />      
        <link rel="stylesheet" href="styles/general.css?version=1" />
        <link rel="stylesheet" href="styles/theme1.css" />		
        <link rel="stylesheet" href="styles/contact.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCapzhevrbn666B8_cZ3Z_uLhrtTwT9B4" type="text/javascript"></script>
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
		if(isset($_SESSION['rank']) and htmlentities($_SESSION['rank'])>0){	
				$myRank=htmlentities($_SESSION['rank']);
				}else{
					$myRank=0;
				}
		if ($stmt = $mysqli->prepare("SELECT value 
				  FROM static_text 
                                  WHERE name = 'contact'")) {
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        // get variables from result.
        $stmt->bind_result($contact);
        $stmt->fetch();
		echo "<div id='contactText'>".$contact."</div>";
								  }
		if ($stmt = $mysqli->prepare("SELECT value 
				  FROM static_text 
                                  WHERE name ='map' and visible=1")) {
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        // get variables from result.
        $stmt->bind_result($map);
        $stmt->fetch();
		 if ($stmt->num_rows == 1) {
		echo "<div id='map'>Térkép";
		echo "<div id='mapHolder'><iframe id='mapIframe' src=".$map." frameborder='0' allowfullscreen></iframe></div></div>";
								  
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