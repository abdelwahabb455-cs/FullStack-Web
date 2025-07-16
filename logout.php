<?php
session_start();
session_destroy();
header("Location: interface.php");
exit();
?>
