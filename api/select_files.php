<?php
include '../src/connection.php';
if($_SERVER['REQUEST_METHOD']=='GET'){
$area = $_GET['area'];
$sql = "Select * from files where area = '$area'";
$result = $mysqli->query($sql);
$data = array();
while($row = $result->fetch_assoc()){
$data[] = $row;
}
echo json_encode($data);}
?>