<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo Theme::pageTitle(); ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

  <?php echo Theme::appIcons(); ?>
  <?php echo Theme::pageMeta(); ?>

  <?php
  echo View::make('meta/google-tag-manager', [
    'account' => AppOption::getInstance()->analytics_tag_manager_key
  ]);
  ?>
  <script src="<?php echo Theme::asset('lib/modernizr/modernizr.js'); ?>"></script>

  <?php wp_head(); ?>

</head>

<?php global $body_class; ?>
<body <?php body_class((isset($body_class)) ? $body_class : null); ?>>

<?php
echo View::make('meta/google-tag-manager-noscript', [
  'account' => AppOption::getInstance()->analytics_tag_manager_key
]);
?>
