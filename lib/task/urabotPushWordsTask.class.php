<?php

class urabotPushWordsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'urabot';
    $this->name             = 'pushWords';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [urabot:pushWords|INFO] task does things.
Call it with:

  [php symfony urabot:pushWords|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    set_time_limit(0);
    sleep((int)urabotToolkit::getRand(60 * 60 * 2));
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $results = Doctrine::getTable('CreatedHistory')->getRandWords(true, 100);
    $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
    if ($results)
    {
      $text = sprintf('%s が %s で %s。', $results['who'], $results['where'], $results['do']);
      $twitter->post('statuses/update', array('status' => $text));
    }
  }
}
