<?php
class Child
{
  public static function add($userId, $name, $birthdate)
  {
    $params = array(':userId' => $userId, ':name' => $name, ':birthdate' => $birthdate);
    return getDatabase()->execute('INSERT INTO child(c_u_id, c_name, c_birthdate)
      VALUES(:userId, :name, :birthdate)', $params);
  }

  public static function getByUserId($userId)
  {
    $params = array(':userId' => $userId);
    return getDatabase()->all('SELECT * FROM child WHERE c_u_id=:userId', $params);
  }
}
