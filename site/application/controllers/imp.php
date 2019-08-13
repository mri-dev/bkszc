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
