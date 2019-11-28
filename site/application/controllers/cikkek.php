<?
use PortalManager\News;
use PortalManager\Template;
use PortalManager\Pagination;

class cikkek extends Controller{
	function __construct(){
		parent::$user_opt = $user_option;
		parent::__construct();

		$this->out( 'bodyclass', 'article' );

		$url = DOMAIN.__CLASS__;
		$image = \PortalManager\Formater::sourceImg($this->view->settings['logo']);
		$title = 'Bejegyzéseink';
		$description = $this->view->settings['page_title'].' friss bejegyzései. Kövesd oldalunkat és tájékozódj az újdonságokról!';
		$cikkroot = '/cikkek/';
		$is_archiv = (isset($_GET['archive'])) ? true : false;

		// Aktuális
		$news = new News( false, array( 'db' => $this->db ) );
		$hirek = array();
		$arg = array(
			'limit' => 25,
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
				$hirek[] = (new News(false, array( 'db' => $this->db )))->get($hir[ID]);
			}
		}
		$this->out( 'aktual_news', $hirek );
		unset($news);
		unset($hirek);

		$news = new News( false, array( 'db' => $this->db ) );
		$temp = new Template( VIEW . __CLASS__.'/template/' );
		$this->out( 'template', $temp );

		// archív dátumok
		$adlimit = (isset($_GET['archive'])) ? false : 25;
		$archive_dates = $news->getArchiveDates( $adlimit );
		$this->out('archive_dates', $archive_dates);

		if ( isset($_GET['cikk']) ) {
			$this->out( 'bodyclass', 'article singlearticle article-slug-'.$_GET['cikk'] );
			$this->out( 'news', $news->get( trim($_GET['cikk']) ) );
			$this->out( 'is_tematic_cat', 1);
			$news->log_view($this->view->news->getId());

			$arg = array(
				'limit' => 4,
				'page' 	=> 1,
				'in_cat' => (isset($_GET['cat']) && $_GET['cat'] != '' && $_GET['cat'] != 'olvas') ? $this->view->newscats[$_GET['cat']][ID] : false,
				'order' => array(
					'by' => 'rand()'
				),
				"except_id" => $this->view->news->getId()
			);
			if ($is_archiv) {
				$arg['only_archiv'] = true;
			} else {
				$arg['hide_archiv'] = true;
			}
			$this->out( 'related', $news->getTree( $arg ) );

			$url = $this->view->news->getUrl();
			if ( $this->view->news->getImage() ) {
				$image = \PortalManager\Formater::sourceImg($this->view->news->getImage());
			}
			$title = $this->view->news->getTitle() . ' | Cikkek';
			$description = substr(strip_tags($this->view->news->getDescription()), 0 , 350);

		} else {
			$cat_slug =  trim($_GET['cat']);

			// Kategória adatok
			$catdata = $this->db->squery("SELECT ID, neve, szulo_id FROM cikk_kategoriak WHERE slug = :slug", array('slug' => trim($_GET['cat'])))->fetch(\PDO::FETCH_ASSOC);
			$cat_id = (int)$catdata['ID'];
			$cat_parent_row = $news->parentCategoryData($cat_id);
			$cat_parent_row = array_reverse($cat_parent_row);
			array_pop($cat_parent_row);
			$this->out('cat_parent_row', $cat_parent_row);
			$cat_name = $catdata['neve'];
			$this->out( 'currentcat', array(
				'name' => $catdata['neve'],
				'id' => $catdata['ID'],
				'slug' =>$catdata['slug']
			));

			if ($cat_id != 0) {
				$parentcatdata = $this->db->squery("SELECT ID, neve, slug FROM cikk_kategoriak WHERE ID = :id", array('id' => trim($catdata['szulo_id'])))->fetch(\PDO::FETCH_ASSOC);
				if ($parentcatdata['neve'] != '') {
					$this->out( 'parent_cat', array(
						'name' => $parentcatdata['neve'],
						'id' => $parentcatdata['ID'],
						'slug' =>$parentcatdata['slug']
					));
				}
			}

			if ($cat_slug == '') {
				$headimgtitle = (!$is_archiv) ? 'Bejegyzéseink': 'Archívum';
				if (isset($_GET['date'])) {
					$headimgtitle .= ' - '.utf8_encode(strftime ('%Y. %B', strtotime($_GET['date'])));
					$title = $headimgtitle . ' | Bejegyzéseink';
				}

				$this->out( 'head_img_title', $headimgtitle);
				$this->out( 'head_img', IMGDOMAIN.'/src/uploads/covers/cover-archive.jpg' );
			} else {

				$title = $cat_name.' | Bejegyzéseink';
				$description = $cat_name.' friss bejegyzései. Kövesd oldalunkat és tájékozódj az újdonságokról!';
				$this->out( 'head_img_title', (!$is_archiv) ? $cat_name : 'Archívum:'.$this->view->newscatslist[$cat_slug]['neve']  );
				$this->out( 'head_img', IMGDOMAIN.'/src/uploads/covers/cover-archive.jpg' );
			}
			$arg = array(
				'limit' => 12,
				'hide_offline' => true,
				'in_cat' => $cat_id,
				'page' => (isset($_GET['page'])) ? (int)str_replace('P','', $_GET['page']) : 1,
			);
			$this->out('current_page', $arg['page']);

			if (isset($_GET['date'])) {
				$arg['on_date'] = $_GET['date'];
			}
			if (isset($_GET['src']) && !empty($_GET['src'])) {
				$arg['search'] = trim($_GET['src']);
			}

			$this->out( 'list', $news->getTree( $arg ) );

			$navroot = (in_array($_GET['cat'], $news->tematic_cikk_slugs)) ? $_GET['cat'] : '/'.__CLASS__.'/kategoriak'.( (isset($_GET['cat'])) ? '/'.$_GET['cat'] : '' );

			if ($is_archiv) {
				$navroot = '/'.__CLASS__.'/date/'.$_GET['date'];
			}

			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $news->getCurrentPage(),
				'max' 		=> $news->getMaxPage(),
				'root' => $navroot,
				'item_limit'=> 12
			)))->render() );
		}

		$catarg = array();
		if (isset($_GET['src']) && !empty($_GET['src'])) {
			$catarg['search'] = trim($_GET['src']);
		}
		$catarg['usetree'] = false;
		if ($cat_id != 0) {
			$catarg['childof'] = $cat_id;
		}
		$this->out( 'newscats', $news->categoryList($catarg));
		$this->out( 'newscatslist', $this->view->newscats);

		$this->out( 'cikkroot', $cikkroot );
		$this->out( 'is_archiv', $is_archiv );
		$this->out( 'hideheadimg', true );

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
