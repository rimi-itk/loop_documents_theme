<?php

/**
 * @file
 * Document template.
 */
?>
<div class="loop-book--book">
  <div class="loop-book--book-navigation guide--nav-wrapper">
    <?php
    if (isset($loop_documents_menu)) {
      echo '<h2>' . $loop_documents_menu['#title'] . '</h2>';
      echo render($loop_documents_menu);
    }
    ?>

    <?php
    if (isset($loop_documents_roots)) {
      echo '<h2>' . t('Document collections') . '</h2>';
      echo render($loop_documents_roots);
    }
    ?>
  </div>

  <div class="loop-book--book-content">
    <?php include drupal_get_path('theme', 'loop') . '/templates/node/node--page.tpl.php'; ?>
  </div>
</div>
