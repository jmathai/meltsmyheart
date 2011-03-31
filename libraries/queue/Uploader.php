<?php
class Uploader
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
    $this->s3 = new S3(getSecret('amz_key'), getSecret('amz_secret'));
  }

  public function perform()
  {
    $this->photoFile = $this->args['photoPath'];
    // this is already safe'd in Site
    $this->photoSafeName = basename($this->photoFile);
    $this->processPhoto();
    Photo::setUse($this->args['userId'], $this->args['entryId'], 1);
  }

  public function tearDown()
  {
    unset($this->s3);
  }

  protected function processPhoto()
  {
    $im = new ImageMagick($this->photoFile);
    // base 1024x1024
    $baseFile = "{$this->dirDestBase}/{$this->photoSafeName}";
    $im->scale(1024, 1024, $baseFile, true);
    $im->exiftran($baseFile);

    // thumb 150x150
    /*$thumbFile = "{$this->dirDestThumb}/{$this->photoSafeName}";
    $im = new ImageMagick($baseFile, getConfig()->get('paths')->exe);
    $im->scale(150, 150, $thumbFile, true);*/

    $systemPath = getConfig()->get('paths')->photos;
    $thumbPath = null; //$thumbPath = str_replace($systemPath, '', $thumbFile);
    $basePath =  str_replace($systemPath, '', $baseFile);
    $originalPath =  str_replace($systemPath, '', $this->photoFile);

    //exif
    $dateTaken = !empty($this->args['photo']['dateTaken']) ? $this->args['photo']['dateTaken'] : time();
    $photoData = Photo::exif($this->photoFile);
    if($photoData)
      $dateTaken = $photoData['dateTaken'];
    $exif = json_encode($photoData);
    Photo::update($this->args['userId'], $this->args['entryId'], $thumbPath, $basePath, $originalPath, $exif, $dateTaken);

    $s3Success = $this->s3->uploadFile(getConfig()->get('thirdparty')->amz_bucket, "original{$this->yearMonth}/{$this->photoSafeName}", "{$this->photoFile}");
    if($s3Success)
      unlink($this->photoFile);
  }

  public static function safeName($url)
  {
    return md5(rand(0,1000).'-'.$url).'.jpg'; //rand(0,1000).'_'.time().'_'.preg_replace('/[^a-zA-Z0-9-.]/', '_', basename($url));
  }
}
