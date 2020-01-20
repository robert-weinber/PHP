<?php 
	$thisPage=basename($_SERVER['PHP_SELF']);
	if (isset($_SESSION['popup'])) {
			$popupmsg=$_SESSION['popup'];
	include_once 'includes/popups.php';
        }
		require_once 'vendor/autoload.php';

use WindowsAzure\Common\ServicesBuilder;

use WindowsAzure\Common\ServiceException;

use WindowsAzure\Common\CloudConfigurationManager;

use WindowsAzure\Blob\Models\Block;

use WindowsAzure\Blob\Models\CreateContainerOptions;

use WindowsAzure\Blob\Models\ListBlobsOptions;

use WindowsAzure\Blob\Models\ListContainersOptions;

$connectionString = "DefaultEndpointsProtocol=http;AccountName=contentblobs;AccountKey='IW6a5jRSF8ORoWvJbDYSNKnQ3ymiLO3uKADHLlurG7E9sryOF49I7vV2PhigSTuYvSMFLTjgK9J02bai8H4JXw=='";


$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);


    $blob = $blobRestProxy->getBlob("assets", "headerlogo.png");
      //$cimer=$blob->geturl();
	  //echo "cimer: ".$cimer;

	?>
	<div id="headerbar"><img id="banner" src="https://contentblobs.blob.core.windows.net/assets/headerlogo.png"/>
	<?php 
	if ($stmt = $mysqli->prepare("SELECT value 
				  FROM static_text 
                                  WHERE name = 'header' LIMIT 1")) {
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        // get variables from result.
        $stmt->bind_result($value);
        $stmt->fetch();
				
				echo "<p id='organ'>".$value."</p></div>";
				}
	?>
<div id="menuwrapper">
<ul id="menu">
  <li class="menuelem" <?php if($thisPage=='index.php') echo 'id="active"';?>><a <?php if($thisPage!='index.php') echo 'href="index.php"';?>><span class="vmiddle">Bemutatkozás</span></a></li>
  <li class="menuelem" <?php if($thisPage=='hirek.php') echo 'id="active"';?>><a <?php if($thisPage!='hirek.php') echo 'href="hirek.php"';?>><span class="vmiddle">Hírek</span></a></li>  
  <li class="menuelem" <?php if($thisPage=='galeria.php') echo 'id="active"';?>><a <?php if($thisPage!='galeria.php') echo 'href="galeria.php"';?>><span class="vmiddle">Média</span></a></li>
  <li class="menuelem" <?php if($thisPage=='dokumentumok.php') echo 'id="active"';?>><a <?php if($thisPage!='dokumentumok.php') echo 'href="dokumentumok.php"';?>><span class="vmiddle">Letöltések</span></a></li>  
  <li class="menuelem" <?php if($thisPage=='kapcsolat.php') echo 'id="active"';?>><a <?php if($thisPage!='kapcsolat.php') echo 'href="kapcsolat.php"';?>><span class="vmiddle">Kapcsolat</span></a></li>  
  </ul>
  <?php
  echo '<div id="loginmenu"><div id="arrow" onclick="loginExpand()" >
						<img id="arrow"src="http://www.freeiconspng.com/uploads/arrow-down-icon-png-22.png"></div>
						<div id="loginMessage">';
        if (login_check($mysqli) == true) {
			
                        echo 'Bejelentkezve: ';
						if(isset($_SESSION['rank']) and htmlentities($_SESSION['rank'])==2){
						echo '<span id="myname"><a href="editor.php">'.htmlentities($_SESSION['username']).'</a></span>';
						}else{
						echo "<span id='myname'>".htmlentities($_SESSION['username'])."</span>";
						}
						echo  ' </div></div>';
 
            echo ' <div class="logindrop" id="logindropdownIn"><form class="loggedinForm" action="includes/logout.php" method="post" name="logout_form">
			<input type="hidden" name="goBack" value="'.$thisPage.'"/>
			<input class="logoutBtn loggedinBtn" type="submit" value="Kijelentkezés" /></form>
			<form class="loggedinForm" action="changePW.php" method="post" name="changePW_form">
			<input class="changePW loggedinBtn" type="submit" value="Adatmódosítás" /></form></div>';
        } else {
                        echo 'Kijelentkezve</div></div>';
						echo '<div class="logindrop" id="logindropdownOut">
						<form id="loginForm" action="includes/process_login.php" method="post" name="login_form">   
			<input type="hidden" name="goBack" value="'.$thisPage.'"/>
            <div class="pwLog">Email: <input class="logintext" type="text" name="email" /></div>
            <div class="pwLog">Jelszó: <input class="logintext" type="password" name="password" id="password"/></div>
            <input type="button" class="loginBtn" value="Bejelentkezés" onclick="formhash(this.form, this.form.password);" /></form>
				   <a class="regBtn" href="register.php"><button>Regisztráció</button></a><a class="newPwBtn" href="newPW.php"><button>Új jelszó</button></a>
        </div>';
                }
?> 
  </div>
  <div id="searchholder"><img name='searchexpand' id="searchexpand" src="https://contentblobs.blob.core.windows.net/assets/search.png"><div id="searchbar" ><form action="searchPage.php" method="post" >
<input type='text' 
                name='searchtext' 
                id='searchtext' />
<input type="submit" id='searchbutton'
                   value="Keresés" />
				   </form></div></div>
 <script type="text/JavaScript" src="js/rotate.js"></script> 
 <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>>
 <script type="text/JavaScript" src="js/windowmanager.js"></script> 
 <script type="text/javascript">



  </script>