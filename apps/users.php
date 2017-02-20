<?php
$list = [];
$res1 = mysqli_query($db['chat_project'], "SELECT *,status AS last FROM users WHERE status+10>CURRENT_TIMESTAMP ORDER BY login");
while ($user1 = mysqli_fetch_assoc($res1))
	$list[] = $user1;
$res2 = mysqli_query($db['chantage'], "SELECT * FROM users WHERE last+10>CURRENT_TIMESTAMP ORDER BY login");
while ($user2 = mysqli_fetch_assoc($res2))
	$list[] = $user2;
$res3 = mysqli_query($db['Chat_coffee'], "SELECT * FROM users WHERE last+10>CURRENT_TIMESTAMP ORDER BY login");
while ($user3 = mysqli_fetch_assoc($res3))
	$list[] = $user3;
$res4 = mysqli_query($db['chat-ouille'], "SELECT *,last_act AS last FROM users WHERE last_act+10>CURRENT_TIMESTAMP ORDER BY login");
while ($user4 = mysqli_fetch_assoc($res4))
	$list[] = $user4;
usort($list, function($a, $b)
{
	return $a['login'] > $b['login'];
});
$list = array_filter($list, function($msg)
{
    static $duplicates = [];
    if(in_array($msg['login'], $duplicates))
        return false;
    $duplicates[] = $msg['login'];
    return true;
});
$list = array_values($list);
$i = 0;
while ($i < count($list))
{
	$user = $list[$i];
	if (filter_var($user['avatar'], FILTER_VALIDATE_URL) == false)
		$user['avatar'] = 'https://ssl.gstatic.com/accounts/ui/avatar_2x.png';
	require('views/user.phtml');
	$i++;
}
?>