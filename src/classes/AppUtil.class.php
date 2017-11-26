<?php if(!isset($_SESSION)) session_start();

require_once 'config.php';

class AppUtil{

    static public $UPLOAD_SIZE_LIMIT = 5000000;

    static function debug(){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    static function Boolean($in){
        switch (gettype($in)) {
            case 'string':
                if($in === '1' || strtolower($in) === 'true') return TRUE;
                return FALSE;
                break;
            case 'integer':
                if($in === 1) return TRUE;
                return FALSE;
                break;
            default:
                # code...
                break;
        }
    }

    static function ToString($in){
        switch (gettype($in)) {
            case 'string':
                return $in;
                break;
            case 'boolean':
                if($in) return 'true';
                return 'false';
                break;
            default:
                return $in;
                break;
        }
    }

    static public function colorislight($hex) {
        $hex       = str_replace('#', '', $hex);
        $r         = (hexdec(substr($hex, 0, 2)) / 255);
        $g         = (hexdec(substr($hex, 2, 2)) / 255);
        $b         = (hexdec(substr($hex, 4, 2)) / 255);
        $lightness = round((((max($r, $g, $b) + min($r, $g, $b)) / 2) * 100));
        return ($lightness >= 50 ? true : false);
    }

    // $opts = ['genre'=>'f', 'label' => ['singulier'=> 'réponse', 'pluriel' => 'réponses'], 'verbe'=>'Afficher'];
    static function TogglerLabel($n, $opts){
        if($n == 1){
            return $opts['verbe'].(($opts['genre'] == 'f') ? ' la ' : ' le ').$opts['label']['singulier'];
        }
        if($n > 1){
            return $opts['verbe']." les $n ".$opts['label']['pluriel'];
        }
        return NULL;
    }

    static function UniMulti($n, $opts){
        $output = $opts['phrase'];
        $tagToReplace = "{{count}}";
        $output = str_replace($tagToReplace, $n, $output);
        $tagToReplace = "{{nom}}";
        $output = str_replace($tagToReplace, (($n > 1) ? $opts['label']['pluriel'] : $opts['label']['singulier']), $output);
        if($n == 1){
            $tagToReplace = "{{pronom}}";
            return str_replace($tagToReplace, (($opts['genre'] == 'f') ? ' la ' : ' le '), $output);
        }
        if($n > 1){
            $tagToReplace = "{{pronom}}";
            return str_replace($tagToReplace, 'les', $output);
        }

        return NULL;
    }

    static function getUIDType($uid, $lowercase = FALSE){
        $i_ = strpos($uid, '_');
        $entityName = substr($uid, 0, $i_);
        return ($lowercase) ?  strtolower($entityName) : ucfirst($entityName);
    }

    /**
    *   @function getFileData
    *   return {address: <formatted address from google>, date: date from exif (YYYY:MM:DD HH:mm:ss)}
    */
    static function getFileData($filepath){
        $exif = self::EXIF($filepath);
        if(isset($exif['lat']) && isset($exif['lon']))
            $o['address'] = self::getAddress($exif['lat'], $exif['lon'])['formatted_address'];

        if(isset($exif['date']))
            $o['date'] = $exif['date'];

        return $o;
    }

    static function EXIF($filepath){
        function gps2Num($coordPart) {
            $parts = explode('/', $coordPart);

            if (count($parts) <= 0)
                return 0;

            if (count($parts) == 1)
                return $parts[0];

            return floatval($parts[0]) / floatval($parts[1]);
        }

        function getGps($exifCoord, $hemi) {

            $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
            $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
            $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

            $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

            return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

        }
        $exif = exif_read_data($filepath, 'IFD0');
        $o = FALSE;
        if($exif !== FALSE){
            $exif = exif_read_data($filepath, 0, true);
            $o = [];

            if(isset($exif['IFD0']['DateTime'])){
                $o['date'] = $exif['IFD0']['DateTime'];
            }

            if(isset($exif['GPS']["GPSLongitude"])){
                $o['lon'] = getGps($exif['GPS']["GPSLongitude"], $exif['GPS']['GPSLongitudeRef']);
                $o['lat'] = getGps($exif['GPS']["GPSLatitude"], $exif['GPS']['GPSLatitudeRef']);
            }
        }

        return $o;
    }


    // Function to get the client IP address
    static function getClientIP() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }


    static function json_decode_file($filePath, $arrayMode = TRUE){
        if(!file_exists($filePath)) return NULL;
        return json_decode(file_get_contents($filePath), $arrayMode);
    }

    static public function put_file($filepath, $array){
        return (file_put_contents($filepath, json_encode($array)) > 0);
    }

    static function timestampToSQL($timestamp){
        return date("Y-m-d H:i:s", $timestamp);
    }

