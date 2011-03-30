<?php
class GmailReader
{
  public $mbox;
  public function __construct( $user, $pass )
  {
   $this->mbox = imap_open("{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX",$user,$pass)
    or die("can't connect: " . imap_last_error());
  }

  public function openSentMail()
  {
   imap_reopen($this->mbox, "{imap.gmail.com:993/imap/ssl/novalidate-cert}[Gmail]/Sent Mail" )
    or die("Failed to open Sent Mail: " . imap_last_error());
  }

  public function openMailBox($mailbox)
  {
   imap_reopen($this->mbox, "{imap.gmail.com:993/imap/ssl/novalidate-cert}$mailbox" )
    or die("Failed to open $mailbox: " . imap_last_error());
  }

  public function getMailboxInfo()
  {
   $mc = imap_check($this->mbox);
   return $mc;
  }

  /**
   * $date should be a string
   * Example Formats Include:
   * Fri, 5 Sep 2008 9:00:00
   * Fri, 5 Sep 2008
   * 5 Sep 2008
   * I am sure other's work, just test them out.
   */
  public function getHeadersSince($date)
  {
   $uids = $this->getMessageIdsSinceDate($date);
   $messages = array();
   if($uids)
   {
     foreach( $uids as $k=>$uid )
     {
      $messages[] = $this->retrieve_header($uid);
     }
   }
   return $messages;
  }

  public function getAllEmail()
  {
    $messages = array();
    $messageIds = imap_search($this->mbox, 'ALL');
    if($messageIds)
    {
      foreach($messageIds as $messageId)
      {
        $messages[] = $this->retrieve_message($messageId);
      }
    }
    return $messages;
  }

  /**
   * $date should be a string
   * Example Formats Include:
   * Fri, 5 Sep 2008 9:00:00
   * Fri, 5 Sep 2008
   * 5 Sep 2008
   * I am sure other's work, just test them out.
   */
  public function getEmailSince($date)
  {
   $uids = $this->getMessageIdsSinceDate($date);
   $messages = array();
   if($messages)
   {
     foreach( $uids as $k=>$uid )
     {
      $messages[] = $this->retrieve_message($uid);
     }
   }
   return $messages;
  }

  public function getMessageIdsSinceDate($date)
  {
   return imap_search( $this->mbox, 'SINCE "'.$date.'"');
  }

  public function retrieve_header($messageid)
  {
     $message = array();

     $header = imap_header($this->mbox, $messageid);
     $structure = imap_fetchstructure($this->mbox, $messageid);

     $message['messageid'] = $messageid;
     $message['identifier'] = "{$header->message_id}-{$header->fromaddress}";
     $message['subject'] = $header->subject;
     $message['fromaddress'] =   $header->fromaddress;
     $message['toaddress'] =   $header->toaddress;
     $message['ccaddress'] =   isset($header->ccaddress) ? $header->ccaddress : null;
     $message['date'] =   $header->date;

     return $message;
  }

  public function retrieve_message($messageid)
  {
     $message = array();

     $header = imap_header($this->mbox, $messageid);
     $structure = imap_fetchstructure($this->mbox, $messageid);
     $message['messageid'] = $messageid;
     $message['identifier'] = "{$header->message_id}-{$header->fromaddress}";
     $message['subject'] = $header->subject;
     $message['fromaddress'] =   $header->fromaddress;
     $message['toaddress'] =   $header->toaddress;
     $message['ccaddress'] =   isset($header->ccaddress) ? $header->ccaddress : null;
     $message['date'] =   $header->date;

    if ($this->check_type($structure))
    {
     $message['body'] = imap_fetchbody($this->mbox,$messageid,"1"); ## GET THE BODY OF MULTI-PART MESSAGE
     if(!$message['body']) {$message['body'] = null;}
    }
    else
    {
     $message['body'] = imap_body($this->mbox, $messageid);
     if(!$message['body']) {$message['body'] = null;}
    }

    return $message;
  }

  public function check_type($structure) ## CHECK THE TYPE
  {
    if($structure->type == 1)
      {
       return(true); ## YES THIS IS A MULTI-PART MESSAGE
      }
   else
      {
       return(false); ## NO THIS IS NOT A MULTI-PART MESSAGE
      }
  }

  public function delete($messageId)
  {
    imap_delete($this->mbox, $messageId);
  }

  public function expunge()
  {
    imap_expunge($this->mbox);
  }
}

