<?php

session_start();

if(isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/../database/dao.php";
    $sql = "select * from user
            where id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    $sqlTweet = "select * from tweets order by date desc";
    $tweetResult = $mysqli->query($sqlTweet);
    
    $sqlFollow = "select follow from follows where uid={$_SESSION["user_id"]}";
    $followResult = $mysqli->query($sqlFollow);
    $rowF = mysqli_fetch_array($followResult);
    if($rowF) {
        $followResult = $mysqli->query($sqlFollow);
        while($row = mysqli_fetch_array($followResult)) {
            $result_array[] = $row["follow"];
        }
    } else {
        $result_array = (array) null;
    }
 
}

function counter($sql) {
    $mysqli = require __DIR__ . "/../database/dao.php";
    $count = 0;
    $rtwt_result = $mysqli->query($sql);
    while($retweet = mysqli_fetch_array($rtwt_result)) {
        $count++;
    }
    if($count === 0) {
        $count = "";
    }
    return $count;
}

function checkSelected($uid, $sql) {
    $mysqli = require __DIR__ . "/../database/dao.php";
    $rtwt_result = $mysqli->query($sql);
    while($retweet = mysqli_fetch_array($rtwt_result)) {
        if($uid == $retweet["uid"]) {
            return True;
        }
    }
    return False;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">
    <link rel="stylesheet" href="/phpWeb/css/mainpage.css">
    <title>Twitter2.0</title>
</head>

<body>
    <?php if(isset($user)):?>
        <p>Hi <?=htmlspecialchars($user["name"]) ?></p>
        <a href="../logout/logout.php">Log out</a>
        <div>
            <form action="process_tweet.php" method="post">
                <h2>Tweet</h2>
                <textarea id="tweet" name="tweet" placeholder="Tweet..." required></textarea>
                <button id="tweetMe">Tweet me</button>
            </form>
        </div>
        <br />
        <div class="tab">
            <button class="tablinks" onclick="openTweets(event, 'allTweets')" id="defaultOpen">All Tweets</button>
            <button class="tablinks" onclick="openTweets(event, 'followedUsers')">Followed Users</button>
        </div>

        <div id="allTweets" class="tabcontent">
            <h2>All Tweets</h2>
            <hr />
            <?php  {
                
            } ?> 
            <?php 
                while($row = mysqli_fetch_array($tweetResult)) {
                    include '../common/tweetcard.php';
                }
            ?>
        </div>
        <div id="followedUsers" class="tabcontent">
            <h2>Followed Users</h2>
            <hr />
            <?php 
                $sqlTweet = "select * from tweets order by date desc";
                $tweetResult = $mysqli->query($sqlTweet);
                while($row = mysqli_fetch_array($tweetResult)) {?>
                    <?php
                        if(in_array($row["uid"], $result_array)) {
                            include '../common/tweetcard.php';
                        } ?>
            <?php } ?>
        </div>
    <?php 
        else: ?>
        <p><a href="../login/login.php">Log in</a> or <a href="../signup/signup.html">Sign up</a></p>
    
    <?php endif; ?>
</body>
<script>

    function openTweets(evt, tweetName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tweetName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

</script>
</html>