<?php
$errors = [];
$db = [
	"chat_project"=>mysqli_connect("192.168.1.10","chat","chat","chat"),
	"chantage"=>mysqli_connect("192.168.1.62","chantage","chantage","chantage"),
	"Chat_coffee"=>mysqli_connect("192.168.1.52", "tchat", "tchat", "tchat"),
	"chat-ouille"=>mysqli_connect("192.168.1.79","chat-ouille","chat-ouille","chat-ouille")
];
session_start();
$access = ["login", "register", "tchat", "messages"];
$page = "login";
if (isset($_GET['page']) && in_array($_GET['page'], $access))
{
    $page = $_GET['page'];
}
require('apps/traitement_users.php');
require('apps/traitement_messages.php');
if (isset($_GET['ajax']))
	require('apps/'.$page.'.php');
else
	require('apps/skel.php');
?>