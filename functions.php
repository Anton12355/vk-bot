<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 13.02.2018
 * Time: 20:35
 */

function init() {
	styles();
	// проверка - передан ли атрибут строки action
	if ( ! empty( $_GET['action'] ) ) {

		// определение переменной
		$action = $_GET['action'];

		// если функция, имя которой соответствует значению переменной $action существует
		if ( function_exists( $action ) ) {

			// происходит вывозов указанной функции
			$action();
		}
	} else {
		my_func();
	}
}

function my_func() {
	include 'main.php';
}

function get_users( $user_ids = null ) {
	global $vk;
	if ( ! empty( $_REQUEST['user_ids'] ) ) {
		$user_ids = $_REQUEST['user_ids'];
		$users    = $vk->api( 'users.get', array(
			'user_ids' => $user_ids,
			'fields'   => array(
				'photo_max',
				'photo',
				'city',
				'sex',
			),
		) );
		echo '<pre>';
		print_r( $users );
		echo '</pre>';

		template_users( $users );
	} else {
		echo 'Укажите значение атрибута <code>user_ids</code>';
	}
}

function template_users( $users ) {
	if ( ! empty( $users ) ) {
		$out = '';
		if ( is_array( $users ) ) {
			foreach ( $users as $user ) {
				$out .= '<div class="users__item" data-sex="' . $user['sex'] . '">' . template_user( $user ) . '</div>';
			}
		}

		$out = '<div class="users">' . $out . '</div>';

		echo $out;
	}
}

function template_user( $user ) {
	$link = '';
	if ( ! empty( $user['id'] ) ) {
		$link = '//vk.com/id' . $user['id'];
	}
	/*echo '<pre>';
	print_r( $user );
	echo '</pre>';*/

	$keys = array(
		'first_name',
		'last_name',
		'photo_max',
		'city',
	);

	if ( is_array( $user ) ) {
		foreach ( $keys as $key ) {
			if ( empty( $user[ $key ] ) ) {
				$user[ $key ] = '';
			}
		}
	}
	if ( ! empty( $user['city']['title'] ) ) {
		$city = $user['city']['title'];
	} else {
		$city = '';
	}
	$out = '<a target="_blank" href="' . $link . '" class="user" style="background-image: url(' . $user['photo_max'] . ');">' .
	       '<div class="user__name">' . $user['first_name'] . ' ' . $user['last_name'] . '</div>' .
	       '<div class="user__city">' . $city . '</div>' .
	       '</a>';

	return $out;
}

function get_friends( $user_ids = null ) {
	global $vk;
	$action = 'get_friends';

	if ( ! empty( $_REQUEST['user_ids'] ) ) {
		$limit = 20;
		if ( empty( $_GET['page'] ) ) {
			$page = 0;
		} else {
			$page = $_GET['page'];
		}
		$user_ids = $_REQUEST['user_ids'];
		$users    = $vk->api( 'friends.get', array(
			'user_id' => $user_ids,
			'offset'  => $limit * $page,
			'count'   => $limit,
			'fields'  => array(
				'photo_max',
				'photo',
				'city',
				'sex',
			),
		) );

		/*
		// создаем новый, вспомогательный, массив
		$new_users = [];

		// перебираем список полученных пользователей
		foreach ( $users['items'] as $user ) {

			// если текущий пользователь - женщина
			if ( $user['sex'] == 1 ) {

				// добавляем пользователя в новый список
				$new_users[] = $user;
			}
		}

		// заменяем старый список на вновь сформированный
		$users['items'] = $new_users;
		//$users['сщгте'] = $new_users;

		/*echo '<pre>';
		print_r( $users['items'] );
		echo '</pre>';*/

		get_city( $users );
		get_sex( $users );


		echo pagination( $users['count'], $limit, $action, $user_ids );
		template_users( $users['items'] );


	} else {
		echo 'Укажите значение атрибута <code>user_ids</code>';
	}
}

function get_sex( $users, $sex = 1 ) {
	?>
	<form class="choose-sex">
		<p>Выберите пол друзей</p>
		<p><input name="sex" type="radio" value="1">Женщина</p>
		<p><input name="sex" type="radio" value="2">Мужчина</p>
		<p><input name="sex" type="radio" value="0">Любой</p>
	</form>
	<div class="sex-radio">
		<?php
		// перебираем список полученных пользователей
		foreach ( $users['items'] as $user ) {

			if ( $user['sex'] == $sex ) {
				?>
				<div class="sex-radio__item" data-sex="1">Привет, <?php echo $user['first_name']; ?></div>
				<?php
			} else {
				?>
				<div class="sex-radio__item" data-sex="2">Привет, <?php echo $user['first_name']; ?></div>
				<?php
			}
		}
		?>
	</div>
	<?php

	/*echo '<pre>';
	print_r( $users['items'] );
	echo '</pre>';*/

	return $users;
}

