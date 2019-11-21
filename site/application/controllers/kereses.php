<?php
use PortalManager\Programs;
use PortalManager\Pagination;
use PortalManager\News;
use ShopManager\Categories;
use ShopManager\Category;
use PortalManager\Gallery;

class kereses extends Controller{
		function __construct(){
			parent::__construct();
			$title = 'Keresés';
			$list = array();
			$bodyclass = 'searchresult';

			// Partnerek
			// id: 7
			$news = new News( false, array( 'db' => $this->db ) );
			$arg = array(
				'page' => 1,
				'limit' => 999,
				'in_cat' => \PARTNER_CAT_ID
			);
			$this->out( 'partnereink_news', $news->getTree( $arg ) );

			$navroot = '/search';

			////////////////////////////////////////
			// KERESÉS
			////////////////////////////////////////
			$lisgroup = (!empty($_GET['group'])) ? $_GET['group'] : 'article';

			// Cikk keresés
			if ($lisgroup == 'article')
			{
				$news = new News( false, array( 'db' => $this->db ) );
				$search = array();
				$search['text'] = $_GET['src'];
				$search['method'] = (!isset($_GET['src_type'])) ? 'ft' : $_GET['src_type'];
				$arg = array(
					'limit' => 10,
					'search' => $search,
					'page' => (isset($_GET['page'])) ? (int)str_replace('P','', $_GET['page']) : 1
				);
				if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
					if ($_GET['orderby'] == 'date') {
						$arg['order']['by'] = 'h.idopont';
						$arg['order']['how'] = $_GET['order'];
					}
					if ($_GET['orderby'] == 'name') {
						$arg['order']['by'] = 'h.cim';
						$arg['order']['how'] = $_GET['order'];
					}
				}
				$list = $news->getTree( $arg );
				$page_current = $news->getCurrentPage();
				$page_max = $news->getMaxPage();
				$total_result = $news->sitem_numbers;
				$bodyclass .= ' articles';
			}

			// Galéria keresés
			if ($lisgroup == 'gallery')
			{
				$news = new Gallery( array( 'db' => $this->db ) );
				$search = array();
				$search['text'] = $_GET['src'];
				$search['method'] = (!isset($_GET['src_type'])) ? 'ft' : $_GET['src_type'];
				$arg = array(
					'limit' => 10,
					'search' => $search,
					'page' => (isset($_GET['page'])) ? (int)str_replace('P','', $_GET['page']) : 1
				);
				if (isset($_GET['cats']) && !empty($_GET['cats'])) {
					$arg['in_cat'] = (array)$_GET['cats'];
				}
				if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
					if ($_GET['orderby'] == 'date') {
						$arg['order']['by'] = 'g.uploaded';
						$arg['order']['o'] = $_GET['order'];
					}
					if ($_GET['orderby'] == 'name') {
						$arg['order']['by'] = 'g.title';
						$arg['order']['o'] = $_GET['order'];
					}
				}
				$list = $news->simpleGalleryList( $arg );
				$page_current = $news->page_current;
				$page_max = $news->page_max;
				$total_result = $news->sitem_numbers;
				$bodyclass .= ' galleries';
			}

			// Esemény keresés
			if ( $lisgroup == 'programs' ) {
				$news = new Programs( false, array( 'db' => $this->db ) );

				$search['text'] = $_GET['src'];
				$search['method'] = (!isset($_GET['src_type'])) ? 'ft' : $_GET['src_type'];
				$arg = array(
					'limit' => 10,
					'search' => $search,
					'page' => (isset($_GET['page'])) ? (int)str_replace('P','', $_GET['page']) : 1
				);
				if (isset($_GET['cats']) && !empty($_GET['cats'])) {
					$arg['in_cat'] = (array)$_GET['cats'];
				}
				if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
					if ($_GET['orderby'] == 'date') {
						$arg['order']['by'] = 'h.idopont';
						$arg['order']['how'] = $_GET['order'];
					}
					if ($_GET['orderby'] == 'name') {
						$arg['order']['by'] = 'h.cim';
						$arg['order']['how'] = $_GET['order'];
					}
				}
				$list = $news->getTree( $arg );
				$page_current = $news->getCurrentPage();
				$page_max = $news->getMaxPage();
				$total_result = $news->sitem_numbers;
				$bodyclass .= ' events';
			}

			////////////////////////////////////////
			// END of KERESÉS
			////////////////////////////////////////

			// Lista output
			$this->out( 'list', $list );
			$this->out( 'listgroup', $lisgroup );
			$this->out( 'bodyclass', $bodyclass );
			$this->out( 'total_result', $total_result );
			$this->out( 'page_current', $page_current );
			$this->out( 'page_max', $page_max );

			$navafter = '/?';
			$srcq = $_GET;
			unset($srcq['tag']);
			unset($srcq['page']);
			$navafter .= http_build_query($srcq);
			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $page_current,
				'max' 		=> $page_max,
				'root' => $navroot,
				'after' => $navafter,
				'item_limit'=> 12
			)))->render() );

			// Kategoriák
			$categories = new Categories( array( 'db' => $this->db ) );
			$categories->setTable( 'cikk_kategoriak' );
			$cat_tree 	= $categories->getTree();
			$this->out( 'categories', $cat_tree );

			$title = '„'.$_GET['src'].'” kulcsszóra keresés eredménye ';

			switch ($this->view->listgroup) {
				case 'article':
					$title .= 'a bejegyzések között';
				break;
				case 'gallery':
					$title .=  'a galériák között';
				break;
				case 'programs':
					$title .=  'az események között';
				break;
			}

			$title .=  ' | Keresés';

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description',$total_result.' db keresési találat.');
			$SEO .= $this->view->addMeta('keywords','keresés,kereső,gundel,iskola,'.$_GET['src'].' találati lista,'.$_GET['src']);
			$SEO .= $this->view->addMeta('revisit-after','1 days');

			// FB info
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url',DOMAIN.$_SERVER['REQUEST_URI']);
			$SEO .= $this->view->addOG('image',DOMAIN.substr(IMG,1).'noimg.jpg');
			$SEO .= $this->view->addOG('site_name',$this->view->settings['page_title']);

			$this->view->SEOSERVICE = $SEO;


			parent::$pageTitle = $title;
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
