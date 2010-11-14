<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony-1.4.8/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  static protected $user_name = null;

  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    require_once sfConfig::get('sf_root_dir').'/default.inc.php';

    if (null === self::$user_name)
    {
      $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
      $user = $twitter->get('account/verify_credentials');
      $http_info = $twitter->http_info;
      if ('200' == $http_info['http_code'])
      {
        self::$user_name = $user->screen_name;
        sfConfig::set('user_name', self::$user_name);
      }
    }
  }
}
