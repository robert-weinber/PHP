<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'includes/db_connect_admin.php';
include_once 'includes/functions_admin.php';
 
sec_session_start();
if (login_check($mysqli) == true and htmlentities($_SESSION['rank'])==2) {

$sql ="SELECT images.id, images.name, albums.name
FROM images
INNER JOIN albums
ON images.album=albums.id";

$outp = "[";
$eredmeny = mysqli_query($mysqli,$sql);
	while($egysor = mysqli_fetch_array($eredmeny, MYSQL_BOTH)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"Name":"'  . $rs["images.id"] . '",';
    $outp .= '"City":"'   . $rs["images.name"]        . '",';
    $outp .= '"Country":"'. $rs["albums.name"]     . '"}'; 
}
$outp .="]";

$conn->close();

echo($outp);
}
?>