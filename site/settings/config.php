<?
  	////////////////////////////////////////
	// Protocol
	$protocol = 'http://';
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
		$protocol = 'https://';
	}
	// Domain név
	define('DOMAIN',$protocol.$_SERVER['HTTP_HOST'].'/');
	define('CURRENT_URI',$protocol.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"] );
	define('MDOMAIN',$_SERVER['HTTP_HOST']);
	define('CLR_DOMAIN',str_replace(array($protocol,"www."),"",substr('www.'.DOMAIN,0,-1)));
	define('DEVMODE', true);
	define('TS', '202011091400');
	define('PARTNER_CAT_ID', 6);

	// Üdvözlő üzenet
	define('WELCOME','Üdvözlünk az oldalunkon!');

	define('AJAX_GET','/ajax/get/');
	define('AJAX_POST','/ajax/post/');
	define('AJAX_BOX','/ajax/box/');

  define('POPUP_RECEIVER_URL', $protocol.'www.bkszc.ideasandbox.eu');
	define('POPUP_IMG_ROOT', $protocol.'www.cp.bkszc.ideasandbox.eu');
	define('CETELEM_SANDBOX_MODE', false);
	//define('CETELEM_HAS_ERROR', true);

	// Facebook APP
		define('FBAPPID','114468722051781');
		define('FBSECRET','abde731025555f2f290fcf618a52a0ed');

	////////////////////////////////////////
	// Ne módosítsa innen a beállításokat //
	define('APIKEY_GOOGLE_MAP_EMBEDKEY', 'AIzaSyD99pf6f7JFVgvmiieIvtlJyMlS15I36qg');

	date_default_timezone_set('Europe/Berlin');
	// PATH //
		define('FREE_SHIPPING_PRICE', 15000);

		define('TEMP','v1.0');
		define('PATH', realpath($_SERVER['HTTP_HOST']));

		define('APP_PATH','application/');

		define('LIBS','../admin/'.APP_PATH . 'libs/');

		define('MODEL',APP_PATH . 'models/');

		define('VIEW',APP_PATH . 'views/'.TEMP.'/');

		define('CONTROL',APP_PATH . 'controllers/');

		define('STYLE','/src/css/');
		define('SSTYLE','/public/'.TEMP.'/styles/');

		define('JS','/src/js/');
		define('SJS','/public/'.TEMP.'/js/');

		define('UPLOADS',	$protocol.'cp.bkszc.ideasandbox.eu/src/uploads/');
		define('IMG',		$protocol.'cp.bkszc.ideasandbox.eu/src/images/');
		define('IMGDOMAIN',	$protocol.'cp.bkszc.ideasandbox.eu/');
		define('SOURCE',	$protocol.'cp.bkszc.ideasandbox.eu/src/');
		define('STOREDOMAIN',$protocol.'cp.bkszc.ideasandbox.eu/');

	// Környezeti beállítások //

		define('CAPTCHA_PUBLIC_KEY','6Lfim_oSAAAAADzkdJjq8LJ0BTT0X4eBLpSy3emZ');

		define('CAPTCHA_PRIVATE_KEY','6Lfim_oSAAAAAABh5QkbpyJaAzVpvsGaC7IcCnG4');

		define('ARUKERESO_CLIENT_KEY', '');

		define('SKEY','sdfew86f789w748rh4z8t48v97r4ft8drsx4');

		define('NOW',date('Y-m-d H:i:s'));

		define('PREV_PAGE',$_SERVER['HTTP_REFERER']);

		define('RPDOCUMENTROOT', '../admin/src/uploaded_files');

		define('PRODUCTIONSITE', true);

	// Adminisztráció

		define('ADMROOT',$protocol.'cp.bkszc.ideasandbox.eu/');

	require "data.php";
?>
