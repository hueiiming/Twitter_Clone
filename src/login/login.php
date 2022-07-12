<?php

$is_invalid = false;

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/../database/dao.php";
    
    $sql = sprintf("select * from user
            where email = '%s'",
            $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if($user) {
        if(password_verify($_POST["password"], $user["password_hash"])) {

            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: /phpWeb/src/mainpage/mainpage.php");
            exit;
        }
    }

    $is_invalid = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">


</head>
<body>
    <h1>Login</h1>

    <?php if($is_invalid): ?>
        <em style="color: red">Invalid login</em>
    <?php endif; ?>

    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <button>Log in</button>
    </form>
</body>
</html>