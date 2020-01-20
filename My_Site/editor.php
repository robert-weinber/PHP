<!DOCTYPE html>
<html lang="hu">
<?php
include_once 'includes/db_connect_admin.php';
include_once 'includes/functions_admin.php';
 
sec_session_start();
 
if (login_check($mysqli) == true) {
    $logged = 'Be';
} else {
    $logged = 'Ki';
}
?>



    <head>
        <meta charset="UTF-8">
        <title>Admin Felület</title>
        <link rel="stylesheet" href="styles/general.css?version=1" />
        <link rel="stylesheet" href="styles/albums.css?version=1" />
        <link rel="stylesheet" href="styles/articles.css" />
        <link rel="stylesheet" href="styles/editor.css" />
        <link rel="stylesheet" href="styles/theme1.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
		<script src="js/jquery.cookie.js"></script>
		<?php
		if(isset($_POST['users'])){
			$_SESSION['mode']='users';
		}
		if(isset($_POST['vids'])){
			$_SESSION['mode']='vids';
		}
		if(isset($_POST['pics'])){
			$_SESSION['mode']='pics';
		}
		if(isset($_POST['articles'])){
			$_SESSION['mode']='articles';
		}
		if(isset($_POST['downloads'])){
			$_SESSION['mode']='downloads';
		}
		if(isset($_POST['staticText'])){
			$_SESSION['mode']='staticText';
		}
		if(isset($_POST['banners'])){
			$_SESSION['mode']='banners';
		}
		if(isset($_SESSION['mode']) and ($_SESSION['mode']=='articles' or $_SESSION['mode']=='staticText'))
		echo '<link rel="stylesheet" href="editor/easyeditor.css">
<script src="editor/jquery.easyeditor.js"></script>';
		?>
    </head>
    <body>
	<?php 	
	include_once 'includes/top_menu.php';
	?>
