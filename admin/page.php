<?php

  $do=isset($_GET['do'])?$_GET['do']:'manage';
  if($do=='manage')
  {
      echo "in man";
  }
  elseif($do=='add')
  {
    echo "in add";
  }
  elseif($do=='insert')
  {
    echo "in ins";
  }
  else
  {
    echo "noooooo";
  }
