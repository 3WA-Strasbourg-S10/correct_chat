<?php
if (isset($_GET['page']) && $_GET['page'] == "logout")
{
	session_destroy();
	header('Location: index.php');
	exit;
}
if (isset($_POST['action']))
{
	$action = $_POST['action'];
	if ($action == "register")
	{
		if (isset($_POST['login'], $_POST['birthdate'], $_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['avatar']))
		{
			// login, password, email, birthdate, avatar
			$login = $_POST['login'];// 31
			$birthdate = $_POST['birthdate'];// DATE
			$email = $_POST['email'];// 127
			$password1 = $_POST['password1'];// 72
			$password2 = $_POST['password2'];// ~= $password1
			$avatar = $_POST['avatar'];// URL
			if (strlen($login) > 31)
			{
				$errors[] = "Login trop long (> 31)";
			}
			else if (strlen($login) < 2)
			{
				$errors[] = "Login trop court (< 2)";
			}
			if (strlen($password1) < 6)
			{
				$errors[] = "Password trop court (< 6)";
			}
			else if (strlen($password1) > 72)
			{
				$errors[] = "Password trop long (> 72)";
			}
			else if ($password1 != $password2)
			{
				$errors[] = "Les mots de passe ne correspondent pas";
			}
			if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
			{
				$errors[] = "Email invalide";
			}
			if (filter_var($avatar, FILTER_VALIDATE_URL) == false)
			{
				$errors[] = "Avatar invalide";
			}
			if (count($errors) == 0)
			{
				$login = mysqli_real_escape_string($db['chat_project'], $login);
				$email = mysqli_real_escape_string($db['chat_project'], $email);
				$birthdate = mysqli_real_escape_string($db['chat_project'], $birthdate);
				$avatar = mysqli_real_escape_string($db['chat_project'], $avatar);
				$hash = password_hash($password1, PASSWORD_BCRYPT, ["cost"=>12]);
				mysqli_autocommit($db['chat_project'], FALSE);
				mysqli_autocommit($db['chantage'], FALSE);
				mysqli_autocommit($db['Chat_coffee'], FALSE);
				mysqli_autocommit($db['chat-ouille'], FALSE);
				$res1 = mysqli_query($db['chat_project'], "INSERT INTO users (login, password, email, birthdate, avatar) VALUES('".$login."', '".$hash."', '".$email."', '".$birthdate."', '".$avatar."')");
				$res2 = mysqli_query($db['chantage'], "INSERT INTO users (login, password, email, avatar) VALUES('".$login."', '".$hash."', '".$email."', '".$avatar."')");
				$res3 = mysqli_query($db['Chat_coffee'], "INSERT INTO users (login, password, date, email, avatar) VALUES('".$login."', '".$hash."', '".$birthdate."', '".$email."', '".$avatar."')");
				$res4 = mysqli_query($db['chat-ouille'], "INSERT INTO users (login, password, email, birthdate, avatar) VALUES('".$login."', '".$hash."', '".$email."', '".$birthdate."', '".$avatar."')");
				if ($res1 === true && $res2 === true && $res3 === true && $res4 === true)
				{
					mysqli_commit($db['chat_project']);
					mysqli_commit($db['chantage']);
					mysqli_commit($db['Chat_coffee']);
					mysqli_commit($db['chat-ouille']);
					header('Location: index.php?page=login');
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
	}
	if ($action == "login")
	{
		if (isset($_POST['login'], $_POST['password']))
		{
			$login = $_POST['login'];
			$password = $_POST['password'];
			$login = mysqli_real_escape_string($db['chat_project'], $login);
			$res1 = mysqli_query($db['chat_project'], "SELECT * FROM users WHERE login='".$login."'");
			$res2 = mysqli_query($db['chantage'], "SELECT * FROM users WHERE login='".$login."'");
			$res3 = mysqli_query($db['Chat_coffee'], "SELECT * FROM users WHERE login='".$login."'");
			$res4 = mysqli_query($db['chat-ouille'], "SELECT * FROM users WHERE login='".$login."'");
			if ($res1 && $res2 && $res3 && $res4)
			{
				$user1 = mysqli_fetch_assoc($res1);
				$user2 = mysqli_fetch_assoc($res2);
				$user3 = mysqli_fetch_assoc($res3);
				$user4 = mysqli_fetch_assoc($res4);
				if ($user1 && $user2 && $user3 && $user4)
				{
					$verif1 = password_verify($password, $user1['password']);
					$verif2 = password_verify($password, $user2['password']);
					$verif3 = password_verify($password, $user3['password']);
					$verif4 = password_verify($password, $user4['password']);
					if ($verif1 && $verif2 && $verif3 && $verif4)
					{
						$_SESSION['id'] = [$user1['id'], $user2['id'], $user3['id'], $user4['id']];
						$_SESSION['login'] = $user1['login'];
						header('Location: index.php?page=tchat');
						exit;
					}
					else
					{
						if (!$verif1)
							$errors[] = "chat_project > Incorrect Password";
						if (!$verif2)
							$errors[] = "chantage > Incorrect Password";
						if (!$verif3)
							$errors[] = "Chat_coffee > Incorrect Password";
						if (!$verif4)
							$errors[] = "chat-ouille > Incorrect Password";
					}
				}
				else
				{
					if (!$user1)
						$errors[] = "chat_project > User not found";
					if (!$user2)
						$errors[] = "chantage > User not found";
					if (!$user3)
						$errors[] = "Chat_coffee > User not found";
					if (!$user4)
						$errors[] = "chat-ouille > User not found";
				}
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
			}
		}
	}
}
?>