<div id="editorcontent">
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
       
		if(isset($_SESSION['rank']) and htmlentities($_SESSION['rank'])==2){
			
			include_once('includes/adminSQL.php');
		
$freekbytes = disk_free_space(".")/1024; 
$totalkbytes = disk_total_space(".")/1024; 
$mykbytes=$totalkbytes-$freekbytes;
echo "</br>Tárhely: ".$mykbytes." KB/ ".$totalkbytes." KB (".round($mykbytes/$totalkbytes*100, 1)."%)";
	if(isset($_POST['optimiseDB'])){
			$q = mysqli_query($mysqli,"OPTIMIZE TABLE `albums` ,
`articles` ,
`banners` ,
`downloads` ,
`images` ,
`important` ,
`login_attempts` ,
`members` ,
`static_text` "); 
if(mysqli_fetch_array($q)) {  
    echo "Az adatbázis optimalizálása sikeres!";  
}
		}
		
		$q = mysqli_query($mysqli,"SHOW TABLE STATUS");  
$size = 0;  
while($row = mysqli_fetch_array($q)) {  
    $size += $row["Data_length"] + $row["Index_length"];  
}
$roundedsize=round($size/1024, 3);
echo "</br>Adatbázis: ".$roundedsize." KB/5000 KB (".round($roundedsize/5000*100 ,1)."%)";
echo "<form id='optbd' ACTION='' METHOD=POST><input type=submit name='optimiseDB' value='Adatbázis optimalizálása'></form>";

		
		echo "<form id='editormodes' ACTION='' METHOD=POST>
		<input type=submit name='users' value='Felhasználók'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='users') echo " id = 'activebutton'";
		echo "><input type=submit name='pics' value='Képek'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='pics') echo " id = 'activebutton'";
		echo "><input type=submit name='vids' value='Videók'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='vids') echo " id = 'activebutton'";
		echo "><input type=submit name='articles' value='Cikkek'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='articles') echo " id = 'activebutton'";
		echo "><input type=submit name='downloads' value='Dokumentumok'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='downloads') echo " id = 'activebutton'";
		echo "><input type=submit name='staticText' value='Statikus Szövegek'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='staticText') echo " id = 'activebutton'";
		echo "><input type=submit name='banners' value='Bannerek'";
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='banners') echo " id = 'activebutton'";
		echo "></form>";
		
		
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='users'){
		$sql = "SELECT id, username_first, username_last, email, organization, rank 
				  FROM members
				  ORDER BY rank DESC, id ASC";
		$eredmeny = mysqli_query($mysqli,$sql);
		echo "<table border=1 id='usertable'><tr><td class='w2'>ID</td><td class='w10'>Felhasználónév</td><td class='w10'>E-mail</td><td class='w10'>Szervezet</td><td class='td5'><img class='w2' src='https://contentblobs.blob.core.windows.net/assets/lockgreen.png' /></td></tr>";
    while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		$access=false;
		
									echo "
									<tr><form id='userform".$egysor['id']."' ACTION='' METHOD=POST>									
									<td>".$egysor['id']."</td><td>".$egysor['username_last']." ".$egysor['username_first']."</td><td>".$egysor['email']."</td><td>".$egysor['organization']."</td><td>";
									if($egysor['rank']==2)
									echo "<div class='w2'>Admin</div>";
								if($egysor['rank']==1)
									echo '<input class="w2" type="checkbox" name="access" value="access'.$egysor['id'].'" checked>';
								if($egysor['rank']==0)
									echo '<input class="w2" type="checkbox" name="access" value="access'.$egysor['id'].'">';
									echo "
									<input type=hidden name='maildaddress' value='".$egysor['email']."'>
									<input type=hidden name='username' value='".$egysor['username_first']."'>
									<input type=hidden name='oldaccess' value='".$egysor['rank']."'>
									<input type=hidden name='userID' value='".$egysor['id']."'>";
									if($egysor['rank']!=2)
									echo "</td><td><input class='w10' type=submit name='modUser' value='Módosítás'></td><td><input class='w10' type=submit  class='confirmable' name='delUser' value='Törlés'></td>";
									echo "</form></tr>";  
									  
									  
								  }
								  echo "</table>";
		
		}
		
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='pics'){
			echo "<form ACTION='' METHOD=POST><table><tr><td class='w20' >Új album</td><td><input class='w20' name='newAlbumName' type='text' placeholder='Album neve'></td><td><input class='w20' name='newAlbumDate' type='text' placeholder='Dátum'></td><td>";
			echo '<input class="w2" type="checkbox" name="newAlbumAccess" value="1"></td>';
    echo "<td><input class='w10' type=submit name='addAlbum' value='Felvétel'></td></tr></form>";
			echo "<tr><td></td><td class='w20'>Albumok</td><td class='w20'>Dátum</td><td><img class='w2' src='https://contentblobs.blob.core.windows.net/assets/lock.png' /></td></tr>";
								  $sqlA = "SELECT id, name, public, dátum, ordering
FROM albums
 ORDER BY ordering";	
	$eredmenyA = mysqli_query($mysqli,$sqlA);
	while($egysorA = mysqli_fetch_array($eredmenyA, MYSQL_BOTH)) {
    echo "<tr><td><button class='w10' value='' onclick='show(".$egysorA['id'].")' >Képek mutatása</button></td>
	<form enctype='multipart/form-data' ACTION='' METHOD=POST>
	<td><input class='w20' type='text' name='albumModName' value='".$egysorA['name']."'></td><td><input class='w20' type='text' name='albumModDate' value='".$egysorA['dátum']."'></td><td>";
	
								if($egysorA['public']==1)
									echo '<input class="w2" type="checkbox" name="albumAccess" value="1" checked>';
								if($egysorA['public']==0)
									echo '<input class="w2" type="checkbox" name="albumAccess" value="1">';	
									$onHomepage=false;
							$sqlI = "SELECT id, type, otherID
							FROM important
							WHERE otherID='".$egysorA['id']."'";	
					$eredmenyI = mysqli_query($mysqli,$sqlI);
					while($egysorI = mysqli_fetch_array($eredmenyI, MYSQL_BOTH)) {
						$onHomepage=true;
					}
								if($onHomepage){
									echo '<input type="checkbox" name="albumOnHome" value="1" checked>';
								}else{
									echo '<input type="checkbox" name="albumOnHome" value="1">';	
								}
								
									
									echo "</td>";
    echo "<td><input class='w10' type=submit name='modAlbum' value='Módosítás'><br>
	<input type=hidden name='albumID' value='".$egysorA['id']."'>
	<input type=hidden name='oldOrder' value='".$egysorA['ordering']."'>
	<input class='w10' type=submit name='delAlbum' class='confirmable' value='Törlés (Képekkel Együtt)'></td>
	<td><input class='w20' name='file[]' type='file' id='file' multiple='multiple'/><br>
	<input class='w20' type=submit name='addToAlbum' value='Kép(ek) Hozzáadása'></td>
	<td><button class='uparrow' type=submit name='albumUp'  value='Fel'><img src='https://contentblobs.blob.core.windows.net/assets/up_arrow.png'></button><br>
						<button class='downarrow' type=submit name='albumDown' value='Le'><img src='https://contentblobs.blob.core.windows.net/assets/down_arrow.png'></button></td></form></tr>";						  
								 

$sql = "SELECT id, name, album, public 
				  FROM images
				  WHERE album = '".$egysorA['id']."'";
		$eredmeny = mysqli_query($mysqli,$sql);
		echo "<td colspan='8'><div class='picholder' id='showPicks".$egysorA['id']."' ";
		if(isset($_COOKIE["shown".$egysorA['id']])){
			echo $_COOKIE["shown".$egysorA['id']];
		}else{
			echo "hidden";
		}
		echo "><table border='1'><tr><td class='w20'>Kép</td><td class='w20'>Album</td><td><img class='w2' src='https://contentblobs.blob.core.windows.net/assets/lock.png' /></td></tr>";
    while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		$access=false;
									echo '<tr><td><div id="photoHolderLarge'.$egysor['id'].'" class="photoHolderLarge" valign="center"><div class="photoBack" id="photoback'.$egysor['id'].'" onclick="small('.$egysor['id'].')"  /></div>
									<img class="photoLarge" id="img'.$egysor['id'].'" src="https://contentblobs.blob.core.windows.net/pictures/'.$egysor['name'].'" /></div>
									<img class="editorPhoto" onclick="large('.$egysor['id'].')" src="https://contentblobs.blob.core.windows.net/pictures/'.$egysor['name'].'" /></td>';
									echo "<form ACTION='' METHOD=POST><td><select class='w20' name='albumIndex'>";
	$sqlA = "SELECT id, name, public
FROM albums
 GROUP BY id";	
	$eredmeny2 = mysqli_query($mysqli,$sqlA);
	while($egysor2 = mysqli_fetch_array($eredmeny2, MYSQL_BOTH)) {
    echo "<option value='".$egysor2['id']."'"; 
	echo (($egysor['album']==$egysor2['id']) ?  "selected='selected'":"");
    echo ">";
    echo $egysor2['name']."</option>";
    }
	echo "</select></td>";
									
									echo "<td>";
								if($egysor['public']==1)
									echo '<input class="w2" type="checkbox" name="access" value="access'.$egysor['id'].'" checked>';
								if($egysor['public']==0)
									echo '<input class="w2" type="checkbox" name="access" value="access'.$egysor['id'].'">';
									echo "
									<input type=hidden name='picID' value='".$egysor['id']."'>
									<input type=hidden name='picName' value='".$egysor['name']."'>
									</td><td><input class='w10' type=submit name='modPic' value='Módosítás'><br>
									<input class='w10' type=submit name='delPic' class='confirmable' value='Törlés'></td></form></tr>";  
								  }
								  echo "</table></div></td>";
	}
								  echo "</table>";
		}
		
				if(isset($_SESSION['mode']) and $_SESSION['mode']=='vids'){
			if ($stmtA = $mysqli->prepare("SELECT id, name, link, ordering, public
				  FROM vids
				  ORDER BY ordering DESC")){
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
	echo "<tr><form enctype='multipart/form-data' ACTION='' METHOD=POST>	";
						
						echo "<input class='w20' type='text' name='newVidName' placeholder='Új Videó Neve'>";
						echo "<input class='w20' type='text' name='newVidLink' placeholder='Új Videó Linkje'>";
						echo "<input class='w10' type='submit' name='newVid' value='Felvétel'/></td>";
			echo "</form></tr>";
		echo "<table border='1'><tr><td class='w20'>Videó</td><td class='w2'>Pozíció</td><td class='w20'>Link</td><td class='w10'>Szerkesztés</td></tr>";
        $stmtA->bind_result($id, $name, $link, $ordering, $public);
         while($stmtA->fetch()){
			 echo "<tr><form enctype='multipart/form-data' ACTION='' METHOD=POST>	";
			 echo "<input type=hidden name='vidID' value='".$id."'>
				   <input type=hidden name='oldOrder' value='".$ordering."'>
				   <input type=hidden name='vidLink' value='".$link."'>";
						echo "<td><div class='editorVid'>";
						echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$link.'" frameborder="0" allowfullscreen></iframe>';
						echo "</div></td>";
						echo "<td><button class='uparrow' type=submit name='vidUp'  value='Fel'><img src='https://contentblobs.blob.core.windows.net/assets/up_arrow.png'></button><br>
						<button class='downarrow' type=submit name='vidDown' value='Le'><img src='https://contentblobs.blob.core.windows.net/assets/down_arrow.png'></button></td>";
						echo "<td><input class='w20' type='text' name='modVidName' value='".$name."'></br><input class='w20' type='text' name='modVidLink' value='".$link."'></td>";
						echo "<td><input class='w10' type='submit' name='modVid' value='Módosítás'/><br>
						<input class='w10' type='submit' class='confirmable' name='delVid' value='Törlés'/></td>";
			echo "</form></tr>";
	}	
	echo "</table>";
		}
		
		}
		
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='articles'){
			
			$sql = "SELECT id, title, text, date, public, thumbnail, ordering
				  FROM articles
				  ORDER BY ordering";
		$eredmeny = mysqli_query($mysqli,$sql);
		echo "<table border='1'><tr><td class='w20'>Kép</td><td class='w20'>Cím</td><td class='w50'>Szöveg</td><td><img class='w2' src='https://contentblobs.blob.core.windows.net/assets/lock.png' /></td><td class='w2'>Pozíció</td><td class='w20'>Kép</td><td class='w10'>Művelet</td></tr>";
    while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		$access=false;
		//$preDesc=file_get_contents("content/articles/".$egysor['text'].".txt");
		/*$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
curl_setopt($ch, CURLOPT_URL, "content/articles/".$egysor['text'].".txt");
$preDesc = curl_exec($ch);
curl_close($ch);*/
//echo "Ezt találtam: ".$preDesc;
									echo "
									<tr><form enctype='multipart/form-data' ACTION='' METHOD=POST>
									<input type=hidden name='preTitle' value='".$egysor['title']."'>
									<input type=hidden name='preDesc' value='".$egysor['text']."'>".
									//<input type=hidden name='textID' value='".$egysor['text']."'>
									"<input type=hidden name='artDate' value='".$egysor['date']."'>
									<input type=hidden name='artID' value='".$egysor['id']."'>
									<input type=hidden name='oldOrder' value='".$egysor['ordering']."'><td>";
									if($egysor['thumbnail']==""){
										echo "<img class='artImg' src='https://contentblobs.blob.core.windows.net/articlethumbnails/placeholder.jpg'/>";
									}else{
									echo "<img class='artImg' src='https://contentblobs.blob.core.windows.net/articlethumbnails/".$egysor['thumbnail']."'/>";
									}
									echo "</td><td>".$egysor['title']."</td>
									<td><div id='artrow".$egysor['id']."' class='artRow'>"./*$preDesc.*/"</div>";
									echo $egysor['text'];
									//echo "<script type='text/javascript'>
									//	 $('#artrow".$egysor['id']."').load('content/articles/".$egysor['text'].".txt');
									//	 </script>";
									echo "</td><td>";
									
									
									if($egysor['public']==1)
									echo '<input class="w2" type="checkbox" name="articleAccess" value="1" checked>';
								if($egysor['public']==0)
									echo '<input class="w2" type="checkbox" name="articleAccess" value="1">';								
									echo "</td><td>";
									$onHomepage=false;
							$sqlI = "SELECT id, type, otherID
							FROM important
							WHERE otherID='".$egysor['id']."' and type='article'";	
					$eredmenyI = mysqli_query($mysqli,$sqlI);
					while($egysorI = mysqli_fetch_array($eredmenyI, MYSQL_BOTH)) {
						$onHomepage=true;
					}
								if($onHomepage){
									echo '<input type="checkbox" name="articleOnHome" value="1" checked>';
								}else{
									echo '<input type="checkbox" name="articleOnHome" value="1">';								}
									
									
									
									echo "<button class='uparrow' type=submit name='artUp'  value='Fel'><img src='https://contentblobs.blob.core.windows.net/assets/up_arrow.png'></button><br>
						<button class='downarrow' type=submit name='artDown' value='Le'><img src='https://contentblobs.blob.core.windows.net/assets/down_arrow.png'></button>";
									echo "</td><td>
									<input class='w20' name='file' type='file' id='file'/>
									<input class='w20' type=submit name='modArtThumb' value='Kép cseréje'></td><td>
									<input class='w10' type=submit name='modArt' value='Szerkesztés'>
									<input class='w10' type=submit name='updateArticle' value='Hozzáférés módosítása'>
									<input class='w10' type=submit class='confirmable' name='delArticle' value='Törlés'>
									</td></form></tr>";  
									  
									  
								  }
								  echo "</table>";
		echo "<form ACTION='' METHOD=POST><input class='w10' type=submit name='newArt' value='Új cikk'></form>";		
		
			                                                                       //////////////// EDITOR
			if(isset($_POST['modArt']) or isset($_POST['newArt'])){
				if(isset($_POST['modArt'])){
					$preTitle=$_POST['preTitle'];
					$preDesc=$_POST['preDesc'];
					$artButton='<input type=hidden name="artID" value="'.$_POST["artID"].'">
					<button class="w10" name="modArticle" id="artsub" type="submit" class="btn">Cikk módosítása</button>';
				}else{
					$preTitle="";
					$preDesc="";
					$artButton='<button class="w10" name="addArticle" id="artsub" type="submit" class="btn">Cikk mentése</button>';
				}
			echo '<form action="" id="myform" class="demo-form" METHOD=POST>
    <input class="w20" name="title" type="text" id="title" placeholder="Cím" value="'.$preTitle.'">
    <textarea name="description" id="description" rows="10" placeholder="Szövegtörzs">'.$preDesc.'</textarea>
    
    '.$artButton;
	//if(isset($_POST['modArt']))
		//echo '<input type=hidden name="textID" value="'.$_POST['textID'].'">  ';
	
echo '</form>';
	//if(isset($_POST['modArt']))
/*echo "<script type='text/javascript'>
										 $('#description').load('content/articles/".$_POST['textID'].".txt');
										 </script>";*/
										 
			}
		}
				
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='downloads'){
			
	echo "<form enctype='multipart/form-data' ACTION='' METHOD=POST><table><tr>
	<td><input class='w20' name='newFileName' type='text' placeholder='Új fájl neve'>";
	echo '</td><td><input class="w5" type="checkbox" name="newFileAccess" value="1">';
    echo "</td><td><input class='w20' name='file' type='file' id='file'/>
	</td><td><input class='w10' type=submit name='addFile' value='Felvétel'></td></tr></table></form>";
	echo "<table border='1'><tr><td class='w20'>Fájl neve</td><td class='w10'>Típus</td><td><img class='w2' src='https://contentblobs.blob.core.windows.net/assets/lock.png' /></td><td class='w5'>Link Tesztelése</td class='w2'><td>Pozíció</td><td class='w10'>Szerkesztés</td></tr>";
	$sql = "SELECT id, name, public, generatedname, description, ordering
FROM downloads ORDER BY ordering";	
	$eredmeny = mysqli_query($mysqli,$sql);
	while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		$ext=pathinfo($egysor['generatedname'], PATHINFO_EXTENSION);
		$extimg="https://contentblobs.blob.core.windows.net/ext/".$ext.".png";
		//if(file_exists($extimg))
			$ext="<img class='editorPhoto' src='".$extimg."' />";
		echo "<tr><form ACTION='' METHOD=POST>
		<td><input class='w20' type='text' name='fileModName' value='".$egysor['name']."'></td>
		<td>".$ext."</td><td>";
		echo "<input type=hidden name='fileID' value='".$egysor['id']."'>
			  <input type=hidden name='oldOrder' value='".$egysor['ordering']."'>";
		echo "<input type=hidden name='fileGenName' value='".$egysor['generatedname']."'>";
		if($egysor['public']==1)
					echo '<input id="chb'.$egysor['id'].'" class="w2" type="checkbox" name="fileAccess" value="1" checked>';
		if($egysor['public']==0)
					echo '<input id="chb'.$egysor['id'].'" class="w2" type="checkbox" name="fileAccess" value="1">';
		echo "<label for='chb".$egysor['id']."'><span></span></label></td><td><a href='download.php?file=".$egysor['generatedname']."'>Letöltés</a></td>";
		echo "<td>
			  <button class='uparrow' type=submit name='fileUp' value='Fel'><img src='https://contentblobs.blob.core.windows.net/assets/up_arrow.png'></button><br>
			  <button class='downarrow' type=submit name='fileDown' value='Le'><img src='https://contentblobs.blob.core.windows.net/assets/down_arrow.png'></button></td>
			  <td><input class='w10' type=submit name='modFile' value='Módosítás'><br>
			  <input type=submit class='w10' class='confirmable w10' name='delFile' value='Törlés'></td>";
		echo"</form></tr>";	}
		}
		
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='staticText'){
			echo "<div>";
			$sql = "SELECT id, name, value, visible
				  FROM static_text
				  ORDER BY id";
		$eredmeny = mysqli_query($mysqli,$sql);
    while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
	if($egysor['name']=='welcome')			
		$staticName="Nyitólap";
	if($egysor['name']=='header')			
		$staticName="Fejléc";
	if($egysor['name']=='contact')			
		$staticName="Kapcsolat";
	if($egysor['name']=='aszer')			
		$staticName="ÁSZER";
	if($egysor['name']=='map')			
		$staticName="Térkép";
		if($egysor['name']!='map'){
			
		
									echo "
									<h4 class='staticTitle'>".$staticName."</h4>
									<form ACTION='' METHOD=POST>
									<input type=hidden name='preValue' value='".$egysor['value']."'>
									<input type=hidden name='staticIDpre' value='".$egysor['id']."'>
									<div class='staticText'>".$egysor['value']."</div>";					
									
									
									
									echo "<input class='w10' type=submit name='modStatic' value='Szerkesztés'></form>";  
									  
		}else{
			
		
									echo "
									<form id='mapform' ACTION='' METHOD=POST>
									<input type=hidden name='preValue' value='".$egysor['value']."'>
									<input type=hidden name='staticIDpre' value='".$egysor['id']."'>
									<h4 class='staticTitle'>".$staticName."</h4>";
									if($egysor['visible']==0)
									echo '<input class="w2" id="mapvisible" type="checkbox" name="mapvisible" value="1">';	
									if($egysor['visible']==1){
									echo '<input class="w2" id="mapvisible" type="checkbox" name="mapvisible" value="1" checked>';
									
									echo "<input class='w20' type=text name='mapSrc' value='' placeholder='Ide illessze be a térkép linkjét!'>
									<input class='w10' type=submit name='modMap' value='Módosítás'></form>
									<div id='mapedit'><iframe src=".$egysor['value']." width='600' height='450' frameborder='0' style='border:0' allowfullscreen></iframe></div>"; 
									}									  
		} 
		
								  }
								  echo "</div>";
								  echo "<div>";
								                                                                         //////////////// EDITOR
			if(isset($_POST['modStatic'])){
					$preValue=$_POST['preValue'];
					$artButton='<input type=hidden name="staticID" value="'.$_POST["staticIDpre"].'">
					<button class="w20" name="modStaticValue" id="artsub" type="submit" class="btn">Szöveg módosítása</button>';
				
			echo '<form action="" id="myform" class="demo-form" METHOD=POST>     

    
    <textarea name="description" id="description" rows="10" placeholder="Szövegtörzs">'.$preValue.'</textarea>

    '.$artButton.'
</form>';
			}
								  
								  echo "</div>";
		}
		
		if(isset($_SESSION['mode']) and $_SESSION['mode']=='banners'){
			if ($stmtA = $mysqli->prepare("SELECT id, image, link, ordering
				  FROM banners ORDER BY ordering")){
		$stmtA->execute();    // Execute the prepared query.
        $stmtA->store_result();
	echo "<tr><form enctype='multipart/form-data' ACTION='' METHOD=POST>	";
						
						echo "<td><input class='w20' name='file' type='file' id='file'/>";
						echo "<input class='w20' type='text' name='newLink' placeholder='Új Link'>";
						echo "<input class='w10' type='submit' name='newBanner' value='Felvétel'/></td>";
			echo "</form></tr>";
		echo "<table border='1'><tr><td class='w20'>Banner</td><td class='w2'>Pozíció</td><td class='w20'>Kép, Link</td><td class='w10'>Szerkesztés</td></tr>";
        $stmtA->bind_result($id, $image, $link, $ordering);
         while($stmtA->fetch()){
			 echo "<tr><form enctype='multipart/form-data' ACTION='' METHOD=POST>	";
			 echo "<input type=hidden name='bannerID' value='".$id."'>
				   <input type=hidden name='oldOrder' value='".$ordering."'>
				   <input type=hidden name='bannerImg' value='".$image."'>";
						echo "<td><div class='editorBanner'>";
						echo "<a href='http://".$link."' target='_blank'>";
						echo "<img src='https://contentblobs.blob.core.windows.net/banners/".$image."'/>";
						echo "</a>";
						echo "</div></td>";
						echo "<td><button class='uparrow' type=submit name='bannerUp'  value='Fel'><img src='https://contentblobs.blob.core.windows.net/assets/up_arrow.png'></button><br>
						<button class='downarrow' type=submit name='bannerDown' value='Le'><img src='https://contentblobs.blob.core.windows.net/assets/down_arrow.png'></button></td>";
						echo "<td><input class='w20' name='file' type='file' id='file'/><br>";
						echo "<input class='w20' type='text' name='modLink' value='".$link."'></td>";
						echo "<td><input class='w10' type='submit' name='modBanner' value='Módosítás'/><br>
						<input class='w10' type='submit' class='confirmable' name='delBanner' value='Törlés'/></td>";
			echo "</form></tr>";
	}	
	echo "</table>";
		}
		
		}
		
		
if(isset($_SESSION['mode']) and ($_SESSION['mode']=='staticText' or $_SESSION['mode']=='articles')){		
$sql ="SELECT images.id AS id, images.name AS name, albums.name AS album
FROM images
INNER JOIN albums
ON images.album=albums.id
ORDER BY album";

$imglist =  '[';
$albnumber=0;
$albname="";
$eredmeny = mysqli_query($mysqli,$sql);
	while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		if($albname!=$egysor['album']) 
		$albnumber=0;
		$albnumber++;
		$albname=$egysor['album'];
		if($imglist != '[')$imglist.=",";
		$imglist.= "{title: '".$egysor['album']." (".$albnumber.")', value: 'https://contentblobs.blob.core.windows.net/pictures/".$egysor['name']."'}";
		}
		$imglist.=  "]";
		
		echo '
		   <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

     <script type="text/javascript">
	 $(document).ready(function(){
		 tinymce.init({ selector:"#description" ,
  height: 500,
  plugins: [
    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    "table contextmenu directionality emoticons textcolor paste fullpage textcolor colorpicker textpattern"
  ],

  toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
  toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
  toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | ltr rtl | visualchars visualblocks nonbreaking pagebreak",
  
  fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt 1vw 2vw 3vw 4vw 5vw 1vh 2vh 3vh 4vh 5vh",
  image_list: '.$imglist.',


  menubar: false,
  toolbar_items_size: "small",
  
  content_css: [
    "//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css",
    "//www.tinymce.com/css/codepen.min.css"
  ]
});
});
</script>
';
		
}
		
		
		}
	mysqli_close($mysqli);
		?>
   </div>
   <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

     <script type="text/javascript">
	 $(document).ready(function(){
		 $('.confirmable').click(function(){
			 return confirm('Biztosan törölni szeretné az elemet?');
		 });	
			$('#mapvisible').click(function(){
			 $('#mapform').submit();
		 });	
	 
	 });