function get_city( $users ) {
	/*echo '<pre>';
	//print_r(array_count_values($users));
	echo '</pre>';
	$result = array_reduce($users['items']['city'],'array_merge',array());
	echo 'Результат ';
	print_r($result);*/
	//foreach ( $users['items'] as $user ) {
	//$result=
	//echo '<pre>';
	//echo $result;*///print_r( $user['city'] );
	//echo '</pre>';
	//}
}

function pagination( $count, $limit, $action, $user_id ) {

	$pages = ceil( $count / $limit );

	$out = '';

	for ( $i = 0; $i < $pages; $i ++ ) {
		$out .= '<a class="pages__item" href="?user_ids=' . $user_id . '&action=' . $action . '&page=' . $i . '">' . ( $i + 1 ) . '</a>';
	}
	$out = '<div class="pages">' . $out . '</div>';

	return $out;
}


function styles() {
	?>
	<style>
		div:before, div:after {
			clear: both;
			content: '';
			display: block;
		}

		.pages {
		}

		.pages__item {
			display: block;
			float: left;
			padding: 20px;
		}

		.pages__item:hover {
			background: #eee;
		}

		.users {
		}

		.users__item {
			float: left;
		}

		.user {
			display: block;
			width: 300px;
			height: 300px;
			background: url() 50% 50% / cover no-repeat;
			position: relative;
			margin: 10px;
		}

		.user__city {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			text-align: center;
			color: #fff;
		}

		.user__name {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100%;
			text-align: center;
			color: #fff;
		}

		img {
			max-width: 100%;
			height: auto;
		}

		.post {
			width: 500px;
			border: 1px solid #eee;
			float: left;
			margin: 0 10px 10px 0;
		}

		.authorize-vk {
			background-color: #edeef0;
			width: 400px;
		}

		.authorize {
			margin-bottom: 15px;
		}

		.authorize h3 {
			margin: 0;
		}

		.authorize div {
			margin-top: 8px;
		}

		.wall_post, .wall_repost {
			border: 1px solid #000;
			padding: 10px;
			width: 400px;
		}

		.wall_post div, .wall_repost div {
			margin-bottom: 10px;
		}

		.type_of_recipient p, .type_of_recipient_of_repost p {
			margin: 0;
			margin-top: 8px;
		}

	</style>
	<?php
}


// http://wordpress/vk-bot/index.php?action=get_users&user_ids=17439143,763899,1


function post_content( $atts ) {
	global $vk;

	$atts = parse_args( $atts, array(
		'owner_id' => '',
		//'from_group' => 1,
		'message'  => '',
		//'attachment' => '',
	) );
	echo '<pre>';
	print_r( $atts );
	echo '</pre>';

	$response = $vk->api( 'wall.post', $atts );

	print_r( $response );
}

function do_post() {
	if ( $_POST['type_of_recipient'] === 'group' ) {
		$owner_id = '-' . $_POST['owner_id'];
	} else {
		$owner_id = $_POST['owner_id'];
	}
	if ( isset( $_POST['from_group'] ) ) {
		$flag = $_POST['from_group'];
	} else {
		$flag = 0;
	}
	if ( isset( $_POST['message'] ) ) {
		$message = $_POST['message'];
	} else {
		$message = '';
	}
	if ( isset( $_POST['attachment'] ) ) {
		$attachment = $_POST['attachment'];
	} else {
		$attachment = '';
	}

	post_content( array(
		'owner_id'   => $owner_id,
		'from_group' => $flag,
		'message'    => $message,
		'attachment' => $attachment,
		//	'attachment' => 'https://citaty.info/files/characters/14334.png',

	) );
}

function repost_content( $atts ) {
	global $vk;

	$atts = parse_args( $atts, array(
		'object'      => '',
		'message'     => '',
		'group_id'    => '',
		'mark_as_ads' => '',
	) );
	echo '<pre>';
	print_r( $atts );
	echo '</pre>';

	$response = $vk->api( 'wall.repost', $atts );

	print_r( $response );
}

function do_repost() {
	if ( $_POST['type_of_recipient_of_repost'] === 'group' ) {
		$group_id = $_POST['group_id'];
	} else {
		$group_id = '';
	}
	if ( isset( $_POST['mark_as_ads'] ) ) {
		$ads = $_POST['mark_as_ads'];
	} else {
		$ads = 0;
	}
	if ( isset( $_POST['message_of_repost'] ) ) {
		$message = $_POST['message_of_repost'];
	} else {
		$message = '';
	}
	if ( isset( $_POST['object'] ) ) {
		$object = $_POST['object'];
	} else {
		$object = '';
	}

	repost_content( array(
		'object'      => $object,
		'message'     => $message,
		'group_id'    => $group_id,
		'mark_as_ads' => $ads,
	) );
}

