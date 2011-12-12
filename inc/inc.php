<?php
 include_once('config.php');
 include_once('common.php');

 function __autoload ( $name )
  {
   $mask = 'Module';
   $pos = strlen($name) - strlen($mask);
   if
    (
     strpos($name, $mask) === $pos
     && preg_match('/^(.*)'.preg_quote($mask).'$/i', $name, $m)
     && is_array($m) && isset($m[1])
    )
    {
     require_once( sprintf('mod/%s.php', $m[1]) );
    }
  }

