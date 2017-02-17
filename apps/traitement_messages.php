<?php
if (isset($_POST['content'], $_SESSION['id']))
{
	$content = $_POST['content'];
	if (strlen($content) > 4095)
	{
		$errors[] = "Contenu trop long (> 4095)";
	}
	else if (strlen($content) < 2)
	{
		$errors[] = "Contenu trop court (< 2)";
	}
	if (count($errors) == 0)
	{
		$content = mysqli_real_escape_string($db['chat_project'], $content);
		mysqli_autocommit($db['chat_project'], FALSE);
		mysqli_autocommit($db['chantage'], FALSE);
		mysqli_autocommit($db['Chat_coffee'], FALSE);
		mysqli_autocommit($db['chat-ouille'], FALSE);
		$res1 = mysqli_query($db['chat_project'], "INSERT INTO messages (id_author, content) VALUES('".$_SESSION['id'][0]."', '".$content."')");
		$res2 = mysqli_query($db['chantage'], "INSERT INTO messages (id_author, content) VALUES('".$_SESSION['id'][1]."', '".$content."')");
		$res3 = mysqli_query($db['Chat_coffee'], "INSERT INTO messages (id_author, content) VALUES('".$_SESSION['id'][2]."', '".$content."')");
		$res4 = mysqli_query($db['chat-ouille'], "INSERT INTO messages (id_author, content) VALUES('".$_SESSION['id'][3]."', '".$content."')");
		if ($res1 === true && $res2 === true && $res3 === true && $res4 === true)
		{
			mysqli_commit($db['chat_project']);
			mysqli_commit($db['chantage']);
			mysqli_commit($db['Chat_coffee']);
			mysqli_commit($db['chat-ouille']);
			header('Location: index.php?page=tchat');
			exit;
		}
		else
		{
			if ($res1 !== true)
				$errors[] = "chat_project > ".mysqli_error($db['chat_project']);
			if ($res2 !== true)
				$errors[] = "chantage > ".mysqli_error($db['chantage']);
			if ($res3 !== true)
				$errors[] = "Chat_coffee > ".mysqli_error($db['Chat_coffee']);
			if ($res4 !== true)
				$errors[] = "chat-ouille > ".mysqli_error($db['chat-ouille']);
			mysqli_rollback($db['chat_project']);
			mysqli_rollback($db['chantage']);
			mysqli_rollback($db['Chat_coffee']);
			mysqli_rollback($db['chat-ouille']);
			mysqli_autocommit($db['chat_project'], TRUE);
			mysqli_autocommit($db['chantage'], TRUE);
			mysqli_autocommit($db['Chat_coffee'], TRUE);
			mysqli_autocommit($db['chat-ouille'], TRUE);
		}
	}
}
?>