<?php
namespace PortalManager;

use Interfaces\InstallModules;

class Gallery implements InstallModules
{
  const DBGROUP = 'Galeria_Group';
  CONST DBTABLE = 'Galeria_Items';
  CONST DBITEMXREF = 'Galeria_Items_xref_Categories';
  const MODULTITLE = 'Galéria';

  private $db = null;
  public $settings = array();

  function __construct( $arg = array() )
  {
    $this->db = $arg['db'];
    $this->settings = $arg['db']->settings;

    if( !$this->checkInstalled() && strpos($_SERVER['REQUEST_URI'], '/install') !== 0) {
      \Helper::reload('/install?module='.__CLASS__);;
    }

    return $this;
  }

  public function updateImages( $images )
  {
    $ret = array(
      'deleted' => array()
    );

    if (empty($images)) {
      return false;
    }

    foreach ((array)$images as $img) {
      if (isset($img['deleting']) && $img['deleting'] == 'true')
      {
        // Törlés
        $get = $this->db->squery("SELECT * FROM ".self::DBTABLE." WHERE ID = :id", array('id' => $img['ID']))->fetch(\PDO::FETCH_ASSOC);
        $file = $_SERVER['DOCUMENT_ROOT'].'/'.$get['filepath'];
        if (file_exists($file)) {
          if (unlink($file)) {
            $this->db->squery("DELETE FROM ".self::DBTABLE." WHERE ID = :id", array('id' => $get['ID']));
            $ret['deleted'][] = $get;
          }
        }

      } else {
        // Szerkesztés
        $imgid = (int)($img['ID']);
        $title = ($img['title'] == '') ? NULL : addslashes($img['title']);
        $desc = ($img['description'] == '') ? NULL : addslashes($img['description']);
        $sort = ($img['sorrend'] == '') ? 100 : (int)($img['sorrend']);

        $this->db->update(
          self::DBTABLE,
          array(
            'title' => $title,
            'description' => $desc,
            'sorrend' => $sort
          ),
          sprintf('ID = %d', $imgid)
        );
      }
    }

    return $ret;
  }

  public function buildGalleries( &$cat )
  {
    $mod = array();
    foreach ((array)$cat as $cslug => $c) {
      $c['items'] = $this->getCategoryItems( $c['ID'] );
      $mod[$cslug] = $c;
    }

    $cat = $mod;
    return $cat;
  }

  public function getCategoryData( $id = 0 )
  {
    $id = (int)$id;

    $list = array();
    $qarg = array();

    $groupqry = "SELECT
      c.*
    FROM cikk_kategoriak as c
    WHERE c.ID = :id
    ";
    $qarg['id'] = $id;

    $groupqry = $this->db->squery( $groupqry, $qarg );

    if ($groupqry->rowCount() == 0) {
      return $list;
    }

    $list = $groupqry->fetch(\PDO::FETCH_ASSOC);
    $list['url'] = '/galeria/folders/kategoriak/'.$list['slug'];

    return $list;
  }

  public function getCategoryItems( $cat_id )
  {
    $list = array();
    $qarg = array();

    $groupqry = "SELECT
      g.*
    FROM ".self::DBTABLE." as g
    WHERE 1=1 and :catid IN (SELECT cat_id FROM ".self::DBITEMXREF." WHERE galeria_id = g.ID)
    ORDER BY g.sorrend ASC, g.uploaded DESC
    ";
    $qarg['catid'] = $cat_id;

    $groupqry = $this->db->squery( $groupqry, $qarg );

    if ($groupqry->rowCount() == 0) {
      return $list;
    }

    foreach ($groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['url'] = '/galeria/folder/'.$d['slug'];
      $d['images'] = unserialize($d['filepath']);
      unset($d['filepath']);
      $list[] = $d;
    }

    return $list;
  }

