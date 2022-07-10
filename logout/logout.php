<?php

session_start();
session_destroy();

header("Location: /phpWeb/mainpage/mainpage.php");
exit;
