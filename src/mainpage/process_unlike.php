<?php
session_start();

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "delete from likes where twt_id = {$_GET["tweet"]} and uid = {$_SESSION["user_id"]}";

if ($mysqli->query($sql) === TRUE) {
    // header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
}