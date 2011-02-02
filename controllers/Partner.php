<?php
class Partner
{
  public static function affiliatePayment($affiliateId)
  {
    Site::requireLogin();
    $userId = getSession()->get('userId');
    $affiliate = Affiliate::getByUserId($userId);
    if($affiliate['a_id'] == $affiliateId)
    {
      $params = array('affiliateId' => $affiliateId, 'email' => getSession()->get('email'), 'amount' => $_POST['amount'],
        'balance' => Affiliate::getBalance($affiliateId), 'name' => $_POST['name'], 'address' => $_POST['address'], 'cityStateZip' => $_POST['citystatezip']);
      Resque::enqueue('mmh_email', 'Email', array('subject' => 'New Affiliate Payment Request', 'email' => getConfig()->get('email')->from_email, 
        'template' => getTemplate()->get('email/affiliate-payment.php', $params)));
      getRoute()->redirect('/affiliate?m=paymentRequestSubmitted');
    }
    else
    {
      getRoute()->redirect('/affiliate?e=couldNotFindAffiliateAccount');
    }
  }

  public static function logAndRedirect($affiliateKey)
  {
    if(!$parsedCookie = Affiliate::parseCookie() && $parsedCookie['affiliateKey'] != $affiliateKey)
    {
      $userToken = uniqid();
      $affiliate = Affiliate::getByKey($affiliateKey);
      Affiliate::setCookie($userToken, $affiliateKey, $affiliate['a_id']);
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
      Resque::enqueue('mmh_email', 'Email', array('subject' => sprintf('Welcome to the %s affiliate program', getConfig()->get('site')->name), 
        'email' => getSession()->get('email'), 'template' => getTemplate()->get('email/affiliate.php')));
    }
    getRoute()->redirect('/affiliate');
  }

  public static function view()
  {
    Site::requireLogin();
    $userId = getSession()->get('userId');
    $affiliate = Affiliate::getByUserId($userId);
    $stats = Affiliate::getStats($affiliate['a_id']);
    $balance = Affiliate::getBalance($affiliate['a_id']);
    $js = getTemplate()->get('javascript/formValidator.js.php', array('formId' => 'affiliateForm'));
    getTemplate()->display('template.php', array('body' => 'affiliateView.php', 'affiliate' => $affiliate, 
      'balance' => $balance, 'stats' => $stats, 'js' => $js));
  }
}
