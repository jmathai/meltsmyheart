<?php
/* defaults */
if(!isset($c_name))
  $c_name = 'my children';
$strings = array();
$strings['invalidFields'] = 'There were invalid fields found in the form.';
$strings['loginFailed'] = 'We couldn\'t log you in with that email address and password.';
$strings['accountCreationError'] = 'There was a problem creating your account.';
$strings['emailAlreadyExists'] = 'An account with that email address already exists.';
$strings['emailDoesNotExist'] = 'We couldn\'t find an account with that email address.';
$strings['facebookStatus'] = "See photos of {$c_name} on " . getConfig()->get('site')->name . '.';
$strings['facebookCaption'] = getConfig()->get('site')->name . ' is the fastest and most beautiful way to share photos of your children.';
$strings['facebookDescription'] = "Experience {$c_name} growing up through photos on " . getConfig()->get('site')->name . '.';
$strings['paymentRequestSubmitted'] = 'Your payment request has been submitted.';
$strings['couldNotFindAffiliateAccount'] = 'We could not find an affiliate account for you.';


$strings['quotes'] = array();
$strings['quotes'][] = array('quote' => 'You can learn many things from children.  How much patience you have, for instance.', 'by' => 'Franklin P. Jones');
$strings['quotes'][] = array('quote' => 'Children need love, especially when they do not deserve it.', 'by' => 'Harold Hulbert');
$strings['quotes'][] = array('quote' => 'Every child comes with the message that God is not yet discouraged of man.', 'by' => 'Rabindranath Tagore');
$strings['quotes'][] = array('quote' => 'Children make you want to start life over.', 'by' => 'Muhammad Ali');
$strings['quotes'][] = array('quote' => 'Anyone who thinks the art of conversation is dead ought to tell a child to go to bed.', 'by' => 'Robert Gallagher');
return $strings;
