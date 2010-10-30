<?php
/**
 * @copyright 2010 Shinichi Urabe
 */

require_once realpath('./').'/../default.inc.php';

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
