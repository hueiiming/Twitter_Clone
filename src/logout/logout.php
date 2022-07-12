<?php

session_start();
session_destroy();

header("Location: /phpWeb/src/mainpage/mainpage.php");
exit;
