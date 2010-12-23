<?php
class Api
{
  const statusSuccess = 200;
  const statusForbidden = 403;
  const statusNotFound = 404;
  const statusError = 500;
  // response handlers
  public static function error($message, $params = null)
  {
    self::json($message, self::statusError, $params);
  }

  public static function success($message, $params = null)
  {
    self::json($message, self::statusSuccess, $params);
  }

  public static function forbidden($message, $params = null)
  {
    self::json($message, self::statusForbidden, $params);
  }

  public static function notFound($message, $params = null)
  {
    self::json($message, self::statusNotFound, $params);
  }

  private static function json($message, $code, $params = null)
  {
    echo getTemplate()->json(array('status' => $code, 'message' => $message, 'params' => $params));
    if(!isset($_SERVER['argc']) || $_SERVER['argc'] == 0)
      die();
  }

}
