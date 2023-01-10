<?php

include_once "Utils.php";


class mediaFile
{

  const FIND_FILE_COVER = 1;
  const FIND_FILE_METADATA = 2;

  /**
   * To find out any file with the same name as file but different format. False check any file with different format.
   * @var Boolean $use_name_as_pattern 
   */
  public $use_name_as_pattern = true;

  protected $detectableFiles = [];
  protected $aceptableMetadataFiles = [];
  protected $aceptableCoverFiles = [];
  protected $aceptablePartnerFiles = [];
  protected $aceptablePrefixFiles = [];
  protected $aceptableSufixFiles = [];

  protected $fullfile;
  protected $path_file;
  protected $name_file;
  protected $info;
  protected $getId;
  protected $getID3;

  protected $units = array(
    'bit' => 0.125,
    'KB' => 1024,
    'MB' => 1048576,
    'GB' => 1073741824,
    'TB' => 1099511627776
  );

  public function __construct($file)
  {
    if (is_file($file))
    {
      $this->fullfile = $file;
      $this->path_file = Utils::getPathFromFilePath($file);
      $this->name_file = Utils::getFilenameFromFilePath($file);
    } else throw new Exception("{$file} is not a file.");
  }

  public function getFullData()
  {
    $ret = [];

    $n = $this->getFriendlyName();
    $n = (!empty($n)) ? $n : $this->name_file;
    // Datos básicos del sistema
    $ret[$n]['system'] = $this->getSimpleInfo();


    // Cover si lo hubiera
    $c = $this->getCover();
    if (!empty($c)) $ret[$n]['cover'] = (count($c) > 1) ? $c : $c[0];

    // Metadata si lo hubiera
    $r = $this->getMetadata();
    if (!empty($r)) $ret[$n]['metadata'] = $r;

    return $ret;
  }

  private function initializeID3()
  {
    // Initialize getID3 engine
		$this->getID3 = new getID3;
		$this->getID3->option_md5_data        = true;
		$this->getID3->option_md5_data_source = true;
		$this->getID3->encoding               = 'UTF-8';
  }

  /**
	* Extract information using getID3
	*
	* @param    string  $file    Audio file to extract info from.
	*
	* @return array
	*/
  protected function getInfoID3()
  {
    $this->initializeID3();
    // Analyze file
		$this->info = $this->getID3->analyze($this->fullfile);

		// Exit here on error
		if (isset($this->info['error'])) {
			throw new Exception("'{$this->fullfile}' has raised an error: " . $this->info['error']);
		}
  }

  protected function getSimpleInfo()
  {
    $this->info = Utils::getInfoFromFile($this->fullfile);
    if (empty($this->info)) throw new Exception("String '{$this->fullfile}' is not a file.");
    return $this->info;
  }

    /**
     * return size of file
     * @param string $type unit default - kilobytes
     * allowed types:
     * KB: Kilobytes
     * MB: Megabytes
     * GB: Gigabytes
     * TB: Terabytes
     * bit: bites
     * @return int size of file
     * */
  protected function getSize($type = "KB")
  {
      $n_type = isset($this->units[$type]) ? $this->units[$type] : 1;
      return (isset($this->info['size'])) ? $this->info['size'] * $n_type : 0;
  }

  protected function getBase64()
  {
    if (!array_key_exists('fileformat', $this->info)) return null;

    $data = file_get_contents($this->fullfile);
    $base64 = 'data:image/' . ((strtolower($this->info['fileformat']) == 'svg') ? 'svg+xml' : $this->info['fileformat']) . ';base64,';
    return $base64 . base64_encode($data);
  }

  protected function getFriendlyName()
  {
    if (!array_key_exists('filerealname', $this->info)) return null;
    $n = '';
    $n = $this->info['filerealname'];
    $n = str_replace("_", " ", $n);
    $n = strtolower($n);
    $n = ucwords($n);
    return $n;
  }

  protected function getCover()
  {
    $ret = [];


    return $ret;
  }

  protected function getMetadata()
  {
    $ret = [];




    return $ret;
  }

  protected function findPartnerFiles()
  {
    $ret = [];
    // Empezamos por el cover : $aceptableCoverFiles


    // Luego los metadatos : $aceptableMetadataFiles


    return $ret;
  }

  private function arrItinerator($arr)
  {
    if (empty($arr)) return $arr;
    $ret = [];
    $fullpath = $this->path_file.DIRECTORY_SEPARATOR;
    foreach ($arr as $value)
    {
      if (strpos($value, '*') !== false || strpos($value, '.') === false)
      {
        $usename = ($this->use_name_as_pattern) ? $this->name_file : '*';
        $pat = (strpos($value, '.') === false) ? $usename.'.'.$value : $value;
        foreach (glob($fullpath.$pat) as $file)
          $ret[] = $fullpath.$file;
      } else if (file_exists($fullpath.$value)) $ret[] = $fullpath.$value;
    }

    return $ret;
  }
}