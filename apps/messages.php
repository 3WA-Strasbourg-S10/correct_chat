<?php
$list = [];
$res1 = mysqli_query($db['chat_project'], "SELECT messages.*, users.login AS author, users.avatar FROM messages LEFT JOIN users ON users.id=messages.id_author ORDER BY date DESC");
while ($msg1 = mysqli_fetch_assoc($res1))
	$list[] = $msg1;
$res2 = mysqli_query($db['chantage'], "SELECT messages.*, users.login AS author, users.avatar FROM messages LEFT JOIN users ON users.id=messages.id_author ORDER BY date DESC");
while ($msg2 = mysqli_fetch_assoc($res2))
	$list[] = $msg2;
$res3 = mysqli_query($db['Chat_coffee'], "SELECT messages.*, users.login AS author, users.avatar FROM messages LEFT JOIN users ON users.id=messages.id_author ORDER BY date DESC");
while ($msg3 = mysqli_fetch_assoc($res3))
	$list[] = $msg3;
$res4 = mysqli_query($db['chat-ouille'], "SELECT messages.*, users.login AS author, users.avatar FROM messages LEFT JOIN users ON users.id=messages.id_author ORDER BY date DESC");
while ($msg4 = mysqli_fetch_assoc($res4))
	$list[] = $msg4;
usort($list, function($a, $b)
{
	return $a['date'] < $b['date'];
});
$list = array_filter($list, function($msg)
{
    static $duplicates = [];
    if(in_array($msg['content'].'|'.$msg['author'], $duplicates))
        return false;
    $duplicates[] = $msg['content'].'|'.$msg['author'];
    return true;
});
$list = array_values($list);
$i = 0;
while ($i < count($list))
{
	$message = $list[$i];
	if (filter_var($message['avatar'], FILTER_VALIDATE_URL) == false)
		$message['avatar'] = 'https://ssl.gstatic.com/accounts/ui/avatar_2x.png';
	require('views/message.phtml');
	$i++;
}
?>