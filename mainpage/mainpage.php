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
    <link rel="stylesheet" href="../css/mainpage.css">
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
                    ?>
                    <div class="tweet-wrap">
                        <div class="tweet-header">
                            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="" class="avator">
                            <div class="tweet-header-info">
                            <?=htmlspecialchars($row["username"])?> <span>@<?=htmlspecialchars($row["username"])?></span><span>. (<?=date("Y-m-d", strtotime($row["date"]))?>) <?=date("h:i A", strtotime($row["date"])) ?>
                            <?php
                                if(in_array($row["uid"], $result_array)) { 
                                ?>
                                    <span class="followStatus">Followed</span>
                            <?php 
                            
                                } else {
                                    if($_SESSION["user_id"] != $row['uid']) { ?>
                                        <span class="followStatus"><a href="process_follow.php?follow=<?=$row['uid']?>">Follow</a></span>
                            <?php   } else { ?>
                                        <span class="followStatus"><a href="delete_tweet.php?tweet=<?=$row['id']?>">Delete tweet</a></span>
                            <?php
                                    }
                                }
                            ?> 
                        </span>
                            <p><?=$row["tweet"]?></p>
                            
                            </div>
                            
                        </div>
                        <div class="tweet-info-counts">
                            <div class="comments">
                                <svg class="feather feather-message-circle sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                <div class="comment-count">33</div>
                                </div>
                                
                                <div class="retweets">
                                    <?php if(checkSelected($_SESSION["user_id"], "select * from retweets where twt_id = {$row['id']}")) {?>
                                        <a href="process_unretweet.php?tweet=<?=$row['id']?>">
                                            <svg style="color: green;" class="feather feather-repeat sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                        </a>
                                        <?php } else {?>
                                            <a href="process_retweet.php?tweet=<?=$row['id']?>">
                                                <svg class="feather feather-repeat sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                            </a>
                                    <?php }?>
                                    <div class="retweet-count">
                                        <?php
                                            echo counter("select * from retweets where twt_id = {$row["id"]}");
                                        ?>
                                    </div>
                                </div>
                            
                            <div class="likes">
                                <?php if(checkSelected($_SESSION["user_id"], "select * from likes where twt_id = {$row['id']}")) { ?>
                                <a href="process_unlike.php?tweet=<?=$row['id']?>">
                                    <svg style="fill: red; color: red;" class="feather feather-heart sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </a>
                                <?php } else { ?>
                                    <a href="process_like.php?tweet=<?=$row['id']?>">
                                        <svg class="feather feather-heart sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    </a>
                                <?php } ?>
                                <div class="likes-count">
                                <?php
                                    echo counter("select * from likes where twt_id = {$row['id']}");
                                ?>
                                </div>
                            </div>
                            
                            <div class="message">
                            <svg class="feather feather-send sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </div>
                        </div>
                    </div>
        <?php } ?>
        </div>
        <div id="followedUsers" class="tabcontent">
            <h2>Followed Users</h2>
            <hr />
            <?php 
                $sqlTweet = "select * from tweets order by date desc";
                $tweetResult = $mysqli->query($sqlTweet);
                while($row = mysqli_fetch_array($tweetResult)) {?>
                    <?php
                        if(in_array($row["uid"], $result_array)) { ?>
                            <div class="tweet-wrap">
                            <div class="tweet-header">
                                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="" class="avator">
                                <div class="tweet-header-info">
                                <?=htmlspecialchars($row["username"])?> <span>@<?=htmlspecialchars($row["username"])?></span><span>. (<?=date("Y-m-d", strtotime($row["date"]))?>) <?=date("h:i A", strtotime($row["date"])) ?>
                                <span class="followStatus">Followed</span>

                                </span>
                                <p><?=$row["tweet"]?></p>
                                
                                </div>
                                
                            </div>
                            <div class="tweet-info-counts">
                            <div class="comments">
                                <svg class="feather feather-message-circle sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                <div class="comment-count">33</div>
                                </div>
                                
                                <div class="retweets">
                                    <?php if(checkSelected($_SESSION["user_id"], "select * from retweets where twt_id = {$row['id']}")) {?>
                                        <a href="process_unretweet.php?tweet=<?=$row['id']?>">
                                            <svg style="color: green;" class="feather feather-repeat sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                        </a>
                                        <?php } else {?>
                                            <a href="process_retweet.php?tweet=<?=$row['id']?>">
                                                <svg class="feather feather-repeat sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                            </a>
                                    <?php }?>
                                    <div class="retweet-count">
                                        <?php
                                            echo counter("select * from retweets where twt_id = {$row["id"]}");
                                        ?>
                                    </div>
                                </div>
                            
                            <div class="likes">
                                <?php if(checkSelected($_SESSION["user_id"], "select * from likes where twt_id = {$row['id']}")) { ?>
                                <a href="process_unlike.php?tweet=<?=$row['id']?>">
                                    <svg style="fill: red; color: red;" class="feather feather-heart sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </a>
                                <?php } else { ?>
                                    <a href="process_like.php?tweet=<?=$row['id']?>">
                                        <svg class="feather feather-heart sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    </a>
                                <?php } ?>
                                <div class="likes-count">
                                <?php
                                    echo counter("select * from likes where twt_id = {$row['id']}");
                                ?>
                                </div>
                            </div>
                            
                            <div class="message">
                            <svg class="feather feather-send sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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