function show(id){
		$("#showPicks"+id).toggle();
		if($("#showPicks"+id).css("display")=="none")
			$.cookie("shown"+id, "hidden");
		if($("#showPicks"+id).css("display")=="block")
			$.cookie("shown"+id, "");
}
var enlargedObject=null;
function large(type, id){
	$("#large"+type+id).fadeToggle();
	if(type=="PhotoHolder"){
		enlargedObject=$("#img"+id);
		setLargePosition();
	}
}
function setLargePosition(){
		if(!mobile){			
		console.log($(window).width());
		console.log(mobile);
		console.log(enlargedObject.width());
		enlargedObject.css({height:'80vh', width:'auto'});
		console.log(($(window).width()-enlargedObject.width())/2);
		enlargedObject.css({top:'10vh', left:($(window).width()-enlargedObject.width())/2});
		}else{
		console.log($(window).height());
		console.log(mobile);
		console.log(enlargedObject.height());
		enlargedObject.css({width:'80vw', height:'auto'});
			console.log(($(window).height()-enlargedObject.height())/2);
		enlargedObject.css({left:'10vw', top:($(window).height()-enlargedObject.height())/2});
		}
}
function small(type, id){
		$("#large"+type+id).fadeToggle();
		enlargedObject=null;
}
  </script>
    </body>
</html>