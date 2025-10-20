<?php
session_start();
$unit = $_SESSION['unit'];
include "../../src/connection.php";
if($_SERVER['REQUEST_METHOD']=='GET'){
$case_id = $_GET['case_id'];
$division = $_GET['division'];


$sql = "Select * from files where unit = '$unit' and case_id = '$case_id' and division = '$division'";
$result = $conn->query($sql);
$data = array();
while($row = $result->fetch_assoc()){
$data[] = $row;
}
echo json_encode($data);}
?>