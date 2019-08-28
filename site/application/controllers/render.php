<? class render extends Controller{
		function __construct(){
			parent::__construct();
		}

		public function thumbnail()
		{
			if ( $_GET['i'] != '') {
				$arg = array();

				if ( $_GET['w'] != '') {
					$arg['s'] = $_GET['w'];
				}

				$img = SOURCE.'uploads/'.$_GET['i'];
				Images::thumbImg( $img , $arg );
			}

		}

		function __destruct(){
			// RENDER OUTPUT
				//parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				//parent::__destruct();				# FOOTER
		}
	}

?>
