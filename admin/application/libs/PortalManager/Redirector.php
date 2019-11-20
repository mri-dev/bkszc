<?php
namespace PortalManager;

class Redirector
{
	const TABLE 		= "redirector";

	private $db 		= null;
	private $get 		= false;
	private $site 		= false;
	private $has_red 	= false;
	private $target_url = '';

	public function __construct( $site = false, $get = false, $arg = array() )
	{
		$this->db 	= $arg['db'];
		$this->get 	= $get;
		$this->site = $site;

		return $this;
	}

	public function start()
	{
		if ( !$this->site) return false;
		if ( !$this->get) return false;

		// Reset
		$this->has_red 		= false;
		$this->target_url 	= '';
		$redirect 			= false;

		// Load
		$redirect = $this->geturl();

		// Feldolgoz
		if ($redirect)
		{
			$this->has_red 		= true;
			$this->target_url 	= $redirect;
		}
	}


	public function hasRedirect()
	{
		return $this->has_red;
	}

	public function redirect()
	{
		if (strpos($this->target_url, 'http://') === 0) {
			return $this->target_url;
		}

		if (strpos($this->target_url, 'https://') === 0) {
			return $this->target_url;
		}

		$to = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->target_url;

		return $to;
	}

	public function getGET()
	{
		return $this->get;
	}

	public function __destruct()
	{
		$this->db 		= null;
		$this->has_red 	= false;
		$this->target_url = '';
	}

	private function geturl()
	{
		$trimmed = rtrim($this->getGET(),'/');
		$trim_add = $trimmed . '/';
		$q = "SELECT redirect_to FROM ".self::TABLE." WHERE 1=1 and site = '".$this->site."' and (watch='".$this->getGET()."' or watch='".$trimmed."' or watch='".$trim_add."') LIMIT 0,1";
		return $this->db->query($q)->fetchColumn();
	}
}
