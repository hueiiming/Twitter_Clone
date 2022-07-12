<?php
session_start();

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "delete from tweets where id = {$_GET["tweet"]}";

if ($mysqli->query($sql) === TRUE) {
    // header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
} 
