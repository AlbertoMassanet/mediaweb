<?php



class Utils
{
    public static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function isValid($pattern, $arr)
    {
        if(!is_array($arr)) return false;
        if (array_key_exists($pattern, $arr) && !empty($arr[$pattern])) return true;
        return false;
    }

    /**
     * Checks to see if a variable is a valid JSON string
     *
     * @param string $string The value to check
     * @return boolean TRUE if the value is JSON, FALSE if not
     */
    public static function isValidJSON($string) {
        @json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function getUniqueString($value, $divider = ',')
    {
        return (is_array($value)) ? implode($divider, $value) : $value;
    }

    /**
     * Removes any character in an array from string
     *
     * @param String $str String to be sanitized
     * @param Array $invalid_characters Array of Characters to be removed from string
     * @return String Sanitized string
     */
    public static function clearStringOf($str, $invalid_characters = array("$", "%", "#", "<", ">", "|"))
    {
        //$invalid_characters = array("$", "%", "#", "<", ">", "|");
         return str_replace($invalid_characters, "", $str);
    }

    /**
     * Validate url
     * @throws Exception
     */
    public static function ValidateUrl(string $url, bool $use_regex = false)
    {
        $regex = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

        if ($use_regex && !preg_match($regex, $url)) {
            //throw new Exception("Invalid URL");
            return false;
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            //throw new Exception("Invalid URL");
            return false;
        }
        return true;
    }

    public static function formatFileSize ($bytes, $precision = 2)
    {
        if (!is_integer($bytes)) return $bytes;
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }

    public static function getInfoFromFile($file)
    {
        if (!is_file($file)) return [];

        $file_info = [];
        $pathinfo = pathinfo($file);
        $stat = stat($file);

            $file_info['realpath'] = realpath($file);
            $file_info['dirname'] = $pathinfo['dirname'];
            $file_info['basename'] = $pathinfo['basename'];
            $file_info['filename'] = $pathinfo['filename'];
            $file_info['extension'] = $pathinfo['extension'];
            $file_info['mime'] = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
            $file_info['encoding'] = finfo_file(finfo_open(FILEINFO_MIME_ENCODING), $file);
            $file_info['size'] = $stat[7];
            $file_info['filesize'] = self::formatFileSize($stat[7]);
        
        return $file_info;
    }

    public static function getExtension($nombre_fichero)
    {
        return strtolower(substr(strrchr($nombre_fichero, '.'), 1));
    }

    public static function saveToFile($file, Array $arr_data, $fullExcp = false)
    {
        $encodedString = json_encode($arr_data);
        try {
            return file_put_contents($file, $encodedString);
        } catch (Exception $e) {
            $r = $e->getMessage();
            if ($fullExcp)
            {
                $r .= " /Code: " . $e->getCode() . " /Line: " . $e->getLine();
            }
            return $r;
        }
        //return file_put_contents($file, $encodedString) ?? null;
    }

    public static function loadFromFile($file, $fullExcp = false)
    {
        try {
            $fileContents = file_get_contents($file);
            return json_decode($fileContents, true);
        } catch (Exception $e) {
            $r = $e->getMessage();
            if ($fullExcp)
            {
                $r .= " /Code: " . $e->getCode() . " /Line: " . $e->getLine();
            }
            return $r;
        }
        
        
    }

    public static function getAbsolutePath(string $path)
    {
        // Cleaning path regarding OS
        $path = mb_ereg_replace('\\\\|/', DIRECTORY_SEPARATOR, $path, 'msr');
        // Check if path start with a separator (UNIX)
        $startWithSeparator = $path[0] === DIRECTORY_SEPARATOR;
        // Check if start with drive letter
        preg_match('/^[a-z]:/', $path, $matches);
        $startWithLetterDir = isset($matches[0]) ? $matches[0] : false;
        // Get and filter empty sub paths
        $subPaths = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'mb_strlen');

        $absolutes = []; $len_s = count($subPaths)-1; $s = 0;
        foreach ($subPaths as $subPath) {
            $s++;
            if ('.' === $subPath) {
                continue;
            }
            // if $startWithSeparator is false
            // and $startWithLetterDir
            // and (absolutes is empty or all previous values are ..)
            // save absolute cause that's a relative and we can't deal with that and just forget that we want go up
            if ('..' === $subPath
                && !$startWithSeparator
                && !$startWithLetterDir
                && empty(array_filter($absolutes, function ($value) { return !('..' === $value); }))
            ) {
                $absolutes[] = $subPath;
                continue;
            }
            if ('..' === $subPath) {
                array_pop($absolutes);
                continue;
            }
            if ($s <= $len_s) $absolutes[] = $subPath;
        }

        return
            (($startWithSeparator ? DIRECTORY_SEPARATOR : $startWithLetterDir) ?
                $startWithLetterDir.DIRECTORY_SEPARATOR : ''
            ).implode(DIRECTORY_SEPARATOR, $absolutes);
    }

    public static function arrayKeySearch(array $haystack, string $search_key, &$output_value, int $occurence = 1){
        $result             = false;
        $search_occurences  = 0;
        //$output_value       = null;
        if($occurence < 1){ $occurence = 1; }
        foreach($haystack as $key => $value){
            if($key == $search_key){
                $search_occurences++;
                if($search_occurences == $occurence){
                    $result         = true;
                    $output_value[] = $value;
                    break;
                }
            }else if(is_array($value) || is_object($value)){
                if(is_object($value)){
                    $value = (array)$value;
                }
                $result = self::arrayKeySearch($value, $search_key, $output_value, $occurence);
                if($result){
                    break;
                }
            }
        }
        return $result;
    }

    public static function getFilenameFromFilePath($path)
    {
        return (is_file($path)) ? basename($path) : "";
    }

    public static function getPathFromFilePath($path)
    {
        return (is_file($path)) ? pathinfo($path, PATHINFO_DIRNAME) : "";
    }

    public static function removeHtml($text)
    {
        $text = strip_tags($text);
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }

    public static function convertHtml($text)
    {
        return htmlentities($text, ENT_DISALLOWED | ENT_COMPAT | ENT_HTML5, "UTF-8");
    }

    /**
     * Removes any dangerous javascript, HTML, or CSS code in a string
     *
     * @param String $input String to be sanitized
     * @return String Sanitized string
     */
    public static function sanitize($input) {
        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
        );
        return preg_replace($search, '', $input);
    }
}