<?php

if(empty($_POST["name"])) {
    die("Name is required");
}

if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if(strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if(!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if(!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if($_POST["password"] !== $_POST["password_cfm"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

date_default_timezone_set('Asia/Singapore');
$dateTs = date('Y-m-d H:i:s', time());

$mysqli = require __DIR__ . "/../database/dao.php";

$sql = "insert into user (name, email, password_hash, date, following, followers) 
        values (?, ?, ?, ?, ?, ?)";
        
$stmt = $mysqli->prepare($sql);
$result = $stmt->execute([$_POST["name"],
                        $_POST["email"],
                        $password_hash,
                        $dateTs,
                        "",
                        ""]);
if($result) {
    header("Location: /phpWeb/signup/signup_success.html");
    exit;
} else {
    die($mysqli->error . " " . $mysqli->errno);
} 
