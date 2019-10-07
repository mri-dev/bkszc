<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- STYLES -->
<link rel="icon" href="<?=IMG?>icons/favicon.ico" type="image/x-icon">
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
<?php $this->switchJSAsync('defer'); ?>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js"></script>
<script defer src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script defer src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/hu.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-sanitize.min.js"></script>
<?php $this->switchJSAsync('async'); ?>
<?=$this->addJS('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js',true)?>
<?php $this->switchJSAsync('defer'); ?>
<script defer src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script defer src='//www.google.com/recaptcha/api.js?hl=hu'></script>

<!-- Angular Material Library -->
<script defer src="//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
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

<script defer type="text/javascript" src="/src/vendors/autocomplete/scripts/jquery.mockjax.js"></script>
<script defer type="text/javascript" src="/src/vendors/autocomplete/dist/jquery.autocomplete.min.js"></script>
<script defer type="text/javascript" src="/src/vendors/md-date-range-picker/md-date-range-picker.js"></script>
<script defer type="text/javascript" src="/src/vendors/angular-timer/dist/assets/js/angular-timer-all.min.js"></script>
<script defer type="text/javascript" src="<?=JS?>slick/slick.min.js"></script>
<script defer type="text/javascript" src="<?=JS?>fancybox/jquery.fancybox.js?v=2.1.4"></script>
<script defer type="text/javascript" src="<?=JS?>fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
