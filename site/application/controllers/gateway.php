<?
use Applications\Simple;
use Applications\Cetelem;
use MailManager\Mailer;
use PortalManager\Template;
use PortalManager\Request;
use PortalManager\Admin;
use PortalManager\Traffic;

class gateway extends Controller
{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = '';


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

		function test()
		{
			$this->hidePatern = true;

			$this->Admin = new Admin( false, array( 'db' => $this->db ));
			switch ( $this->view->gets[2] ) {
				case 'img':
					//echo realpath(__FILE__);
					$this->Admin->autoProductImageConnecter( array( 'image_path' => '../../admin/src/products/all' ));
				break;
			}
		}
		/**
		 * WEBSHOP API
		 */
		function api() {
			$this->hidePatern = true;
			$error = false;
			$result = array(
				"error" => 0,
				"msg" => ""
			);

			$valid_commands = array( 'articleUpdate', 'saleReport', 'webshopSale', 'inventory' );

			$postjson 	= file_get_contents('php://input');
			$api 		= json_decode( urldecode($postjson) );

			if( !$error )
				if ( !$api ) {
					$error = "Hibás JSON kérés. Kérjük, hogy ellenőrízze a struktúrát!";
				} else {
					$error = false;
				}

			if( !$error )
				if ( $api->command == "" ) {
					$error = "Ismeretlen művelet nem végrehajtható!";
				} else {
					$error = false;
				}

			if( !$error )
				if ( !in_array( $api->command, $valid_commands ) ) {
					$error = "command=".$api->command . ": művelet nem engedélyezett!";
				} else {
					$error = false;
				}

			if ( !$error ) {
				switch ( $api->command ) {
					// Megrendelés értesítő visszaigazolás
					case 'saleReport':

					break;
					// Termék raktárkészlet frissítés
					case 'inventory':



					break;
					// Termék frissítés
					case 'articleUpdate':
						/**
						 * Termék fő adatok
						 * - articleid
						 * - name
						 * - number
						 * - description
						 * */
						$prod_data = $api->parameters->article;

						/**
						 * Variációk a termékeknek
						 * - variantid
						 * - color_number
						 * - color_name
						 * - size
						 * - netprice
						 * */
						$variants = $api->parameters->variants;

						/**
						 * Hibaüzenet frissítés során
						 * */
						$error = $api->parameters->error;

						$inserted = 0;

						if( count($variants) > 0 ) {
							foreach ( $variants as $va ) {
								$netto 	= 0;
								$brutto = 0;

								$netto = (int)$va->netprice;

								if ( $netto <= 0 || !$netto ) {
									$netto = 0;
								}

								$brutto = $netto * 1.27;

								if ( $netto != 0 ) {

									$update_data = array();

									$update_data['netto_ar'] = $netto;
									$update_data['brutto_ar'] = $brutto;

									if ( $prod_data->name ) 		$update_data['nev'] = $prod_data->name;
									if ( $prod_data->description ) 	$update_data['rovid_leiras'] = $prod_data->description;
									if ( $va->color_number) 		$update_data['szin_kod'] = $va->color_number;
									if ( $va->color_name ) 			$update_data['szin'] = $va->color_name;
									if ( $va->size ) 				$update_data['meret'] = $va->size;


									$check_usage = $this->db->query( sprintf("SELECT 1 FROM shop_termekek WHERE raktar_articleid = %d and raktar_variantid = %d;", $prod_data->articleid, $va->variantid))->rowCount();

									try {

										if ( $check_usage !== 0 ) {
											$this->db->update(
												'shop_termekek',
												$update_data,
												sprintf("raktar_articleid = %d and raktar_variantid = %d", $prod_data->articleid, $va->variantid )
											);
										} else {
											$inserted++;
											$update_data['raktar_articleid'] 	= $prod_data->articleid;
											$update_data['raktar_variantid'] 	= $va->variantid;
											$update_data['raktar_number'] 		= $prod_data->number;
											$update_data['cikkszam']			= $prod_data->articleid.'-'.$va->variantid;
											$update_data['kulcsszavak'] 		= $prod_data->name . ' '. str_replace(array( ' / ', ', ', ',' ), ' ', $va->color_name ) . ' ' . $va->size;
											// Alapértelmezett márka
											$update_data['marka'] = $this->view->settings['alapertelmezett_marka'];
											// Alapértelmezett termék állapot
											$update_data['keszletID'] = $this->view->settings['alapertelmezett_termek_allapot'];
											// Alapértelmezett szállítási idő
											$update_data['szallitasID'] = $this->view->settings['alapertelmezett_termek_szallitas'];
											$update_data['lathato'] = 0;

											$ins_data = array();
											foreach ( $update_data as $d ) {
												$ins_data[] = $d;
											}

											$this->db->insert(
												'shop_termekek',
												array_combine(
													array_keys($update_data),
													$ins_data
												)
											);
										}

									} catch (Exception $e) {
										$error = $e->getMessage();
									}

								}
							}

							if( $inserted > 0 ){
								// Értesítő e-mail új termékek létrehozásáról
								$mail = new Mailer(
									$this->view->settings['page_title'],
									$this->view->settings['email_noreply_address'],
									$this->view->settings['mail_sender_mode']
								);
								$mail->add( $this->view->settings['alert_email'] );
								$arg = array(
									'settings' 		=> $this->view->settings,
									'infoMsg' 		=> 'Ezt az üzenetet a rendszer küldte. Kérjük, hogy ne válaszoljon rá!',
									'new_items' 	=> $inserted,
									'source_str_json' => $postjson
								);
								$mail->setSubject( 'API értesítő: új termékek kerültek a webáruházba' );
								$mail->setMsg( (new Template( VIEW . 'templates/mail/' ))->get( 'admin_api_newproducts', $arg ) );
								$re = $mail->sendMail();
							}
						}

					break;
				}
			}

			/**
			 * RESPONSE
			 */
			if ( $error ) {
				$result['sended_json'] = $postjson;
				$result['error'] = 1;
				$result['msg'] = $error;


			}

			$result_json = json_encode( $result, JSON_UNESCAPED_UNICODE );

			try {
				$this->db->insert(
					"api_request",
					array_combine(
						array( "command","referencia","datum","parancs_json","valasz_json", "post_json", "get_json" ),
						array( $api->command, $_SERVER['HTTP_REFERER'], date('Y-m-d'), urldecode($postjson), $result_json, json_encode($_POST, JSON_UNESCAPED_UNICODE), json_encode($_GET, JSON_UNESCAPED_UNICODE) )
					)
				);
			}catch(\Exception $e){
				echo $e->getMessage();
			}


			header('Contant-Type: application/json');

			echo $result_json;

		}

