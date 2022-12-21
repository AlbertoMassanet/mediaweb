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

  public static $MEDIA_TYPES = [
    "book" => ["epub", "html", "htm", "pdf", "rtf", "txt", "cbc", "fb2", "lit", "mobi", "odt", "doc", "docx", "prc", "pdb", "pml", "cbz", "cbr"],
    "audio" => ["mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"],
    "video" => ["avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"],
    "images" => ["jpg", "jpeg", "gif", "png"],
  ];
  
  protected $path = null;
  protected $files = [];

  private $arrPath = [];
  private $activeDir;

  public function __construct($path)
  {
    if (!empty($path)) $this->path = $path;
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

  private function _recursiveDir($path, $media = "")
  {
    $files = array();
   
    if(is_dir($path))
    {
        if($handle = opendir($path))
        {
            while(($name = readdir($handle)) !== false)
            {
                if(!preg_match("#^\.#", $name))
                  if(is_dir($path . "/" . $name))
                  {
                      $files[$name] = $this->_recursiveDir($path . "/" . $name);
                  }
                  else
                  {
                      $files[] = $name;
                  }
            }
           
            closedir($handle);
        }
    }

    return $files;

  }

    // List files in tree, matching wildcards * and ?
  public function tree($path){
    static $match;

    // Find the real directory part of the path, and set the match parameter
    $last=strrpos($path,"/");
    if(!is_dir($path)){
      $match=substr($path,$last);
      while(!is_dir($path=substr($path,0,$last)) && $last!==false)
        $last=strrpos($path,"/",-1);
    }
    if(empty($match)) $match="/*";
    if(!$path=realpath($path)) return;

    // List files
    foreach(glob($path.$match) as $file){
      $list[]=substr($file,strrpos($file,"/")+1);
    } 

    // Process sub directories
    foreach(glob("$path/*", GLOB_ONLYDIR) as $dir){
      $list[substr($dir,strrpos($dir,"/",-1)+1)]=$this->tree($dir);
    }
  
    return @$list;
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
   * findFiles("C:", array (
    *  "jpg",
    *  "pdf",
    *  "png",
    *  "html"
    *))
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