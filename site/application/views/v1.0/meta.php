<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="apple-touch-icon" sizes="180x180" href="<?=SOURCE?>favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?=SOURCE?>favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?=SOURCE?>favicon/favicon-16x16.png">
<link rel="manifest" href="<?=SOURCE?>favicon/site.webmanifest">
<link rel="mask-icon" href="<?=SOURCE?>favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#2b5797">
<meta name="theme-color" content="#ffffff">
<!-- STYLES -->
<?=$this->addStyle('master', 'media="all"')?>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<?=$this->addStyle('bootstrap.min', 'media="all"', true, true)?>
<?=$this->addStyle('bootstrap-theme.min', 'media="all"', true, true)?>
<?=$this->addStyle('FontAwesome.min', 'media="all"', true, true)?>
<?=$this->addStyle('dashicons.min','media="all"', true, true)?>
<!--<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">-->
<?=$this->addStyle('media', 'media="all"', false)?>
<link rel="stylesheet" type="text/css" href="<?=JS?>fancybox/jquery.fancybox.css?v=2.1.4" media="all" />
<link rel="stylesheet" type="text/css" href="<?=JS?>fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<link rel="stylesheet" type="text/css" href="<?=JS?>slick/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="<?=JS?>slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="<?=JS?>md-date-range-picker/md-date-range-picker.min.css"/>
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.css" />

<!-- JS's -->
<!-- Angular Material requires Angular.js Libraries -->
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/hu.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-sanitize.min.js"></script>
<?=$this->addJS('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js',true)?>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src='//www.google.com/recaptcha/api.js?hl=hu'></script>
<script defer src='//cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js'></script>

<!-- Angular Material Library -->
<script src="//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
<?=$this->addJS('bootstrap.min', false, true, true)?>
<?=$this->addJS('jquery.cookieaccept',false,false, true)?>
<?=$this->addJS('master',false,false)?>
<?=$this->addJS('pageOpener',false,false, true)?>
<?=$this->addJS('user',false,false, true)?>
<?=$this->addJS('jquery.cookie',false, true, true)?>
<?=$this->addJS('angular.min',false, true, true)?>
<?=$this->addJS('app',false,false)?>
<?=$this->addJS('upload',false,false, true)?>
<?=$this->addJS('angular-cookies',false, false, true)?>
<?php $this->switchJSAsync('defer'); ?>
<?=$this->addJS('counter',false,false)?>
<?php $this->switchJSAsync(false); ?>

<script type="text/javascript" src="/src/vendors/autocomplete/scripts/jquery.mockjax.js"></script>
<script type="text/javascript" src="/src/vendors/autocomplete/dist/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="/src/vendors/md-date-range-picker/md-date-range-picker.js"></script>
<script type="text/javascript" src="/src/vendors/angular-timer/dist/assets/js/angular-timer-all.min.js"></script>
<script type="text/javascript" src="<?=JS?>slick/slick.min.js"></script>
<script type="text/javascript" src="<?=JS?>fancybox/jquery.fancybox.js?v=2.1.4"></script>
<script type="text/javascript" src="<?=JS?>fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
