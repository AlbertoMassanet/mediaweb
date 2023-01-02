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
define('SETTING_JSON_FILE', './save.json');


include_once './src/Media.php';
include_once './src/vendor/Request/Request.php';

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
 * @return Array if invalid media type or not found return empty array
 */
function executeSearch($media)
{
  global $settingsArr, $media_types;
  
  if (isset($settingsArr['pathbook']) && $media == "book") $pathmedia = $settingsArr['pathbook'];
  if (isset($settingsArr['pathvideo']) && $media == "video") $pathmedia = $settingsArr['pathvideo'];
  if (isset($settingsArr['pathaudio']) && $media == "audio") $pathmedia = $settingsArr['pathaudio'];
  if (isset($settingsArr['pathimage']) && $media == "images") $pathmedia = $settingsArr['pathimage'];
  if (!isset($pathmedia)) return [];
  $obj = new Media($pathmedia, $media, $media_types[$media], $settingsArr);
  return $obj->run();
  //TODO
}

// Read setting json file
$settingsArr = [];
if (file_exists(SETTING_JSON_FILE)) $settingsArr = Utils::loadFromFile(SETTING_JSON_FILE);


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
     if (file_exists(SAVEFILE))
     {
       unlink(SAVEFILE);
     }
   
     $res = Utils::saveToFile(SAVEFILE, $arrRecieved, true);
     
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
  }

  if (key_exists('media', $_REQUEST))
  {
    if ($res = executeSearch($_REQUEST['media']) != []) 
    {
      $objSent = $res;
      $returnType = RETURN_READ_OK;
    }
  }

}






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