/**
 * Функция парсинга массива, со вставкой дефолтных значений в пустые элементы
 *
 * @param $atts
 * @param $defaults
 */
function parse_args( $atts, $defaults ) {
	foreach ( $defaults as $key => $value ) {
		if ( empty( $atts[ $key ] ) ) {
			$atts[ $key ] = $defaults[ $key ];
		}
	}

	return $atts;
}

function get_wall() {
	global $vk;
	if ( ! empty( $_REQUEST['user_ids'] ) ) {
		$user_ids = $_REQUEST['user_ids'];
		$atts     = [
			'owner_id' => $user_ids,
		];

		$atts = parse_args( $atts, array(
			'owner_id' => $user_ids,
			'count'    => 100,
			//'from_group' => 1,
			//'message'  => '',
			//'attachment' => '',
		) );
		echo '<pre>';
		print_r( $atts );
		echo '</pre>';

		$response = $vk->api( 'wall.get', $atts );

		foreach ( $response['items'] as $post ) {
			the_post( $post );
		}
		echo '<pre>';
		//print_r( $response );
		echo '</pre>';
	}
}

function the_attachment( $data ) {
	$attachment_default = parse_args( $data, Array(
			'type'  => 'video',
			'video' => Array(
				'id'              => 456239115,
				'owner_id'        => - 66364235,
				'title'           => '',
				'duration'        => 715,
				'description'     => '',
				'date'            => 1518557314,
				'comments'        => 1,
				'views'           => 59541,
				'width'           => 800,
				'height'          => 100,
				'photo_130'       => '',
				'photo_320'       => '',
				'photo_800'       => '',
				'access_key'      => '',
				'first_frame_320' => '',
				'first_frame_160' => '',
				'first_frame_130' => '',
				'first_frame_800' => '',
			)
		)
	);

	?>
	<div class="attachment">
		<img src="<?php echo $data[ $data['type'] ]['photo_130']; ?>"
		     alt="<?php echo htmlspecialchars( ! empty( $data[ $data['type'] ]['title'] ) ? $data[ $data['type'] ]['title'] : '' ); ?>"
		     width="<?php echo $data[ $data['type'] ]['width']; ?>"
		     height="<?php echo $data[ $data['type'] ]['height']; ?>"
		     class="attachment__photo">
	</div>
	<?php
}

function the_post( $atts ) {
	$attachment_default = Array(
		'type'  => 'video',
		'video' => Array(
			'id'              => 456239115,
			'owner_id'        => - 66364235,
			'title'           => '',
			'duration'        => 715,
			'description'     => '',
			'date'            => 1518557314,
			'comments'        => 1,
			'views'           => 59541,
			'width'           => 1920,
			'height'          => 1080,
			'photo_130'       => '',
			'photo_320'       => '',
			'photo_800'       => '',
			'access_key'      => '',
			'first_frame_320' => '',
			'first_frame_160' => '',
			'first_frame_130' => '',
			'first_frame_800' => '',
		)
	);
	$atts               = parse_args( $atts, array(
			'id'            => 1387678,
			'from_id'       => - 67290125,
			'owner_id'      => - 67290125,
			'date'          => 1518586178,
			'marked_as_ads' => 0,
			'post_type'     => 'post',
			'text'          => '',
			'is_pinned'     => 1,
			'attachments'   => Array(),
			'post_source'   => Array(
				'type' => 'vk',
			),
			'comments'      => Array(
				'count'    => 6,
				'can_post' => 1,
			),
			'likes'         => Array(
				'count'       => 0,
				'user_likes'  => 0,
				'can_like'    => 1,
				'can_publish' => 1,
			),

			'reposts' => Array(
				'count'         => 0,
				'user_reposted' => 0,
			),
		)
	);
	?>

	<div class="post">
		<div class="post__data"><?php echo date( 'H:i:s, d.m.Y', $atts['date'] ); ?></div>
		<div class="post__attachments">
			<?php
			if ( ! empty( $atts['attachments'] ) ) {
				foreach ( $atts['attachments'] as $attachment ) {
					the_attachment( $attachment );
				}
			}
			?>
		</div>
		<div class="post__text"><?php echo $atts['text']; ?></div>
		<div class="post__meta">
			<div class="post__likes"><?php echo $atts['likes']['count']; ?></div>
			<div class="post__reposts"><?php echo $atts['reposts']['count']; ?></div>
			<div class="post__comments"><?php echo $atts['comments']['count']; ?></div>
		</div>
	</div>

	<?php
}