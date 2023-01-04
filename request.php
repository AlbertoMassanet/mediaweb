<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


// HTTP response status codes
define('RETURN_CREATE_OK', 201);
define('RETURN_READ_OK', 200);
define('RETURN_UPDATE_OK', 204);
define('RETURN_DELETE_OK', 200);
define('RETURN_ERROR_BAD_REQUEST', 400);

// Constants
define('SETTING_JSON_FILE', './config.json');
define('PATH_DATA_FILES', './');

include_once './src/Media.php';
// include_once './src/vendor/Request/Request.php';

$media_types = [
  "book" => ["epub", "html", "htm", "pdf", "rtf", "txt", "cbc", "fb2", "lit", "mobi", "odt", "doc", "docx", "prc", "pdb", "pml", "cbz", "cbr"],
  "audio" => ["mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"],
  "video" => ["avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"],
  "images" => ["jpg", "jpeg", "gif", "png"],
];

/**
 * Search a media type and return an array
 * 
 * @param String $media media type (audio, video, book or images)
 * @return Array if invalid media type or not found return null
 */
function executeSearch($media)
{
  global $settingsArr, $media_types;
  
  if (isset($settingsArr['pathbook']) && $media == "book") $pathmedia = $settingsArr['pathbook'];
  if (isset($settingsArr['pathvideo']) && $media == "video") $pathmedia = $settingsArr['pathvideo'];
  if (isset($settingsArr['pathaudio']) && $media == "audio") $pathmedia = $settingsArr['pathaudio'];
  if (isset($settingsArr['pathimage']) && $media == "images") $pathmedia = $settingsArr['pathimage'];
  
  if (!isset($pathmedia)) return null;
  $obj = new Media($pathmedia, $media, $media_types[$media], $settingsArr);
  if ($obj) $res = $obj->run();
  return ($res) ?? $obj->run();
  //TODO
}

// Read setting json file
$settingsArr = [];
if (file_exists(SETTING_JSON_FILE)) $settingsArr = Utils::loadFromFile(SETTING_JSON_FILE);
// die("Leyendo settingArr: " . print_r($settingsArr,true));

$arrRecieved = null;
$recieved= file_get_contents("php://input");
if (Utils::isValidJSON($recieved)) $arrRecieved = json_decode($recieved, true);

$objSent = null;
$returnType = RETURN_ERROR_BAD_REQUEST;

//die(print_r($arrRecieved,true));

if (is_array($arrRecieved))
{
  /**
   * POST
   */

   if (key_exists('savefile', $arrRecieved))
   {
     if (file_exists(SETTING_JSON_FILE))
     {
       unlink(SETTING_JSON_FILE);
     }
   
     $res = Utils::saveToFile(SETTING_JSON_FILE, $arrRecieved, true);
     
     if (is_string($res))
     {
       $objSent = $res;
       $returnType = RETURN_ERROR_BAD_REQUEST;
     } else {
       $returnType = RETURN_CREATE_OK;
       $objSent = $res;
     }
   }
} else {
  /**
   * GET
   */
  if (key_exists('read', $_REQUEST))
  {
    if (file_exists(SETTING_JSON_FILE))
    {
      $res = Utils::loadFromFile(SETTING_JSON_FILE);
      $objSent = $res;
      $returnType = (!is_array($res)) ? RETURN_ERROR_BAD_REQUEST : RETURN_READ_OK;
      
    } else {
      $objSent = ['error' => 'No file found'];
    }
  } else
  if (key_exists('media', $_REQUEST))
  {
    $res = executeSearch($_REQUEST['media']);
    //die("No llega nada: " . print_r($res,true));
    if ($res)
    {
      $objSent = $res;
      $returnType = RETURN_READ_OK;
    }
  } else
  if (in_array($media_types, $_REQUEST)) {
    foreach ($media_types as $key => $v)
    {
      if (array_key_exists($key, $_REQUEST)) $res = executeSearch($key);
    }

    if ($res)
    {
      $objSent = $res;
      $returnType = RETURN_READ_OK;
    }
  }

}


// if ($returnType == RETURN_ERROR_BAD_REQUEST) die("No llega nada: " . print_r($arrRecieved,true));



http_response_code($returnType);
if (!is_null($objSent))
{
  header("Expires: 0");
  header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
  header("Pragma: no-cache");
  header('Content-type: application/json; charset=utf-8');

  echo json_encode($objSent);
} 
// else {
//   header('HTTP/1.0 404 Not Found');
//   echo "<h1>Error 404 Not Found</h1>";
//   echo "The page that you have requested could not be found.";
//   exit();
// }




