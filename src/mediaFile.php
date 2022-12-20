<?php

include_once "Utils.php";
include_once "vendor/getid3/getid3.php";

class mediaFile
{
  protected $detectableFiles = [];
  protected $aceptableMetadataFiles = [];
  protected $aceptableCoverFiles = [];
  protected $aceptablePartnerFiles = [];
  protected $aceptablePrefixFiles = [];
  protected $aceptableSufixFiles = [];

  protected $file;
  protected $info;
  protected $getId;

  protected $useID3 = false;

  protected $units = array(
    'bit' => 0.125,
    'KB' => 1024,
    'MB' => 1048576,
    'GB' => 1073741824,
    'TB' => 1099511627776
  );

  public function __construct($file, $useID3 = true)
  {
    if (is_file($file))
    {
      $this->file = $file;
      $this->useID3 = $useID3;
      if ($this->useID3) 
        $this->initializeID3(); 
      else 
        $this->getSimpleInfo();
    } else throw new Exception("{$file} is not a file.");
  }

  protected function initializeID3()
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
  protected function infoID3()
  {
    // Analyze file
		$this->info = $this->getID3->analyze($this->file);

		// Exit here on error
		if (isset($this->info['error'])) {
			throw new Exception("'{$this->file}' has raised an error: " . $this->info['error']);
		}
  }

  protected function getSimpleInfo()
  {
    $this->info = Utils::getInfoFromFile($this->file);
    if (empty($this->info)) throw new Exception("String '{$this->file}' is not a file.");
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


}