  public function getGallery( $slug )
  {
    $list = array();

    $groupqry = "SELECT
      g.*
    FROM ".self::DBTABLE." as g
    WHERE 1=1 and g.slug = :slug
    ORDER BY g.sorrend ASC
    ";

    $groupqry = $this->db->squery( $groupqry, array('slug' => $slug) );

    if ($groupqry->rowCount() == 0) {
      return $list;
    }

    foreach ($groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['url'] = '/galeria/folder/'.$d['slug'];
      $d['images'] = unserialize($d['filepath']);
      $d['default_cat'] = $this->getCategoryData( $d['default_cat'] );
      unset($d['filepath']);
      $list[$d['slug']] = $d;
    }

    return $list[$slug];
  }

  public function loadGalleries()
  {
    $list = array();

    $groupqry = "SELECT
      g.ID,
      g.neve,
      g.slug,
      g.szulo_id,
      g.kep,
      (SELECT count(i.ID) FROM ".self::DBTABLE." as i WHERE i.gallery_id = g.ID) as imagesnum
    FROM ".self::DBGROUP." as g
    WHERE 1=1
    ORDER BY g.sorrend ASC
    ";

    $groupqry = $this->db->query( $groupqry );

    if ($groupqry->rowCount() == 0) {
      return $list;
    }

    foreach ($groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['ID'] = (int)$d['ID'];
      $d['imagesnum'] = (int)$d['imagesnum'];
      $d['has_kep'] = ($d['kep'] == '') ? false : true;
      $d['kep'] = (!$d['has_kep']) ? IMGDOMAIN . 'src/images/no-image.png' : UPLOADS . str_replace("/src/uploads/","",$d['kep']);
      $d['images'] = $this->getImages( $d['ID'] );
      $list[$d['slug']] = $d;
    }

    return $list;
  }

  public function registerImage( $gallery_id, $imagedata )
  {
    $this->db->insert(
      self::DBTABLE,
      array(
        'gallery_id' => $gallery_id,
        'filepath' => $imagedata['filepath'],
        'origin_name' => $imagedata['origin_name'],
        'kiterjesztes' => $imagedata['kiterjesztes'],
        'filemeret' => $imagedata['filemeret']
      )
    );

    return $this->db->lastInsertId();
  }

  function getImages( $gallery_id = 0 )
  {
    $list = array();
    $qryparam = array();

    $qry = "SELECT
      i.*
    FROM ".self::DBTABLE." as i
    WHERE 1=1 ";

    if ($gallery_id != 1) {
      $qry .= " and i.gallery_id = :gallery";
      $qryparam['gallery'] = (int)$gallery_id;
    }

    $qry .=" ORDER BY i.sorrend ASC, i.uploaded DESC";

    $qry = $this->db->squery( $qry, $qryparam );

    if ($qry->rowCount() == 0) {
      return $list;
    }

    foreach ($qry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['filepath'] = str_replace("/src/images/","", IMG) . '/' . $d['filepath'];
      $list[] = $d;
    }

    return $list;
  }

  public function getLastGalleries()
  {
    $list = array();

    $groupqry = "SELECT
      g.*
    FROM ".self::DBTABLE." as g
    WHERE 1=1
    ORDER BY g.uploaded DESC
    LIMIT 0, 10
    ";

    $groupqry = $this->db->squery( $groupqry, array('slug' => $slug) );

    if ($groupqry->rowCount() == 0) {
      return $list;
    }

    foreach ($groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['url'] = '/galeria/folder/'.$d['slug'];
      $d['images'] = unserialize($d['filepath']);
      $d['default_cat'] = $this->getCategoryData( $d['default_cat'] );
      unset($d['filepath']);
      $list[$d['slug']] = $d;
    }

    return $list;
  }

