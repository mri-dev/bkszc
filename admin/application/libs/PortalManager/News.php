<?
namespace PortalManager;

use PortalManager\Formater;
use ShopManager\Categories;

/**
* class News
* @package PortalManager
* @version v1.0
*/
class News
{
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
	private $selected_news_id = false;
	private $item_limit_per_page = 50;
	public $sitem_numbers = 0;
  public $tematic_cikk_slugs = array('boltok', 'intezmenyek', 'vendeglatas', 'turizmus', 'szolgaltatasok', 'latnivalok', 'tura-utvonal');

	function __construct( $news_id = false, $arg = array() )
	{
		$this->db = $arg['db'];
		if ( $news_id ) {
			$this->selected_news_id = $news_id;
		}
    $this->categories = new Categories(array('db' => $this->db));
	}

	public function get( $news_id_or_slug )
	{
		$data = array();
		$qry = "SELECT
			h.*,
		(SELECT count(uid) FROM ".self::DBVIEW." WHERE prog_id = h.ID) as uvisit
		FROM hirek as h ";

		if (is_numeric($news_id_or_slug)) {
			$qry .= " WHERE h.ID = ".$news_id_or_slug;
		}else {
			$qry .= " WHERE h.eleres = '".$news_id_or_slug."'";
		}

		$qry = $this->db->query($qry);

		$this->current_get_item = $qry->fetch(\PDO::FETCH_ASSOC);

    $this->preparePublicDates($this->current_get_item);

		return $this;
	}

