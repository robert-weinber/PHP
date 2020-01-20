<?php
require_once 'vendor/autoload.php';

use WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;

// Create blob REST proxy.
$connectionString = "DefaultEndpointsProtocol=http;AccountName=contentblobs;AccountKey='IW6a5jRSF8ORoWvJbDYSNKnQ3ymiLO3uKADHLlurG7E9sryOF49I7vV2PhigSTuYvSMFLTjgK9J02bai8H4JXw=='";
$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);





if (isset($_POST['file'])) {
	//$extensions = array("pdf", "zip", "doc", "xls", "rar", "exe", "ppt", "docx");
$file = $_POST['file'];

$filepath = "https://contentblobs.blob.core.windows.net/downloads/".$file;
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$fileName = $_POST['fileName'].".".$ext;

try    {
    // Get blob.
    $blob = $blobRestProxy->getBlob("downloads", $file);
	header('Content-Type: application/'.$ext);
header("Content-Disposition: attachment; filename=\"$fileName\"");
    fpassthru($blob->getContentStream());

}
catch(ServiceException $e){
    // Handle exception based on error codes and messages.
    // Error codes and messages are here:
    // http://msdn.microsoft.com/library/azure/dd179439.aspx
header('Location: error.php');
	header("HTTP/1.0 404 Not Found");
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}

//echo "exsists ".file_exists($filepath);
//echo "readable ".is_readable($filepath); 
//echo "preq ".in_array($ext, $extensions);
//preg_match('/\.pdf$/',$file)
/*
if (file_exists($filepath) && is_readable($filepath)) {
header('Content-Type: application/'.$ext);
header("Content-Disposition: attachment; filename=\"$fileName\"");
readfile($filepath);
}else{
header('Location: error.php');
}
} else {
header("HTTP/1.0 404 Not Found");*/
}
?>

