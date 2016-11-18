<?php
$list_element = ($is_select)
  ? 'select'
  : 'ul';
?>
<div class="<?php echo $container_class; ?>">
  <?php echo $label; ?>
  <<?php echo $list_element; ?>>
    <?php echo $pagination_items; ?>
  </<?php echo $list_element; ?>>
</div>
