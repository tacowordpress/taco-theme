<?php
$current_page_class = (isset($is_current_page) && $is_current_page)
  ? 'class="on"'
  : null;
?>
<li <?php echo $current_page_class; ?> data-page="<?php echo $page_number; ?>"><?php echo $link; ?></li>
