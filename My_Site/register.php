<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Regisztráció</title>
		<link name="msapplication-square150x150logo" content="content/assets/Home-icon.png" />
		<link rel="icon" href="content/assets/Home-icon.png">
		<link rel="apple-touch-icon" href="content/assets/Home-icon.png" sizes="57x57" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>      
        <link rel="stylesheet" href="styles/general.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    </head>
    <body>
	<?php 	
	include_once 'includes/top_menu.php';
	?>
	<div id="content">
        <h1>Regisztráció a további tartalmak megtekintéséhez:</h1>
        <?php
		//echo esc_url($_SERVER['REQUEST_URI']);
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
        <ul>
            <li>Az Email-címnek valós formátumúnak kell lennie(felhasználó@szolgáltató.hu).</li>
            <li>A jelszónak legalább 6 karakter hosszúnak kell lennie.</li>
            <li>A jelszónak tartalmaznia kell:
                <ul>
                    <li>Legalább egy nagybetűt (A..Z)</li>
                    <li>Legalább egy kisbetűt (a..z)</li>
                    <li>Legalább egy számot (0..9)</li>
                </ul>
            </li>
            <li>A jelszónak és a megerősítésének pontosan meg kell egyeznie.</li>
        </ul>
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" 
                method="post" 
                name="registration_form"> 
			<input type="hidden" name="goBack" value="
				<?php
				if(isset($_GET['goBack']))
					echo $_GET['goBack'];
				else
					echo "index.php";
				?>
			"/>
				<table>
				<tr><td>Vezetéknév:</td><td><input type='text' 
                name='lastname' 
                id='lastname' /></td></tr>
				<tr><td>Keresztnév:</td><td><input type='text' 
                name='firstname' 
                id='firstname' /></td></tr>
				<tr><td>Email:</td><td><input type="text" name="email" id="email" /></td></tr>
				<tr><td>Szervezet:</td><td><input type='text' 
                name='organisation' 
                id='organisation' /></td></tr>
                <tr><td>Jelszó:</td><td><input type="password"
                             name="password" 
                             id="password"/></td></tr>
				<tr><td>Jelszó megerősítése:</td><td><input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /></td></tr></table>
            <input type="button" 
                   value="Regisztráció" 
                   onclick="return regformhash(this.form,
                                   this.form.lastname,
                                   this.form.firstname,
                                   this.form.email,
                                   this.form.organisation,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
        </form>
		</div>
		<?php 	
	include_once 'includes/banner_bar.php';
	mysqli_close($mysqli);
	?>
    </body>
</html>
