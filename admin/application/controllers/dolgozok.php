<?php
use PortalManager\Pagination;
use PortalManager\DolgozokLista;

class dolgozok extends Controller
{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Dolgozók listája / Adminisztráció';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

      if ($this->view->adm->user['user_group'] != 'admin') {
				$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), 'dolgozok', true);
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

				if($filtered){
					setcookie('filtered','1',time()+60*24*7,'/'.$this->view->gets[0]);
				}else{
					setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
				}
				Helper::reload('/dolgozok/1');
      }
      
      $handler = new DolgozokLista( $this->view->gets[2],  array( 'db' => $this->db )  );

      // Hír fa betöltés
			$current_page = (int)preg_replace("/[^0-9]/", "", $this->view->gets[1]);
			$current_page = ($current_page == 0) ? 1 : $current_page;

			$arg = array(
				'limit' => 100,
				'page' 	=>$current_page
      );
            
			if (isset($_COOKIE['filter_nev'])) {
				$arg['search'] = array(
					'text' => $_COOKIE['filter_nev'],
					'how' => 'ee'
				);
			}

      $page_tree 	= $handler->getTree( $arg );
			$this->out( 'list', $page_tree );

			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $handler->getCurrentPage(),
				'max' 		=> $handler->getMaxPage(),
				'root' 		=> '/'.__CLASS__,
				'item_limit'=> 12
			)))->render() );

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
      $handler = new DolgozokLista( $this->view->gets[3],  array( 'db' => $this->db )  );

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
					$id = $handler->add($_POST);
					Helper::reload('/dolgozok/creator/szerkeszt/'.$id.'?rmsg=success::Új dolgozó sikeresen létrehozva.');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			} 

			$refurl = $_SERVER['HTTP_REFERER'];

			if (!empty($refurl) && $_GET['b'] == '1') {
				$_SESSION['dolgozo_ref_url'] = $refurl;
			}

      switch($this->view->gets[2])
      {
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
							$ret = $handler->save($_POST);
							$svd = json_encode($ret, \JSON_UNESCAPED_UNICODE );
							$svd = base64_encode( $svd );
							Helper::reload('/dolgozok/creator/szerkeszt/'.$this->gets[3].'?saved='.$svd);
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'item', $handler->get( $this->view->gets[3]) );
				break;
				case 'torles':
					if(Post::on('delId')){
						try{
							$handler->delete($this->view->gets[3]);
							$back = $_SESSION['dolgozo_ref_url'];
							Helper::reload();
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'item', $handler->get( $this->view->gets[3]) );
				break;
			}

			if (isset($_SESSION['dolgozo_ref_url'])) {
				$this->out('backurl', $_SESSION['dolgozo_ref_url']);
			}
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