    static function DateFormatFrench($timestamp, $format = 'U'){
        if($timestamp === NULL || $timestamp === '' || ($format == 'Y-m-d' && strlen($timestamp) !== 10)) return NULL;
        $m_fr = ['01'=>'janvier', '02'=>'février', '03'=>'mars', '04'=>'avril', '05'=>'mai', '06'=>'juin', '07'=>'juillet', '08'=>'août', '09'=>'septembre', '10'=>'octobre', '11'=>'novembre', '12'=>'décembre'];
        switch ($format) {
            // Timestamp Unix (Second since 1st Jan. 1970)
            case 'U':
                return AppUtil::timestampToSQL($timestamp);
            // 2016-11-30
            case 'Y-m-d':
                $date = $timestamp;
                break;
            case 'ELAPSED':
                $UniMultiArgs = [
                    'second' => ['inSecond'=>1, 'genre'=>'f', 'label'=>['singulier'=>'seconde', 'pluriel'=>'secondes']],
                    'minute' => ['inSecond'=>60, 'genre'=>'f', 'label'=>['singulier'=>'minute', 'pluriel'=>'minutes']],
                    'hour' => ['inSecond'=>3600, 'genre'=>'f', 'label'=>['singulier'=>'heure', 'pluriel'=>'heures']],
                    'day' => ['inSecond'=>86400, 'genre'=>'m', 'label'=>['singulier'=>'jour', 'pluriel'=>'jours']],
                    'week' => ['inSecond'=>604800, 'genre'=>'f', 'label'=>['singulier'=>'semaine', 'pluriel'=>'semaines']],
                    'month' => ['inSecond'=>2419200, 'genre'=>'m', 'label'=>['singulier'=>'mois', 'pluriel'=>'mois']],
                    'year' => ['inSecond'=>29030400, 'genre'=>'m', 'label'=>['singulier'=>'an', 'pluriel'=>'ans']]
                ];

                $t = floor(time() - $timestamp);

                $scaleFound = FALSE;
                $i = 0;
                foreach ($UniMultiArgs as $scale => $value) {
                    if($value['inSecond'] > $t){
                        $scale = array_keys($UniMultiArgs)[($i-1>=0)?$i-1:0];
                        break;
                    }
                    $i++;
                }

                $t /= $UniMultiArgs[$scale]['inSecond'];
                $t = floor($t);

                if($t == 0) return 'un instant';

                $UniMultiArgs[$scale]['phrase'] = '{{count}} {{nom}}';

                return AppUtil::UniMulti($t, $UniMultiArgs[$scale]);

            default:
                return NULL;
        }
        $Y = substr($date, 0, 4);
        $m = substr($date, 5, 2);
        $d = substr($date, 8, 2);
        $m_out = $m_fr[$m];
        $d_out = $d == 1 ? '1er' : $d;
        return $d_out.' '.$m_out.' '.$Y;
    }


    // Get the image extension given the mime type
    public static function FileExt($contentType){
        $map = array(
            'application/pdf'   => '.pdf',
            'application/zip'   => '.zip',
            'image/gif'         => '.gif',
            'image/jpeg'        => '.jpg',
            'image/png'         => '.png',
            'text/css'          => '.css',
            'text/html'         => '.html',
            'text/javascript'   => '.js',
            'text/plain'        => '.txt',
            'text/xml'          => '.xml',
        );
        if (isset($map[$contentType]))
        {
            return $map[$contentType];
        }

        // HACKISH CATCH ALL (WHICH IN MY CASE IS
        // PREFERRED OVER THROWING AN EXCEPTION)
        $pieces = explode('/', $contentType);
        return '.' . array_pop($pieces);
    }

