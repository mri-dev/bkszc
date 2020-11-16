<?
namespace PortalManager;

use PortalManager\Formater;
use ShopManager\Categories;

/**
* class DolgozokLista
* @package PortalManager
* @version v1.0
*/
class DolgozokLista
{
  const DB = 'dolgozok';
  const DBVIEW = 'cikk_views';
  const DBVIEWHISTORY = 'cikk_view_history';

	private $db = null;
	public $tree = false;
	private $max_page = 1;
	private $current_page = 1;
	private $current_item = false;
	private $current_get_item = false;
	private $tree_steped_item = false;
	public $tree_items = 0;
	private $walk_step = 0;
	private $selected_id = false;
	private $item_limit_per_page = 50;
	public $sitem_numbers = 0;

	function __construct( $id = false, $arg = array() )
	{
		$this->db = $arg['db'];
		if ( $id ) {
			$this->selected_id = $id;
		}
	}

	public function get( $id )
	{
		$data = array();
		$qry = "SELECT
		  d.*
		FROM ".self::DB." as d ";
    $qry .= " WHERE d.ID = ".$id;
    $qry = $this->db->query($qry);   

    $this->current_get_item = $qry->fetch(\PDO::FETCH_ASSOC);
    
		return $this;
	}

	public function add( $data )
	{
		$nev 	= ($data['nev']) ?: false;
    $kep 	= ($data['profilkep']) ?: NULL;
		$tantargyak = ($data['tantargyak']) ?: NULL;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;

		if (!$nev) { throw new \Exception("Kérjük, hogy adja meg az <strong>Dolgozó nevét</strong>!"); }

    $upd = array(
      'nev' => addslashes($nev),
      'profilkep' => $kep,
      'tantargyak' => addslashes($tantargyak),
      'lathato' => $lathato
    );

		$this->db->insert(
			self::DB,
      $upd
		);

		$id = $this->db->lastInsertId();

		return $id;
	}

	public function save( $data )
	{ 
    $ret = array(
      'success' => 0,
      'messages' => []
    );

    $nev 	= ($data['nev']) ?: false;
    $kep 	= ($data['profilkep']) ?: NULL;
		$tantargyak = ($data['tantargyak']) ?: NULL;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;

		if (!$nev) { throw new \Exception("Kérjük, hogy adja meg az <strong>Dolgozó nevét</strong>!"); }

    $upd = array(
      'nev' => addslashes($nev),
      'profilkep' => $kep,
      'tantargyak' => addslashes($tantargyak),
      'lathato' => $lathato
    );


		$this->db->update(
			self::DB,
			$upd,
			sprintf("ID = %d", $this->selected_id)
		);

    $ret['success'] = 1;
    $ret['messages'][] = '<strong>Sikeresen mentésre kerültek a dolgozó adatai.</strong>';
    
    return $ret;
	}

	public function delete( $id = false )
	{
		$del_id = ($id) ?: $this->selected_id;

		if ( !$del_id ) return false;

		$this->db->query(sprintf("DELETE FROM ".self::DB." WHERE ID = %d", $del_id));
	}
  
