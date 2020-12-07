<?php

class helpdesk extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Helpdesk / Adminisztráció';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), true, true);

      $videos = [];

      $video_root = '/src/helpdesk/videos/';

      /** Cikkek videók */
      $videos['I. Cikkek']['I.1.'] = [
        'title' => 'I.1. Cikkek feltöltése',
        'video' => $video_root.'articles_create.mp4'
      ];
      $videos['I. Cikkek']['I.2.'] = [
        'title' => 'I.2. Minta',
        'video' => $video_root.'1.mp4'
      ];
      $videos['I. Cikkek']['I.3.'] = [
        'title' => 'I.3. Minta 2',
        'video' => $video_root.'2.mp4'
      ];

      /** Galéria videók */
      $videos['II. Galéria'][] = [
        'title' => 'II.1. Új galéria létrehozása',
        'video' => $video_root.'gallery_var.mp4'
      ];
      $this->out( 'videos', $videos );		
    }
    
		function __destruct(){
			// RENDER OUTPUT
      parent::bodyHead();					# HEADER
      $this->view->render(__CLASS__);		# CONTENT
      parent::__destruct();				# FOOTER
		}
	}

?>
