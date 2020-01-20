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
        <title>Keresés</title>        
		<link name="msapplication-square150x150logo" content="content/assets/Home-icon.png" />
		<link rel="icon" href="content/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="content/assets/Home-icon.png" sizes="57x57" />
        <link rel="stylesheet" href="styles/general.css?version=1" />
		<link rel="stylesheet" href="styles/articles.css" />
		<link rel="stylesheet" href="styles/albums.css" />
		<link rel="stylesheet" href="styles/downloads.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/JavaScript" src="js/contentmanager.js"></script> 
        <script type="text/JavaScript" src="js/searchhighlight.js"></script> 


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
				if(isset($_POST['searchtext']) and $_POST['searchtext']!=""){
        					$highlight=$_POST['searchtext'];
							echo "<input type='hidden' id='searchvalue' value='".$highlight."'>";
		$searchword="%".$_POST['searchtext']."%";
		echo "<h4>A keresés eredmény erre a kifejezésre: \"".$highlight."\"</h4>";
		if ($stmt = $mysqli->prepare("SELECT id, title, text, date, public, thumbnail
				  FROM articles
                                  WHERE title LIKE ? OR text LIKE ? and public <= ?")) {
		$stmt->bind_param('ssi', $searchword, $searchword, $myRank);
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
		if ($stmt->num_rows > 0) {
			echo "<p>".$stmt->num_rows." találat a cikkek között</p>";
        // get variables from result.
         $stmt->bind_result($id, $title, $text, $date, $public, $thumbnail);
         while($stmt->fetch()){
			 include 'includes/builder/article.php';		
		 }	  
								  }
								  }
								  if ($stmt = $mysqli->prepare("SELECT id, name, date, public, dátum
				  FROM albums
                                  WHERE name LIKE ? and public <= ?")) {
		$stmt->bind_param('si', $searchword, $myRank);
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
		if ($stmt->num_rows > 0) {
			echo "<p>".$stmt->num_rows." találat az albumok között</p>";
        // get variables from result.
         $stmt->bind_result($id, $name, $date, $public, $datum);
         while($stmt->fetch()){
			 include 'includes/builder/album.php';		
		 }	  
								  }
								  }
								  if ($stmt = $mysqli->prepare("SELECT id, name, public, generatedname, description
				  FROM downloads
                                  WHERE name LIKE ? OR description LIKE ? and public <= ?")) {
		$stmt->bind_param('ssi', $searchword, $searchword, $myRank);
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
		if ($stmt->num_rows > 0) {
			echo "<p>".$stmt->num_rows." találat a letölthető dokumentumok között</p>";
        // get variables from result.
         $stmt->bind_result($id, $name, $public, $generatedname, $description);
         while($stmt->fetch()){
			 include 'includes/builder/downloader.php';		
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