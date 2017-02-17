<?php
if (isset($_SESSION['id']))
{
	if (isset($_SESSION['admin']) && $_SESSION['admin'] == true)
	{
		require('views/header_admin.phtml');
	}
	else
	{
		require('views/header_in.phtml');
	}
}
else
{
	require('views/header.phtml');
}
// header : news/home, login, register
// header_in : news/home, logout
// header_admin : news/home, logout, create article
?>