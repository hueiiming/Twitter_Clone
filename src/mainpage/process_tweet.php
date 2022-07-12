<?php
session_start();

date_default_timezone_set('Asia/Singapore');
$dateTs = date('Y-m-d H:i:s', time());

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "insert into tweets (uid, username, tweet, date, retweet, likes)
        values (?,?,?,?,?,?)";


$result = $mysqli->query("select name from user where id = {$_SESSION["user_id"]}");
$username = $result->fetch_assoc();

$stmt = $mysqli->prepare($sql);
$result = $stmt->execute([$_SESSION["user_id"],
                        $username["name"],
                        $_POST["tweet"],
                        $dateTs,
                        "",
                        ""]);

if($result) {
    // header("Location: mainpage.php");
} else {
    die($mysqli->error . " " . $mysqli->errno);
} 