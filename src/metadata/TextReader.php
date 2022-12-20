<?php


class TextReader
{
  public $posiblesTitulos = [
      'título original',
      'TÍTULO ORIGINAL',
      'titulo original',
      'título',
      'TÍTULO',
      'titulo'
  ];

  public $posiblesTitulosAlt = [
      'otros títulos',
      'OTROS TÍTULOS',
      'otros titulos',
  ];

  // TODO: No esta funcionando correctamente
  public $posiblesDireccion = [
      'DIRECCIÓN',
      'director',
      'DIRECTOR',
      'direccion',
      'dirección',
  ];

  public $posiblesReparto = [
      'reparto',
  ];

  public $posiblesAno = [
      'AÑO',
      'año',
  ];

  public $posiblesPais = [
      'país',
      'PAÍS',
      'pais',
  ];

  public $posiblesProductora = [
      'productora',
  ];

  public $posiblesGenero = [
      'GÉNERO',
      'género',
      'genero',
  ];

  private $title = "";
  private $altTitle = "";
  private $year = "";
  private $country = "";
  private $produc = "";
  private $direc = [];
  private $cast = [];
  private $tag = [];

  private $text, $original_text;

  public function __construct($text)
  {
    $this->original_text = $text;
    $this->text = $text;
    $this->generateValues();
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getAltTitle()
  {
    return $this->altTitle;
  }

  // Return Arr
  public function getDir()
  {
    return $this->direc;
  }

  // Return Arr
  public function getCast()
  {
    return $this->cast;
  }

  public function getYear()
  {
    return $this->year;
  }

  public function getCountry()
  {
    return $this->country;
  }

  public function getProd()
  {
    return $this->produc;
  }

  // Return Arr
  public function getTag()
  {
    return $this->tag;
  }


  public function getRemainder()
  {
    return $this->text;
  }

  public function getOriginal()
  {
    return $this->original_text;
  }

  protected function generateValues()
  {
    
    $this->title = $this->leer($this->original_text, $this->posiblesTitulos);
    $this->altTitle = $this->leer($this->original_text, $this->posiblesTitulosAlt);
    $this->direc = $this->leerArr($this->original_text, $this->posiblesDireccion);
    $this->cast = $this->leerArr($this->original_text, $this->posiblesReparto);
    $this->year = $this->leer($this->original_text, $this->posiblesAno);
    $this->country = $this->leer($this->original_text, $this->posiblesPais);
    $this->produc = $this->leer($this->original_text, $this->posiblesProductora);
    $this->tag = $this->leerArr($this->original_text, $this->posiblesGenero);
  }

  protected function leer($str, $possibles)
    {

        foreach ($possibles as $possible)
        {
            $possible = strtolower($possible);
            if (stripos($str, $possible) !== false)
            {
                
                $from = stripos($str, $possible) + strlen($possible) + 1;
                $plus = $this->_findEL($str, $from);
                $this->text = substr($str, $from + $plus);
                return substr($str, $from, $plus);
            }
        }

        return "";
    }

    protected function leerArr($str, $possibles)
    {

        foreach ($possibles as $possible)
        {
            $possible = strtolower($possible);
            if (stripos($str, $possible) !== false)
            {
                
                $from = stripos($str, $possible) + strlen($possible) + 1;
                $plus = $this->_findEL($str, $from);
                $this->text = substr($str, $from + $plus);
                return explode(",", substr($str, $from, $plus));
            }
        }

        return [];
    }

    private function _findEL($str, $from = 0)
    {
        $t = strpos($str, "\n", $from);
        if ($t) {
            return $t - $from;
        } 

        return 0;
    }
}