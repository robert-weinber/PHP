<?php
include_once 'psl-config_admin.php';   // As functions.php is not included
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);
mysqli_set_charset($mysqli,"utf8");
