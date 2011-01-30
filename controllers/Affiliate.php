<?php
class Affiliate
{
  public static function view()
  {
    getTemplate()->display('template.php', array('body' => 'affiliateView.php'));
  }

  public static function signup()
  {
    if(!User::isLoggedIn())
      getRoute()->redirect('/join/affiliate?r=/affiliate/signup');
    $prefs = getSession()->get('prefs');
    if(!isset($prefs['isAffiliate']) || $prefs['isAffiliate'] != 1)
    {
      $prefs['isAffiliate'] = 1;
      User::updatePrefs(getSession()->get('userId'), $prefs);
    }
    getRoute()->redirect('/affiliate');
  }
}
