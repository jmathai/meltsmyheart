<?php
class Comment
{
  const typePhoto = 'photo';
  public static function add($userId, $childId, $targetUserId, $itemId, $name, $comment, $dateCreated=null, $type=self::typePhoto)
  {
    $userId = intval($userId);
    if($dateCreated === null)
      $dateCreated = time();
    $params = array(':userId' => $userId, ':childId' => $childId, ':targetUserId' => $targetUserId, 
      ':itemId' => $itemId, ':name' => $name, ':comment' => $comment, ':dateCreated' => $dateCreated, ':type' => $type);
    return getDatabase()->execute('INSERT INTO comment(co_u_id, co_c_id, co_target_u_id, co_item_id, co_name, co_comment, co_dateCreated, co_type)
      VALUES(:userId, :childId, :targetUserId, :itemId, :name, :comment, :dateCreated, :type)');
  }

  public static function addEmail($to, $from, $subject, $body, $parsed, $date)
  {
    $params = array(':to' => $to, ':from' => $from, ':subject' => $subject, ':body' => $body, ':parsed' => $parsed, ':date' => $date);
    return getDatabase()->execute('INSERT INTO comment_email(ce_to, ce_from, ce_subject, ce_body, ce_bodyParsed, ce_date)
      VALUES(:to, :from, :subject, :body, :parsed, :date)', $params);
  }

  public static function generateEmailHash($email, $itemId, $type=self::typePhoto)
  {
    $hash = sha1(sprintf('%s~%s~%s', $email, $itemId, $type));
    $params = array(':hash' => $hash, ':email' => $email, ':itemId' => $itemId, ':type' => $type);
    $hashId = getDatabase()->execute('REPLACE INTO email_hash(eh_hash, eh_email, eh_itemId, eh_itemType)
      VALUES(:hash, :email, :itemId, :type)', $params);
    // if hashId in db then return the hash
    return $hashId ? $hash : false;
  }

  public static function generateEmailAddress($email, $itemId, $type=self::typePhoto)
  {
    $username = getConfig()->get('emailer')->username;
    $domain = getConfig()->get('emailer')->domain;
    $hash = self::generateEmailHash($email, $itemId, $type);
    if($hash)
      return sprintf('%s+%s@%s', $username, $hash, $domain);

    return false;
  }

  public static function getById($type, $itemId, $childId)
  {
    return getDatabase()->all('SELECT * FROM comment WHERE co_c_id=:childId AND co_item_id=:itemId AND co_type=:type AND co_isActive=:isActive',
      array(':childId' => $childId, ':itemId' => $itemId, ':type' => $type, ':isActive' => 1));
  }

  public static function getHashFromEmailAddress($email)
  {
    $email = self::parseOutEmail($email);

    if(!preg_match('/^.+\+(.+)@.+$/', $email, $matches))
      return false;

    return $matches[1];
  }

  public static function getGeneratedEmailAddress($email)
  {
    $hash = self::getHashFromEmailAddress($email);
    if(!$hash)
      return false;

    return getDatabase()->one('SELECT * FROM email_hash WHERE eh_hash=:hash', array(':hash' => $hash));
  }

  public static function parseOutEmail($email)
  {
    if(preg_match('/<(.+)>/', $email, $matches))
      $email = $matches[1];

    return $email;
  }
}
