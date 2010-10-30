<?php
/**
 * @copyright 2010 Shinichi Urabe
 */

chdir(dirname(__FILE__));
require_once realpath('./').'/../default.inc.php';

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);

// Twitterに発言をPOST
$followers_results = $twitter->get('statuses/followers');
$http_info_followers = $twitter->http_info;
$friends_results = $twitter->get('statuses/friends');
$http_info_friends = $twitter->http_info;

if('200' == $http_info_followers['http_code'] && '200' == $http_info_friends['http_code'])
{
  $friends = array();
  foreach ($friends_results as $v)
  {
    $friends[$v->id] = $v->name;
  }

  $followers = array();
  foreach ($followers_results as $v2)
  {
    if (isset($friends[$v2->id]))
    {
      continue;
    }
    $followers[$v2->id] = $v2->name;
  }
}
else
{
  echo 'データの取得に失敗しました';
  exit;
}

foreach ($followers as $k => $v3)
{
  $twitter->post('friendships/create', array('id' => $k));
  $http_info_creates = array();
  $http_info_create = $twitter->http_info;
  if ('200' == $http_info_create['http_code'])
  {
    echo sprintf('ID:%s Name:%s をfollowしました', $k, $v3)."\n";
  }
  else
  {
    echo sprintf('ID:%s Name:%s のfollow に失敗したようです', $k, $v3)."\n";
  }
}

