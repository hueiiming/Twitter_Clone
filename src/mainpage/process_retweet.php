<?php
session_start();

$mysqli = require __DIR__ . "/../database/dao.php";

date_default_timezone_set('Asia/Singapore');
$dateTs = date('Y-m-d H:i:s', time());

$sql = "insert into retweets (twt_id, uid, date) 
        values (?, ?, ?)";
    
$stmt = $mysqli->prepare($sql);
$result = $stmt->execute([$_GET["tweet"], $_SESSION["user_id"], $dateTs]);

if($result) {
    // header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
} 