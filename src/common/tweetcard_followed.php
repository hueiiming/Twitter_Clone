<script>
    function updateFollowedTweetFunction(id, url) {
        jQuery.ajax({
            url: url + id, //link to your php
            method: 'POST', 
            data: $(id).serialize() 
        }).done(function (response) { 
            $('body').load('mainpage.php');
        });
    }
</script>
<div class="tweet-wrap">
    <div class="tweet-header">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="" class="avator">
        <div class="tweet-header-info">
            <?=htmlspecialchars($row["username"])?> <span>@<?=htmlspecialchars($row["username"])?></span><span>. (<?=date("Y-m-d", strtotime($row["date"]))?>) <?=date("h:i A", strtotime($row["date"])) ?>
            <?php if(in_array($row["uid"], $result_array)) { ?>
                <span class="followStatus"><a onclick="if(confirm('Unfollow <?=htmlspecialchars($row['username'])?>?')) updateFollowedTweetFunction(<?=$row['uid']?>, '../mainpage/process_unfollow.php?follow='); return false;" href="">Unfollow</a></span>
            <?php } else if ($_SESSION["user_id"] == $row["uid"]) { ?>
                <span class="followStatus"><a onclick="if(confirm('Delete Tweet?')) updateFollowedTweetFunction(<?=$row['id']?>, '../mainpage/delete_tweet.php?tweet='); return false;" href="">Delete tweet</a></span>
            <?php } ?>
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
                    <a onclick="if(confirm('Undo Retweet?')) updateFollowedTweetFunction(<?=$row['id']?>, '../mainpage/process_unretweet.php?tweet='); return false;" href="">
                        <svg style="color: green;" class="feather feather-repeat sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                    </a>
                    <?php } else {?>
                        <a onclick="if(confirm('Retweet?')) updateFollowedTweetFunction(<?=$row['id']?>, '../mainpage/process_retweet.php?tweet='); return false;" href="">
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
                <a onclick="updateFollowedTweetFunction(<?=$row['id']?>, '../mainpage/process_unlike.php?tweet='); return false;" href="">
                <svg style="fill: red; color: red;" class="feather feather-heart sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </a>
            <?php } else { ?>
                <a onclick="updateFollowedTweetFunction(<?=$row['id']?>, '../mainpage/process_like.php?tweet='); return false;" href="">
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