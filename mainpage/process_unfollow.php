<?php
session_start();

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "delete from follows where uid = {$_SESSION["user_id"]} and follow = {$_GET["follow"]}";

if ($mysqli->query($sql) === TRUE) {
    header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
}