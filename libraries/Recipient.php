<?php
class Recipient
{
  public static function add($userId, $name, $email, $mobile=null)
  {
    $params = array(':userId' => $userId, ':name' => $name, ':email' => $email, ':mobile' => $mobile);
    return getDatabase()->execute('INSERT INTO `recipient`(r_u_id, r_name, r_email, r_mobile) 
      VALUES(:userId, :name, :email, :mobile)', $params);
  }

  public static function delete($userId, $recipientId) 
  {
    return getDatabase()->execute('DELETE FROM recipient WHERE r_id=:recipientId AND r_u_id=:userId',
      array(':userId' => $userId, ':recipientId' => $recipientId));
  }

  public static function getByEmail($userId, $email) {
    return getDatabase()->one('SELECT * FROM recipient WHERE r_u_id=:userId AND r_email=:email', array(':userId' => $userId, ':email' => $email));
  }

  public static function getByUserId($userId) {
    return getDatabase()->all('SELECT * FROM recipient WHERE r_u_id=:userId', array(':userId' => $userId));
  }
}
