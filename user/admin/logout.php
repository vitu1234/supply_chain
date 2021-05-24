<?php
session_start();
unset($_SESSION['user']);
	# code...
	header("Location:index.php");

?>