<?php
class Fetcher extends Uploader
{
  // inherited from Uploader
  // public function setUp() {}

  public function perform()
  {
    $this->photoUrl = $this->args['photo']['originalUrl'];
    $this->photoSafeName = self::safeName($this->photoUrl);
    $this->photoFile = "{$this->dirDestOriginal}/{$this->photoSafeName}";

    // file pointer and curl
    $fp = fopen($this->photoFile, 'w');
    $ch = curl_init($this->photoUrl);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $data = curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    $this->processPhoto();
  }

  // inherited from Uploader
  // public function tearDown() { }
}
