<?
use DatabaseManager\Database;

use PortalManager\AdminUser;
use PortalManager\Menus;
use PortalManager\Template;
use PortalManager\Users;
use PortalManager\Redirector;
use ShopManager\Categories;
use PortalManager\News;
use Applications\Captcha;
use PortalManager\Installer;

class Controller {
    public $db = null;
    public $hidePatern 	= true;
    private $theme_wire 	= '';
    private $theme_folder 	= '';
    private $start_time     = 0;
    private $finish_time    = 0;
    private $is_admin       = false;

    public static $pageTitle;
    public $fnTemp          = array();
    public static $user_opt = array();

    function __construct($arg = array())
    {
        $this->start_time = microtime(true);
        $this->is_admin = $arg['admin'];
        Session::init();
        Helper::setMashineID();
        $this->gets = Helper::GET();
        setlocale(LC_ALL, "hu_HU");
        setlocale(LC_TIME, 'hu_HU');
        //$this->memory_usage();

        //var_dump($_GET);

        // CORE
        // $this->model 		= new Model();
        $this->view = new View();
        $this->db = new Database();
        $this->installer = new Installer(array('db'=> $this->db));
        //////////////////////////////////////////////////////
        $this->view->settings = $this->getAllValtozo();
        $this->gets = Helper::GET();
        $this->view->gets = $this->gets;

        $this->AdminUser = new AdminUser( array( 'db' => $this->db, 'view' => $this->view, 'settings' => $this->view->settings )  );
        $this->view->adm = $this->AdminUser;
        $this->view->adm->logged = ($this->AdminUser) ? $this->AdminUser->isLogged() : false;

        if ( $this->view->adm->logged ) {
          $this->view->adm = $this->AdminUser;
          $this->view->adm->logged = $this->AdminUser->isLogged();
        }

        $this->User = new Users(array(
          'db' => $this->db,
          'view' => $this->view,
          'admin' => $this->is_admin
        ));
        $this->out( 'USERS', $this->User);

        $this->out( 'db',   $this->db );
        $this->out( 'user', $this->User->get( self::$user_opt ) );

        if ($this->gets[0] != 'ajax')
        {
          // Only admin
          if ( !defined('PRODUCTIONSITE') )
          {
            $this->out( 'modules', $this->installer->listModules(array('only_active' => true)) );
          }

          // redirector
          if ( defined('PRODUCTIONSITE') )
          {
            $redrirector = new Redirector('web', ltrim($_SERVER['REQUEST_URI'], '/'), array('db' => $this->db));
            $redrirector->start();
            $goto = $redrirector->redirect();
            if ($goto) {
              header("HTTP/1.1 301 Moved Permanently");
              header("Location: ".$goto);
              exit();
            }
          }

          $templates = new Template( VIEW . 'templates/' );
          $this->out( 'templates', $templates );
          //$this->out( 'highlight_text', $this->Portal->getHighlightItems() );
          //$this->out( 'slideshow', $this->Portal->getSlideshow() );

          // Menük
          $tree = null;
          $menu_header  = new Menus( false, array( 'db' => $this->db ) );
          // Header menü
          $menu_header->addFilter( 'menu_type', 'header' );
          $menu_header->isFinal(true);
          $tree   = $menu_header->getTree(false, array('admin' => false));
          $this->out( 'menu_header',  $tree );

          // Footer menü
          $tree = null;
          $menu_footer  = new Menus( false, array( 'db' => $this->db ) );
          $menu_footer->addFilter( 'menu_type', 'footer' );
          $menu_footer->isFinal(true);
          $tree   = $menu_footer->getTree(false, array('admin' => false));
          $this->out( 'menu_footer',  $tree );

          // Mobil menü
          $tree = null;
          $menu_footer  = new Menus( false, array( 'db' => $this->db ) );
          $menu_footer->addFilter( 'menu_type', 'mobil' );
          $menu_footer->isFinal(true);
          $tree   = $menu_footer->getTree(false, array('admin' => false));
          $this->out( 'menu_mobil',  $tree );

          unset($tree);

          if ( $_GET['msgkey'] ) {
              $this->out( $_GET['msgkey'], Helper::makeAlertMsg('pSuccess', $_GET[$_GET['msgkey']]) );
          }

        }

        $this->out( 'states', array(
            0=>"Bács-Kiskun",
            1=>"Baranya",
            2=>"Békés",
            3=>"Borsod-Abaúj-Zemplén",
            4=>"Budapest",
            5=>"Csongrád",
            6=>"Fejér",
            7=>"Győr-Moson-Sopron",
            8=>"Hajdú-Bihar",
            9=>"Heves",
            10=>"Jász-Nagykun-Szolnok",
            11=>"Komárom-Esztergom",
            12=>"Nógrád",
            13=>"Pest",
            14=>"Somogy",
            15=>"Szabolcs-Szatmár-Bereg",
            16=>"Tolna",
            17=>"Vas",
            18=>"Veszprém",
            19=>"Zala",
        ) );
        $this->out( 'kozterulet_jellege', $this->kozterulet_jellege() );

        if(!$arg['hidePatern']){ $this->hidePatern = false; }

        $this->view->valuta  = 'Ft';
    }