    // Upload and set preview for a given project
    static function uploadImage($file, $folder, $filename = NULL){
        try{
            if ($file['error'] !== UPLOAD_ERR_OK)
                throw new Exception('Erreur lors de l\'upload');

            if(!in_array($file['type'], ['image/gif', 'image/jpeg', 'image/png']))
                throw new Exception('Format de fichier invalide');
            $ext = self::FileExt($file['type']);

            if($file['size'] > self::$UPLOAD_SIZE_LIMIT)
                throw new Exception('Fichier trop volumineux. ('.(self::$UPLOAD_SIZE_LIMIT/1000000).'Mo max)');

            // Path to folder where the upload is done
            $folder = (isset($folder)) ? $folder : self::UPLOAD_DIR;
            $basename   = (NULL !== $filename) ? $filename : uniqid('media_');

            // // Build the filename
            $clean = self::toAscii($basename);
            $filename =  $clean.$ext;
            // Build the file path
            $filepath =  $folder.$filename;

            // Remove all duplicates
            foreach (glob($folder.$clean.'*') as $path)
                unlink($path);


            if(@!move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$filepath))
                throw new Exception('Erreur lors de l\'ajout du fichier');

            $cacheCleaner = '?'.time();
            return ['filename'=>$filepath, 'size'=>$file['size'], 'contentType'=>$file['type']];
        } catch (Exception $e) {
            return FALSE;
        }
    }

    static function MakeLink($label, $opts) {
        $slug = isset($opts['slug']) ? $opts['slug'] : NULL;
        $uid = isset($opts['uid']) ? $opts['uid'] : NULL;
        $classes = isset($opts['classes']) ? $opts['classes'] : [];
        $href = (isset($slug)) ? $slug : $uid;

        // The link markup
        ob_start(); ?>
            <a href="#<?=$href?>" class="<?=implode(' ', $classes)?>"><?=$label?></a>
        <?php return ob_get_clean();
    }

    static function ContextMenu($data){
        if(!isset($data['ContextMenu']))
            return new Exception('No ContextMenu reference');

        $tplFile = '../templates/tooltip/'.$data['ContextMenu'].'.php';
        if(!file_exists($tplFile))
            return new Exception('No ContextMenu template');

        $l = new Template($tplFile, []);
        $html = $l->output();

         return [
             'log'=>'ContextMenu load',
             'out'=>$html
         ];
    }


    static function ValueOrNULL($arr, $key){
         return isset($arr[$key]) ? $arr[$key] : NULL;
    }

    static public function checkArgument($post, $k){
        try {
            if(!isset($post[$k]))
                throw new Exception("AppUtil::checkArgument $k not defined", 1);

            return $post[$k];
        } catch (Exception $e) {
            AppUtil::error($e->getMessage());
        }
    }

    // Check if the return of a call has thrown an Exception
    // If Exception thrown, send error and leave script.
    static function check($response){
        gettype($response) === 'object' && get_class($response) === 'Exception' && self::error($response->getMessage());
    }
    static function send($values, $error = FALSE){
        self::check($values);

        $out = [
                    'error'=>$error,
                    'log' => isset($values['log']) ? $values['log'] : NULL,
                    'out' => isset($values['out']) ? $values['out'] : $values,
                ];
        echo json_encode($out);
        die();
    }
    // Send error flag and error massages
    static function error($out = "Erreur indéfinie"){
        self::send($out, TRUE);
    }


    static public function FolderList($folder){
        $folders = [];
        foreach (scandir($folder) as $key => $value) {

            if($key > 1){ // Skip . & ..

                // Do not display these files
                if(in_array($value, [
                    '.DS_Store',
                    'localhost.css',
                    'index.php',
                    'data'
                ])) continue;

                // Get folder's info.json
                $infoData = NULL;
                $finfoFile =  $folder.'/'.$value.'/info.json';
                if(file_exists($finfoFile))
                    $infoData = json_decode(file_get_contents($finfoFile), TRUE);

                // $folderInfo = isset($infoData->{'folder-info'}) ? $infoData->{'folder-info'} : NULL;
                // $folderImg = isset($infoData->{'folder-preview'}) ? $infoData->{'folder-preview'} : NULL;
                $model = array_merge([
                    'folderPath' =>  $value,
                    'folder-preview' =>  NULL,
                    'folder-info' =>  NULL,
                    'type' => NULL
                ], $infoData !== NULL ? $infoData : []);


                ob_start(); ?>
                    <section data-type="<?=$model['type']?>" class="folder" onclick="location.assign('<?=$model['folderPath']?>')">
                        <figure>
                            <img src="<?=$model['folder-preview']?>" alt="" onerror="this.parentNode.style.display = 'none';">
                        </figure>
                        <a href="/<?=$model['folderPath']?>"><?=$model['folderPath']?></a>
                        <span class="folder-info"><?=$model['folder-info']?></span>
                    </section>
                <?php $model['html'] = ob_get_clean();

                $folders[] = $model;
            }
        }

         // Sort the multidimensional array by category
         usort($folders, function($a,$b) {
              return $a['type']<$b['type'];
         });


         ob_start();
         echo '<nav class="folders">';
         $categories = [];
         foreach ($folders as $key => $folder) {
             if(!in_array($folder['type'], $categories)){
                $categories[] = $folder['type'];
                echo '<h1 style="width:100%; float:left; padding: .5em;">'.ucfirst($folder['type']).'</h1>';
            }
             echo $folder['html'];
         }
         echo '</nav>';
         return ob_get_clean();
    }

}

// Get the time ig GET is "now"
if(isset($_GET['now']) && array_keys($_GET)[0] == $_GET['now']) AppUtil::send(date('Y-m-d H:i:s'));
if(isset($_GET['post']) && array_keys($_GET)[0] == $_GET['post']) AppUtil::send($_POST);
