<?php
use PortalManager\Programs;
use PortalManager\Template;
use PortalManager\Pagination;

class esemenyek extends Controller
{
	function __construct(){
		parent::$user_opt = $user_option;
		parent::__construct();

		$this->out( 'bodyclass', 'article esemenyek' );

		print_r($_GET);

		$url = DOMAIN.__CLASS__;
		$image = \PortalManager\Formater::sourceImg($this->view->settings['logo']);
		$title = 'Események';
		$description = $this->view->settings['page_title'].' esemény naptára. Kövesd oldalunkat és tájékozódj az eseményekről!';

		//print_r($_GET);

		$news = new Programs( false, array( 'db' => $this->db ) );
		$temp = new Template( VIEW . __CLASS__.'/template/' );
		$this->out( 'template', $temp );

		// Közelgő események
		$arg = array(
			'limit' => 12,
			'page' => (isset($_GET['page'])) ? (int)str_replace('P','', $_GET['page']) : 1,
			'order' => array(
				'by' => 'idopont',
				'how' => 'ASC'
			)
		);
		$arg['date']['min'] = date('Y-m-d');
		$this->out( 'list', $news->getTree( $arg ) );

		if ( isset($_GET['cikk']) )
		{
			// Cikk oldal
			$this->out( 'news', $news->get( trim($_GET['cikk']) ) );
			$news->log_view($this->view->news->getId());

			$url = $this->view->news->getUrl();
			if ( $this->view->news->getImage() ) {
				$image = \PortalManager\Formater::sourceImg($this->view->news->getImage());
			}
			$title = $this->view->news->getTitle() . ' | Események';
			$description = substr(strip_tags($this->view->news->getDescription()), 0 , 350);

		}
		else
		{
			// Lista oldal
			if ($cat_slug == '') {
				$this->out( 'head_img_title', 'Események' );
				//$this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );
			} else {
				$this->out( 'head_img_title', $this->view->programcats[$cat_slug]['neve'] );
				//$this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );
			}

			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $news->getCurrentPage(),
				'max' 		=> $news->getMaxPage(),
				'root' 		=> '/'.__CLASS__. (isset($_GET['cat']) ? '/'.$_GET['cat']: ''),
				'item_limit'=> 12
			)))->render() );

			$newest = new Programs( false, array( 'db' => $this->db ) );
			$this->out( 'newest', $newest->getTree( array(
				'limit' => 1,
				'page' => 1
			) ) );
		}

		unset($news);

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->view->addMeta('description', $description);
		$SEO .= $this->view->addMeta('keywords', $keywords);
		$SEO .= $this->view->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->view->addOG('title',$title.' | '.$this->view->settings['page_title']);
		$SEO .= $this->view->addOG('type','website');
		$SEO .= $this->view->addOG('url',$url);
		$SEO .= $this->view->addOG('image',$image);
		$SEO .= $this->view->addOG('site_name',$title.' | '.$this->view->settings['page_title']);

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
