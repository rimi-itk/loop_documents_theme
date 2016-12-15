<?php

/**
 * @file
 * Document collection template.
 */
?>
<div class="loop-book--book">
  <div class="loop-book--book-navigation guide--nav-wrapper">
    <?php
    if (isset($loop_documents_menu)) {
      echo render($loop_documents_menu);
    }
    ?>
  </div>

  <div class="loop-book--book-content">
    <?php include drupal_get_path('theme', 'loop') . '/templates/node/node--page.tpl.php'; ?>
  </div>
</div>
