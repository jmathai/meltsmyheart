<?php
class QueueDummy
{
  public function setUp() { }
  public function perform()
  {
    User::getById(1);
    getLogger()->info('MySql query performed at ' . date('Y/m/d h:i:s'));
  }
  public function tearDown() { }
}
