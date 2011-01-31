<?php
class Partner
{
  public static function logAndRedirect($affiliateKey)
  {
    if(!isset($_COOKIE['affiliateKey']) || $_COOKIE['affiliateKey'] == $affiliateKey)
    {
      $userToken = uniqid();
      $affiliate = Affiliate::getByKey($affiliateKey);
      Affiliate::setCookie($userToken, $affilateKey, $affiliate['a_id']);
      Affiliate::logUser(Affiliate::view, $userToken, $affiliate['a_id']);
    }
    getRoute()->redirect('/join/referred');
  }
   
  public static function signup()
  {
    if(!User::isLoggedIn())
      getRoute()->redirect('/join/affiliate?r=/affiliate/signup');
    $prefs = getSession()->get('prefs');
    if(!isset($prefs['isAffiliate']) || $prefs['isAffiliate'] != 1)
    {
      $userId = getSession()->get('userId');
      $prefs['isAffiliate'] = 1;
      User::updatePrefs($userId, $prefs);
      $affiliateId = Affiliate::add($userId);
      if(!$affiliateId)
        throw new Exception('Could not add affiliate id');
    }
    getRoute()->redirect('/affiliate');
  }

  public static function view()
  {
    Site::requireLogin();
    $userId = getSession()->get('userId');
    $affiliate = Affiliate::getByUserId($userId);
    $js = getTemplate()->get('javascript/formValidator.js.php', array('formId' => 'affiliateForm'));
    getTemplate()->display('template.php', array('body' => 'affiliateView.php', 'affiliate' => $affiliate, 'js' => $js));
  }
}
