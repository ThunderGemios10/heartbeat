<?php
session_start();
unset($_SESSION["valid"]);
unset($_SESSION["state"]);
unset($_SESSION["token"]);
unset($_SESSION["userinfo"]);
unset($_SESSION["valid"]);
header("location: .");
?>