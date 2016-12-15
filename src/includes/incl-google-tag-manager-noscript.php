<?php $analytics_tag_manager_key = AppOption::getInstance()->analytics_tag_manager_key; ?>
<?php if($analytics_tag_manager_key): ?>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $analytics_tag_manager_key; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
