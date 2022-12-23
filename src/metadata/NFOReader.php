<?php


class NFOReader {
  private $content;
  private $xml;
  private $isXml = false;
  private $path;
  private $filename;

  public function __construct($filePath, $path = "", $filename = "") {
    // Leemos el contenido del archivo .nfo
    $this->path = (!empty($path)) ? $path : pathinfo($filePath, PATHINFO_DIRNAME);
    $this->filename = (!empty($filename)) ? $filename : pathinfo($filePath, PATHINFO_FILENAME);
    $this->content = file_get_contents($filePath);
    $xml = simplexml_load_string($this->content);
    if ($xml !== FALSE) {
      unset($this->content);
      $this->xml = $xml;
      $this->isXml = true;
    }
  }

  public function getTitle() {
    // Buscamos el título de la película/serie en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Title: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }
    } else {
      $title = $this->xml->movie->title;
      if ($title) {
        return (string)$title;
      }
    }
    return null;
  }

  public function getOriginalTitle() {
    // Buscamos el título original en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Original Title: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }
      if (preg_match('/^Release Name: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }
    } else {
      $originaltitle = $this->xml->movie->originaltitle;
      if ($originaltitle) {
        return (string)$originaltitle;
      }
    }

    return null;
  }

  public function getTagline()
  {
        // Buscamos el eslogan en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Tagline: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }
    } else {
      $tagline = $this->xml->movie->tagline;
      if ($tagline) {
        return (string)$tagline;
      }
    }

    return null;
  }

  public function getDurationInSeconds()
  {
        // Buscamos la duracion en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Duration: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }
    } else {
      $durationinseconds = $this->xml->movie->fileinfo->streamdetails->video->durationinseconds;
      if ($durationinseconds) {
        return (integer)$durationinseconds;
      }
    }

    return null;
  }

  public function getYear() {
    // Buscamos el año de la película/serie en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Year: (\d+)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $year = $this->xml->movie->year;
      if ($year) {
        return (integer)$year;
      } 
    }

    return null;
  }

  public function getGenres() {
    // Buscamos los géneros de la película/serie en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Genre: (.*)$/m', $this->content, $matches)) {
        // Si hay más de un género, los separamos en un array
        if (strpos($matches[1], ',') !== false) {
          return explode(',', $matches[1]);
        } else {
          return array($matches[1]);
        }
      }
    } else {
        $genres = [];
        foreach ($this->xml->movie->genre as $genre) {
          if ($genre) $genres[] = (string) $genre;
        }
        return $genre;
    }   
    return array();
  }

  public function getCoverImage() {
    // Buscamos la URL de la portada en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Cover: (https?:\/\/.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $cover = $this->path . DIRECTORY_SEPARATOR . $this->filename . '*.jpg';
      $res = [];
      foreach (glob($cover) as $file)
        $res[] = $file;
      return $res; 
    }
  }

  public function getPlot() {
    // Buscamos la trama en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Plot: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $plot = $this->xml->movie->plot;
      if ($plot) {
        return (string)$plot;
      } 
    }

    return null;
  }

  public function getDirector() {
    // Buscamos el director en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Director: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $director = $this->xml->movie->director;
      if ($director) {
        return (string)$director;
      } 
    }

    return null;
  }
  
  public function getCountry() {
    // Buscamos el país en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Country: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $country = $this->xml->movie->country;
      if ($country) {
        return (string)$country;
      } 
    }

    return null;
  }

  public function getCredits() {
    // Buscamos los créditos en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Credits: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $credits = [];
      foreach ($this->xml->movie->credits as $credit) {
        if ($credit) $credits[] = (string) $credit;
      }
      return $credits;
    }

    return null;
  }

  public function getLanguage() {
    // Buscamos el idioma en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Language: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $language = $this->xml->movie->language;
      if ($language) {
        return (string)$language;
      } 
    }

    return null;
  }
  
  public function getActors() {
    // Creamos un array para almacenar los actores
    $actors = array();
  
    if (isset($this->content))
    {
      // Buscamos los actores en el contenido del archivo
      if (preg_match_all('/^Actor: (.*)$/m', $this->content, $matches)) {
        $actors = $matches[1];
      }  
    } else {
      $c = 0;
      foreach ($this->xml->movie->actor as $perform) {
        if ($perform) 
        {
          $actors[$c]['name'] = (string) $perform->name;
          $actors[$c]['role'] = (string) $perform->role;
          if ($perform->thumb)
          {
            $actors[$c]['thumb'] = (string) $perform->thumb;
          } else {
            $actors[$c]['thumb'] = $this->getPossibilityThumbActors($perform->name);
          }
          $c++;
        }
        
      }
    }

    return $actors;
  }

  public function getReleaseDate()
  {
      // Buscamos la fecha de estreno en el contenido del archivo
    if (isset($this->content))
    {
      if (preg_match('/^Release Date: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      }      
    } else {
      $releasedate = $this->xml->movie->releasedate;
      if ($releasedate) {
        return (string)$releasedate;
      } 
      $releasedate = $this->xml->movie->premiered;
      if ($releasedate) {
        return (string)$releasedate;
      }
    }

    return null;
  }

  public function getRating()
  {
    // Buscamos la clasificación en el contenido del archivo (X/10)rating
    if (isset($this->content))
    {
      if (preg_match('/^Rating: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      } 
    } else {
      $rating = $this->xml->movie->rating;
      if ($rating) {
        return (integer)$rating;
      } 
    }

    return null;
  }

  public function getStudio()
  {
    // Buscamos la productora en el contenido del archivo (X/10)rating
    if (isset($this->content))
    {
      if (preg_match('/^Studio: (.*)$/m', $this->content, $matches)) {
        return $matches[1];
      } 
    } else {
      $studio = $this->xml->movie->studio;
      if ($studio) {
        return (integer)$studio;
      } 
    }

    return null;
  }

  public function getExternalRatings()
  {
    // Creamos un array para almacenar los ratings
    $ratings = array();
  
    if (isset($this->content))
    {
      // Buscamos los actores en el contenido del archivo
      if (preg_match_all('/^Ratings: (.*)$/m', $this->content, $matches)) {
        $ratings = $matches[1];
      }  
    } else {
      $c = 0;
      foreach ($this->xml->movie->ratings->rating as $rating) {
        if ($rating)
        {
          $attributes = $rating->attributes();
          $ratings[$c]['name'] = (integer) $attributes->name;
          $ratings[$c]['max'] = (integer) $attributes->max;
          $ratings[$c]['votes'] = (integer) $rating->votes;
          $ratings[$c]['value'] = (float) $rating->value;
          $c++;
        }
        
      }
    }

    return $ratings;
  }

  public function getStreamDetailsXML()
  {
    $audio = $this->xml->movie->fileinfo->streamdetails->audio;
    $video = $this->xml->movie->fileinfo->streamdetails->video;
    $audioArr = [];
    $videoArr = [];

    if ($audio) {
      $audioArr['bitrate'] = (string) $audio->bitrate;
      $audioArr['channels'] = (string) $audio->channels;
      $audioArr['codec'] = (string) $audio->codec;
      $audioArr['language'] = (string) $audio->language;
      $audioArr['longlanguage'] = (string) $audio->longlanguage;
    }

    if ($video) {
      $videoArr['aspect'] = (string) $video->aspect;
      $videoArr['bitrate'] = (string) $video->bitrate;
      $videoArr['codec'] = (string) $video->codec;
      $videoArr['durationinseconds'] = (string) $video->durationinseconds;
      $videoArr['height'] = (string) $video->height;
      $videoArr['scantype'] = (string) $video->scantype;
      $videoArr['width'] = (string) $video->width;
      $videoArr['filesize'] = (string) $video->filesize;
    }
    return ['audio' => $audioArr, 'video' => $videoArr];

  }

  public function getFanart()
  {
    $f = $this->path . DIRECTORY_SEPARATOR . $this->filename . '-fanart.jpg';
    return file_exists($f) ?? $f;
  }

  public function getExtrafanart()
  {
    $ret = [];
    $paththumb = $this->path . DIRECTORY_SEPARATOR . 'extrafanart' . DIRECTORY_SEPARATOR;
    if (is_dir($paththumb))
    {
      $namethumb = $paththumb . '*.jpg';
      foreach (glob($namethumb) as $file)
      {
        $ret[] = $file;
      }
    }
    return $ret;
  }

  public function getExtrathumbs()
  {
    $ret = [];
    $paththumb = $this->path . DIRECTORY_SEPARATOR . 'extrathumbs' . DIRECTORY_SEPARATOR;
    if (is_dir($paththumb))
    {
      $namethumb = $paththumb . '*.jpg';
      foreach (glob($namethumb) as $file)
      {
        $ret[] = $file;
      }
    }
    return $ret;
  }

  private function getPossibilityThumbActors($name_actor)
  {
    $paththumb = $this->path . DIRECTORY_SEPARATOR . '.actors' . DIRECTORY_SEPARATOR;
    if (is_dir($paththumb))
    {
      $namethumb = $paththumb . str_replace($name_actor, '_', ' ') . '.jpg';
      if (file_exists($namethumb)) 
        return $namethumb;
    }
    return null;
  }
}