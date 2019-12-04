<?
namespace FileManager;

use FileManager\FileChecker;
use FileManager\FileManagerException;
use \DirectoryIterator;

/**
* Class FileLister
* @package FileManager
* @version 1.0
*/
class FileLister {
	const VERSION = '1.0beta';
	
	private $called_folder 	= null;
	private $file_iterator	= null;

	/**
	 * Osztály meghívásakor adjuk meg az ellenőrízni kívánt mappát
	 * @param string $folder A vizsgálanndó mappa elérési útja
	 */
	function __construct($folder) {
		
		// per jel eltávolítás
		$folder = ltrim($folder, '/');

		$check = new FileChecker( $folder );

		// mappa típus vizsgálata
		if( !$check->isDir() ){
			throw new FileManagerException(__CLASS__.': a megadott elérési út ('.$folder.') nem mappa!');
		}

		// mappa olvasás vizsgálata
		if( !$check->readable() ){
			throw new FileManagerException(__CLASS__.': a megadott elérési út ('.$folder.') nem olvasható! Állítsa be a jogokat, hogy olvasható legyen a célmappa!');
		}

		$this->called_folder = $folder;

		$this->file_iterator = new DirectoryIterator(realpath($this->called_folder));
	}

	/**
	 * Aktuálisan használt mappa elérési útja
	 * @return string
	 */
	public function getPath(){
		return $this->called_folder;
	}

	/**
	 * Mappa elemek listázása
	 * @param  array  $filters Szűrők beállítása
	 *                         Ezzel szűkíthető a listázás
	 *                         Használható szűrők:
	 *                         - string allowedExtension css|txt|xml
	 *                         - boolean recursive Rekurzív olvasás. Mappák esetén tovább ellenőrzi a mappa tartalmát 
	 *                         - boolean showOnlyDir false Csak mappákat mutassa 
	 *                         - boolean hideThumbnailImg false Thumbnail képek rejtése, thb_ kezdőtag
	 * @return mixed 	
	 */
	public function getFolderItems(array $filters = array()){
		$items = array();
		$allowed_extensions = false;

		if( isset($filters['allowedExtension']) && is_string($filters['allowedExtension']) && $filters['allowedExtension'] !== '' ) {
			$filters['allowedExtension'] = rtrim($filters['allowedExtension'], '|');
			$filters['allowedExtension'] = ltrim($filters['allowedExtension'], '|');
			$allowed_extensions = explode('|', $filters['allowedExtension']);
		}
		
		foreach( $this->file_iterator as $file ){

			if( $file->isDot() ) continue;

			if( ($allowed_extensions && in_array( $file->getExtension(), $allowed_extensions )) || !$allowed_extensions || $file->isDir() ) {
				$is_dir = $file->isDir();

				if ( $filters['showOnlyDir'] && !$is_dir ) continue;
				if ( $filters['hideThumbnailImg'] && strpos($file->getFilename(), 'thb') === 0 ) continue;

				$items[] = array(
					'name' 		=> $file->getFilename(),
					'size' 		=> $file->getSize(),
					'sizes' 	=> array(
						'kb' 	=> $file->getSize() / 1024,
						'mb' 	=> $file->getSize() / 1024 / 1024
					),
					'extension' => $file->getExtension(),
					'path' 		=> $file->getPath(),
					'short_path'=> $this->called_folder.'/',
					'src_path' 	=> $this->called_folder.'/'.$file->getFilename() . ( ($is_dir) ? '/':'' ),
					'is_dir' 	=> $is_dir,
					'time' 		=> $file->getMTime()
				);	

				if ( $filters['recursive'] && $is_dir ) {
					$items = $this->moreItem( $items, $file->getFilename(), $filters );
				}
			}
		}

		return $items;
	}

	public function moreItem( $items, $subfolder, $filters )
	{
		$sf = $this->called_folder.'/'.$subfolder;

		$allowed_extensions = false;

		if( isset($filters['allowedExtension']) && is_string($filters['allowedExtension']) && $filters['allowedExtension'] !== '' ) {
			$filters['allowedExtension'] = rtrim($filters['allowedExtension'], '|');
			$filters['allowedExtension'] = ltrim($filters['allowedExtension'], '|');
			$allowed_extensions = explode('|', $filters['allowedExtension']);
		}

		$list = new DirectoryIterator(realpath( $sf ));

		foreach( $list as $l ){
			if( $l->isDot() ) continue;

			if ( $filters['showOnlyDir'] && !$is_dir ) continue;

			if ( $filters['hideThumbnailImg'] && strpos($l->getFilename(), 'thb') === 0 ) continue;

			if( ($allowed_extensions && in_array( $l->getExtension(), $allowed_extensions )) || !$allowed_extensions ) {

				$is_dir = $l->isDir();
				$items[] = array(
					'sub' 		=> 1,
					'name' 		=> $l->getFilename(),
					'size' 		=> $l->getSize(),
					'sizes' 	=> array(
						'kb' 	=> $l->getSize() / 1024,
						'mb' 	=> $l->getSize() / 1024 / 1024
					),
					'extension' => $l->getExtension(),
					'path' 		=> $l->getPath(),
					'short_path'=> $sf.'/',
					'src_path' 	=> $sf.'/'.$l->getFilename() . ( ($is_dir) ? '/':'' ),
					'is_dir' 	=> $is_dir,
					'time' 		=> $l->getMTime()
				);

				if ( $filters['recursive'] && $is_dir ) {
					$items = $this->moreItem( $items, $l->getFilename(), $filters );
				}
			}
		}

		return $items;
	}
}
?>