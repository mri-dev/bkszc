<?php
namespace PortalManager;

class Redirectors
{
	const TABLE 		= "redirector";

	private $db 		= null;

	public function __construct( $arg = array() )
	{
		$this->db 	= $arg['db'];

		return $this;
	}

	public function get( $id )
	{
		if( !$id ) return false;

		return $this->db->squery("SELECT * FROM ".self::TABLE." WHERE ID = :id", array( 'id' => $id ))->fetch(\PDO::FETCH_ASSOC);
	}

	public function create( $post )
	{
		extract($post);

		if ( empty($site) ) {
			throw new \Exception("Kérjük, hogy válassza ki a site-ot.");
		}
		if ( empty($watch) ) {
			throw new \Exception("[Indító URL]: Kérjük, hogy határozza meg azt az elérési utat, amit át szeretne irányítani.");
		}
		if ( empty($redirect_to) ) {
			throw new \Exception("[Átirányítási cél URL]: Kérjük, hogy határozza meg azt az elérési utat, ahova át szeretné irányítani az indító URL-t.");
		}

		$this->db->insert(
			self::TABLE,
			$post
		);
	}

	public function delete( $id )
	{
		if( !$id ) return false;
		$this->db->squery("DELETE FROM ".self::TABLE." WHERE ID = :id", array('id' => $id));
	}

	public function edit( $id, $post )
	{
		if( !$id ) return false;
		extract($post);

		if ( empty($site) ) {
			throw new \Exception("Kérjük, hogy válassza ki a site-ot.");
		}
		if ( empty($watch) ) {
			throw new \Exception("[Indító URL]: Kérjük, hogy határozza meg azt az elérési utat, amit át szeretne irányítani.");
		}
		if ( empty($redirect_to) ) {
			throw new \Exception("[Átirányítási cél URL]: Kérjük, hogy határozza meg azt az elérési utat, ahova át szeretné irányítani az indító URL-t.");
		}

		$this->db->update(
			self::TABLE,
			$post,
			"ID = ".$id
		);
	}

	public function getList( $arg = array() )
	{
		$list = array();

		$q = "SELECT * FROM ".self::TABLE." ORDER BY site ASC";

		$arg['multi'] = true;

		extract($this->db->q($q, $arg));

		return $ret;
	}

	public function __destruct()
	{
		$this->db 		= null;
	}
}
