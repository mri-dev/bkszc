<?
namespace PortalManager;

/** 
* class Image
* @package PortalManager
* @version v1.0
*/
class Image
{
	private $image = null;

	const ORIENTATION_LANDSCAPE = 1;
	const ORIENTATION_PORTRAIT = 2;
	const ORIENTATION_CUBE = -1;

	function __construct( $image ) {
		if ( !file_exists( $image ) ) {			
			throw new \Exception( __CLASS__."(): A megadott képfájl nem létezik vagy nem olvasható: ".$image );
		}

		$this->image = $image;

		return $this;
	}

	public function orientation()
	{
		list( $w, $h ) = getimagesize( $this->image ); 

		if ( $w > $h ) {			
			return self::ORIENTATION_LANDSCAPE;
		} else if( $w < $h ) {
			return self::ORIENTATION_PORTRAIT;
		} else if( $w == $h ) {
			return self::ORIENTATION_CUBE;
		}
	}

	public static function makeThumbnail( $src, $dir, $name, $pref, $maxWidth, $type ){
		// Alap műveletek
			# Forrás fájl másolása
			copy($src,$dir.$pref.$name.$type);
			# Forrás kép elérése
			$src = $dir.$pref.$name.$type;
			# Virtuálos kép létrehozás
			switch ($type) {
				case '.jpg':
					$wi = imagecreatefromjpeg($src);
				break;
				case '.png':
					$wi = imagecreatefrompng($src);
				break;				
			}
			
			# Kép méreteinek beolvasása
			list($iw,$ih) 	= getimagesize($src);
		
		// Méretarányos méretcsökkentés
		$dHeight = floor($ih * ($maxWidth / $iw));

		switch ($type) {
			case '.jpg':
				
			break;
			case '.png':
				/*imagealphablending($wi, false);
				imagesavealpha($wi, true); 	*/			
			break;				
		}
		
		// Kép módosító
  		$vi = imagecreatetruecolor($maxWidth, $dHeight);

  		switch ($type) {
			case '.jpg':
				imagejpeg($vi,$dir.$pref.$name.$type,85);
			break;
			case '.png':
				imagealphablending($vi, false);
				imagesavealpha($vi,true);
				$transparent = imagecolorallocatealpha($vi, 255, 255, 255, 127);
				imagefilledrectangle($vi, 0, 0, $maxWidth, $dHeight, $transparent);			
			break;				
		}
			
  		imagecopyresampled($vi, $wi, 0, 0, 0, 0, $maxWidth, $dHeight, $iw, $ih);
		
		// Módosítások érvényesítése / Output
		switch ($type) {
			case '.jpg':
				imagejpeg($vi,$dir.$pref.$name.$type,85);
			break;
			case '.png':
				imagepng($vi,$dir.$pref.$name.$type);				
			break;				
		}
			
		// Temponális változók eltávolítása
		imagedestroy($vi);
		imagedestroy($wi);
	}
}
?>