<?php
 /*******************************************************************************************
  * Name:  CImageMagick.php
  *
  * Image manipulation class
  *
  * Usage:
  *
  *******************************************************************************************/
class ImageMagick
{
  private $image, $pathExe;
  /*******************************************************************************************
  * Description
  *   scales an image
  *
  * Input
  *   height  int
  *   width   int
  *   lock    bool
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function scale($width = false, $height = false, $dest = false, $larger = false)
  {
    $proceed = true;

    if($larger === false)
    {
      $arrImage              =   @getimagesize($this->image);
      $i_width               =   $arrImage[0];
      $i_height              =   $arrImage[1];
      if($i_width <= $width && $i_height <= $height)
      {
        $proceed = false;
      }
    }

    if($proceed === true)
    {
      $width = intval($width);
      $height= intval($height);


      $quality = 90; // vree said so
      if($dest === false)
      {
        $command = "{$this->pathExe}convert -size {$width}x{$height} -filter Lanczos -quality {$quality} +profile \"*\" -resize \"{$width}x{$height}>\" {$this->imageEscaped} {$this->imageEscaped}";
      }
      else
      {
        $dest = escapeshellarg($dest);
        $command = "{$this->pathExe}convert -size {$width}x{$height} -filter Lanczos -quality {$quality} +profile \"*\" -resize \"{$width}x{$height}>\" {$this->imageEscaped} {$dest}";
      }
      exec($command);
      return true;
    }
    else
    {
      return false;
    }
  }
 /*******************************************************************************************
  * Description
  *   Convert the loaded image to black and white
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function desaturate($dest = false) {
    if(!$dest)
      $dest = $this->imageEscaped;
    else
      $dest = escapeshellarg($dest);
    $command = "{$this->pathExe}convert -colorspace GRAY {$this->imageEscaped} {$dest}";
    exec($command);
  }

  public function contrast($dest = false)
  {
    if(!$dest)
      $dest = $this->imageEscaped;
    else
      $dest = escapeshellarg($dest);
    $command = "{$this->pathExe}convert {$this->imageEscaped} -fx \"(1.0/(1.0+exp(10.0*(0.5-u)))-0.006693)*1.0092503\" {$dest}";
    exec($command);
  }
  
  /*******************************************************************************************
  * Description
  *   crops an image
  *
  * Input
  *   height  int
  *   width   int
  *   dest    string
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function crop($width = false, $height = false, $dest = false)
  {
    $quality = 90; // vree said so
    $imageInfo = getimagesize($this->image);
    $sRatio = floatval($imageInfo[0] / $imageInfo[1]);
    $dRatio = floatval($width / $height);
    if($dRatio > $sRatio) // crop width
    {
      $dWidth = $imageInfo[0];
      $dHeight= $height * floatval($imageInfo[0] / $width);
    }
    if($dRatio < $sRatio) // crop height
    {
      $dWidth = $width * floatval($imageInfo[1] / $height);
      $dHeight= $imageInfo[1];
    }
    
    
    if($dest === false)
    {
      $cmd    = "{$this->pathExe}mogrify -gravity Center -crop {$dWidth}x{$dHeight}+0+0 {$this->imageEscaped}";
    }
    else
    {
      $dest = escapeshellarg($dest);
      $cmd    = "{$this->pathExe}convert -gravity Center -crop {$dWidth}x{$dHeight}+0+0 {$this->imageEscaped} {$dest}";
    }

    exec($cmd); // perform initial crop maintaining source size
    
    $finalSrc = $dest === false ? $this->imageEscaped : $dest;
    $command = "mogrify -size {$width}x{$height} -filter Lanczos -quality {$quality} +profile \"*\" -resize \"{$width}x{$height}>\" {$finalSrc}";
    exec($command); // perform resize to make image destination size
  }
  
  /*******************************************************************************************
  * Description
  *   Composite images
  *
  * Input
  *   arr_src   array
  *   dest      string
  *   x_offset  int
  *   y_offset  int
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function composite($arr_src, $dest, $x_offset, $y_offset)
  {
    $continue = true;
    $dest = escapeshellarg($dest);
    if(count($arr_src) == 2)
    {
      $img_1_info = @getimagesize($arr_src[0]);
      $img_2_info = @getimagesize($arr_src[1]);
      if($img_1_info[0] == $img_2_info[0] && $img_1_info[1] == $img_2_info[1])
      {
        $continue = false;
      }
    }

    if($continue === true)
    {
      $images   = implode(' ', $arr_src);
      $command  = "composite -geometry +{$x_offset}+{$y_offset} {$images} {$dest}";
      exec($command);
    }

    return true;
  }

  /*******************************************************************************************
  * Description
  *   Crop an image to a square
  *
  * Input
  *   src       string
  *   dest      string
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function square($dest = false)
  {
    die();
    $imageInfo = @getimagesize($dest);

    if(!$dest)
      $dest = $this->imageEscaped;
    else
      $dest = escapeshellarg($dest);

    if($imageInfo !== false)
    {
      $floor  = min($imageInfo[0], $imageInfo[1]);
      $cmd    = "{$this->pathExe}convert -gravity Center -crop {$floor}x{$floor}+0+0 {$src} {$dest}";
      exec($cmd);
    }
  }


  function rotate($src, $dest, $degrees)
  {
    //$cmd = "{$this->pathExe}convert -rotate {$degrees} {$src} {$dest}";
    $cmd = "{$this->pathExe}convert -rotate {$degrees} {$src} {$dest}";
    exec($cmd);
  }

 /*******************************************************************************************
  * Description
  *   Convert the loaded image to sepia tone
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function sepia($dest) {
    $this->desaturate($dest);
    $dest = escapeshellarg($dest);
    $command = "{$this->pathExe}convert -colorize 0/14/47 \"{$this->imageEscaped}\" \"{$dest}\"";
    exec($command);
  }

  function convert($src, $dest, $extras = false)
  {
    $dest = escapeshellarg($dest);
    $cmd = "{$this->pathExe}convert {$src} {$dest}";
    exec($cmd);
  }
  
  function exiftran($src, $dest = false)
  {
    $src = escapeshellarg($src);
    if($dest === false)
    {
      $cmd = "exiftran -ai {$src}";
    }
    else
    {
      $dest = escapeshellarg($dest);
      $cmd = "exiftran -a {$src} -o {$dest}";
    }
    exec($cmd);
  }
  
  /*******************************************************************************************
  * Description
  *   sets a source image
  *
  * Input
  *   src   string
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function image($src)
  {
  }

  /*******************************************************************************************
  * Description
  *   Constructor
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function ImageMagick($imageSrc, $pathExe = '')
  {
    $this->image = $imageSrc;
    $this->imageEscaped = escapeshellarg($imageSrc);
    $this->pathExe = $pathExe;
  }
}