    function out( $viewKey, $output ){
        $this->view->$viewKey = $output;
    }

    function bodyHead($key = ''){
        $mode       = false;
        $subfolder  = '';

        $this->theme_wire   = ($key != '') ? $key : '';

        if($this->getThemeFolder() != ''){
            $mode       = true;
            $subfolder  = $this->getThemeFolder().'/';
        }

        # Oldal címe
        if(self::$pageTitle != null){
            $this->view->title = self::$pageTitle . ' | ' . $this->view->settings['page_title'];
        } else {
            $this->view->title = $this->view->settings['page_title'] . " &mdash; ".$this->view->settings['page_description'];
        }

        # Render HEADER
        if(!$this->hidePatern){
            $this->view->render($subfolder.$this->theme_wire.'header'.( (isset($_GET['header'])) ? '-'.$_GET['header'] : '' ),$mode);
        }

        # Aloldal átadása a VIEW-nek
        $this->view->called = $this->fnTemp;
    }

    public function kozterulet_jellege()
    {
       $arr = array(
            'akna',
            'akna-alsó',
            'akna-felső',
            'alagút',
            'alsórakpart',
            'arborétum',
            'autóút',
            'barakképület',
            'barlang',
            'bejáró',
            'bekötőút',
            'bánya',
            'bányatelep',
            'bástya',
            'bástyája',
            'csárda',
            'csónakházak',
            'domb',
            'dűlő',
            'dűlők',
            'dűlősor',
            'dűlőterület',
            'dűlőút',
            'egyetemváros',
            'egyéb',
            'elágazás',
            'emlékút',
            'erdészház',
            'erdészlak',
            'erdő',
            'erdősor',
            'fasor',
            'fasora',
            'felső',
            'forduló',
            'főmérnökség',
            'főtér',
            'főút',
            'föld',
            'gyár',
            'gyártelep',
            'gyárváros',
            'gyümölcsös',
            'gát',
            'gátsor',
            'gátőrház',
            'határsor',
            'határút',
            'hegy',
            'hegyhát',
            'hegyhát dűlő',
            'hegyhát',
            'köz',
            'hrsz',
            'hrsz.',
            'ház',
            'hídfő',
            'iskola',
            'játszótér',
            'kapu',
            'kastély',
            'kert',
            'kertsor',
            'kerület',
            'kilátó',
            'kioszk',
            'kocsiszín',
            'kolónia',
            'korzó',
            'kultúrpark',
            'kunyhó',
            'kör',
            'körtér',
            'körvasútsor',
            'körzet',
            'körönd',
            'körút',
            'köz',
            'kút',
            'kültelek',
            'lakóház',
            'lakókert',
            'lakónegyed',
            'lakópark',
            'lakótelep',
            'lejtő',
            'lejáró',
            'liget',
            'lépcső',
            'major',
            'malom',
            'menedékház',
            'munkásszálló',
            'mélyút',
            'műút',
            'oldal',
            'orom',
            'park',
            'parkja',
            'parkoló',
            'part',
            'pavilon',
            'piac',
            'pihenő',
            'pince',
            'pincesor',
            'postafiók',
            'puszta',
            'pálya',
            'pályaudvar',
            'rakpart',
            'repülőtér',
            'rész',
            'rét',
            'sarok',
            'sor',
            'sora',
            'sportpálya',
            'sporttelep',
            'stadion',
            'strandfürdő',
            'sugárút',
            'szer',
            'sziget',
            'szivattyútelep',
            'szállás',
            'szállások',
            'szél',
            'szőlő',
            'szőlőhegy',
            'szőlők',
            'sánc',
            'sávház',
            'sétány',
            'tag',
            'tanya',
            'tanyák',
            'telep',
            'temető',
            'tere',
            'tető',
            'turistaház',
            'téli kikötő',
            'tér',
            'tömb',
            'udvar',
            'utak',
            'utca',
            'utcája',
            'vadaskert',
            'vadászház',
            'vasúti megálló',
            'vasúti őrház',
            'vasútsor',
            'vasútállomás',
            'vezetőút',
            'villasor',
            'vágóhíd',
            'vár',
            'várköz',
            'város',
            'vízmű',
            'völgy',
            'zsilip',
            'zug',
            'állat és növ.kert',
            'állomás',
            'árnyék',
            'árok',
            'átjáró',
            'őrház',
            'őrházak',
            'őrházlak',
            'út',
            'útja',
            'útőrház',
            'üdülő',
            'üdülő-part',
            'üdülő-sor',
            'üdülő-telep',
            );

        asort($arr);
        uasort($arr, array('Controller', 'Hcmp'));

        return $arr;
    }

