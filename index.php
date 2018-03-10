<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 13.02.2018
 * Time: 19:32
 */
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

header( 'Content-type: text/html;charset=utf-8' );
session_start();

require_once 'Vkontakte.php';
require_once 'functions.php';

global $vk;

use \BW\Vkontakte as Vk;

$vk_bot_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$blank = 'https://oauth.vk.com/blank.html';

echo $vk_bot_uri;

$vk = new Vk( array(
	'client_id'     => '6371627',
	'client_secret' => 'MqzhFe8NiHajNKrk07LV',
	'scope'         => array( 'groups,offline,wall' ),
	'redirect_uri'  => $blank,//$vk_bot_uri,
) );

// если вернулся ответ в виде запроса с параметро code
if ( isset( $_GET['code'] ) ) {

	// производится авторизация
	$vk->authenticate();

	// в глобальную переменную access_token записывается полученный приложением токен
	$_SESSION['access_token'] = $vk->getAccessToken();

	// происходит перенаправление на основную страницу
	header( 'location: ' . $vk_bot_uri );
	die();
} else {

	// если токен хранится в сессии
	if ( ! empty( $_SESSION['access_token'] ) ) {

		// токен устанавливается для доступа к API
		$vk->setAccessToken( $_SESSION['access_token'] );

		// запуск функции init
		init();
	} else {
		//Код, полученный через blank.html
		if(isset($_GET['code'])) {
			$_SESSION['code'] = $_GET['code'];
			echo '<br>Код доступа '.$_GET['code'];
		}
		// если авторизация не пройдена - выводится url  и ссылка для авторизации
		?>
        <p>Доверенный redirect URI: <code><?php echo $vk_bot_uri; ?></code></p>
		<form class="authorize-vk authorize" method="post" action="<?php echo $vk->getLoginUrl(); ?>">
			<h3>Авторизация пользователя на vk.com</h3>
			<!--<div>
				<label for="for_send">Для отправки сообщенией</label>
				<input type="checkbox" id="for_send" name="for_send" value="1" checked>
			</div>-->
			<div><input type="submit" value="Войти"></div>
		</form>
		<form class="authorize" method="get">
			<div>Если получен код для оправки сообщений</div>
			<div><input type="text" name="code" placeholder="Введите code"></div>
			<div><input type="submit"></div>
		</form>
		<?php
	}
}
