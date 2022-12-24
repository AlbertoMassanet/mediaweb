<?php

include_once "mediaImages.php";
include_once "mediaBook.php";
include_once "mediaAudio.php";
include_once "mediaVideo.php";

/**
 * ITINERATOR OF FILES
 */

class mediaFiles
{
  public $media_types = [];
  public $media = "";

  public $path = null;
  public $files = [];

  protected $objMedia;

  private $arrPath = [];
  private $activeDir;

  public function __construct($path, $media = "", $media_types = [])
  {
    if (!empty($path)) 
    {
      $this->path = $path;
      if (!empty($media)) $this->media = $media;
      if (!empty($media_types)) $this->media_types = $media_types;
    }
    else throw new Exception("Path cannot be empty.");
  }
  /**
   * Search files 
   * 
   * @param String $media Media Type to search into path (if empty, search all media types)
   * @return Array if invalid media type or not found return empty array
   */
  protected function searchFilesByMediaType($media = "")
  {
    //TODO
  }

  /**
   * List specified extension files in directories recursively 
   * 
   * @param String $dir Full path to directory. Ex. '/path/to/directory'
   * @param Array $fileTypes Array of extensions to find Ex. array('jpg', 'png', 'gif')
   * 
   * @return Array 
   */
  protected function listDirectory($dir, $fileTypes) {
    // Abrimos el directorio
    $directory = opendir($dir);
  
    // Creamos un array para almacenar los archivos que cumplan con los tipos especificados
    $selectedFiles = array();
  
    // Iteramos a través de cada archivo del directorio
    while (($file = readdir($directory)) !== false) {
      // Verificamos si el archivo es un directorio o no
      if (is_dir($dir . '/' . $file)) {
        // Si es un directorio, llamamos recursivamente a la función
        $selectedFiles = array_merge($selectedFiles, $this->listDirectory($dir . '/' . $file, $fileTypes));
      } else {
        // Si no es un directorio, verificamos si el archivo cumple con los tipos especificados
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (in_array($extension, $fileTypes)) {
          // Si cumple, agregamos el archivo al array
          $selectedFiles[] = $dir . '/' . $file;
        }
      }
    }
  
    // Cerramos el directorio y devolvemos el array con los archivos seleccionados
    closedir($directory);
    return $selectedFiles;
  }

    // List files in tree, matching wildcards * and ?
  protected function tree($path){
    static $match;

    // Find the real directory part of the path, and set the match parameter
    $last=strrpos($path,"/");
    if(!is_dir($path)){
      $match=substr($path,$last);
      while(!is_dir($path=substr($path,0,$last)) && $last!==false)
        $last=strrpos($path,"/",-1);
    }
    $patt = (!empty($this->media_types) && !empty($this->media)) ? '.{' . implode(',', $this->media_types[$this->media]) . '}' : '';
    if(empty($match)) $match="/*" . $patt;
    if(!$path=realpath($path)) return;

    // List files
    foreach(glob($path.$match, GLOB_BRACE) as $file){
      $list[]=substr($file,strrpos($file,"/")+1);
    } 

    // Process sub directories
    foreach(glob("$path/*", GLOB_ONLYDIR) as $dir){
      $r = $this->tree($dir);
      if ($r) $list[substr($dir,strrpos($dir,"/",-1)+1)]=$r;
    }
  
    return ($list) ?? @$list;
  }

  public function getRecursiveFolderList($curDir,$currentA=false)
  {                   
    $dirs = glob($curDir . '/*', GLOB_ONLYDIR);    
   
    $cur = 0;
    foreach($dirs as $dir)
      {
        $currentA[$cur]['path'] = $dir;
        $currentA[$cur] = $this->getRecursiveFolderList($dir,$currentA[$cur]);
           
        ++$cur;
      }

    return $currentA;
  }

  /**
   * Find specific files from a directory
   * findFiles("C:", array (
    *  "jpg",
    *  "pdf",
    *  "png",
    *  "html"
    * ))
    *
   */
  public function findFiles($directory, $extensions = array()) {
    function glob_recursive($directory, &$directories = array()) {
        foreach(glob($directory, GLOB_ONLYDIR | GLOB_NOSORT) as $folder) {
            $directories[] = $folder;
            glob_recursive("{$folder}/*", $directories);
        }
    }
    glob_recursive($directory, $directories);
    $files = array ();
    foreach($directories as $directory) {
        foreach($extensions as $extension) {
            foreach(glob("{$directory}/*.{$extension}") as $file) {
                $files[$extension][] = $file;
            }
        }
    }
    return $files;
  }
}