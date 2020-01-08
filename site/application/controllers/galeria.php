<?php
use PortalManager\Gallery;
use PortalManager\News;

class galeria extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Galéria';
			$page_desc = 'Fotó galériák iskolánk eseményeiről.';
			$page_img = SOURCE.'images/no-image-gallery.jpg';

			$this->out( 'bodyclass', 'article galleries' );
			$this->out( 'head_img_title', 'Galéria' );
			$cat = (isset($_GET['cat']) && !empty($_GET['cat'])) ? $_GET['cat'] : false;

      $galleries = new Gallery(array('db' => $this->db));

			if (isset($_GET['folder']) && !empty($_GET['folder']))
			{
					$folder = $galleries->getGallery( $_GET['folder'] );
					$this->out( 'gallery', $folder );

					parent::$pageTitle = $folder['title'].' | Galéria';
					$page_desc = $folder['title'].' galéria - '.count($folder['images']). ' db kép.';

					if ($folder['belyeg_kep'] != '') {
						$page_img = UPLOADS.$folder['belyeg_kep'];
					}
			}

			$newgalleries = $galleries->getLastGalleries();
			$this->out( 'newgalleries', $newgalleries );

			// Partnerek
			// id: 7
			$news = new News( false, array( 'db' => $this->db ) );
			$arg = array(
				'page' => 1,
				'limit' => 999,
				'in_cat' => \PARTNER_CAT_ID
			);
			$this->out( 'partnereink_news', $news->getTree( $arg ) );

			// Mappák
			$news = new News( false, array( 'db' => $this->db ) );
			$catarg = array();
			$catarg['usetree'] = false;
			$catarg['childof'] = 127;
			if ($this->view->gallery && $this->view->gallery['default_cat']['ID']) {
				$cat = $this->view->gallery['default_cat']['slug'];
			}
			$catarg['showallpostc'] = true;
			$folders = $news->categoryList($catarg);
			$galleries->buildGalleries( $folders );

			$this->out( 'folders', $folders);
			$this->out( 'cat', $cat);
			$this->out( 'cikkroot', '/galeria');

			if (isset($_GET['cat']) && !empty($_GET['cat'])) {
				parent::$pageTitle = $folders[$cat]['neve'].' | Galéria kategóriák';
				$page_desc = $folders[$cat]['neve'].' galéria kategória - '.count($folders[$cat]['items']). ' db album.';
			}

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description', $page_desc );
			$SEO .= $this->view->addMeta('keywords',$this->view->settings['page_keywords']);
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('title', $this->view->settings['page_title'] . ' - '.$this->view->settings['page_description']);
			$SEO .= $this->view->addOG('description', $page_desc );
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url', CURRENT_URI );
			$SEO .= $this->view->addOG('image', $page_img );
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