  public function getTree( $arg = array() )
	{
		$tree 		= array();

		if ( $arg['limit'] ) {
			if( $arg['limit'] > 0 ) {
				$this->item_limit_per_page = ( is_numeric($this->item_limit_per_page) && $this->item_limit_per_page > 0) ? (int)$arg['limit'] : $this->item_limit_per_page;
			} else if( $arg['limit'] == -1 ){
				$this->item_limit_per_page = 999999999999;
			}
    }
    
		$qry = "
			SELECT SQL_CALC_FOUND_ROWS
				d.*
			FROM dolgozok as d
			WHERE d.ID IS NOT NULL ";

		if( $arg['except_id'] ) {
			$qry .= " and d.ID != ".$arg['except_id'];
		}

    if( isset($arg['hide_offline']) && !empty($arg['hide_offline']) ) {
      $qry .= " and d.lathato = 1";
    }

    if( isset($arg['lathato']) ) {
      $qry .= " and d.lathato = ".(int)$arg['lathato'];
    }

		if( isset($arg['in_id']) && !empty($arg['in_id']) ) {
      $qry .= " and d.ID IN(".implode(",", (array)$arg['in_id']).")";
    }

    // Keresés
    if ( isset($arg['search']) && !empty($arg['search']) )
    {
      $src = '';

      if ( $arg['search']['text'] == '' ) {
        $src .= ' and 2=1';
      } else {
        switch ($arg['search']['method'])
        {
          // bármilyen szóra
          case 'ee':
            $xtext = explode(" ", trim($arg['search']['text']));
            $src .= ' and (';
            foreach ((array)$xtext as $xt) {
              $src .= "(h.nev LIKE '%".trim($xt)."%' or h.tantargyak LIKE '%".trim($xt)."%') or ";
            }
            $src = rtrim($src, ' or ');
            $src .= ')';
          break;
          case 'ae':
            $xtext = explode(" ", trim($arg['search']['text']));
            $src .= ' and (';
            foreach ((array)$xtext as $xt) {
              $src .= "(h.nev LIKE '%".trim($xt)."%' or h.tantargyak LIKE '%".trim($xt)."%') and ";
            }
            $src = rtrim($src, ' and ');
            $src .= ')';
          break;
          // Alap és teljes szöveg
          default: case 'ft':
            $src .= " and (h.nev LIKE '%".$arg['search']['text']."%' or h.tantargyak LIKE '%".$arg['search']['text']."%')";
          break;
        }
      }

      $qry .= $src;
		}


		if( $arg['order'] ) {
      if ($arg['order'] == 'in_id') {
        $qry .= " ORDER BY FIELD(d.ID, ".implode(",", $arg['in_id']).")";
      } else {
        $qry .= " ORDER BY ".$arg['order']['by']." ".$arg['order']['how'];
      }
		} else {
			$qry .= " ORDER BY d.nev ASC ";
		}

		// LIMIT
		$current_page = ($arg['page'] ?: 1);
		$start_item = $current_page * $this->item_limit_per_page - $this->item_limit_per_page;
		$qry .= " LIMIT ".$start_item.",".$this->item_limit_per_page.";";

    //echo $qry;

		$top_news_qry 	= $this->db->query($qry);
		$top_page_data 	= $top_news_qry->fetchAll(\PDO::FETCH_ASSOC);

		$this->sitem_numbers = $this->db->query("SELECT FOUND_ROWS();")->fetch(\PDO::FETCH_COLUMN);

		$this->max_page = ceil($this->sitem_numbers / $this->item_limit_per_page);
		$this->current_page = $current_page;

		if( $top_news_qry->rowCount() == 0 ) return $this;

		foreach ( $top_page_data as $top_page ) {
			$this->tree_items++;
			$this->tree_steped_item[] = $top_page;

			$tree[] = $top_page;
		}

		$this->tree = $tree;

		return $this;
	}

	public function has_items()
	{
		return ($this->tree_items === 0) ? false : true;
	}

	/**
	 * Végigjárja az összes Hírt, amit betöltöttünk a getTree() függvény segítségével. while php függvénnyel
	 * járjuk végig. A while függvényen belül használjuk a the_news() objektum függvényt, ami az aktuális Hír
	 * adataiat tartalmazza tömbbe sorolva.
	 * @return boolean
	 */
	public function walk()
	{
		if( !$this->tree_steped_item ) return false;

		$this->current_item = $this->tree_steped_item[$this->walk_step];
		$this->current_get_item = $this->current_item;

		$this->walk_step++;

		if ( $this->walk_step > $this->tree_items ) {
			// Reset Walk
			$this->walk_step = 0;
			$this->current_item = false;

			return false;
		}

		return true;
	}

	public function getWalkInfo()
	{
		return array(
			'walk_step' => $this->walk_step,
			'tree_steped_item' => $this->tree_steped_item,
			'tree_items' => $this->tree_items,
			'current_item' => $this->current_item,
		);
	}

	public function the_item()
	{
		return $this->current_item;
	}

	public static function textRewrites( $text )
	{
		// Kép
		$text = str_replace( '../../../src/uploads/', UPLOADS, $text );
		$text = str_replace( '/system/imagemanager/files/', UPLOADS, $text );

		return $text;
	}

	/*===============================
	=            GETTERS            =
	===============================*/

	public function getFullData()
	{
		return $this->current_get_item;
	}

  public function getValue( $key )
  {
    return (isset($this->current_get_item[$key])? $this->current_get_item[$key] : false);
  }

	public function getImage( $url = false )
	{
		if ( $url ) {
      if ($this->current_get_item['profilkep'] == '') {
        return false;
      }
			return UPLOADS . str_replace('/src/uploads/','',$this->current_get_item['profilkep']);
		} else {
			return $this->current_get_item['profilkep'];
		}
	}
	public function getId()
	{
		return $this->current_get_item['ID'];
  }
  
	public function getName()
	{
		return $this->current_get_item['nev'];
	}
	public function getPreferencia()
	{
		return $this->current_get_item['tantargyak'];
  }
  
  public function getOsztaly()
	{
		return (!empty($this->current_get_item['osztaly'])) ? $this->current_get_item['osztaly'] : false;
	}

	public function getVisibility()
	{
		return ($this->current_get_item['lathato'] == 1 ? true : false);
	}
	
	public function getMaxPage()
	{
		return $this->max_page;
	}
	public function getCurrentPage()
	{
		return $this->current_page;
	}
  
	/*-----  End of GETTERS  ------*/
	public function __destruct()
	{
		$this->db = null;
		$this->tree = false;
		$this->max_page = 1;
		$this->current_page = 1;
		$this->current_item = false;
		$this->current_get_item = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
		$this->selected_id = false;
		$this->item_limit_per_page = 50;
		$this->sitem_numbers = 0;
	}
}
?>
