<?
namespace Applications;

class Simple
{
	private $config 			= array();
	private $live 				= null;
	private $currency 			= 'HUF';
	private $order_id 			= null;
	private $datas 				= null;
	private $utanvet 			= false;
	private $discount 			= 0;
	private $transport_price 	= 0;

	private $pay_form = null;

	function __construct() {
		require_once "simplesdk/config.php";
		require_once "simplesdk/SimplePayV21.php";

		$this->config = $config;

		return $this;
	}

	public function ipnConfirm( )
	{
		$headers = getallheaders();
		$json = file_get_contents('php://input');

		$this->live = new \SimplePayIpn;
		$this->live->addConfig( $this->config );

		if ($this->live->isIpnSignatureCheck($json, $headers['signature'])) {
    	$this->live->runIpnConfirm();
		}
	}

	public function setFinish( $arr = array() )
	{
		$this->live = new \SimplePayFinish;
		$this->live->addConfig( $this->config );

		if (isset($arr['orderRef'])) {
		  $this->live->addData('orderRef', $arr['orderRef']);
		}

		if (isset($arr['transactionId'])) {
		  $this->live->addData('transactionId', $arr['transactionId']);
		}

		if (isset($arr['merchant'])) {
		  $this->live->addConfigData('merchantAccount', $arr['merchant']);
		}

		$this->live->transactionBase['currency'] = 'HUF';

		$this->live->runFinish();
	}

	public function getBackResult()
	{
		$this->live = new \SimplePayBack;
		$this->live->addConfig( $this->config );

		$result = array();

		$r = str_replace(' ', '+', $_REQUEST['r']);
		$s = str_replace(' ', '+', $_REQUEST['s']);

		if (isset($r) && isset($s)) {
	    if ($this->live->isBackSignatureCheck($r, $s)) {
	      $result = $this->live->getRawNotification();
	    }
		}

		return $result;
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function prepare()
	{
		$this->live = new \SimplePayStart;

		$this->live->addData( 'currency', $this->currency );
		$this->live->addConfig( $this->config );

		$this->live->addItems(array(
        'ref' => 'TAM_ALAPITVANY_'.date('Y'),
        'title' => 'Online támogatás: gundeliskola.hu.',
        'description' => 'Támogatás a gundeliskola.hu oldalon.',
        'amount' => '1',
        'price' => $this->datas[price],
        'tax' => '0',
    ));

		$adomany_forma = $this->datas['adomanyozo_forma'];

		$this->live->addData('orderRef', $this->order_id);
		$this->live->addData('customer', $this->datas[nev]);
		$this->live->addData('customerEmail', $this->datas[email]);
		$this->live->addData('language', 'HU');

		if ($adomany_forma == 'Cég/Szervezet') {
			$this->live->addGroupData('invoice', 'company', $this->datas[nev]);
		} else {
			$this->live->addGroupData('invoice', 'name', $this->datas[nev]);
		}
		$this->live->addGroupData('invoice', 'country', 'hu');
		$this->live->addGroupData('invoice', 'state', $this->datas[cim_megye]);
		$this->live->addGroupData('invoice', 'city', $this->datas[cim_varos]);
		$this->live->addGroupData('invoice', 'zip', $this->datas[cim_irsz]);
		$this->live->addGroupData('invoice', 'address', $this->datas[cim_uhsz]);
		//$this->live->addGroupData('invoice', 'address2', '');
		$this->live->addGroupData('invoice', 'phone', $this->datas[phone]);

		$timeoutInSec = 600;
		$timeout = @date("c", time() + $timeoutInSec);
		$this->live->addData('timeout', $timeout);
		$this->live->addData('methods', array('CARD'));
		$this->live->addData('url', $this->config['URL']);
		$this->live->formDetails['element'] = 'button';
		$this->live->formDetails['elementText'] = 'Fizetés OTP Simple-lel >';
		$this->live->runStart();
		$this->live->getHtmlForm('Fizetés');
		$this->pay_form = $this->live->returnData['form'];
	}

	public function getPayButton()
	{
		return $this->pay_form;
	}

	/** SETTERS **/
	public function setTransportPrice( $price )
	{
		$this->transport_price = $price;
	}
	public function setDiscount( $price )
	{
		$this->discount = $price;
	}
	public function setUtanvet( $flag )
	{
		$this->utanvet = $flag;
	}
	public function setOrderId($id)
	{
		$this->order_id = $id;
		return $this;
	}
	public function setData($data)
	{
		$this->datas = $data;
		return $this;
	}
	public function setMerchant($currency, $merchant )
	{
		$this->config[$currency.'_MERCHANT'] = $merchant;
		return $this;
	}

	public function setSecretKey($currency, $key )
	{
		$this->config[$currency.'_SECRET_KEY'] = $key;
		return $this;
	}

	public function setPayMethod( $method )
	{
		$this->config['METHOD'] = $method;
		return $this;
	}

	public function getConfig( )
	{
		return $this->config;
	}

}
?>
