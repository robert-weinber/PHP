<!DOCTYPE html>
<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
 if (login_check($mysqli) == true) {
	 
	 		if (isset($_POST['kn']) and isset($_POST['op'])) {
			$password=filter_input(INPUT_POST, 'op', FILTER_SANITIZE_STRING);
			$vname=filter_input(INPUT_POST, 'vn', FILTER_SANITIZE_STRING);
			$update=" `username_last` = '".$vname."'";			
			$kname=filter_input(INPUT_POST, 'kn', FILTER_SANITIZE_STRING);
			$update.=", `username_first` = '".$kname."'";			
			$emailad=filter_input(INPUT_POST, 'em', FILTER_SANITIZE_EMAIL);
			if ($stmt = $mysqli->prepare("SELECT id
				  FROM members 
                                  WHERE id != ? AND email= ?")) {
        $stmt->bind_param('is', $_SESSION['user_id'], $emailad); 
		$stmt->execute();
        $stmt->fetch();
        if ($stmt->num_rows == 0) {
			$update.=", `email` = '".$emailad."'";	
		}else{
			$_SESSION['popup'] = "Az Email-cím már használatban van.";
								  }}	else{
			//echo "Az Email-cím már használatban van.";
								  }
			$organ=filter_input(INPUT_POST, 'org', FILTER_SANITIZE_STRING);
			$update.=", `organization` = '".$organ."'";			
			if (isset($_POST['p'])){
			$newpassword = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($newpassword) != 128) {
        $error_msg .= '<p class="error">A jelszó nem tesz eleget a kritériumoknak.</p>';
    }
	if (empty($error_msg)) {
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        $newpassword = hash('sha512', $newpassword . $random_salt);
			$update.=", `password` = '".$newpassword."'";			
	}
			}
	
		 if ($stmt = $mysqli->prepare("SELECT id, username_first, password, salt, rank 
				  FROM members 
                                  WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('s', $_SESSION['user_id']); 
		$stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $db_password, $salt, $rank);
        $stmt->fetch();
        $oldpassword = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1 and $oldpassword==$db_password) {
		$sql="UPDATE members SET ".$update."  WHERE id = ".$_SESSION['user_id'];
		//echo $sql;
		if (mysqli_query($mysqli, $sql)) {
    $_SESSION['popup'] = "Az adatok sikeresen módosítva";
	$_SESSION['username'] = $kname;
					$_SESSION['usernameFamily'] = $vname;
					$_SESSION['organ'] = $organ;
					$_SESSION['email'] = $emailad;
	
		$to=$emailad;
		$subj="Adatmódosítás";
		$msg="Kedves ".$kname.",<br>
		<br>
		Ön sikeresen megváltoztatta az adatait.";
		include_once('includes/mailer.php');
		echo "A módosítás sikeres!";
	
} else {
    header('Location: ../error.php?err=Password change failure: UPDATE');
}
        
    }else{
		echo "A változtatás sikertelen! Hibás a régi jelszó!";
	}								  
	}
		
		}
	 
	 
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Adatmódosítás</title>  
		<link name="msapplication-square150x150logo" content="content/assets/Home-icon.png" />
		<link rel="icon" href="content/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="content/assets/Home-icon.png" sizes="57x57" />      
        <link rel="stylesheet" href="styles/general.css" />
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
                   
	/*				
			foreach($_POST as $key=>$value)
{
  echo "$key=$value";
}		
					
			*/		
					
					
					
					
					

		
		/////////////////
		$myname = $_SESSION['username'];
				   $myfamname = $_SESSION['usernameFamily'];
					$myorg = $_SESSION['organ'];
					$mymail = $_SESSION['email'];
		} else {
    header('Location: ./index.php');
}
        ?> 
<div>
<form action="" method="post" name="changePW_form">
<table>
<tr><td>Vezetéknév:</td><td><input type="text" name="vname" id="vname" value="<?php echo $myfamname ?>"/></td></tr>
<tr><td>Kerezstnév:</td><td><input type="text" name="kname" id="kname" value="<?php echo $myname ?>"/></td></tr>
<tr><td>Email-cím:</td><td><input type="text" name="emailad" id="emailad" value="<?php echo $mymail ?>"/></td></tr>
<tr><td>Szervezet:</td><td><input type="text" name="organ" id="organ" value="<?php echo $myorg ?>"/></td></tr>
<tr><td>Új jelszó:</td><td><input type="password" name="newpw" id="newpw" value="" /></td></tr>
<tr><td>Új jelszó újra:</td><td><input type="password" name="newconf" id="newconf" value="" /></td></tr>
<td colspan=2><p>Az adatok módosításához adja meg jelszavát:</p></td>
<tr><td>Régi jelszó:</td><td><input type="password" name="oldPW" id="oldPW" /></td></tr>
<tr><td>Régi jelszó újra:</td><td><input type="password" name="oldConfirm" id="oldConfirm" /></td></tr>
</table>
<input type="button" 
                   value="Módosítás" 
                   onclick="return changeformhash(this.form,
                                   this.form.oldPW,
                                   this.form.oldConfirm
								   );" /> 
</form>
</div>
</div>  
<?php 	
	include_once 'includes/banner_bar.php';
	mysqli_close($mysqli);
	?> 
   </body>
</html>