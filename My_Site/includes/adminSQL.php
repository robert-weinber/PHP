<?php
// SQL lekérdezések, amiket az adminfelület gombjai indítanak el...
require_once 'vendor/autoload.php';

use WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;

$connectionString = "DefaultEndpointsProtocol=http;AccountName=contentblobs;AccountKey='IW6a5jRSF8ORoWvJbDYSNKnQ3ymiLO3uKADHLlurG7E9sryOF49I7vV2PhigSTuYvSMFLTjgK9J02bai8H4JXw=='";
		$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);

//     FELHASZNÁLÓK
	if(isset($_POST['modUser'])){
	$allow=0;
	$sendmail=false;
			if(isset($_POST['access'])){
			$allow=1;
			if(isset($_POST['access']) and $_POST['oldaccess']==0)
			$sendmail=true;
			}
	if ($insert_stmt = $mysqli->prepare("UPDATE members SET `rank` = ? WHERE `id` = ?")) {
            $insert_stmt->bind_param('ii', $allow, $_POST['userID']);
            if ($insert_stmt->execute()) {
				echo "Az felhasználó sikeresen módosítva";			
	if($sendmail){
		$to=$_POST['maildaddress'];
		$subj="Hozzáférés megadva.";
		$msg="Kedves ".$_POST['username'].",<br>
		<br>
		Adminisztrátrounk jóváhagyta az ön hozzáférését a korlátozott tartalmakhoz.";
		include_once('includes/mailer.php');
	}
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
	
	if(isset($_POST['delUser'])){
	
	
	if ($stmt = $mysqli->prepare("DELETE FROM members WHERE `id` = ?")) {
            $stmt->bind_param('i', $_POST['userID']);
            if ($stmt->execute()) {
    echo "A felhasználó sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
	
//   ALBUMOK
	if(isset($_POST['modAlbum'])){
		$albID=0;
		if(isset($_POST['albumID']))
				$albID=$_POST['albumID'];
	$allow=0;
			if(isset($_POST['albumAccess']))
				$allow=1;
	
			if(isset($_POST['albumOnHome'])){
				$alreadyThere=false;
				if ($stmt = $mysqli->prepare("SELECT id FROM `important` WHERE type='album' and otherID = ?")) {
            $stmt->bind_param('i', $albID);
            $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
						$alreadyThere=true;
					}
				if(!$alreadyThere){
					if ($insert_stmt = $mysqli->prepare("INSERT INTO `important` (`id`, `type`, `otherID`, `originalDate`) VALUES (NULL, 'album', ?, ?)")) {
            $insert_stmt->bind_param('is', $albID, $_POST['albumDate']);
            if ($insert_stmt->execute()) {
    echo "Az album felvéve a főoldalra";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
					}
				}
				}
			}else{
				if ($stmt = $mysqli->prepare("DELETE FROM important WHERE `otherID` = ? and `type` = 'album'")) {
            $stmt->bind_param('i', $albID);
            if ($stmt->execute()) {
    echo "Az album levéve a főoldalról";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
				}
			}
			
	if ($stmt = $mysqli->prepare("UPDATE albums SET `public` = ?, `name` = ?, `dátum` = ? WHERE `id` = ?")) {
            $stmt->bind_param('issi',$allow, $_POST['albumModName'], $_POST['albumModDate'], $albID);
            if ($stmt->execute()) {
    echo "Az album hozzáférése sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}

	}

	if(isset($_POST['addAlbum'])){
		$newName="Új Album";
		$newDate="Dátum Nem Meghatározott";
		if(isset($_POST['newAlbumName']))
				$newName=$_POST['newAlbumName'];
		if(isset($_POST['newAlbumDate']))
				$newDate=$_POST['newAlbumDate'];
	$allow=0;
			if(isset($_POST['newAlbumAccess']))
				$allow=1;
	$result=mysqli_query($mysqli,"SELECT MAX(ordering) as total  FROM albums");
	if($egysor=mysqli_fetch_assoc($result)) {
		$total=$egysor['total']+1;
	}
	if ($insert_stmt = $mysqli->prepare("INSERT INTO `albums` (`id`, `name`, `dátum`, `date`, `public`, `ordering`) VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, ?, ?)")) {
            $insert_stmt->bind_param('ssii',$newName, $newDate, $allow, $total);
            if ($insert_stmt->execute()) {
    echo "Az album sikeresen felvéve".$allow;
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
	
	if(isset($_POST['delAlbum'])){
	
	if ($stmt = $mysqli->prepare("DELETE FROM albums WHERE `id` = ?")) {    /////// AZ ALBUM TÖRLÉSE
            $stmt->bind_param('i', $_POST['albumID']);
            if ($stmt->execute()) {
	if ($stmt = $mysqli->prepare("UPDATE albums SET `ordering` = `ordering`-1 WHERE `ordering` > ?")) {    /////// A TÖBBI ALBUM SORRENDJÉNAK KORRIGÁLÁSA
            $stmt->bind_param('i', $_POST['oldOrder']);
            if ($stmt->execute()) {
    echo "Az albumok hozzáigazítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	$sql = "SELECT id, name
				  FROM images WHERE `album` =".$_POST['albumID'];
		$eredmeny = mysqli_query($mysqli,$sql);
		while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		$picID=$egysor['id'];
		try    {
		$blobRestProxy->deleteBlob("pictures", $egysor['name']);
	if ($stmt = $mysqli->prepare("DELETE FROM images WHERE `id` = ?")) {   /////// A KÉPEK TÖRLÉSE
            $stmt->bind_param('i', $picID);
            if ($stmt->execute()) {
    //echo "A kép sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
		}catch(ServiceException $e){
			echo "törlés sikertelen";
			$code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
			}
			}
    echo "Az album sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
	
//   KÉPEK
	if(isset($_POST['addToAlbum'])){
		$j = 0; //Variable for indexing uploaded image 
		
    for ($i = 0; $i < count($_FILES['file']['name']); $i++) { //loop to get individual element from the array
        $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
        $ext = explode('.', basename($_FILES['file']['name'][$i])); //explode file name from dot(.) 
        $file_extension = end($ext); //store extensions in the variable
		$newName=md5(uniqid()).".".$ext[count($ext) - 1];
        $j = $j + 1; //increment the number of uploaded images according to the files in array       
$content = fopen($_FILES['file']['tmp_name'][$i],'r');
        if (($_FILES["file"]["size"][$i] < 4000000) //KB 4 megabájt max
            && in_array($file_extension, $validextensions)) {
            if ($blobRestProxy->createBlockBlob("pictures", $newName, $content)) { //if file moved to uploads folder
                echo '<span id="noerror">Kép('.$j.'.) sikeresen feltöltve!</span><br/><br/>';
				if ($insert_stmt = $mysqli->prepare("INSERT INTO `images` (`id`, `name`, `album`, `public`) VALUES (NULL, ?, ?, '1')")) {
            $insert_stmt->bind_param('si',$newName, $_POST['albumID']);
            if ($insert_stmt->execute()) {
    echo "Az album sikeresen felvéve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
				}
				
				
            } else { //if file was not moved.
                echo $j.
                ').<span id="error">Sikertelen feltöltés, próbálja újra!</span><br/><br/>';
            }
        } else { //if file size and file type was incorrect.
            echo $j.
            ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
        }
    }
	}
	
	if(isset($_POST['modPic'])){
		$albID=0;
		if(isset($_POST['albumIndex']))
				$albID=$_POST['albumIndex'];
	$allow=0;
			if(isset($_POST['access']))
				$allow=1;
			
	if ($stmt = $mysqli->prepare("UPDATE images SET `public` = ?, album = ? WHERE `id` = ?")) {
            $stmt->bind_param('iii', $allow, $albID, $_POST['picID']);
            if ($stmt->execute()) {
    echo "A kép sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
	
	if(isset($_POST['delPic'])){
	
	try    {
    $blobRestProxy->deleteBlob("pictures", $_POST['picName']);
	
	if ($stmt = $mysqli->prepare("DELETE FROM images WHERE `id` = ?")) {
            $stmt->bind_param('i', $_POST['picID']);
            if ($stmt->execute()) {
    echo "Az kép sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}
catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}
	}
	
//   CIKKEK
	if(isset($_POST['addArticle'])){
	
	echo "Felvétel...";
	$result=mysqli_query($mysqli,"SELECT MAX(ordering) as total  FROM articles");
	if($egysor=mysqli_fetch_assoc($result)) {
		$total=$egysor['total']+1;
	}
	//$newName=md5(uniqid());
	//$newPath= "content/articles/".$newName.".txt";
	$newText=$_POST['description'];
	//file_put_contents($newPath, $newText);
	if ($insert_stmt = $mysqli->prepare("INSERT INTO `articles` (`id`, `title`, `text`, `date`, `ordering`) VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, ?)")) {
            $insert_stmt->bind_param('ssi',$_POST['title'], $newText, $total);
            if ($insert_stmt->execute()) {
    echo "A cikk sikeresen felvéve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}
	
	if(isset($_POST['updateArticle'])){
	
	echo "Módosítás...";
	$allow=0;
			if(isset($_POST['articleAccess']))
				$allow=1;
	if ($insert_stmt = $mysqli->prepare("UPDATE articles SET `public` = ? WHERE `id` = ?")) {
            $insert_stmt->bind_param('ii', $allow, $_POST['artID']);
            if ($insert_stmt->execute()) {
    echo "A cikk hozzáférése sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	
	if(isset($_POST['articleOnHome'])){
		$alreadyThere=false;
				if ($stmt = $mysqli->prepare("SELECT id FROM `important` WHERE type='article' and otherID = ?")) {
            $stmt->bind_param('i', $albID);
            $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
						$alreadyThere=true;
					}
				if(!$alreadyThere){
					if ($insert_stmt = $mysqli->prepare("INSERT INTO `important` (`id`, `type`, `otherID`, `originalDate`) VALUES (NULL, 'article', ?, ?)")) {
            $insert_stmt->bind_param('is',$_POST['artID'], $_POST['artDate']);
            if ($insert_stmt->execute()) {
    echo "A cikk felvéve a főoldalra";
} else {
   echo "Error: <br>" . mysqli_error($mysqli);
}
					}
					}
				}
			}else{
				if ($insert_stmt = $mysqli->prepare("DELETE FROM important WHERE `otherID` = ? and `type` = 'article'")) {
            $insert_stmt->bind_param('i', $_POST['artID']);
            if ($insert_stmt->execute()) {
    echo "A cikk levéve a főoldalról";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
			}
			}
}

	if(isset($_POST['modArticle'])){
		//$path= "content/articles/".$_POST['textID'].".txt";
		$newText=$_POST['description'];
		//file_put_contents($path, $newText);
	if ($stmt = $mysqli->prepare("UPDATE articles SET `title` = ?, `text` = ? WHERE `id` = ?")) {
            $stmt->bind_param('ssi',$_POST['title'], $newText, $_POST['artID']);
            if ($stmt->execute()) {
    echo "A cikk sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}

	if(isset($_POST['modArtThumb'])){
	
	echo basename($_FILES['file']['name']);
        $ext = explode('.', basename($_FILES['file']['name']));
        $file_extension = end($ext); //kiterjesztés
		$newName=$_POST['artID'].".".$ext[count($ext) - 1];//random név      
$content = fopen($_FILES['file']['tmp_name'],'r');

        if (($_FILES["file"]["size"]/*[$i]*/ < 40000000) //KB 40 mega max
			) {
            if ($blobRestProxy->createBlockBlob("articlethumbnails", $newName, $content)) { //sikeres feltöltés
                echo 
                ').<span id="noerror">Fájl sikeresen feltöltve!</span><br/><br/>';
				
				
				if ($stmt = $mysqli->prepare("UPDATE articles SET `thumbnail` = ? WHERE `id` = ?")) {
            $stmt->bind_param('si',$newName, $_POST['artID']);
            if ($stmt->execute()) {
    echo "A cikk képe sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
            } else { //if file was not moved.
                echo //$j.
                ').<span id="error">Sikertelen feltöltés, próbálja újra!</span><br/><br/>';
            }
        } else { //if file size and file type was incorrect.
            echo //$j.
            ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
        }
	
}

	if(isset($_POST['delArticle'])){
	
	echo "Törlés...";
	$thumbname="";
	$sql = "SELECT thumbnail
				  FROM articles
				  WHERE `id` = ".$_POST['artID']."";
		$eredmeny = mysqli_query($mysqli,$sql);
	while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
		$thumbname=$egysor['thumbnail'];
	}
	try    {
    $blobRestProxy->deleteBlob("articlethumbnails", $thumbname);
	
	if ($stmt = $mysqli->prepare("DELETE FROM articles WHERE `id` = ?")) {
            $stmt->bind_param('i', $_POST['artID']);
            if ($stmt->execute()) {
		if ($stmt = $mysqli->prepare("UPDATE articles SET `ordering` = `ordering`-1 WHERE `ordering` > ?")) {    /////// A TÖBBI CIKK SORRENDJÉNAK KORRIGÁLÁSA
            $stmt->bind_param('i', $_POST['oldOrder']);
            if ($stmt->execute()) {
    echo "A cikkek hozzáigazítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
    echo "A cikk sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}
catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}
	}
		
//   FÁJLOK
	if(isset($_POST['addFile'])){

	//print_r($_FILES); 
        $ext = explode('.', basename($_FILES['file']['name']));
        $file_extension = end($ext); //kiterjesztés
		$newName=md5(uniqid()).".".$ext[count($ext) - 1];//random név
		$content = fopen($_FILES['file']['tmp_name'],'r');
        if (($_FILES["file"]["size"]/*[$i]*/ < 40000000) //KB 40 mega max
			) {
            if ($blobRestProxy->createBlockBlob("downloads", $newName, $content)) { //sikeres feltöltés
                echo 
                ').<span id="noerror">Fájl sikeresen feltöltve!</span><br/><br/>';
				$allow=0;
				if(isset($_POST['newFileAccess']))
					$allow=1;
				$result=mysqli_query($mysqli,"SELECT MAX(ordering) as total  FROM downloads");
	if($egysor=mysqli_fetch_assoc($result)) {
		$total=$egysor['total']+1;
	}
				if ($insert_stmt = $mysqli->prepare("INSERT INTO `downloads` (`id`, `name`, `generatedname`, `public`, `description`, `ordering`) VALUES (NULL, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssisi', $_POST['newFileName'], $newName, $allow, $_POST['newFileDesc'], $total);
            if ($insert_stmt->execute()) {
    echo "A fájl sikeresen felvéve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
				}
				
				
            } else { //if file was not moved.
                echo //$j.
				//print_r($_FILES); 
                ').<span id="error">Sikertelen feltöltés, próbálja újra!</span><br/><br/>';
            }
        } else { //if file size and file type was incorrect.
            echo //$j.
            ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
        }
    //}
	}
	
	if(isset($_POST['delFile'])){
	
	echo "Törlés...";
	try    {
    $blobRestProxy->deleteBlob("downloads", $_POST['fileGenName']);
	if ($stmt = $mysqli->prepare("DELETE FROM downloads WHERE `id` = ?")) {
            $stmt->bind_param('i', $_POST['fileID']);
            if ($stmt->execute()) {
				if ($stmt = $mysqli->prepare("UPDATE downloads SET `ordering` = `ordering`-1 WHERE `ordering` > ?")) {    /////// A TÖBBI DOKUMENTUM SORRENDJÉNAK KORRIGÁLÁSA
            $stmt->bind_param('i', $_POST['oldOrder']);
            if ($stmt->execute()) {
    echo "A dokumentumok hozzáigazítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
    echo "A fájl sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}
catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}
	
	}
	
	if(isset($_POST['modFile'])){
	$allow=0;
	if(isset($_POST['fileAccess']))
	$allow=1;
if ($stmt = $mysqli->prepare("UPDATE downloads SET `name` = ?, `description` = ?, `public` = ? WHERE `id` = ?")) {
            $stmt->bind_param('ssii',$_POST['fileModName'], $_POST['fileModDesc'], $allow, $_POST['fileID']);
            if ($stmt->execute()) {
    echo "A Fájl sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}
	
//   STATIKUS SZÖVEGEK
	if(isset($_POST['modStaticValue'])){
	
	echo "Módosítás...";
	$allow=0;
			if(isset($_POST['mapvisible']))
				$allow=1;
	if ($stmt = $mysqli->prepare("UPDATE static_text SET `value` = ? WHERE `id` = ?")) {
            $stmt->bind_param('si',$_POST['description'], $_POST['staticID']);
            if ($stmt->execute()) {
    echo "A szöveg sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}			
}	
}

	if(!isset($_POST['modStatic']) and !isset($_POST['modMap']) and isset($_POST['staticIDpre'])){
	
	echo "Láthatóság...";
	$visible=0;
			if(isset($_POST['mapvisible']))
				$visible=1;
	if ($stmt = $mysqli->prepare("UPDATE static_text SET `visible` = ? WHERE `id` = ?")) {
            $stmt->bind_param('ii',$visible, $_POST['staticIDpre']);
            if ($stmt->execute()) {
    echo "A térkép láthatósága sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}			
}
}
			
	if(isset($_POST['modMap'])){
	
	echo "Módosítás...";
	$srcSplit = explode('"', $_POST['mapSrc']);
	if(count($srcSplit)>=2){
	$newSrc=$srcSplit[1];
	if (strpos($newSrc, "https://www.google.com/maps/embed") === 0){
		if ($stmt = $mysqli->prepare("UPDATE static_text SET `value` = ? WHERE `id` = ?")) {
            $stmt->bind_param('si',$newSrc, $_POST['staticIDpre']);
            if ($stmt->execute()) {
    echo "A térkép sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
	}	
		}	
	}else{
		echo "Hibás link!";
	}
	}else{
		echo "Hibás link!";
	}
}

//   BANNEREK
	if(isset($_POST['modBanner'])){
		if(isset($_FILES['file'])){
	 $target_path = "content/assets/banners/"; //Feltöltés helye
	echo basename($_FILES['file']['name']);
        $ext = explode('.', basename($_FILES['file']['name']));
        $file_extension = end($ext); //kiterjesztés
		$newName=$_POST['bannerID'].".".$ext[count($ext) - 1];//random név
$content = fopen($_FILES['file']['tmp_name'],'r');
        if (($_FILES["file"]["size"]/*[$i]*/ < 4000000) //KB 4 mega max
			) {
            if ($blobRestProxy->createBlockBlob("banners", $newName, $content)) { //sikeres feltöltés
                echo 
                ').<span id="noerror">Fájl sikeresen feltöltve!</span><br/><br/>';
				
				
				if ($stmt = $mysqli->prepare("UPDATE banners SET `image` = ? WHERE `id` = ?")) {
            $stmt->bind_param('si',$newName, $_POST['bannerID']);
            if ($stmt->execute()) {
    echo "A banner képe sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
            } else { //if file was not moved.
                echo //$j.
                ').<span id="error">Sikertelen feltöltés, próbálja újra!</span><br/><br/>';
            }
        } else { //if file size and file type was incorrect.
            echo //$j.
            ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
        }
	}
	if(isset($_POST['modLink'])){
		if ($stmt = $mysqli->prepare("UPDATE banners SET `link` = ? WHERE `id` = ?")) {
            $stmt->bind_param('si',$_POST['modLink'], $_POST['bannerID']);
            if ($stmt->execute()) {
    echo "A banner linkje sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
}

	if(isset($_POST['newBanner'])){
        $ext = explode('.', basename($_FILES['file']['name']));
        $file_extension = end($ext); //kiterjesztés
		$newName=md5(uniqid()).".".$ext[count($ext) - 1];//random név
$content = fopen($_FILES['file']['tmp_name'],'r');
        if (($_FILES["file"]["size"]/*[$i]*/ < 4000000) //KB 4 mega max
			) {
            if ($blobRestProxy->createBlockBlob("banners", $newName, $content)) { //sikeres feltöltés
                echo 
                ').<span id="noerror">Fájl sikeresen feltöltve!</span><br/><br/>';
				$allow=0;
				if(isset($_POST['newFileAccess']))
					$allow=1;
				$result=mysqli_query($mysqli,"SELECT MAX(ordering) as total  FROM banners");
	if($egysor=mysqli_fetch_assoc($result)) {
		$total=$egysor['total']+1;
	}
				if ($insert_stmt = $mysqli->prepare("INSERT INTO `banners` (`id`, `image`, `link`, `ordering`) VALUES (NULL, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssi', $newName, $_POST['newLink'], $total);
            if ($insert_stmt->execute()) {
    echo "A fájl sikeresen felvéve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
				}
				
				
            } else { //if file was not moved.
                echo //$j.
                ').<span id="error">Sikertelen feltöltés, próbálja újra!</span><br/><br/>';
            }
        } else { //if file size and file type was incorrect.
            echo //$j.
            ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
        }
}

	if(isset($_POST['delBanner'])){
	
	echo "Törlés...";
	try    {
    $blobRestProxy->deleteBlob("banners", $_POST['bannerImg']);
	
if ($stmt = $mysqli->prepare("DELETE FROM banners WHERE `id` = ?")) {
            $stmt->bind_param('i', $_POST['bannerID']);
            if ($stmt->execute()) {
				if ($stmt = $mysqli->prepare("UPDATE banners SET `ordering` = `ordering`-1 WHERE `ordering` > ?")) {    /////// A TÖBBI BANNER SORRENDJÉNAK KORRIGÁLÁSA
            $stmt->bind_param('i', $_POST['oldOrder']);
            if ($stmt->execute()) {
    echo "A bannerek hozzáigazítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
    echo "A banner sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
}
catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}
	
}

//     VIDEÓK
if(isset($_POST['newVid'])){
	$rawLink=$_POST['newVidLink'];
	$legitLink=false;
	if(strlen($rawLink)==11){
		$keyLink=$rawLink;
	$legitLink=true;
	}else{
	$splitLink = explode('watch?v=', $rawLink);
	if(count($splitLink)==2){
	$partLink=$splitLink[1];
	if(strlen($partLink) > 11){
		$splitLink = explode('&', $partLink);
		if(count($splitLink)>=2){
	$keyLink=$splitLink[0];		
	if(strlen($keyLink)==11)
	$legitLink=true;
		}
	}
	if(strlen($partLink) == 11){
		$keyLink=$partLink;
	$legitLink=true;
	}
	}
	}
	if($legitLink){
	echo "Felvétel...";
	$result=mysqli_query($mysqli,"SELECT MAX(ordering) as total  FROM vids");
	if($egysor=mysqli_fetch_assoc($result)) {
		$total=$egysor['total']+1;
	}
	if ($insert_stmt = $mysqli->prepare("INSERT INTO `vids` (`id`, `name`, `link`, `ordering`) VALUES (NULL, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssi',$_POST['newVidName'], $keyLink, $total);
            if ($insert_stmt->execute()) {
    echo "A videó sikeresen felvéve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}else{
		echo "A a link nem megfelelő";
	}
}

	if(isset($_POST['modVid'])){
	$rawLink=$_POST['modVidLink'];
	$legitLink=false;
	if(strlen($rawLink)==11){
		$keyLink=$rawLink;
	$legitLink=true;
	}else{
	$splitLink = explode('watch?v=', $rawLink);
	if(count($splitLink)==2){
	$partLink=$splitLink[1];
	if(strlen($partLink) > 11){
		$splitLink = explode('&', $partLink);
		if(count($splitLink)>=2){
	$keyLink=$splitLink[0];		
	if(strlen($keyLink)==11)
	$legitLink=true;
		}
	}
	if(strlen($partLink) == 11){
		$keyLink=$partLink;
	$legitLink=true;
	}
	}
	}
	if($legitLink){
	if ($insert_stmt = $mysqli->prepare("UPDATE vids SET `link` = ?, `name` = ? WHERE `id` = ?")) {
            $insert_stmt->bind_param('ssi', $keyLink, $_POST['modVidName'], $_POST['vidID']);
            if ($insert_stmt->execute()) {
				echo "A videó sikeresen módosítva";			
	
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}else{
		echo "A a link nem megfelelő";
	}
	}
	
	if(isset($_POST['delVid'])){
	
	
	if ($stmt = $mysqli->prepare("DELETE FROM vids WHERE `id` = ?")) {
            $stmt->bind_param('i', $_POST['vidID']);
            if ($stmt->execute()) {
    echo "A videó sikeresen törölve";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}
	}
	

           /////////////////////////////////////  FEL-LE MOZGATÁS
		  if(isset($_POST['albumUp']) or isset($_POST['albumDown']) or isset($_POST['vidUp']) or isset($_POST['vidDown'])  or isset($_POST['artUp'])or isset($_POST['artDown']) or isset($_POST['fileUp']) or isset($_POST['fileDown']) or isset($_POST['bannerUp']) or isset($_POST['bannerDown'])){
			  $allow=true;
			  $oldOrder=$_POST['oldOrder'];
			  if(isset($_POST['albumUp']) or isset($_POST['albumDown'])){
				  $targetTable="albums";
				  $targetID=$_POST['albumID'];
				  if(isset($_POST['albumUp'])){
					  $command="`ordering`-1";
					  if($oldOrder==1)
						  $allow=false;
				  }else{
					  $command="`ordering`+1";
				  }
			  }
			  if(isset($_POST['vidUp']) or isset($_POST['vidDown'])){
				  $targetTable="vids";
				  $targetID=$_POST['vidID'];
				  if(isset($_POST['vidDown'])){
					  $command="`ordering`-1";
					  if($oldOrder==1)
						  $allow=false;
				  }else{
					  $command="`ordering`+1";
				  }
			  }
			  if(isset($_POST['artUp']) or isset($_POST['artDown'])){
				  $targetTable="articles";
				  $targetID=$_POST['artID'];
				  if(isset($_POST['artUp'])){
					  $command="`ordering`-1";
					  if($oldOrder==1)
						  $allow=false;
				  }else{
					  $command="`ordering`+1";
				  }
			  }
			  if(isset($_POST['fileUp']) or isset($_POST['fileDown'])){
				  $targetTable="downloads";
				  $targetID=$_POST['fileID'];
				  if(isset($_POST['fileUp'])){
					  $command="`ordering`-1";
					  if($oldOrder==1)
						  $allow=false;
				  }else{
					  $command="`ordering`+1";
				  }
			  }
			  if(isset($_POST['bannerUp']) or isset($_POST['bannerDown'])){
				  $targetTable="banners";
				  $targetID=$_POST['bannerID'];
				  if(isset($_POST['bannerUp'])){
					  $command="`ordering`-1";
					  if($oldOrder==1)
						  $allow=false;
				  }else{
					  $command="`ordering`+1";
				  }
			  }
			  if($allow){
			  echo "UPDATE ".$targetTable."
    SET ordering = ".$command." WHERE `id` = ".$targetID." AND `ordering` != (SELECT MAX(`ordering`) FROM ".$targetTable.")";
		/*if ($stmt = $mysqli->prepare("UPDATE ".$targetTable."
    SET ordering = ".$command." WHERE `id` = ".$targetID." AND `ordering` != (SELECT max(`ordering`) AS maxOrder FROM ".$targetTable.")"
	
	)) {
		//"UPDATE ".$targetTable." SET `ordering` = ".$command." WHERE `id` = ".$targetID."")) {
            //$stmt->bind_param('ssi',$targetTable,$command, $targetID);
		//if ($stmt = $mysqli->prepare("UPDATE ? SET `ordering` = ? WHERE `id` = ?")) {
           // $stmt->bind_param('ssi',$targetTable,$command, $targetID);
            if ($stmt->execute()) {
    echo "A banner linkje sikeresen módosítva";
} else {
    echo "Error: <br>" . mysqli_error($mysqli);
}
	}*/
	$nomore="";
	$modOther="";	
	if($command=="`ordering`+1"){
	$result=mysqli_query($mysqli,"SELECT MAX(ordering) as total  FROM ".$targetTable);
	if($egysor=mysqli_fetch_assoc($result)) {
		echo "siker1";
		$total=$egysor['total'];
		echo "old: ".$oldOrder.", total: ".$total;
		$nomore=" AND `ordering` != ".$total;
	}
		$modOther="UPDATE ".$targetTable."
    SET ordering = `ordering`-1 WHERE `ordering` = ".($_POST['oldOrder']+1).";";
		}else{			
		$modOther="UPDATE ".$targetTable."
    SET ordering = `ordering`+1 WHERE `ordering` = ".($_POST['oldOrder']-1).";";
		}
		if($mysqli->query($modOther) === TRUE) {
		echo "siker";
	}
	$sql="UPDATE ".$targetTable."
    SET ordering = ".$command." WHERE `id` = ".$targetID." ".$nomore;
	if($mysqli->query($sql) === TRUE) {
		echo "siker";
	}
		}
}

?>