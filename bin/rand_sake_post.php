<?php
/**
 * @copyright 2010 Shinichi Urabe
 */

chdir(dirname(__FILE__));
require_once realpath('./').'/../default.inc.php';
if (!($fp = fopen(DATA_PATH.'text/sake.txt', 'r')))
{
  die('ファイルを開けません');
}
$sakes = array();
while (!feof($fp))
{
  $data = fgets($fp, 4096);
  if ($data)
  {
    $sakes[] = rtrim($data);
  }
}
fclose($fp);
$sake = get_rand_post($sakes);

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);

$results = $twitter->post('statuses/update', array('status' => sprintf('[日本酒銘柄] %s', $sake)));
$http_info = $twitter->http_info;

if('200' != $http_info['http_code'] || !$results)
{
  echo '投稿に失敗しました';
}

function get_rand_post(array $rands)
{
  if (!count($rands))
  {
    return false;
  }
  mt_srand(microtime() * 10000000);
  $result = array_slice($rands, mt_rand(0, count($rands)), 1);

  return $result[0];
}
