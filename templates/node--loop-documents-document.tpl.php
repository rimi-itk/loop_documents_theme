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
			if (isset($loop_documents_menu['#root'])) {
				$root = $loop_documents_menu['#root'];
				echo '<h2>';
				echo $root->title;

				if (isset($loop_documents_menu['#root_edit_link'])) {
					echo ' (' . $loop_documents_menu['#root_edit_link'] . ')';
				}
				echo '</h2>';

				echo '<fieldset><legend>' . t('Metadata') . '</legend>';
				foreach (array(
					'field_loop_documents_owner',
					'field_loop_documents_version',
					'field_loop_documents_approver',
					'field_loop_documents_approv_date',
					'field_loop_documents_review_date',
				) as $field_name) {
					$field = field_view_field('node', $root, $field_name);
					echo render($field);
				}
				echo '</fieldset>';
			}

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

	<div class="credits">
		<?php
		$field = field_view_field('node', $node, 'field_loop_documents_author', array('label' => 'hidden'));
		echo render($field);
		?>
	</div>
</div>
