<?php
$dbhost='localhost';
$dbuser='root';
$dbpass='';
$db='dental';
$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$db);
if(!$conn){
    echo "Error connecting to database: ";
}
?>