		/**
		 * OTP SIMPLE FIZETÉSI RENDSZER
		 * Feldolgozó egységek
		 * */
		function simple()
		{
			switch ( $this->view->gets[2] ) {
				case 'ipn':
					$this->hidePatern = true;
					header('Content-Type: text/html; charset=utf-8');

					//Import config data
					require $_SERVER['DOCUMENT_ROOT'].'/admin/application/libs/Applications/simplesdk/config.php';
					require $_SERVER['DOCUMENT_ROOT'].'/admin/application/libs/Applications/simplesdk/SimplePayV21.php';

					$json = file_get_contents('php://input');

					$trx = new \SimplePayIpn;
					$trx->addConfig($config);
					if ($trx->isIpnSignatureCheck($json)) {
					  $trx->runIpnConfirm();
					  $confirm = $trx->getIpnConfirmContent();
					  if ($confirm) {
					    // code...
					  }
					}
				case 'idn': break;
				case 'backref': break;
				case 'timeout': break;
				default:
					$this->simple = new Simple();
					$trans = $this->simple->getBackResult();

					if ( !empty($trans['o']) ) {
						$pay_done = 0;
						if ($trans['e'] == 'SUCCESS') {
							$pay_done = 1;
							$this->db->update(
								'tamogatok',
								array(
									'pay_transactionid' => $trans['t'],
									'pay_status' => $trans['e'],
									'pay_done' => $pay_done
								 ),
								 sprintf("hashkey = '%s'", $trans['o'])
							);
						} else {
							$this->db->query("DELETE FROM tamogatok WHERE hashkey = '{$trans['o']}'");
						}

						switch ($trans['e']) {
							case 'SUCCESS':
								$class = 'pay-success';
								$title_status = 'Sikeres tranzakció.';
								$desc = 'Köszönjük támogatását, melyet az OTP Simple rendszerével fizetett ki!';
								$simple_trans = 'SimplePay tranzakció azonosító: <strong>'.$trans['t'].'</strong>';

								// email
								$check_alert = $this->db->query("SELECT * FROM tamogatok WHERE hashkey = '{$trans['o']}'");

								if ( $check_alert->rowCount() != 0 )
								{
									$form = $check_alert->fetch(\PDO::FETCH_ASSOC);

									if ((int)$form['admin_alerted'] == 0) {
										// E-mail küldés az adminnak
										$mail = new Mailer( $this->view->settings['page_title'], SMTP_USER, $this->view->settings['mail_sender_mode'] );
										$mail->add( $this->view->settings['alert_email'] );
										$arg = array(
											'settings' 		=>$this->view->settings,
											'infoMsg' 		=> 'Ezt az üzenetet a rendszer küldte. Kérjük, hogy ne válaszoljon rá!',
											'hashkey' => $form['hashkey'],
											'paymode' => $form['paymode'],
											'adomany_tipus' => $form['adomany_tipus'],
											'adomanyozo_forma' => $form['adomanyozo_forma'],
											'name' => trim($form['name']),
											'email' => trim($form['email']),
											'phone' => trim($form['phone']),
											'tamogatas' => $form['tamogatas'],
											'igazolas' => trim($form['igazolas']),
											'cim_megye' => trim($form['cim_megye']),
											'cim_irsz' => trim($form['cim_irsz']),
											'cim_varos' => trim($form['cim_varos']),
											'cim_uhsz' => trim($form['cim_uhsz']),
										);
										$mail->setSubject( 'Új alapítványi támogatás: '.trim($form['name']).' - '. \Helper::cashFormat($form['tamogatas']).' Ft');
										$mail->setMsg( (new Template( VIEW . 'templates/mail/' ))->get( 'admin_tamogatas', $arg ) );
										$re = $mail->sendMail();
										$this->db->update(
											'tamogatok',
											array(
												'admin_alerted' => 1,
											 ),
											 sprintf("hashkey = '%s'", $trans['o'])
										);
										// E: E-mail küldés az adminnak
									}
								}

							break;

							case 'CANCEL':
								$class = 'pay-cancel';
								$title_status = 'Ön megszakította a fizetést.';
								//$desc = 'Kérjük, ellenőrizze a tranzakció során megadott adatok helyességét. Amennyiben minden adatot helyesen adott meg, a visszautasítás okának kivizsgálása érdekében kérjük, szíveskedjen kapcsolatba lépni kártyakibocsátó bankjával.';
								//$simple_trans = 'SimplePay tranzakció azonosító: <strong>'.$trans['t'].'</strong>';
							break;

							case 'FAIL':
								$class = 'pay-fail';
								$title_status = 'Sikertelen tranzakció.';
								$desc = 'Kérjük, ellenőrizze a tranzakció során megadott adatok helyességét. Amennyiben minden adatot helyesen adott meg, a visszautasítás okának kivizsgálása érdekében kérjük, szíveskedjen kapcsolatba lépni kártyakibocsátó bankjával.';
								$simple_trans = 'SimplePay tranzakció azonosító: <strong>'.$trans['t'].'</strong>';
							break;

							case 'TIMEOUT':
								$class = 'pay-timeout';
								$title_status = 'Ön túllépte a tranzakció elindításának lehetséges maximális idejét.';
								//$desc = 'Kérjük, ellenőrizze a tranzakció során megadott adatok helyességét. Amennyiben minden adatot helyesen adott meg, a visszautasítás okának kivizsgálása érdekében kérjük, szíveskedjen kapcsolatba lépni kártyakibocsátó bankjával.';
								//$simple_trans = 'SimplePay tranzakció azonosító: <strong>'.$trans['t'].'</strong>';
							break;
						}

						$this->out('class', $class);
						$this->out('title_status', $title_status);
						$this->out('desc', $desc);
						$this->out('simple_trans', $simple_trans);

					} else {
						header("Location: /"); exit;
					}
				break;
			}
		}

