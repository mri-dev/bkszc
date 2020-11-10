<?
use PortalManager\News;
use PortalManager\Pagination;
use ShopManager\Categories;
use ShopManager\Category;

class cikkek extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Cikkek / Adminisztráció';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), 'cikkek', true);

			// Archive toggle
			if (Post::on('setArchive'))
			{
			 $switch = (int)$_POST['setArchive'];
			 if ($switch == 1) {
				 	setcookie('showarchive',1,time()+60*24,'/'.$this->view->gets[0]);
				} else{
					setcookie('showarchive',null,time()-3600,'/'.$this->view->gets[0]);
				}
				Helper::reload('/cikkek/1');
			}

			// Szűrő mentése
			if(Post::on('filters'))
			{
				if($_POST['order'] != ''){
					setcookie('filter_order',$_POST['order'],time()+60*24,'/'.$this->view->gets[0]);
				}else{
					setcookie('filter_order','',time()-100,'/'.$this->view->gets[0]);
				}
				Helper::reload('/cikkek/1');
			}

			if(Post::on('filterList'))
			{
				$filtered = false;

				if($_POST['nev'] != ''){
					setcookie('filter_nev',$_POST['nev'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_nev','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['lathato'] != ''){
					setcookie('filter_lathato',$_POST['lathato'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_lathato','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['kategoria'] != ''){
					setcookie('filter_kategoria',$_POST['kategoria'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_kategoria','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['order'] != ''){
					setcookie('filter_order',$_POST['order'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_order','',time()-100,'/'.$this->view->gets[0]);
				}

				if($filtered){
					setcookie('filtered','1',time()+60*24*7,'/'.$this->view->gets[0]);
				}else{
					setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
				}
				Helper::reload('/cikkek/1');
			}

			$categories = new Categories( array( 'db' => $this->db ) );
			$categories->setTable( 'cikk_kategoriak' );
			$news = new News( $this->view->gets[2],  array( 'db' => $this->db )  );

			// Hír fa betöltés
			$current_page = (int)preg_replace("/[^0-9]/", "", $this->view->gets[1]);
			$current_page = ($current_page == 0) ? 1 : $current_page;

			$arg = array(
				'limit' => 25,
				'page' 	=>$current_page
			);
			if (isset($_COOKIE['showarchive'])) {
				$arg['only_archiv'] = true;
			} else {
				$arg['hide_archiv'] = true;
			}
			if (isset($_COOKIE['filter_kategoria'])) {
				$arg['in_cat'] = (int)$_COOKIE['filter_kategoria'];
			}

			if (isset($_COOKIE['filter_lathato'])) {
				$arg['lathato'] = (int)$_COOKIE['filter_lathato'];
			}
			if (isset($_COOKIE['filter_nev'])) {
				$arg['search'] = array(
					'text' => $_COOKIE['filter_nev'],
					'how' => 'ee'
				);
			}

			if (isset($_COOKIE['filter_order'])) {
				$ord = explode('-',$_COOKIE['filter_order']);
				$arg['order'] = array(
					'by' => 'h.'.$ord[0],
					'how' => $ord[1]
				);
			}

			$page_tree 	= $news->getTree( $arg );
			// Hírek
			$this->out( 'news_list', $page_tree );
			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $news->getCurrentPage(),
				'max' 		=> $news->getMaxPage(),
				'root' 		=> '/'.__CLASS__,
				'item_limit'=> 12
			)))->render() );

			// LOAD
			////////////////////////////////////////////////////////////////////////////////////////
			$cat_tree 	= $categories->getTree();
			// Kategoriák
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
		}

		public function creator()
		{
			$news = new News( $this->view->gets[3],  array( 'db' => $this->db ) );

			if (isset($_GET['rmsg'])) {
				$xrmsg = explode('::', $_GET['rmsg']);
				$this->out('msg', \Helper::makeAlertMsg('p'.ucfirst($xrmsg[0]), $xrmsg[1]));
			}
			
			if( isset($_GET['saved']) ) 
			{
				$saved_msg = json_decode(base64_decode($_GET['saved']), true);
				if( !empty($saved_msg['messages']) )
				{
					$rmsg = '';
					foreach( (array)$saved_msg['messages'] as $m )
					{
						$rmsg .= "<div>".$m."</div>";
					}
					if( $saved_msg['success'] == 0){
						$this->out('msg', \Helper::makeAlertMsg('pError', $rmsg));
					} else {
						$this->out('msg', \Helper::makeAlertMsg('pSuccess', $rmsg));
					}
				}				
			}

			if(Post::on('add')){
				try{
					$id = $news->add($_POST);
					Helper::reload('/cikkek/creator/szerkeszt/'.$id.'?rmsg=success::Új cikk sikeresen létrehozva.');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			} 

			$refurl = $_SERVER['HTTP_REFERER'];

			if (!empty($refurl) && $_GET['b'] == '1') {
				$_SESSION['cikkek_ref_url'] = $refurl;
			}

			switch($this->view->gets[2]){
				case 'szerkeszt':
					if(Post::on('save')){
						/* * /
						echo '<pre>';
						print_r($_POST);
						print_r($_FILES);
						echo '</pre>';
						exit;
						/* */
						try{
							$ret = $news->save($_POST);
							$svd = json_encode($ret, \JSON_UNESCAPED_UNICODE );
							$svd = base64_encode( $svd );
							Helper::reload('/cikkek/creator/szerkeszt/'.$this->gets[3].'?saved='.$svd);
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'news', $news->get( $this->view->gets[3]) );
				break;
				case 'torles':
					if(Post::on('delId')){
						try{
							$news->delete($this->view->gets[3]);
							$back = $_SESSION['cikkek_ref_url'];
							Helper::reload();
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'news', $news->get( $this->view->gets[3]) );
				break;
			}

			if (isset($_SESSION['cikkek_ref_url'])) {
				$this->out('backurl', $_SESSION['cikkek_ref_url']);
			}
		}

		public function kategoriak()
		{
			$categories = new Categories( array( 'db' => $this->db ) );
			$categories->setTable( 'cikk_kategoriak' );

			// Új kategória
			if( Post::on('addCategory') )
			{
				try {
					$categories->add( $_POST );
					Helper::reload();
				} catch ( Exception $e ) {
					$this->view->err	= true;
					$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			// Szerkesztés
			if ( $this->view->gets[2] == 'szerkeszt') {
				// Kategória adatok
				$cat_data = (new Category( $this->view->gets[3],  array( 'db' => $this->db )  ))->setTable( 'cikk_kategoriak' )->get();
				$this->out( 'category', $cat_data );

				// Változások mentése
				if( Post::on('saveCategory') )
				{
					try {
						$categories->edit( $cat_data, $_POST );
						Helper::reload();
					} catch ( Exception $e ) {
						$this->view->err	= true;
						$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
					}
				}
			}

			// Törlés
			if ( $this->view->gets[2] == 'torles') {
				// Kategória adatok
				$cat_data = (new Category( $this->view->gets[3],  array( 'db' => $this->db )  ))->setTable( 'cikk_kategoriak' )->get();
				$this->out( 'category_d', $cat_data );

				// Kategória törlése
				if( Post::on('delCategory') )
				{
					try {
						$categories->delete( $cat_data );
						Helper::reload( '/'.$this->gets[0].'/'.$this->gets[1] );
					} catch ( Exception $e ) {
						$this->view->err	= true;
						$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
					}
				}
			}

			// LOAD
			////////////////////////////////////////////////////////////////////////////////////////
			$cat_tree 	= $categories->getTree();
			// Kategoriák
			$this->out( 'categories', $cat_tree );
		}

		function clearfilters(){
			setcookie('filter_nev','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_kategoria','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_lathato','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
			Helper::reload('/cikkek/');
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
