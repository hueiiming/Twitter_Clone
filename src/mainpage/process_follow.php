<?php
session_start();

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "insert into follows (uid, follow) 
        values (?, ?)";
        
$stmt = $mysqli->prepare($sql);
$result = $stmt->execute([$_SESSION["user_id"], $_GET["follow"]]);

if($result) {
    // header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
} 