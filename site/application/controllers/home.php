<?
use PortalManager\News;
use PortalManager\Programs;
use PortalManager\Template;
use PortalManager\Gallery;

class home extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = '';

			$this->out('homepage', true);
			$this->out('bodyclass', 'homepage');

			// Template
			$temp = new Template( VIEW . 'templates/' );
			$this->out( 'template', $temp );

			// Aktuális
			$news = new News( false, array( 'db' => $this->db ) );
			$hirek = array();
			$arg = array(
				'limit' => 20,
				'page' 	=> 1,
				'hide_archiv' => true,
				'hide_offline' => true,
				'in_cat' => 5,
				'order' => array(
					'by' => 'letrehozva',
					'how' => 'DESC'
				)
			);
			$news->getTree( $arg );

			if ( $news->has_news() ) {
				while ( $news->walk() ) {
					$hir = $news->the_news();
					$hirek[] = (new News(false, array( 'db' => $this->db )))->get($hir['ID']);
				}
			}
			$this->out( 'aktual_news', $hirek );
			unset($news);
			unset($hirek);

			// Aktuális Hírek
			$news = new News( false, array( 'db' => $this->db ) );
			$hirek = array();
			$arg = array(
				'limit' => 16,
				'page' 	=> 1,
				'hide_archiv' => true,
				'hide_offline' => true,
				'order' => array(
					'by' => 'letrehozva',
					'how' => 'DESC'
				)
			);
			$news->getTree( $arg );

			if ( $news->has_news() ) {
				while ( $news->walk() ) {
					$hir = $news->the_news();
					$hirek[] = (new News(false, array( 'db' => $this->db )))->get($hir['ID']);
				}
			}
			$this->out( 'news', $hirek );
			unset($news);
			unset($hirek);


			// Alapítványi hírek
			// id: 113
			$news = new News( false, array( 'db' => $this->db ) );
			$arg = array(
				'page' => 1,
				'limit' => 999,
				'in_cat' => 113
			);
			$this->out( 'alapitvany_news', $news->getTree( $arg ) );

			// Program
			$programs = new Programs( false, array( 'db' => $this->db ) );
			$future_programs = $programs->getCalanderItems(array(
				'future' => true,
				'datesoff' => true
			));
			$this->out( 'futureprograms', $future_programs['data'] );

			// Galériák - friss
			$galleries = new Gallery(array('db' => $this->db));
			$newgalleries = $galleries->getLastGalleries(array(
				'lathato' => 1
			));
			$this->out( 'newgalleries', $newgalleries );

			$this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );
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
