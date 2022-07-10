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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">

    <title>Twitter2.0</title>
<style>
    .tab button {
        color: #ccc;
    }
    .tab button.active {
        background-color: #ccc;
        color: #000;
        filter: brightness(110%);
        
    }

    button:hover {
        background-color: #ccc;
        color: #000;
        filter: brightness(110%);
        transition-duration: 0.4s;

    }
</style>
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
            <button class="tablinks" onclick="openCity(event, 'allTweets')" id="defaultOpen">All Tweets</button>
            <button class="tablinks" onclick="openCity(event, 'followedUsers')">Followed Users</button>
        </div>
        <div id="allTweets" class="tabcontent">
            <h2>All Tweets</h2>
            <hr />
            <?php  {
                
            } ?> 
            <?php 
                while($row = mysqli_fetch_array($tweetResult)) {
                    ?>
                <table>
                    <tr>
                        <td><?=htmlspecialchars($row["username"])?></td>
                        <td><?=$row["tweet"]?></td>
                        <td>(<?=date("Y-m-d", strtotime($row["date"]))?>) <?=date("h:i A", strtotime($row["date"])) ?></td>
                        <?php
                            if(in_array($row["uid"], $result_array)) { 
                            ?>
                                <td>Followed</td>
                        <?php 
                        
                            } else {
                                if($_SESSION["user_id"] != $row['uid']) { ?>
                                    <td><a href="process_follow.php?follow=<?=$row['uid']?>">Follow</a></td>
                        <?php   } else { ?>
                                    <td><a href="delete_tweet.php?tweet=<?=$row['id']?>">Delete tweet</a></td>
                        <?php
                                }
                             }
                        ?> 
                    </tr>   
                </table>
        <?php } ?>
        </div>
        <div id="followedUsers" class="tabcontent">
            <h2>Followed Users</h2>
            <hr />
            <?php 
                $sqlTweet = "select * from tweets order by date desc";
                $tweetResult = $mysqli->query($sqlTweet);
                while($row = mysqli_fetch_array($tweetResult)) {?>
                <table>
                    <tr>
                        <?php
                        if(in_array($row["uid"], $result_array)) { ?>
                            <td><?=htmlspecialchars($row["username"])?></td>
                            <td><?=$row["tweet"]?></td>
                            <td>(<?=date("Y-m-d", strtotime($row["date"]))?>) <?=date("h:i A", strtotime($row["date"])) ?></td>
                            <td>Followed</td>
                        <?php } ?>
                    </tr>   
                </table>
        <?php } ?>
        </div>
    <?php 
        else: ?>
        <p><a href="../login/login.php">Log in</a> or <a href="../signup/signup.html">Sign up</a></p>
    
    <?php endif; ?>
</body>
<script>

    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

    function deleteBtn(key) {
        mainArr.splice(key, 1);
        let newString = '';
        let count = 0;
        mainArr.forEach(function (currObj) {
            btn.innerHTML = "Delete tweet";
            btn.id = count;
            newString += currObj.date + ': ' + currObj.tweet +  '<br>' + '<button id="' + count + '" onclick="deleteBtn(\'' + count + '\')"> Delete tweet </button> <br>';
            count++;
        });
        document.getElementById("allTweets").innerHTML = newString;
        console.log(mainArr);

    }


</script>
</html>