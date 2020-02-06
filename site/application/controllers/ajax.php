<?
use PortalManager\Programs;
use Applications\Simple;
use MailManager\Mailer;
use PortalManager\Template;

class ajax extends Controller{
		function __construct()
		{
			header("Access-Control-Allow-Origin: *");
			parent::__construct();
		}

	  private function returnJSON($array)
	  {
	    echo json_encode($array);
	    die();
	  }

		function post(){
			extract($_POST);
			$ret = array(
				'success' => 0,
				'msg' => false
			);
			switch($type)
			{
				case 'tamogatas_form':
			    parse_str($_POST['form'], $form);
					$return = $ret;
			    $return['passed_params'] = $form;
			    $check_aszf = ($_POST['check_aszf'] == 'true') ? true : false;
			    $check_hirlevel = ($_POST['check_hirlevel'] == 'true') ? true : false;
			    $adomanyozo_forma = $form['adomanyozo_forma'];
			    $adomany_tipus = $form['adomany_tipus'];
			    $mode = $_POST['mode'];

			    $return['passed_params']['mode'] = $mode;
			    $return['passed_params']['check_aszf'] = $check_aszf;
			    $return['passed_params']['check_hirlevel'] = $check_hirlevel;

			    // ASZF vizsgálat
			    if( !$check_aszf ) {
			      $return['error']  = 1;
			      $return['msg']    = '<div class="alert alert-danger">A felhasználási és adatvédelmi feltételeket el kell olvasni és el kell fogadni a támogatáshoz!</div>';
			      $this->returnJSON($return);
			    }

			    // Támogatási összeg

			    if( empty($form['cbpay']) ) {
			      $return['error']  = 1;
			      $return['msg']    = '<div class="alert alert-danger">Kérjük, hogy válassza ki a támogatás összegét!</div>';
			      $this->returnJSON($return);
			    }

			    // Támogatási összeg vizsgálat

			    if( $form['cbpay'] == -1 && ( empty($form['othercash']) || $form['othercash'] == '' || $form['othercash'] <= 500) ) {
			      $return['error']  = 1;
			      $return['msg']    = '<div class="alert alert-danger">Egyéb összegű támogatás esetén minimum 500 Ft a támogatási összeg!</div>';
			      $this->returnJSON($return);
			    }

			    // Személyes adatok

			    if( $form['name'] == '' || $form['email'] == '' || $form['telefon'] == '') {
			      $return['error']  = 1;
			      $return['msg']    = '<div class="alert alert-danger">Kérjük, hogy adja meg az adományozóra vonatkozó adatokat: név, email, telefonszám!</div>';
			      $this->returnJSON($return);
			    }

			    // Email validate
			    if ( !filter_var($form['email'], FILTER_VALIDATE_EMAIL) ) {
			      $return['error']  = 1;
			      $return['msg']    = '<div class="alert alert-danger">A megadott e-mail cím nem megfelelő formátumú! Példa: minta@email.hu.</div>';
			      $this->returnJSON($return);
			    }

			    $tamogatas = 0;

			    if ($form['cbpay'] == -1) {
			      $tamogatas = $form['othercash'];
			    } else {
			      $tamogatas = $form['cbpay'];
			    }

			    $session = uniqid();
			    $return['session'] = $session;

			    // OTP simplepay
			    if ( $mode == 'OTPSimple' )
			    {
			      $simple = new Simple();
			      $simple->setOrderId( $session );

			      $simple->setData(array(
			        'nev' => trim($form['name']),
			        'email' => trim($form['email']),
			        'phone' => trim($form['telefon']),
			        'price' => trim($tamogatas),
			        'adomanyozo_forma' => $adomanyozo_forma,
			        'adomany_tipus' => $adomany_tipus,
			        'cim_megye' => trim($form['cim_megye']),
			        'cim_irsz' => trim($form['cim_irsz']),
			        'cim_varos' => trim($form['cim_varos']),
			        'cim_uhsz' => trim($form['cim_uhsz']),
			      ));

			      $missing = $simple->prepare();
			      $return['simplemissing'] = $missing;
			      $btn = $simple->getPayButton();
			    }


			    //$wpdb->show_errors();

			    $this->db->insert(
			      'tamogatok',
			      array(
			        'hashkey' => $session,
			        'paymode' => $mode,
			        'adomany_tipus' => $adomany_tipus,
			        'adomanyozo_forma' => $adomanyozo_forma,
			        'name' => trim($form['name']),
			        'email' => trim($form['email']),
			        'phone' => trim($form['telefon']),
			        'tamogatas' => $tamogatas,
			        'pay_status' => 'START',
			        'hirlevel' => (($check_hirlevel)?1:0),
							'igazolas' => trim($form['igazolas']),
			        'cim_megye' => trim($form['cim_megye']),
			        'cim_irsz' => trim($form['cim_irsz']),
			        'cim_varos' => trim($form['cim_varos']),
			        'cim_uhsz' => trim($form['cim_uhsz']),
			      )
			    );


			    $return['button'] = $btn;
					$return['error'] = 0;

					$this->setSuccess('Fizetés indítása elindulhat!', $return);

					echo json_encode($return);
					return;
				break;
				case 'Calendar':
					$calendar = new Programs(false, array('db' => $this->db));
					$ret['pass'] = $_POST;

					switch($mode){
						case 'syncCalndarItems':
							$arg = array();
							$arg['datestart'] = $_POST['datestart'];
							$arg['dateend'] = $_POST['dateend'];
							$data = $calendar->getCalanderItems( $arg );
							//$ret['data'] = $data;
							$ret['data'] = $data['data'];
							$ret['dates'] = $data['dates'];
						break;
					}

				break;
				case 'log':
					switch($mode){
						case 'searching':
							$this->shop->logSearching($val);
						break;
					}
				break;
				case 'user':
					switch($mode){
						case 'add':
							$err = false;
							try{
								$re = $this->User->add($_POST);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
								$ret['errorCode'] = $e->getCode();
							}

							if(!$err)
							$this->setSuccess('Regisztráció sikeres! Kellemes vásárlást kívánunk!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'login':
							$err = false;
							try{
								$re = $this->User->login($_POST['data']);

								if( $re && $re['remember']){
									setcookie('ajx_login_usr', $re['email'], time() + 60*60*24*3, '/' );
									setcookie('ajx_login_pw', $re['pw'], time() + 60*60*24*3, '/' );
								}else{
									setcookie('ajx_login_usr', null, time() - 3600, '/' );
									setcookie('ajx_login_pw', null , time() -3600, '/' );
								}

							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
								$ret['errorCode'] = $e->getCode();
							}

							if(!$err)
							$this->setSuccess('Sikeresen bejelentkezett!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'resetPassword':
							$err = false;
							try{
								$re = $this->User->resetPassword($_POST['data']);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
								$ret['errorCode'] = $e->getCode();
							}

							if(!$err)
							$this->setSuccess('Új jelszó sikeresen generálva!',$ret);

							echo json_encode($ret);
							return;
						break;
					}
				break;
			}
			echo json_encode($ret); return;
		}

		private function setSuccess($msg, &$ret){
			$ret['msg'] 		= $msg;
			$ret['success'] 	= 1;
			return true;
		}
		private function escape($msg, &$ret){
			$ret['msg'] 		= $msg;
			$ret['success'] 	= 0;
			return true;
		}

		function update () {

			switch ( $this->view->gets[2] ) {
				// Pick Pack Pontok listájának frissítése
				// {DOMAIN}/ajax/update/updatePickPackPont
				/*
				case 'updatePickPackPont':
					$this->model->openLib('PickPackPont',array(
						'database' => $this->model->db,
						'update' => true
					));
				break;
				*/
			}
		}

		function get(){
			extract($_POST);

			switch($type){
				case 'settings':
					$_POST['key'] = ($_POST['key'] != '') ? (array)$_POST['key'] : array();

					if ( empty($_POST['key']) ) {
						$ret['data'] = $this->view->settings;
					} else {
						$settings = array();

						foreach ( $_POST['key'] as $key ) {
							$settings[$key] = $this->view->settings[$key];
						}

						$ret['data'] = $settings;
					}

					$ret['pass'] = $_POST;
					echo json_encode($ret);
				break;
				case 'cartInfo':
					$mid 	= Helper::getMachineID();
					$cart 	= new Cart($mid, array( 'db' => $this->db, 'user' => $this->User->get(), 'settings' => $this->view->settings ));
					echo json_encode($cart->get());
				break;
			}

			$this->view->render(__CLASS__.'/'.__FUNCTION__.'/'.$type, true);
		}

		function box(){
			extract($_POST);

			switch($type){
				case 'recall':
					$this->view->t = $this->shop->getTermekAdat($tid);
				break;
				case 'askForTermek':
					$this->view->t = $this->shop->getTermekAdat($tid);
				break;
				case 'map':
					$shop = new CasadaShop( (int)$tid, array(
						'db' => $this->db
					));

					$this->out('shop',$shop);
				break;
			}

			$this->view->render(__CLASS__.'/'.__FUNCTION__.'/'.$type, true);
		}

		function __destruct(){
		}
	}

?>
