<?php

include_once "Utils.php";


class mediaFile
{

  const FIND_FILE_COVER = 1;
  const FIND_FILE_METADATA = 2;

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