<?php
use PortalManager\Gallery;
use PortalManager\News;

class galeria extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Galéria';

			$this->out( 'bodyclass', 'article galleries' );
			$this->out( 'head_img_title', 'Galéria' );
			$cat = (isset($_GET['cat']) && !empty($_GET['cat'])) ? $_GET['cat'] : false;

      $galleries = new Gallery(array('db' => $this->db));

			// Partnerek
			// id: 7
			$news = new News( false, array( 'db' => $this->db ) );
			$arg = array(
				'page' => 1,
				'limit' => 999,
				'in_cat' => 7
			);
			$this->out( 'partnereink_news', $news->getTree( $arg ) );

			// Mappák
			$news = new News( false, array( 'db' => $this->db ) );
			$catarg = array();
			$catarg['usetree'] = false;
			$catarg['childof'] = 35;
			$catarg['showallpostc'] = true;
			$folders = $news->categoryList($catarg);
			$galleries->buildGalleries( $folders );

			$this->out( 'folders', $folders);
			$this->out( 'cat', $cat);

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description', $this->view->settings['about_us']);
			$SEO .= $this->view->addMeta('keywords',$this->view->settings['page_keywords']);
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('title', $this->view->settings['page_title'] . ' - '.$this->view->settings['page_description']);
			$SEO .= $this->view->addOG('description', $this->view->settings['about_us']);
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url', CURRENT_URI );
			$SEO .= $this->view->addOG('image', $this->view->settings['domain'].'/admin'.$this->view->settings['logo']);
			$SEO .= $this->view->addOG('site_name', $this->view->settings['page_title']);
			$this->view->SEOSERVICE = $SEO;
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				$this->view->news = null;
				parent::__destruct();				# FOOTER
		}
	}

?>
