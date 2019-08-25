<?
use DatabaseManager\Database;

class imp extends Controller
{
  private $dbfrom = null;
  private $dbto = null;

	function __construct(){
		parent::__construct();
		parent::$pageTitle = '';


    $this->dbfrom = new Database(array(
      'host' => 'localhost',
      'name' => 'ideasand_gundel',
      'user' => 'ideasand_bkszc',
      'pw' => '1~0pnp,c_KSz'
    ));
    $this->dbto = new Database();
	}

  /*
    UPDATE hirek SET cim = convert(cast(convert(cim using  latin1) as binary) using utf8), szoveg = convert(cast(convert(szoveg using  latin1) as binary) using utf8), content_after_szoveg = convert(cast(convert(content_after_szoveg using  latin1) as binary) using utf8), bevezeto = convert(cast(convert(bevezeto using  latin1) as binary) using utf8), linkek = convert(cast(convert(linkek using  latin1) as binary) using utf8), forrasinfo = convert(cast(convert(forrasinfo using  latin1) as binary) using utf8)
  */
  public function articles()
  {
    $q = "SELECT
      a.*,
      wd.field_id_1,
      wd.field_id_2,
      wd.field_id_3,
      wd.field_id_4,
      wd.field_id_14,
      wd.field_id_15
    FROM exp_weblog_titles as a
    LEFT OUTER JOIN exp_weblog_data as wd ON wd.entry_id = a.entry_id
    WHERE a.weblog_id = 1";
    $this->dbfrom->query("SET NAMES utf8");
    $qry = $this->dbfrom->squery( $q );
    $data = $qry->fetchAll(\PDO::FETCH_ASSOC);

    $header = array('ID', 'cim', 'eleres', 'hashkey', 'bevezeto', 'szoveg', 'content_after_szoveg', 'forrasinfo', 'linkek', 'belyeg_kep', 'idopont', 'letrehozva', 'lathato', 'default_cat', 'author');
    $insert = array();

    foreach ((array)$data as $d)
    {
      $insert[] = array(
        $d['entry_id'],
        addslashes($d['title']),
        $d['url_title'],
        md5($d['entry_id']),
        addslashes($d['field_id_1']),
        addslashes($d['field_id_2']),
        addslashes($d['field_id_3']),
        addslashes($d['field_id_14']),
        addslashes($d['field_id_15']),
        addslashes($d['field_id_4']),
        $d['year'].'-'.$d['month'].'-'.$d['day'],
        date('Y-m-d', $d['entry_date'] ),
        1,
        ($d['primary_category'] == 0 || is_null($d['primary_category']) || $d['primary_category'] == '') ? NULL : $d['primary_category'],
        $d['author_id']
      );
    }

    if ($insert) {
      /*echo '<pre>';
      print_r($insert);*/

      /* * /
      $this->dbto->multi_insert(
        'hirek',
        $header,
        $insert,
        array(
          'duplicate_keys' => array('hashkey', 'cim', 'eleres', 'bevezeto', 'szoveg', 'content_after_szoveg', 'forrasinfo', 'linkek', 'belyeg_kep', 'idopont', 'letrehozva')
        )
      );
      /* */
    }
  }

  public function article_cats()
  {

    $q = "SELECT
      a.*
    FROM exp_category_posts as a";
    $this->dbfrom->query("SET NAMES utf8");
    $qry = $this->dbfrom->squery( $q );
    $data = $qry->fetchAll(\PDO::FETCH_ASSOC);

    $header = array('cikk_id', 'cat_id');
    $insert = array();

    foreach ((array)$data as $d)
    {
      $insert[] = array(
        $d['entry_id'],
        $d['cat_id']
      );
    }

    if ($insert) {
      // Pre delete
      $this->dbto->query("TRUNCATE cikk_xref_cat");

      /* */
      $this->dbto->multi_insert(
        'cikk_xref_cat',
        $header,
        $insert
      );
      /* */
    }

  }

  public function cats()
  {
    $q = "SELECT c.* FROM exp_categories as c";
    $this->dbfrom->query("SET NAMES utf8");
    $qry = $this->dbfrom->squery( $q );
    $data = $qry->fetchAll(\PDO::FETCH_ASSOC);

    $header = array('ID', 'neve', 'slug', 'hashkey', 'szulo_id', 'sorrend');
    $insert = array();

    foreach ((array)$data as $d) {
      $insert[] = array(
        $d['cat_id'],
        $d['cat_name'],
        $d['cat_url_title'],
        md5($d['cat_id']),
        ($d['parent_id']==0)?NULL:$d['parent_id'],
        (int)$d['cat_order']
      );
    }

    if ($insert) {
      echo '<pre>';
      print_r($insert);

      /**/
      $this->dbto->multi_insert(
        'cikk_kategoriak',
        $header,
        $insert,
        array(
          'duplicate_keys' => array('hashkey', 'neve', 'slug', 'sorrend', 'szulo_id')
        )
      );
      /**/
    }
  }

  public function fixcatdeeps()
  {
    $q = "SELECT c.ID, c.szulo_id, c.deep FROM cikk_kategoriak as c";
    $qry = $this->dbto->squery( $q );
    $data = $qry->fetchAll(\PDO::FETCH_ASSOC);

    $back = array();
    foreach ( (array)$data as $d ) {
      $parent = (int)$d['szulo_id'];
      $parents = array();
      if ($parent != 0) {
        $parents[] = $parent;
      }

      $lm = 10;
      while( $parent !== 0 ){
        $lm--;
        $parent = $this->getCatParent( $parent );
        if ($parent != 0) {
          $parents[] = $parent;
        }
        if ($lm < 0) {
          $parent = 0;
        }
      }

      $d['parent_row'] = $pr;
      $d['parents'] = $parents;
      $d['deep'] = count($parents);

      $this->dbto->update(
        'cikk_kategoriak',
        array(
          'deep' => $d['deep']
        ),
        sprintf("ID = %d", $d['ID'])
      );

      $back[$d['ID']] = $d;
    }

    echo '<pre>';
    print_r($back);
  }

  public function getCatParent( $id )
  {
    $parent = (int)$this->dbto->squery("SELECT szulo_id FROM cikk_kategoriak WHERE ID  = :id", array( 'id' => $id ))->fetchColumn();
    return $parent;
  }

	function __destruct()
  {
    $this->dbfrom = null;
    $this->dbto = null;
		// RENDER OUTPUT
			//parent::bodyHead();					# HEADER
			//$this->view->render(__CLASS__);		# CONTENT
			//parent::__destruct();				# FOOTER
	}
}

?>
