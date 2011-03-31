<?php
class Badge
{
  static $map = null;
  public static function add($childId, $badgeId)
  {
    $params = array(':childId' => $childId, ':badgeId' => $badgeId, ':date' => time());
    return getDatabase()->execute('REPLACE INTO badge(b_c_id, b_bm_id, b_dateCreated)
      VALUES(:childId, :badgeId, :date)', $params);
  }

  public static function doesChildHave($badgeId, $childId)
  {
    $params = array(':badgeId' => $badgeId, ':childId' => $childId);
    $result = getDatabase()->one('SELECT COUNT(*) AS _CNT FROM badge WHERE b_c_id=:childId AND b_bm_id=:badgeId', $params);
    return ($result['_CNT'] > 0);
  }

  public static function getByChild($childId)
  {
    return getDatabase()->all('SELECT * FROM badge INNER JOIN badge_meta ON b_bm_id=bm_id WHERE b_c_id=:childId',
      array(':childId' => $childId));
  }

  public static function getById($badgeId)
  {
    return getDatabase()->one('SELECT * FROM badge_meta WHERE bm_id=:badgeId',
      array(':badgeId' => $badgeId));
  }

  public static function getByTag($tag)
  {
    return getDatabase()->one('SELECT * FROM badge_meta WHERE bm_tag=:tag', array(':tag' => $tag));
  }

  public static function getIdByTag($tag)
  {
    $badge = self::getByTag($tag);
    return $badge ? $badge['bm_id'] : null;
  }

  private static function getMap()
  {
    if(self::$map !== null)
      return self::$map;

    self::$map = array(
      'imnew' => 1,
      'talker' => 2,
      'android' => 3,
    );
    return self::$map;
  }
}
