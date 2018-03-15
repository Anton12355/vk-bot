<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<script src="jquery-3.3.1.min.js"></script>
	<script src="functions.js"></script>
</head>
<body>
<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/vk-bot/vk-bot?action=get_users&user_ids=17439143,763899,1">Users</a>
<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/vk-bot/vk-bot?user_ids=763897&action=get_friends&page=2">Friends</a>
<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/vk-bot/vk-bot?user_ids=763897&action=get_wall">Wall</a>
<h3>Отправка сообщений на стену</h3>
<form class="wall_post" action="?action=do_post" method="post">
	<div>
		<label for="owner_id">ID получателя</label><br>
		<input type="text" id="owner_id" name="owner_id">
	</div>
	<div class="type_of_recipient">
		<label for="type_of_recipient">Тип получателя</label>
		<p><input type="radio" name="type_of_recipient" value="user" id="type_of_recipient" checked>Пользователь</p>
		<p><input type="radio" name="type_of_recipient" value="group">Группа</p>
	</div>
	<div>
		<label for="message">Текст сообщения</label><br>
		<textarea name="message" id="message" cols="45" rows="10"></textarea>
	</div>
	<div>
		<label for="attachment">Идентификатор вложения</label><br>
		<input type="text" id="attachment" name="attachment">
	</div>
	<div>
		<label for="from_group">От лица группы</label>
		<input type="checkbox" id="from_group" name="from_group" value="1">
	</div>
	<input type="submit" name="submit" value="Отправить">
</form>
<form class="wall_repost" action="?action=do_repost" method="post">
	<div>
		<label for="group_id">ID группы, где будет размещена запись</label><br>
		<input type="text" id="group_id" name="group_id">
	</div>
	<div class="type_of_recipient_of_repost">
		<label for="type_of_recipient_of_repost">Тип получателя</label>
		<p><input type="radio" name="type_of_recipient_of_repost" value="user" id="type_of_recipient_of_repost">Текущий пользователь</p>
		<p><input type="radio" name="type_of_recipient_of_repost" value="group" checked>Группа</p>
	</div>
	<div>
		<label for="message_of_repost">Сопроводительный текст</label><br>
		<textarea name="message_of_repost" id="message_of_repost" cols="45" rows="10"></textarea>
	</div>
	<div>
		<label for="object">Идентификатор объекта</label><br>
		<input type="text" id="object" name="object">
	</div>
	<div>
		<label for="mark_as_ads">Пометить как рекламную запись</label>
		<input type="checkbox" id="mark_as_ads" name="mark_as_ads" value="1">
	</div>
	<input type="submit" name="submit" value="Отправить">
</form>
</body>
</html>
<?php
echo print_r( $_SESSION['access_token'] );
echo '<br>' . date( 'H:i:s, d.m.Y', $_SESSION['access_token']['created'] );
?>