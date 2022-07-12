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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/phpWeb/static/css/mainpage-sidebar.css">
    <link rel="stylesheet" href="/phpWeb/static/css/mainpage.css">
    <title>Twitter2.0</title>
</head>
<script>
$(document).ready(function() {
    $("#tweetMe").click(function() {
        if($('#tweet').val() != '') {
            if(confirm('Confirm Tweet?')) {
                var url = "process_tweet.php"; 
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#tweetForm").serialize(), 
                    success: function(data) {
                        $('body').load('mainpage.php'); 
                    }
                });
            }
            return false;
        }
    });
});
</script>
<body>
    <?php if(isset($user)):?>
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <div class="content">
                        <div class="sidebar">
                            <div class="sidebar__brand">
                                <i class="fa fa-twitter"></i>
                            </div>
                            <a id="homeBtn" href="" class="sidebar__item active">
                                <i class="sidebar__item__icon fa fa-home"></i>
                                <span class="sidebar__item__text">Home</span>
                            </a>
                            <a href="#" class="sidebar__item">
                                <i class="sidebar__item__icon fa fa-compass"></i>
                                <span class="sidebar__item__text">Explore</span>
                            </a>
                            <a href="#" class="sidebar__item">
                                <i class="sidebar__item__icon fa fa-bell"></i>
                                <span class="sidebar__item__text">Notifications</span>
                            </a>
                            <a href="#" class="sidebar__item">
                                <i class="sidebar__item__icon fa fa-envelope"></i>
                                <span class="sidebar__item__text">Messages</span>
                            </a>
                            <a href="#" class="sidebar__item">
                                <i class="sidebar__item__icon fa fa-bookmark"></i>
                                <span class="sidebar__item__text">Bookmarks</span>
                            </a>
                            <a href="#" class="sidebar__item">
                                <i class="sidebar__item__icon fa fa-list-alt"></i>
                                <span class="sidebar__item__text">Lists</span>
                            </a>
                        </div>
                    </div>  
                </div>
                <div class="col-sm-8">
                    <form id="tweetForm">
                        <h2>Tweet</h2>
                        <textarea id="tweet" name="tweet" placeholder="Tweet..." required></textarea>
                        <button id="tweetMe">Tweet me</button>
                    </form>
                    <br />
                    <div class="tab">
                        <button class="tablinks" onclick="openTweets(event, 'allTweets')" id="defaultOpen">All Tweets</button>
                        <button class="tablinks" onclick="openTweets(event, 'followedUsers')" id="followedOpen">Followed Users</button>
                    </div>

                    <div id="allTweets" class="tabcontent">
                        <h2>All Tweets</h2>
                        <hr />
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
                                        include '../common/tweetcard_followed.php';
                                    } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="profile">
                        <p>Hi <?=htmlspecialchars($user["name"]) ?></p>
                        <a href="../logout/logout.php">Log out</a>
                    </div>
                </div>
            </div>
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