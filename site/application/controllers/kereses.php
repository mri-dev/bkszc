<?php
use PortalManager\Pagination;
use PortalManager\News;
use ShopManager\Categories;
use ShopManager\Category;

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

			// Cikk keresés
			$news = new News( false, array( 'db' => $this->db ) );
			$arg = array(

			);
			$list = $news->getTree( $arg );
			$bodyclass .= ' articles';

			// Lista output
			$this->out( 'list', $list );
			$this->out( 'bodyclass', $bodyclass );

			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $news->getCurrentPage(),
				'max' 		=> $news->getMaxPage(),
				'root' => $navroot,
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
