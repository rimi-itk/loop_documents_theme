<?php

/**
 * @file
 * Preprocess functions for loop_documents_theme.
 */

/**
 * Implements hook_preprocess_page().
 */
function loop_documents_theme_preprocess_page(&$variables) {
  if (!isset($variables['node'])) {
    return;
  }

  $node = $variables['node'];

  $variables['theme_hook_suggestions'][] = 'page__' . $node->type;
}

/**
 * Implements hook_preprocess_node().
 */
function loop_documents_theme_preprocess_node(&$variables) {
  global $user;

  if (!isset($variables['node'])) {
    return;
  }

  $node = $variables['node'];

  if (!isset($variables['elements']['#view_mode']) || $variables['elements']['#view_mode'] !== 'full') {
    return;
  }

  $variables['display_submitted'] = FALSE;

  $collection_id = NULL;
  if ($node->type === 'loop_documents_collection') {
    $collection_id = $node->nid;
  }
  else {
    $parameters = drupal_get_query_parameters();
    if (!empty($parameters['collection'])) {
      $collection_id = intval($parameters['collection']);
    }
  }

  if (!$collection_id) {
    $menus = loop_documents_get_menus($node);

    if (count($menus) === 1) {
      $collection_id = array_keys($menus)[0];
    }
  }

  if ($collection_id) {
    $menu_name = loop_documents_get_menu_name($collection_id);
    if ($menu_name) {
      $menu = menu_tree($menu_name);
      if ($menu) {
        $query = array(
          'collection' => $collection_id,
        );
        loop_documents_add_query_to_menu($menu, $query);
        $root = node_load($collection_id);
        if ($root) {
          $menu['#root'] = $root;
          if (node_access('update', $root, $user) === TRUE) {
            $menu['#root_edit_link'] = l(t('Edit document collection'), 'node/' . $root->nid . '/edit');
          }
        }
        $variables['loop_documents_menu'] = $menu;
      }
    }
  }
  else {
    $variables['loop_documents_roots'] = $menus;
  }
}

/**
 * Get menu name (if any) from a Document collection node.
 *
 * @param object $node
 *   The node.
 *
 * @return string|null
 *   The menu name.
 */
function loop_documents_get_menu_name($node) {
  if (is_numeric($node)) {
    $node = node_load($node);
  }
  if ($node && isset($node->field_loop_documents_contents[LANGUAGE_NONE][0]['menureference'])) {
    return $node->field_loop_documents_contents[LANGUAGE_NONE][0]['menureference'];
  }

  return NULL;
}

/**
 * Get a menu of all nodes pointing to a menu containing a given node.
 *
 * @param object $node
 *   The node.
 *
 * @return array
 *   The menu.
 */
function loop_documents_get_menus($node) {
  $menus = array();

  $query = db_query('select entity_id from {field_data_field_loop_documents_contents} where field_loop_documents_contents_menureference in (select menu_name from menu_links  where link_path = :link_path)', array('link_path' => 'node/' . $node->nid));
  $roots = node_load_multiple($query->fetchCol());
  foreach ($roots as $root) {
    $menu_name = loop_documents_get_menu_name($root);
    $menus[$root->nid] = array(
      '#theme' => 'menu_link__' . str_replace('-', '_', $menu_name),
      '#attributes' => array(),
      '#title' => $root->title,
      '#href' => 'node/' . $node->nid,
      '#localized_options' => array(
        'query' => array(
          'collection' => $root->nid,
        ),
      ),
      '#below' => array(),
    );
  }

  return $menus;
}

/**
 * Add a query to all items in a menu.
 *
 * @param array $menu
 *   The menu.
 * @param array $query
 *   The query.
 */
function loop_documents_add_query_to_menu(array &$menu, array $query) {
  foreach ($menu as &$item) {
    if (isset($item['#href'])) {
      loop_documents_add_query_to_menu_item($item, $query);
    }
  }
}

/**
 * Add a query to a memu item.
 *
 * @param array $item
 *   The menu item.
 * @param array $query
 *   The query.
 */
function loop_documents_add_query_to_menu_item(array &$item, array $query) {
  if (isset($item['#href'])) {
    if (!isset($item['#localized_options'])) {
      $item['#localized_options'] = array();
    }
    $item['#localized_options'] += array('query' => $query);
  }
  if (isset($item['#below'])) {
    loop_documents_add_query_to_menu($item['#below'], $query);
  }
}
