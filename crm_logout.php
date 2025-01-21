<?php
session_start();
session_destroy();
header("Location: crm_login.php");
exit();
?>