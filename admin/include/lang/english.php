<?php


      function lang($word)
      {
          static $arr=array
          (
              'message'=>'welcome'
          );
          return $arr[$word];
      }