<?php

class uprabotGetKeyWordsTask extends sfBaseTask
{
  protected $last_id = null;
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
    $this->name             = 'getKeyWords';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [urabot:getKeyWords|INFO] task does things.
Call it with:

  [php symfony urabot:getKeyWords|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);

    $twitter_id = Doctrine::getTable('LastId')->getLastTwitterId(LastId::TYPE_RAND_WORDS);
    $mentions = $twitter->get('statuses/mentions', array('since_id' => $twitter_id));
    $http_info = $twitter->http_info;
    foreach ($mentions as $v)
    {
      if (null === $this->last_id)
      {
        $this->last_id = $v->id;
      }

      if ($results = $this->saveWords($v->text) && IS_RETURN_MESSAGE)
      {
        $text = sprintf('@%s 登録しました who:"%s" where:"%s" do:"%s"', $v->screen_name, $results['who'], $results['where'], $results['do']);
        $twitter->post('statuses/update', array('status' => $text));
      }
    }

    if (null !== $this->last_id)
    {
      Doctrine::getTable('LastId')->uniqueSave(LastId::TYPE_RAND_WORDS, $this->last_id);
    }
  }

  protected function saveWords($text)
  {
    $regexp = sprintf('/@%s\s+(.*)?が、(.*)?で、(.*)?。/iu', sfConfig::get('user_name'));
    preg_match($regexp, $text, $matches);

    if ($matches)
    {
      Doctrine::getTable('KeywordWho')->uniqueSave($matches[1]);
      Doctrine::getTable('KeywordWhere')->uniqueSave($matches[2]);
      Doctrine::getTable('KeywordDo')->uniqueSave($matches[3]);

      return array('who' => $matches[1], 'where' => $matches[2], 'do' => $matches[3]);
    }

    return false;
  }
}
