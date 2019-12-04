<?
final class Post{
    public static $p= null;
    public static final function on($key){
        if(isset($_POST[$key])){
            self::$p = $_POST;
            return true;
        }else return false;
    }

    public static final function explode($data){
        $back = array();
            $prefix = self::getPrefixForExplode($data);
            foreach($data as $dk => $dv){
                $back[trim(str_replace($prefix,'',$dk))] = (is_array($dv))?$dv:trim($dv);
            }
            unset($back['submit']);

        return $back;
    }

    public static function cutToKeyVal($data_arry){
        $ret = array();
            $ret['keys'] = array();
            $ret['vals'] = array();

            foreach($data_arry as $dk => $dv){
                $ret['keys'][] = $dk;
                $ret['vals'][] = $dv;
            }

        return $ret;
    }

    private static final function getPrefixForExplode($data_arry){
        $prefix = '';
        $step   = 0;
        $key    = '';

        foreach($data_arry as $dk => $dv){
            if($step >1){ continue; }
                $key = explode("_",$dk);
                $key = $key[0]."_";

            $step++;
        }

        $prefix = $key;
        return $prefix;
    }
}
?>
