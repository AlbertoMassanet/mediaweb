<?php

namespace app\Controllers;

use app\Models\Media;
use app\Utils;
use Exception;

class Controller
{
  protected $media;
  protected $media_type;

  protected $title;

  protected $mainPath;

  protected $config = [];
  protected $data = [];

  public $actions = [];

  public function __construct()
  {
    if (file_exists(SETTING_JSON_FILE)) 
    {
      $t = Utils::loadFromFile(SETTING_JSON_FILE);
      if (is_array($t)) $this->config = $t;
      else throw new Exception("Error reading config file: " . $t);
    }
  }

  protected function getMedia()
  {

  }

  public function view($route, $data = [])
  {

    // Destructurar array
    extract($data);
    
    $route = str_replace('.', '/', $route);
    $activeArr = explode('/', $route);
    $active = $activeArr[count($activeArr) -1];
    
      if (file_exists("../resources/views/{$route}.php"))
      {

        
        ob_start();
        include "../resources/views/{$route}.php";
        $content = ob_get_clean();


        return $content;

      } else {
        http_response_code(500);
        return "No view with that name";
      }
  }

}