  /**
  * Simple list gallery
  **/
  public function addSimpleGallery( $uid, $data )
	{
		$cim = ($data['cim']) ?: false;
		$eleres = ($data['eleres']) ?: false;
		$szoveg = ($data['description']) ?: NULL;
		$belyegkep = ($data['belyegkep']) ?: NULL;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;
		$sorrend = ($data['sorrend']) ? (int)$data['sorrend'] : 100;

		if (!$cim) { throw new \Exception("Kérjük, hogy adja meg az <strong>Galéria címét</strong>!"); }

		if (!$eleres) {
			$eleres = $this->checkEleres( $cim );
		}

    $upd = array(
      'author' => $uid,
      'title' => $cim,
      'slug' => $eleres,
      'description' => $szoveg,
      'belyeg_kep' => $belyegkep,
      'uploaded' => NOW,
      'updated_at' => NOW,
      'lathato' => $lathato,
      'sorrend' => $sorrend,
      'filepath' => NULL
    );

		$this->db->insert(
			self::DBTABLE,
      $upd
		);

		$id = $this->db->lastInsertId();

    // Category insert
    if ( isset($data['cats']) && !empty($data['cats']) )
    {
      foreach ((array)$data['cats'] as $cid)
      {
        if ((int)$cid == 0) {
          continue;
        }

        $this->db->insert(
    			self::DBITEMXREF,
          array(
            'galeria_id' => $id,
            'cat_id' => (int)$cid
          )
    		);
      }
    }

    // Upload images
    $filepaths = array();
    $filepaths = (!$filepaths) ? NULL : serialize($filepaths);

    $this->db->update(
      self::DBTABLE,
      array(
        'filepath' => $filepaths
      ),
      sprintf("ID = %d", $id)
    );

		return $id;
	}

  public function editSimpleGallery( $id, $data )
  {
    $cim = ($data['cim']) ?: false;
    $eleres = ($data['eleres']) ?: false;
    $szoveg = ($data['description']) ?: NULL;
    $default_cat = ($data['default_cat']) ?: NULL;
    $belyegkep = ($data['belyegkep']) ?: NULL;
    $lathato= ($data['lathato'] == 'on') ? 1 : 0;
    $sorrend = ($data['sorrend']) ? (int)$data['sorrend'] : 100;

    if (!$cim) { throw new \Exception("Kérjük, hogy adja meg az <strong>Galéria címét</strong>!"); }

    if (!$eleres) {
      $eleres = $this->checkEleres( $cim );
    }

    $upd = array(
      'title' => $cim,
      'slug' => $eleres,
      'description' => $szoveg,
      'belyeg_kep' => $belyegkep,
      'updated_at' => NOW,
      'lathato' => $lathato,
      'sorrend' => $sorrend,
      'default_cat' => $default_cat
    );

    $this->db->update(
      self::DBTABLE,
      $upd,
      sprintf("ID = %d", (int)$id)
    );

    // Category insert
    if ( isset($data['cats']) && !empty($data['cats']) )
    {
      // reset
      $this->db->squery("DELETE FROM ".self::DBITEMXREF." WHERE galeria_id = :gid", array('gid' => $id));

      foreach ((array)$data['cats'] as $cid)
      {
        if ((int)$cid == 0) {
          continue;
        }
        $this->db->insert(
          self::DBITEMXREF,
          array(
            'galeria_id' => $id,
            'cat_id' => (int)$cid
          )
        );
      }
    }

    // Upload images
    $filepaths = array();
    $filepaths = (!$filepaths) ? NULL : serialize($filepaths);

    $this->db->update(
      self::DBTABLE,
      array(
        'filepath' => $filepaths
      ),
      sprintf("ID = %d", $id)
    );

    return $id;
  }