    /**
    * Magyar ékezetes betűk korrigálás/rewrite rendezéshez
    * */
    static function Hcmp($a, $b)
    {
      static $Hchr = array('á'=>'az', 'é'=>'ez', 'í'=>'iz', 'ó'=>'oz', 'ö'=>'ozz', 'ő'=>'ozz', 'ú'=>'uz', 'ü'=>'uzz', 'ű'=>'uzz', 'cs'=>'cz', 'zs'=>'zz',
       'ccs'=>'czcz', 'ggy'=>'gzgz', 'lly'=>'lzlz', 'nny'=>'nznz', 'ssz'=>'szsz', 'tty'=>'tztz', 'zzs'=>'zzzz', 'Á'=>'az', 'É'=>'ez', 'Í'=>'iz',
       'Ó'=>'oz', 'Ö'=>'ozz', 'Ő'=>'ozz', 'Ú'=>'uz', 'Ü'=>'uzz', 'Ű'=>'uzz', 'CS'=>'cz', 'ZZ'=>'zz', 'CCS'=>'czcz', 'GGY'=>'gzgz', 'LLY'=>'lzlz',
       'NNY'=>'nznz', 'SSZ'=>'szsz', 'TTY'=>'tztz', 'ZZS'=>'zzzz');
       $a = strtr($a,$Hchr);   $b = strtr($b,$Hchr);
       $a=strtolower($a); $b=strtolower($b);
       return strcmp($a, $b);
    }


    function setTitle($title){
        $this->view->title = $title;
    }

    function valtozok($key){
        $d = $this->db->query("SELECT bErtek FROM beallitasok WHERE bKulcs = '$key'");
        $dt = $d->fetch(PDO::FETCH_ASSOC);

        return $dt['bErtek'];
    }

    function getAllValtozo(){
        $v = array();
        $d = $this->db->query("SELECT bErtek, bKulcs FROM beallitasok");
        $dt = $d->fetchAll(PDO::FETCH_ASSOC);

        foreach($dt as $d){
          $ertek = $d['bErtek'];

          if ($ertek != '' && is_object(json_decode($ertek))) {
            $ertek = json_decode($ertek, true);
          }

          $v[$d['bKulcs']] = $ertek;
        }

        $protocol = ($_SERVER['HTTPS']) ? 'https://' : 'http://';

        $v['domain'] = $protocol.str_replace( array('http://','https://'), '', $v['page_url']);

        if (strpos($v['alert_email'],",") !== false)
        {
          $v['alert_email'] = explode(",",$v['alert_email']);
        }

        return $v;
    }

    function setValtozok($key,$val){
        $iq = "UPDATE beallitasok SET bErtek = '$val' WHERE bKulcs = '$key'";
        $this->db->query($iq);
    }

    protected function setThemeFolder($folder = ''){
        $this->theme_folder = $folder;
    }

    protected function getThemeFolder(){
        return $this->theme_folder;
    }

    public function memory_usage()
    {
       echo '-Memory: ',round(memory_get_usage()/1048576,2),' MB used-';
    }
    public function get_speed()
    {
       echo "-Operation Speed:", (number_format($this->finish_time - $this->start_time, 4))," sec-";
    }

    function __destruct(){
        $mode       = false;
        $subfolder  = '';

        if($this->getThemeFolder() != ''){
            $mode       = true;
            $subfolder  = $this->getThemeFolder().'/';
        }

        if(!$this->hidePatern){
            # Render FOOTER
            $this->view->render($subfolder.$this->theme_wire.'footer',$mode);
        }
        $this->db = null;
       // $this->memory_usage();

        $this->finish_time = microtime(true);
        //$this->get_speed();
    }
}

?>