	public function add( $data )
	{
		$cim 	= ($data['cim']) ?: false;
		$eleres = ($data['eleres']) ?: false;
		$szoveg = ($data['szoveg']) ?: NULL;
    $kep 	= ($data['belyegkep']) ?: NULL;
		$bevezeto = ($data['bevezeto']) ?: NULL;
		$content_after_szoveg = ($data['content_after_szoveg']) ?: NULL;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;
		$archiv = ($data['archiv']) ? 1 : 0;
		$sorrend = ($data['sorrend']) ? (int)$data['sorrend'] : 100;
    $archivalva = NULL;
    $optional = $data['optional'];
    $optional_data = array();
    $forrasinfo = ($data['forrasinfo']) ?: NULL;

		if (!$cim) { throw new \Exception("Kérjük, hogy adja meg az <strong>Cikk címét</strong>!"); }

		if (!$eleres) {
			$eleres = $this->checkEleres( $cim );
		}

    // Optional
    if ($optional && !empty($optional)) {
      foreach ((array)$optional as $key => $value) {
        if ($value != '') {
          $optional_data[$key] = (is_string($value)) ? addslashes($value) : $value;
        }
      }
    }

    // new downloads
    if (isset($data['newdownloads']) && $data['newdownloads']['name'][0] != '')
    {
      $dli = -1;
      foreach ( (array)$data['newdownloads']['name'] as $dl )
      {
        $dli++;
        $name = $data['newdownloads']['name'][$dli];
        $file_tmp = $_FILES['downloads']['tmp_name']['file'][$dli];
        $file_err = $_FILES['downloads']['error']['file'][$dli];
        $file_name = uniqid().'_'.basename($_FILES['downloads']['name']['file'][$dli]);

        if ( !empty($name) && !empty($file_tmp) && $file_err == \UPLOAD_ERR_OK ) {
          if(move_uploaded_file( $file_tmp, 'src/uploaded_files/'.$file_name )){
            usleep(500);
            $data['downloads']['name'][] = $name;
            $data['downloads']['file'][] = 'src/uploaded_files/'.$file_name;
          }
        }
      }
    }

    $downloads_raw = $this->prepareRAWDownloads($data['downloads']);
    $downloads = ($downloads_raw) ? serialize($downloads_raw) : NULL;

    $upd = array(
      'cim' => $cim,
      'hashkey' => md5(uniqid()),
      'belyeg_kep' => $kep,
      'eleres' => $eleres,
      'szoveg' => addslashes($szoveg),
      'bevezeto' => addslashes($bevezeto),
      'content_after_szoveg' => addslashes($content_after_szoveg),
      'linkek' => $downloads,
      'idopont' => NOW,
      'letrehozva' => NOW,
      'lathato' => $lathato,
      'optional_contacts' => ($optional_data['contacts']) ? json_encode($optional_data['contacts'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_nyitvatartas' => ($optional_data['nyitvatartas']) ? json_encode($optional_data['nyitvatartas'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_maps' => ($optional_data['maps'] != '') ? $optional_data['maps'] : NULL,
      'optional_logo' => ($optional_data['logo'] != '') ? $optional_data['logo'] : NULL,
      'optional_firstimage' => ($optional_data['firstimage'] != '') ? $optional_data['firstimage'] : NULL,
      'sorrend' => $sorrend,
      'forrasinfo' => $forrasinfo,
    );

		$this->db->insert(
			"hirek",
      $upd
		);

		$id = $this->db->lastInsertId();

    // Check archivalás
    if ($archiv == 1) {
      $prearch = (int)$this->db->squery("SELECT archiv FROM hirek WHERE ID = :id", array('id' => $id))->fetchColumn();
      if ($prearch == 0) {
        $archivalva = NOW;
        $upd['archivalva'] = $archivalva;
        $this->db->update(
    			"hirek",
    			array(
            'archivalva' => $archivalva,
            'archiv' => 1
          ),
    			sprintf("ID = %d", $id)
    		);
      }
    }

		$this->resaveCategories( $id, $data['cats'] );

		return $id;
	}

	public function save( $data )
	{
		$cim 	= ($data['cim']) ?: false;
		$eleres = ($data['eleres']) ?: false;
		$szoveg = ($data['szoveg']) ?: NULL;
		$bevezeto = ($data['bevezeto']) ?: NULL;
		$content_after_szoveg = ($data['content_after_szoveg']) ?: NULL;
		$kep 	= ($data['belyegkep']) ?: NULL;
		$lathato= ($data['lathato']) ? 1 : 0;
		$archiv = ($data['archiv']) ? 1 : 0;
		$sorrend = ($data['sorrend']) ? (int)$data['sorrend'] : 100;
    $forrasinfo = ($data['forrasinfo']) ?: NULL;
    $archivalva = NULL;
    $optional = $data['optional'];
    $optional_data = array();

		if (!$cim) { throw new \Exception("Kérjük, hogy adja meg a <strong>Cikk címét</strong>!"); }

		if (!$eleres) {
			$eleres = $this->checkEleres( $cim );
		}


    // Optional
    if ($optional && !empty($optional)) {
      foreach ((array)$optional as $key => $value) {
        if ($value != '') {
          $optional_data[$key] = (is_string($value)) ? addslashes($value) : $value;
        }
      }
    }

    // downloads delete
    if (!empty($data['del_downloads']))
    {
      $tempdownloads = array();
      $di = -1;
      foreach ((array)$data['downloads']['name'] as $d)
      { $di++;
        if (!in_array($data['downloads']['file'][$di], (array)$data['del_downloads']))
        {
          $tempdownloads['name'][] = $data['downloads']['name'][$di];
          $tempdownloads['file'][] = $data['downloads']['file'][$di];
        } else {
          // delete
          if (file_exists($data['downloads']['file'][$di])) {
            @unlink($data['downloads']['file'][$di]);
          }
        }
      }
      $data['downloads'] = $tempdownloads;
      unset($tempdownloads);
    }

    // new downloads
    if (isset($data['newdownloads']) && $data['newdownloads']['name'][0] != '')
    {
      $dli = -1;
      foreach ( (array)$data['newdownloads']['name'] as $dl )
      {
        $dli++;
        $name = $data['newdownloads']['name'][$dli];
        $file_tmp = $_FILES['downloads']['tmp_name']['file'][$dli];
        $file_err = $_FILES['downloads']['error']['file'][$dli];
        $file_name = uniqid().'_'.basename($_FILES['downloads']['name']['file'][$dli]);

        if ( !empty($name) && !empty($file_tmp) && $file_err == \UPLOAD_ERR_OK ) {
          if(move_uploaded_file( $file_tmp, 'src/uploaded_files/'.$file_name )){
            usleep(500);
            $data['downloads']['name'][] = $name;
            $data['downloads']['file'][] = 'src/uploaded_files/'.$file_name;
          }
        }
      }
    }

    $downloads_raw = $this->prepareRAWDownloads($data['downloads']);
    $downloads = ($downloads_raw) ? serialize($downloads_raw) : NULL;

    $upd = array(
      'cim' => $cim,
      'eleres' => $eleres,
      'belyeg_kep' => $kep,
      'szoveg' => addslashes($szoveg),
      'bevezeto' => addslashes($bevezeto),
      'content_after_szoveg' => addslashes($content_after_szoveg),
      'idopont' => NOW,
      'lathato' => $lathato,
      'optional_contacts' => ($optional_data['contacts']) ? json_encode($optional_data['contacts'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_nyitvatartas' => ($optional_data['nyitvatartas']) ? json_encode($optional_data['nyitvatartas'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_maps' => ($optional_data['maps'] != '') ? $optional_data['maps'] : NULL,
      'optional_logo' => ($optional_data['logo'] != '') ? $optional_data['logo'] : NULL,
      'optional_firstimage' => ($optional_data['firstimage'] != '') ? $optional_data['firstimage'] : NULL,
      'archiv' => $archiv,
      'linkek' => $downloads,
      'forrasinfo' => addslashes($forrasinfo),
      'sorrend' => $sorrend
    );

    // publikáció ideje
    $datepub_year = ($data['datepub_year']) ?: false;
    $datepub_month = ($data['datepub_month']) ?: false;
    $datepub_day = ($data['datepub_day']) ?: false;
    $datepub_time = ($data['datepub_time']) ?: false;

    if ($datepub_year && $datepub_month && $datepub_day) {
       $letrehozva = $datepub_year.'-'.$datepub_month.'-'.$datepub_day;
       if ($datepub_time) {
         $letrehozva .= ' '.$datepub_time;
       }
       $upd['letrehozva'] = $letrehozva;
    }


		$this->db->update(
			"hirek",
			$upd,
			sprintf("ID = %d", $this->selected_news_id)
		);

		$this->resaveCategories( $this->selected_news_id, $data['cats'] );
	}

  private function preparePublicDates( &$data )
  {
    $date = $data['letrehozva'];

    $data['datepub_year'] = date('Y', strtotime($date));
    $data['datepub_month'] = date('m', strtotime($date));
    $data['datepub_day'] = date('d', strtotime($date));
    $data['datepub_time'] = date('H:i', strtotime($date));

    return $data;
  }

  private function prepareRAWDownloads( $postarr )
  {
    $arr = array();
    if ($postarr) {
      $i = -1;
      foreach ((array)$postarr['name'] as $pn ) {
        $i++;
        $pinf = pathinfo($postarr['file'][$i]);
        $arr[] = array(
          1 =>$pn,
          2 => $postarr['file'][$i],
          4 => '.'.$pinf['extension']
        );
      }
    }

    return (!empty($arr)) ? $arr : false;
  }

	public function resaveCategories( $id, $cats = array() )
	{
		// delete previous
		$this->db->squery("DELETE FROM cikk_xref_cat WHERE ctype = 'article' and cikk_id = :cikkid", array(
			'cikkid' => $id
		));

		// reinsert
		if( !empty($cats) )
		foreach ((array)$cats as $cid ) {
			$this->db->insert(
				'cikk_xref_cat',
				array(
          'ctype' => 'article',
					'cikk_id' => $id,
					'cat_id' => $cid
				)
			);
		}
	}

	private function checkEleres( $text )
	{
		$text = Formater::makeSafeUrl($text,'');

		$qry = $this->db->query(sprintf("
			SELECT eleres
			FROM hirek
			WHERE	eleres = '%s' or
						eleres like '%s-_' or
						eleres like '%s-__'
			ORDER BY 	eleres DESC
			LIMIT 		0,1", trim($text), trim($text), trim($text) ));
		$last_text = $qry->fetch(\PDO::FETCH_COLUMN);

		if( $qry->rowCount() > 0 ) {
			$last_int = (int)end(explode("-",$last_text));

			if( $last_int != 0 ){
				$last_text = str_replace('-'.$last_int, '-'.($last_int+1) , $last_text);
			} else {
				$last_text .= '-1';
			}
		} else {
			$last_text = $text;
		}

		return $last_text;
	}

	public function delete( $id = false )
	{
		$del_id = ($id) ?: $this->selected_news_id;

		if ( !$del_id ) return false;

		$this->db->query(sprintf("DELETE FROM hirek WHERE ID = %d", $del_id));
	}

  function parentCategoryData( $id )
  {
    $row = array();
		$has_parent = true;
		$limit = 10;

		$sid = $id;

		while( $has_parent && $limit > 0 ) {
			$q 		= "SELECT ".( ($return_row) ? $return_row.', szulo_id, deep' : '*' )." FROM cikk_kategoriak WHERE ID = ".$sid.";";
			$qry 	= $this->db->query($q);
			$data 	= $qry->fetch(\PDO::FETCH_ASSOC);
			$sid = $data['szulo_id'];

			if( is_null( $data['szulo_id'] ) ) {
				$has_parent = false;
			}

			if( (int)$data['deep'] >= $deep_allow_under ) {
				if (!$return_row) {
					$row[] = $data;
				} else {
					$row[] = $data[$return_row];
				}
			}

			$limit--;
		}

		return $row;
  }

	/**
	 * Hír fa kilistázása
	 * @param int $top_page_id Felső Hír ID meghatározása, nem kötelező. Ha nincs megadva, akkor
	 * az összes Hír fa listázódik.
	 * @return array Hírak
	 */
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

		// Legfelső színtű
		$qry = "
			SELECT SQL_CALC_FOUND_ROWS
				h.*,
				(SELECT count(uid) FROM ".self::DBVIEW." WHERE prog_id = h.ID) as uvisit
			FROM hirek as h
			WHERE h.ID IS NOT NULL ";

		if( $arg['except_id'] ) {
			$qry .= " and h.ID != ".$arg['except_id'];
		}

    if( isset($arg['hide_offline']) && !empty($arg['hide_offline']) ) {
      $qry .= " and h.lathato = 1";
    }

    if( isset($arg['lathato']) ) {
      $qry .= " and h.lathato = ".(int)$arg['lathato'];
    }

    if( isset($arg['on_date']) && !empty($arg['on_date']) ) {
      $qry .= " and h.letrehozva LIKE '".addslashes($arg['on_date'])."%'";
    }

    // Kategória slug exlude
    if( isset($arg['exc_cat_slug']) && !empty($arg['exc_cat_slug']) ) {
      $qry .= " and (";
        foreach ((array)$arg['exc_cat_slug'] as $exc_cat ) {
          $qry .= "'".$exc_cat."' NOT IN (SELECT ck.slug  FROM `cikk_xref_cat` as c LEFT OUTER JOIN cikk_kategoriak as ck ON ck.ID = c.cat_id WHERE c.`cikk_id` = h.ID and c.ctype = 'article') and ";
        }
        $qry = trim($qry," and ");
      $qry .= ")";
    }

		if( isset($arg['in_id']) && !empty($arg['in_id']) ) {
      $qry .= " and h.ID IN(".implode(",", (array)$arg['in_id']).")";
    }

		if (isset($arg['in_cat']) && !empty($arg['in_cat']) && $arg['in_cat'] != 0) {
      $cats = $this->categories->getCategoryChildIDS($arg['in_cat'], true);
      if (is_array($cats)) {
        $qry .= " and(";
        foreach ($cats as $cid) {
          $qry .= $cid." IN (SELECT cat_id FROM cikk_xref_cat WHERE ctype = 'article' and cikk_id = h.ID) or ";
        }
        $qry = rtrim($qry, " or ");
        $qry .= ")";
      }
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
              $src .= "(h.cim LIKE '%".trim($xt)."%' or h.bevezeto LIKE '%".trim($xt)."%') or ";
            }
            $src = rtrim($src, ' or ');
            $src .= ')';
          break;
          case 'ae':
            $xtext = explode(" ", trim($arg['search']['text']));
            $src .= ' and (';
            foreach ((array)$xtext as $xt) {
              $src .= "(h.cim LIKE '%".trim($xt)."%' or h.bevezeto LIKE '%".trim($xt)."%') and ";
            }
            $src = rtrim($src, ' and ');
            $src .= ')';
          break;
          // Alap és teljes szöveg
          default: case 'ft':
            $src .= " and (h.cim LIKE '%".$arg['search']['text']."%' or h.bevezeto LIKE '%".$arg['search']['text']."%')";
          break;
        }
      }

      $qry .= $src;
		}


		if( $arg['order'] ) {
      if ($arg['order'] == 'in_id') {
        $qry .= " ORDER BY FIELD(h.ID, ".implode(",", $arg['in_id']).")";
      } else {
        $qry .= " ORDER BY ".$arg['order']['by']." ".$arg['order']['how'];
      }
		} else {
			$qry .= " ORDER BY h.sorrend ASC, h.letrehozva DESC ";
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

	public function has_news()
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

	/**
	 * A walk() fgv-en belül visszakaphatjuk az aktuális Hír elem adatait tömbbe tárolva.
	 * @return array
	 */
	public function the_news()
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

  public function getArchiveDates( $limit = false )
  {
    $list = array();

    $qry = "SELECT
      substr(letrehozva,1,7) as dateg,
      count(ID) as counts
    FROM `hirek`
    WHERE lathato = 1
    GROUP BY dateg
    ORDER BY dateg DESC";

    if ($limit) {
      $qry .= " LIMIT 0,".$limit;
    }
    $qry = $this->db->query($qry);

    if ($qry->rowCount() == 0 ) {
      return $list;
    }

    foreach ((array)$qry->fetchAll(\PDO::FETCH_ASSOC) as $d) {
      $list[] = array(
        'date' => $d['dateg'],
        'datef' => utf8_encode(strftime ('%Y. %B', strtotime($d['dateg']))),
        'posts' => (int)$d['counts'],
      );
    }

    return $list;
  }

	public function historyList( $limit = 5 )
  {
    $ids = array();
    $uid = \Helper::getMachineID();

    if ( empty($uid) ) {
      return false;
    }

    $getids = $this->db->squery("SELECT prod_id FROM ".self::DBVIEWHISTORY." WHERE uid = :uid ORDER BY watchtime DESC LIMIT 0,".$limit, array(
      'uid' => $uid
    ));

    if ( $getids->rowCount() != 0 )
    {
      $gidsdata = $getids->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($gidsdata as $id) {
        $ids[] = (int)$id['prod_id'];
      }
    }

    if ( !empty($ids) )
    {
      $this->getTree(array(
        'in_id' => $ids,
        'order' => 'in_id'
      ));
      return $this;
    }
  }

  public function log_view( $id = 0 )
  {
    if ( $id === 0 || !$id ) {
      return false;
    }

    $date = date('Y-m-d');
    $uid = \Helper::getMachineID();

    if ( empty($uid) ) {
      return false;
    }

    $check = $this->db->squery("SELECT click FROM ".self::DBVIEW." WHERE uid = :uid and prog_id = :progid and ondate = :ondate", array(
      'uid' => $uid,
      'progid' => $id,
      'ondate' => $date
    ));

    if ( $check->rowCount() == 0 ) {
      $this->db->insert(
        self::DBVIEW,
        array(
          'uid' => $uid,
          'prog_id' => $id,
          'ondate' => $date
        )
      );
    } else {
      $click = $check->fetchColumn();
      $this->db->update(
        self::DBVIEW,
        array(
          'click' => $click + 1
        ),
        sprintf("uid = '%s' and prog_id = %d and ondate = '%s'", $uid, $id, $date)
      );
    }

    // History log
    $hhkey = md5($uid.'_'.$id);
    $this->db->multi_insert(
      self::DBVIEWHISTORY,
      array('hashkey', 'uid', 'prod_id', 'watchtime'),
      array(
        array(
          $hhkey,
          $uid,
          $id,
          date('Y-m-d H:i:s')
        )
      ),
      array(
        'duplicate_keys' => array('hashkey', 'watchtime')
      )
    );
    $this->db->query("DELETE FROM ".self::DBVIEWHISTORY." WHERE datediff(now(), watchtime) > 30");
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
      if ($this->current_get_item['belyeg_kep'] == '') {
        return false;
      }
			return UPLOADS . str_replace('/src/uploads/','',$this->current_get_item['belyeg_kep']);
		} else {
			return $this->current_get_item['belyeg_kep'];
		}
	}
	public function getId()
	{
		return $this->current_get_item['ID'];
	}
  public function getDefaultCatID()
  {
    return (int)$this->current_get_item['default_cat'];
  }
	public function getTitle()
	{
		return $this->current_get_item['cim'];
	}
	public function getUrl( $cat_prefix = false, $include_domain = true )
	{
    /*$tematic_cikk_slug = false;
    $cats = $this->getCategories();
    foreach ((array)$cats['list'] as $l) {
      if (in_array($l['slug'], $this->tematic_cikk_slugs)) {
        $tematic_cikk_slug = $l['slug'];
        break;
      }
    }

    if ($tematic_cikk_slug) {
    	return ( ($include_domain) ? DOMAIN : '/' ) .$tematic_cikk_slug.'/'.$this->current_get_item['eleres'];
    } else {
    	return ( ($include_domain) ? DOMAIN : '/' ) .'cikk/'.$this->current_get_item['eleres'];
    }*/

    return ( ($include_domain) ? DOMAIN : '/' ) .'cikk/'.$this->current_get_item['eleres'];
	}
	public function getAccessKey()
	{
		return $this->current_get_item['eleres'];
	}
	public function getHtmlContent()
	{
		return $this->current_get_item['szoveg'];
	}
	public function getDescription()
	{
		return $this->current_get_item['bevezeto'];
	}
	public function getCatID()
	{
		return $this->current_get_item['cat_id'];
	}
	public function getCommentCount()
	{
		return 0;
	}
	public function getVisitCount()
	{
		return (int)$this->current_get_item['uvisit'];
	}
  public function getSort()
	{
		return (int)$this->current_get_item['sorrend'];
	}
	public function getIdopont( $format = false )
	{
		return ( !$format ) ? $this->current_get_item['letrehozva'] : date($format, strtotime($this->current_get_item['letrehozva']));
	}
	public function getVisibility()
	{
		return ($this->current_get_item['lathato'] == 1 ? true : false);
	}
	public function isFontos()
	{
		return ($this->current_get_item['fontos'] == 1 ? true : false);
	}
  public function isArchiv()
	{
		return ($this->current_get_item['archiv'] == 1 ? true : false);
	}
	public function isKozerdeku()
	{
		return ($this->current_get_item['kozerdeku'] == 1 ? true : false);
	}
	public function getMaxPage()
	{
		return $this->max_page;
	}
	public function getCurrentPage()
	{
		return $this->current_page;
	}
  public function getOptional( $what, $json_decode = false )
  {
    if ($json_decode) {
      return json_decode($this->current_get_item['optional_'.$what], \JSON_UNESCAPED_UNICODE);
    } else {
      return $this->current_get_item['optional_'.$what];
    }
  }
	public function categoryList( $arg = array() )
	{
    if (isset($arg['archiv'])) {
      $archivfilter = true;
    }
    $qp = array();

    if (isset($arg['search'])) {
      $searchfilter = '';
      $xs = explode(" ", trim($arg['search']));
      if ($xs && $xs[0] != "") {
        $searchfilter .= " and (";
        $srcs = '';
        foreach ($xs as $xsrc) {
          $srcs .= "h.cim LIKE '%".trim($xsrc)."%' or ";
        }
        $srcs = rtrim($srcs," or ");
        $searchfilter .= $srcs;
        $searchfilter .= ")";
      }
    }

		$q = "SELECT
      c.*,
      (SELECT count(cx.cikk_id) FROM cikk_xref_cat as cx LEFT OUTER JOIN hirek as h ON h.ID = cx.cikk_id WHERE 1=1 ".$searchfilter." and cx.cat_id = c.ID and h.lathato = 1 ".( ($archivfilter)?'and h.archiv = 1':'and h.archiv = 0' )." ) as postc
    FROM cikk_kategoriak as c
    WHERE
      1=1 ";
    if (isset($arg['usetree']) && !isset($arg['childof'])) {
      $q .= " and c.deep = 0 ";
    }
    if (isset($arg['childof'])) {
      $q .= " and c.szulo_id = :szid ";
      $qp['szid'] = (int)$arg['childof'];
    }

    if (!isset($arg['showallpostc'])) {
      $q .= " and (SELECT count(cx.cikk_id) FROM cikk_xref_cat as cx LEFT OUTER JOIN hirek as h ON h.ID = cx.cikk_id WHERE 1=1 ".$searchfilter." and cx.cat_id = c.ID and h.lathato = 1 ".( ($archivfilter)?'and h.archiv = 1':'and h.archiv = 0' )." ) != 0";
    }
    $q .= " ORDER BY c.sorrend ASC";

		$qry = $this->db->squery( $q, $qp);

		if ($qry->rowCount() == 0) {
			return array();
		} else {
			$data = $qry->fetchAll(\PDO::FETCH_ASSOC);
			$bdata = array();
			foreach ($data as $d) {
				$d['label'] = '<span class="cat-label" style="background-color:'.$d['bgcolor'].';">'.$d['neve'].'</span>';
        $passarg = $arg;
        $passarg['childof'] = $d['ID'];
        if (isset($arg['usetree']) && $arg['usetree'] === true) {
          $d['children'] = $this->categoryList($passarg);
        }
				$bdata[$d['slug']] = $d;
			}
			unset($data);
			unset($qry);

			return $bdata;
		}
	}
	public function getCategories( $by_cikk = true )
	{
		$q = "SELECT
			c.cat_id,
			ct.neve,
      ct.slug,
			ct.bgcolor
		FROM
		cikk_xref_cat as c
		LEFT OUTER JOIN cikk_kategoriak as ct ON ct.ID = c.cat_id
		WHERE 1=1 and c.ctype = 'article' and ct.ID IS NOT NULL and ";
		if ($by_cikk) {
				$q .= " c.cikk_id = :cikk";
		}

		$q .= "	ORDER BY ct.sorrend ASC";

		$param = array();

		if ($by_cikk) {
			$param['cikk'] = $this->getId();
		}

		$qry = $this->db->squery( $q, $param);

		if ($qry->rowCount() == 0) {
			return array();
		} else {
			$data = $qry->fetchAll(\PDO::FETCH_ASSOC);
			$inids = array();

			$bdata = array();
			foreach ($data as $d) {
        $d['is_tematic'] = (in_array($d['slug'], $this->tematic_cikk_slugs)) ? true : false;
				$d['label'] = '<span class="cat-label" style="background-color:'.$d['bgcolor'].';">'.$d['neve'].'</span>';
				$inids[] = $d['cat_id'];
				$bdata[] = $d;
			}
			unset($data);
			unset($qry);

			return array(
				'ids' => $inids,
				'list' => $bdata
			);
		}
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
		$this->selected_news_id = false;
		$this->item_limit_per_page = 50;
		$this->sitem_numbers = 0;
	}
}
?>
