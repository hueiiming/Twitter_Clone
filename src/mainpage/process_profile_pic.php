<?php
session_start();

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "update user set profile_img = ? where id = {$_SESSION['user_id']}";
// $result = mysqli_query($mysqli, $sql);
$img = file_get_contents($_FILES['profile_pic']['tmp_name']);
$stmt = $mysqli->prepare($sql);
$result = $stmt->execute([$img]);

if($result) {
    header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
} 