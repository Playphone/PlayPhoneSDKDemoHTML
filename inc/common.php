<?php

 class Module
 {

  public function __construct ( )
   {
    $this->Render();
   }
/*
  public function RenderCSS ( $name )
   {

   }

  public function RenderHTML ( $name )
   {

   }

  public function RenderJS ( $name )
   {

   }
*/
  final public function Render ( )
   {
    self::_RenderCSS($this);
    self::_RenderHTML($this);
    self::_RenderJS($this);
   }

  final private function _RenderCSS ( Module $module )
   {
    if(!method_exists($module, 'RenderCSS')) return;
    $name = get_class($module);
    ?>
     <style type="text/css">
     <? $module->RenderCSS($name); ?>
     </style>
    <?
   }

  final private function _RenderHTML ( Module $module )
   {
    if(!method_exists($module, 'RenderHTML')) return;
    $name = get_class($module);
    ?>
     <div id="<?=$name?>">
     <? $module->RenderHTML($name); ?>
     </div>
    <?
   }

  final private function _RenderJS ( Module $module )
   {
    if(!method_exists($module, 'RenderJS')) return;
    $name = get_class($module);
    ?>
     <script type="text/javascript">
     if(typeof Modules == 'undefined') var Modules = {};
     Modules.<?=$name?> = (new function(){
      var __getName = function() { return '<?=$name?>'; };
      <? $module->RenderJS($name); ?>
     }());
     </script>
    <?
   }

 }

 function getAppleTouchStartupImageSize()
  {
    $ua = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : false;
    if($ua && preg_match('/iPad.*Mobile/Uis',$_SERVER['HTTP_USER_AGENT'])){
      return '768x1004';
    }
    return '320x460';
  }