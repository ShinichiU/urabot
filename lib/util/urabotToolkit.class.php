<?php

class urabotToolkit
{
  static public function getRand($num)
  {
    mt_srand(microtime() * 10000000);

    return mt_rand(1, $num);
  }
}
