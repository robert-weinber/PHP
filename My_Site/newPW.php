<!DOCTYPE html>
<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
 
if (login_check($mysqli) == false) {
   

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Új jelszó kérése</title>        
        <link rel="stylesheet" href="styles/general.css?version=1" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		
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
				
				if (isset($_POST['p'], $_POST['salty'])) {
			$newpassword = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
			$salt=filter_input(INPUT_POST, 'salty', FILTER_SANITIZE_STRING);
			//$password=$_POST['oldPW'];
    if (strlen($newpassword) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">A jelszó nem tesz eleget a kritériumoknak.</p>';
    }
	
	if (empty($error_msg)) {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        // Create salted password 
        $newpassword = hash('sha512', $newpassword . $random_salt);
        // Insert the new user into the database 
		 if ($stmt = $mysqli->prepare("SELECT id, username_first, email
				  FROM members 
                                  WHERE salt = ? LIMIT 1")) {
        $stmt->bind_param('s', $salt);  // Bind "$id" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        // get variables from result.
        $stmt->bind_result($user_id, $username, $email);
        $stmt->fetch();
        if ($stmt->num_rows == 1) {
		$sql="UPDATE members SET `password` = '".$newpassword."', `salt` = '".$random_salt."' WHERE `id` = '".$user_id."'";
		if (mysqli_query($mysqli, $sql)) {
    echo "A jelszava sikeresen módosítva";
	
		$to=$email;
		$subj="Jelszóváltoztatás";
		$msg="Kedves ".$username.",<br>
		<br>
		Ön sikeresen megváltoztatta a jelszavát.";
		include_once('includes/mailer.php');
		echo "A jelszóváltoztatás sikeres!";
	
} else {
    header('Location: ../error.php?err=Password change failure: UPDATE');
}
        
    }else{
		echo "A jelszóváltoztatás sikertelen! Érvénytelen link!";
	}								  
	}
		}
		}
				
		if (isset($_POST['mail'])) {
			$email = $_POST['mail'];
    
	
	
		 if ($stmt = $mysqli->prepare("SELECT username_first, salt FROM members WHERE email = ? LIMIT 1")){
        $stmt->bind_param('s', $email);  
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($username, $salt);
        $stmt->fetch();
        if ($stmt->num_rows == 1) {
		
		$to=$email;
		$subj="Új jelszó igénylése";
		$msg="Kedves ".$username.",<br>
		<br>
		Az Ön Email-címével jelszóváltoztatást kezdeményeztek.<br>
		Ha ön volt a kezdeményező, kérjük, kattintson erre a <a href='http://" 
		. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."?npw=".strrev($salt)."'>LINKRE</a>!";
		include_once('includes/mailer.php');
		echo "A megerősítő Email elküldve!";
	

        
    }else{
		echo "A jelszóváltoztatás sikertelen! Hibás az Email-cím!";
	}								  
	}
		
		}
		
		if(!isset($_GET['npw'])){
			echo '<div>
<form action="" method="post" name="newPW_form">
<p>Adja meg az Email-címet, amelyhez új jelszót szeretne kérni!</p>
<table>
<tr><td>Email-cím:</td><td><input type="text" name="mail" id="mail" /></td></tr>
</table>
<input type="submit" value="Új jelszó kérése" /> 
</form>
</div>';
		}else{
			echo '<div>
<form action="" method="post" name="changePW_form">
<input type="hidden" name="salty" value="'.strrev($_GET['npw']).'">
<table>
<tr><td>Új jelszó:</td><td><input type="text" name="newPW" id="newPW" /></td></tr>
<tr><td>Új jelszó újra:</td><td><input type="text" name="newConfirm" id="newConfirm" /></td></tr>
</table>
<input type="button" 
                   value="Módosítás" 
                   onclick="return newformhash(this.form,
                                   this.form.newPW,
                                   this.form.newConfirm);" /> 
</form>
</div>';
		}
		} else {
    header('Location: ./index.php');
}
        ?> 

</div>   
<?php 	
	include_once 'includes/banner_bar.php';
	mysqli_close($mysqli);
	?>
   </body>
</html>