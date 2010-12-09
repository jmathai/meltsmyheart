<?php
class Fetcher
{
  public function setUp()
  {
    $this->dirOriginal = '/original';
    $this->dirBase = '/base';
    $this->dirThumb = '/thumb';
    $this->yearMonth = '/'.date('Ym');

    $this->dirDestOriginal = getConfig()->get('paths')->photos.$this->dirOriginal.$this->yearMonth;
    $this->dirDestBase = getConfig()->get('paths')->photos.$this->dirBase.$this->yearMonth;
    $this->dirDestThumb = getConfig()->get('paths')->photos.$this->dirThumb.$this->yearMonth;

    $this->photoUrl = $this->args['photo']['originalUrl'];
    $this->photoSafeName = $this->safeName($this->photoUrl);
    $this->photoFile = "{$this->dirDestOriginal}/{$this->photoSafeName}";
  }

  public function perform()
  {
    $fp = fopen($this->photoFile, 'w');
    $ch = curl_init($this->photoUrl);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $data = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    $im = new ImageMagick($this->photoFile);
    // base 1024x1024
    $baseFile = "{$this->dirDestBase}/{$this->photoSafeName}";
    $im->scale(1024, 1024, $baseFile, true);
    $im->exiftran($baseFile);

    // thumb 150x150
    $thumbFile = "{$this->dirDestThumb}/{$this->photoSafeName}";
    $im = new ImageMagick($baseFile, getConfig()->get('paths')->exe);
    $im->scale(150, 150, $thumbFile, true);

    $systemPath = getConfig()->get('paths')->photos;
    $thumbPath = str_replace($systemPath, '', $thumbFile);
    $basePath =  str_replace($systemPath, '', $baseFile);
    $originalPath =  str_replace($systemPath, '', $this->photoFile);

    //exif
    $dateTaken = time();
    $photoData = Photo::exif($this->photoFile);
    if($photoData)
      $dateTaken = $photoData['dateTaken'];
    $exif = json_encode($photoData);
    Photo::update($this->args['userId'], $this->args['entryId'], $thumbPath, $basePath, $originalPath, $exif, $dateTaken);
  }

  public function tearDown() { }

  private function safeName($url)
  {
    return rand(0,1000).'_'.time().'_'.preg_replace('/[^a-zA-Z0-9-.]/', '_', basename($url));
  }
}