  private function checkEleres( $text )
	{
		$text = \PortalManager\Formater::makeSafeUrl($text,'');

    $q = "SELECT slug
			FROM ".self::DBTABLE."
			WHERE	slug = :text or
						slug like :textone or
						slug like :texttwo
			ORDER BY slug DESC
			LIMIT 0,1";
		$qry = $this->db->squery($q, array('text' => $text, 'textone' => $text.'-_', 'texttwo' => $text.'-__' ));
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

  public function getSimpleGaleria( $id )
  {
    $list = array();
    $qarg = array();

    $groupqry = "SELECT
      g.*
    FROM ".self::DBTABLE." as g
    WHERE 1=1 ";

    $groupqry .= " and g.ID = :id ";
    $qarg['id'] = $id;

    $groupqry = $this->db->squery( $groupqry, $qarg );

    if ( $groupqry->rowCount() == 0 ) {
      return $list;
    }

    $list = $groupqry->fetch(\PDO::FETCH_ASSOC);

    $list['images'] = unserialize($list['filepath']);
    unset($list['filepath']);
    $list['in_cats'] = $this->simpleGalleryItemCats( $id, $list['default_cat'] );

    return $list;
  }

  public function simpleGalleryList( $arg = array() )
  {
    $list = array();
    $qarg = array();

    $groupqry = "SELECT
      g.*
    FROM ".self::DBTABLE." as g
    WHERE 1=1 ";

    if (isset($arg['search']) && !empty($arg['search'])) {
      $groupqry .= " and g.title LIKE :src";
      $qarg['src'] = '%'.$arg['search'].'%';
    }

    if (isset($arg['in_cat']) && !empty($arg['in_cat'])) {
      $groupqry .= " and :in_cat IN (SELECT cat_id FROM ".self::DBITEMXREF." WHERE galeria_id = g.ID) ";
      $qarg['in_cat'] = $arg['in_cat'];
    }

    $groupqry .= " ORDER BY g.sorrend ASC, g.uploaded DESC ";

    $groupqry = $this->db->squery( $groupqry, $qarg );

    if ( $groupqry->rowCount() == 0 ) {
      return $list;
    }

    foreach ( $groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d )
    {
      $d['images'] = unserialize($d['filepath']);
      unset($d['filepath']);
      $d['in_cats'] = $this->simpleGalleryItemCats( $d['ID'], $d['default_cat'] );
      $list[] = $d;
    }

    return $list;
  }

  public function simpleGalleryItemCats( $id, $default_cat = '' )
  {
    $list = array();
    $qarg = array();

    $groupqry = "SELECT
      x.cat_id,
      c.neve
    FROM ".self::DBITEMXREF." as x
    LEFT OUTER JOIN cikk_kategoriak as c ON c.ID = x.cat_id
    WHERE 1=1 and x.galeria_id = :gid";
    $qarg['gid'] = $id;

    $groupqry = $this->db->squery( $groupqry, $qarg );

    if ( $groupqry->rowCount() == 0 ) {
      return $list;
    }

    foreach ( $groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d )
    {
      $list[$d['cat_id']] = array(
        'id' => (int)$d['cat_id'],
        'neve' => $d['neve'],
        'default' => ($default_cat == $d['cat_id']) ? true: false
      );
    }

    return $list;
  }

  public function __destruct()
  {
    $this->db = null;
    $this->settings = array();
  }

    /*******************************
    * Installer
    ********************************/
    public function checkInstalled()
    {
      $check_installed = $this->db->query("SHOW TABLES LIKE '".self::DBTABLE."'")->fetchColumn();

      if ( $check_installed === false ) {
        $cn = addslashes(__CLASS__);
        $this->db->query("DELETE FROM modules WHERE classname = '$cn'");
      }

      return ($check_installed === false) ? false : true;
    }

    public function installer( \PortalManager\Installer $installer )
    {
      $installed = false;


      if (false) {
        /**
        * Vehicles
        **/
        $installer->setTable( self::DBTABLE );
        // Tábla létrehozás
        $table_create =
        "(
          `ID` mediumint(9) NOT NULL,
          `title` varchar(150) NOT NULL,
          `slug` varchar(150) NOT NULL,
          `logo` text,
          `parent_id` mediumint(9) DEFAULT NULL,
          `deep` smallint(6) NOT NULL DEFAULT '0'
        )";
        $installer->createTable( $table_create );

        // Indexek
        $index_create =
        "ADD PRIMARY KEY (`ID`),
        ADD KEY `title` (`title`),
        ADD KEY `parent_id` (`parent_id`)";
        $installer->addIndexes( $index_create );

        // Increment
        $inc_create =
        "MODIFY `ID` mediumint(9) NOT NULL AUTO_INCREMENT";
        $installer->addIncrements( $inc_create );
      }

      // Modul instalállás mentése
      $installed = $installer->setModulInstalled( __CLASS__, self::MODULTITLE, 'etlapok' , 'cutlery' );

      return $installed;
    }
}
?>
