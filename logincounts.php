<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost","root","","csu_login");

$month = date("Y-m");
$today = date("Y-m-d");

$stmt = $conn->prepare("SELECT count, start_date FROM login_metrics WHERE month_year=?");
$stmt->bind_param("s",$month);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows==0){
    $stmt = $conn->prepare("INSERT INTO login_metrics VALUES(?,0,?)");
    $stmt->bind_param("ss",$month,$today);
    $stmt->execute();
    $count = 0;
    $start_date = $today;
}else{
    $row = $result->fetch_assoc();
    $count = (int)$row["count"];
    $start_date = $row["start_date"];
}

if(isset($_GET["action"]) && $_GET["action"]=="increment"){
    $count++;
    $stmt = $conn->prepare("UPDATE login_metrics SET count=? WHERE month_year=?");
    $stmt->bind_param("is",$count,$month);
    $stmt->execute();
}

$end_date = date("Y-m-t");

echo json_encode([
    "count"=>$count,
    "start_date"=>$start_date,
    "end_date"=>$end_date
]);