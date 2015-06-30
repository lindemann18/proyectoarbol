<?php
	session_start();
	if(isset($_SESSION))
	{
		session_destroy();	
	}
	
	echo '<meta http-equiv="Refresh" content="2;url=../login.php">';
?>