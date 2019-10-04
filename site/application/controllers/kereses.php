<?php
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
				'in_cat' => 7
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
				$bodyclass .= ' articles';
			}

			// Galéria keresés
			if ($lisgroup == 'gallery')
			{
				$news = new Gallery( array( 'db' => $this->db ) );
				$search = array();
				$search['text'] = $_GET['src'];
				$search['method'] = (!isset($_GET['src_type'])) ? 'ft' : $_GET['src_type'];
				print_r($_GET['cats']);
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
				$bodyclass .= ' galleries';
			}

			////////////////////////////////////////
			// END of KERESÉS
			////////////////////////////////////////

			// Lista output
			$this->out( 'list', $list );
			$this->out( 'listgroup', $lisgroup );
			$this->out( 'bodyclass', $bodyclass );

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

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description','');
			$SEO .= $this->view->addMeta('keywords','');
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url',DOMAIN);
			$SEO .= $this->view->addOG('image',DOMAIN.substr(IMG,1).'noimg.jpg');
			$SEO .= $this->view->addOG('site_name',TITLE);

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
