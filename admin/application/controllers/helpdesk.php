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
      $videos['I. Cikkek, bejegyzések']['I.1.'] = [
        'title' => 'I.1. Új cikk létrehozása',
        'video' => $video_root.'I_1_cikk_letrahozas.mp4'
      ];
      $videos['I. Cikkek, bejegyzések']['I.2.'] = [
        'title' => 'I.2. Cikk kategóriáinak kezelése',
        'video' => $video_root.'I_2_cikk_kategoria_szerkesztes.mp4'
      ];
      $videos['I. Cikkek, bejegyzések']['I.3.'] = [
        'title' => 'I.3. Cikk listázás használata és lehetősgei',
        'video' => $video_root.'I_3_cikk_listazas_hasznalata_es_lehetosegei.mp4'
      ];
      
      $videos['I. Cikkek, bejegyzések']['I.4.'] = [
        'title' => 'I.4. Cikk publikációs dátum módosítása, cikkek sorrendje',
        'video' => $video_root.'I_4_cikk_publikacio_es_sorrend.mp4'
      ];
      $videos['I. Cikkek, bejegyzések']['I.5.'] = [
        'title' => 'I.5. Cikk áthelyezése másik tanévre',
        'video' => $video_root.'I_5_cikk_athelyezes_masik_tanev.mp4'
      ];
      $videos['I. Cikkek, bejegyzések']['I.5.'] = [
        'title' => 'I.6. Cikk kategóriák kezelése',
        'video' => $video_root.'I_6_cikk_kategoriak_kezelese.mp4'
      ];
      
      /** Menü videók */
      $videos['II. Menü']['II.1.'] = [
        'title' => 'II.1. Menü áttekintése és használata',
        'video' => $video_root.'II_1_menu_attekintese_es_szerkesztese.mp4'
      ];

      /** Oldalak videók */
      $videos['III. Oldalak']['III.1.'] = [
        'title' => 'III.1. Oldalak kezelése',
        'video' => $video_root.'III_1_oldalak_kezelese.mp4'
      ];
      
      /** Programok videók */
      $videos['IV. Programok / Események'][] = [
        'title' => 'IV.1. Események kezelése és ismertetése',
        'video' =>  $video_root.'IV_1_programok_esemenyek.mp4'
      ];

      /** Galéria videók */
      $videos['V. Galéria']['V.1.'] = [
        'title' => 'V.1. Galéria kategóriák működése',
        'video' =>  $video_root.'V_1_galeria_kategoriak.mp4'
      ];
      $videos['V. Galéria']['V.2.'] = [
        'title' => 'V.2. Új galéria létrehozása, képek feltöltése, rendezése és törlése',
        'video' =>  $video_root.'V_2_galeria_letrahozas_kepek_feltoltese.mp4'
      ];

       /** Dolgozók videók */
       $videos['VI. Dolgozók'][] = [
        'title' => 'Hamarosan...',
        'video' => false
      ];

      /** Egyéb videók */
      $videos['X. Egyéb'][] = [
        'title' => 'Fájlkezelő (hamarosan)',
        'video' => false
      ];
      $videos['X. Egyéb'][] = [
        'title' => 'Szövegszerkesztés (hamarosan)',
        'video' => false
      ];
      /**/
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
