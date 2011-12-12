<?php include_once('inc/inc.php'); ?>
<!DOCTYPE html>
<html <?= (CACHE_MANIFEST) ? 'manifest="inc/cache.manifest"' : '' ?>>
  <head>
    <link rel="icon" type="image/png" href="inc/ui/apple-touch-icon-56x56.png" />
    <meta content="application/xhtml+xml; charset=utf-8" http-equiv="content-type" />
    <title><?= TITLE ?></title>

    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="apple-touch-icon" href="inc/ui/apple-touch-icon-56x56.png" />
    <link rel="apple-touch-startup-image" href="inc/ui/apple-touch-startup-image-<?php echo getAppleTouchStartupImageSize(); ?>.png" />

    <script type="text/javascript" src="inc/lib/q.js"></script>
    <script type="text/javascript" src="inc/lib/iscroll.js"></script>
    <link rel="stylesheet" type="text/css" href="inc/ui/ui.css" />
    <script type="text/javascript" src="inc/ui/ui.js"></script>
    <!-- PlayPhone JavaScript SDK Host-->
    <script type="text/javascript" src="<?= SDK_HOST ?>"></script>
  </head>
  <body>
   <div id="wrapper">
    <div id="header" class="ui-header">
     <div id="title"><?= TITLE ?></div>
     <div id="back" class="ui-button-back-blue hidden"><span>Go Back</span></div>
    </div>
    <div id="container">
     <div id="content">
      <div id="home">

       <? new API_InitModule(); ?>

       <div class="ui-box-margin">
        <div class="ui-title-blue-indented">Required Integration</div>
        <div class="ui-list-box">
         <? new API_LoginUserModule(); ?>
         <? new API_DashboardModule(); ?>
         <? new API_VirtualEconomyModule(); ?>
          <? new API_VirtualItemsScreenModule(); ?>
          <? new API_StoreModule(); ?>
           <? new API_ShopCategoriesModule(); ?>
           <? new API_ShopPacksModule(); ?>
        </div>
       </div>

       <div class="ui-box-margin">
        <div class="ui-title-blue-indented">Advanced Features</div>
        <div class="ui-list-box">
         <? new API_CurrentUserInfoModule(); ?>
         <? new API_AchievementsModule(); ?>
          <? new API_GameAchievementsModule(); ?>
          <? new API_UserAchievementsModule(); ?>
         <? new API_SocialGraphModule(); ?>
         <? new API_LeaderboardModule(); ?>
         <? new API_DashboardControlModule(); ?>
        </div>
       </div>

       <div class="ui-box-margin">
        <div class="ui-title-blue-indented">System Information</div>
        <div class="ui-list-box">
         <? new ApplicationInformationModule(); ?>
        </div>
       </div>

      </div>

     </div>
    </div>
   </div>
  </body>
</html>