		/**
		 * CETELEM ÁRUHITEL API
		 * */
		public function cetelem()
		{
			// Cetelem API
			$cetelem = (new Cetelem( $this->view->settings['cetelem_shopcode'], $this->view->settings['cetelem_society'], $this->view->settings['cetelem_barem'], array( 'db' => $this->db ) ))->sandboxMode( CETELEM_SANDBOX_MODE );

			// Log
			$this->db->insert(
				'gateway_cetelem_ipn',
				array(
					'megrendeles' => $this->view->gets[3],
					'statusz' => $this->view->gets[2],
					'datastr' => json_encode($_REQUEST)
				)
			);

			// Megrendelés adatok
			$this->view->order 		= $this->shop->getOrderData($this->view->gets[3]);

			switch ($this->view->gets[2])
			{
				// Tranzakció elkezdése
				case 'start':

					$this->view->szamlazas	= json_decode($this->view->order['szamlazasi_keys'], true);

					$name = explode(" ", $this->view->szamlazas['nev']);

					$data = array(
						'firstName' => $name[0],
						'lastName' 	=> $name[1],
						'pcode' 	=> $this->view->szamlazas[irsz],
						'city' 		=> $this->view->szamlazas[city],
						'address' 	=> $this->view->szamlazas[uhsz],
						'email'  	=> $this->view->order[email],
						'articleId' => 335,
					);

					$datalist = $cetelem->prepareDataJSON($this->view->gets[3], $data);

					$total = 0;

					if(is_array($this->view->order[items]))
					foreach ( $this->view->order[items] as $i )
					{
						$total += $i[subAr];
					}

					if($this->view->order['kedvezmeny'] > 0) {
						$total -= $this->view->order['kedvezmeny'];
					}

					$cetelem->startTransaction($total, $datalist);

				break;
			}

		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
