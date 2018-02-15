<?php

/**
 * @file
 * template.php
 *
 * Contains theme override functions and preprocess functions for the theme.
 */


define("CRESCENT_AMERIFLOW_WHO_WE_ARE_NID", 7);
define("CRESCENT_AMERIFLOW_FLOWBACK_NID", 12);
define("CRESCENT_AMERIFLOW_CAREERS_NID", 16);
define("CRESCENT_AMERIFLOW_LAUNCH_ENVIROEDGE_NID", 77);


/**
 * Implements hook_preprocess_html().
 */
function crescent_ameriflow_preprocess_html(&$vars) {
  $html5 = array(
    '#tag' => 'script',
    '#attributes' => array(
      'src' => base_path() . drupal_get_path('theme', 'crescent_ameriflow') . '/js/lib/html5.js',
    ),
    '#prefix' => '<!--[if (lt IE 9) & (!IEMobile)]>',
    '#suffix' => '</script><![endif]-->',
  );
  drupal_add_html_head($html5, 'crescent_ameriflow_html5');

  $vars['classes_array'][] = 'page';
  if ($node = menu_get_object()) {
    $vars['classes_array'][] = 'page-' . $node->type;
    switch ($node->type) {
      case 'health_safety':
        $vars['classes_array'][] = 'page-tabs';
        break;
      case 'who_we_are':
        $vars['classes_array'][] = 'page-about page-who-we-are';
        break;
      case 'fblog_post':
        $vars['classes_array'][] = 'page-news';
        break;
      case 'enviroedge':
        $vars['classes_array'][] = 'page-enviro';
        break;
    }
  }

  if (in_array("html__blog", $vars['theme_hook_suggestions'])) {
    $vars['classes_array'][] = 'page-news';
  }

  if (in_array("html__remote_blog_post", $vars['theme_hook_suggestions'])) {
    $vars['classes_array'][] = 'page-news';
  }

}

/**
 * Implements hook_preprocess_page().
 */
function crescent_ameriflow_preprocess_page(&$vars) {
  $vars['main_menu'] = (module_exists("fmenu")) ? fmenu_get_menu_tree('main-menu') : "";

  $vars['top_menu'] = theme('links__menu_top_menu', array(
    'links' => menu_navigation_links('menu-top-menu'),
  ));

  $vars['footer_menu'] = theme('links__menu_footer_menu', array(
    'links' => menu_navigation_links('menu-footer-menu'),
  ));


  if ($node = menu_get_object()) {
    switch ($node->type) {
      case 'home':
    }
  }
}


/**
 * Implements hook_preprocess_node().
 */
function crescent_ameriflow_preprocess_node(&$vars) {
  //$node = $vars['node'];
  switch ($vars['type']) {
    case 'corporate_profile':
    case 'core_values':
    case 'about_us':
    case 'management_team':
      if (isset($vars['view_mode']) && $vars['view_mode'] == 'full') {
        drupal_goto("node/" . CRESCENT_AMERIFLOW_WHO_WE_ARE_NID);
      }
      break;
    case 'flowback_tab_item':
      if (isset($vars['view_mode']) && $vars['view_mode'] == 'full') {
        drupal_goto("node/" . CRESCENT_AMERIFLOW_FLOWBACK_NID);
      }
      if (isset($vars['field_fto_img']) && !empty($vars['field_fto_img'])) {
        $vars['add_class'] = 'text-image';
      }
      elseif (isset($vars['field_flowback_list']) && !empty($vars['field_flowback_list'])) {
        $vars['add_class'] = 'text-full';
      }
      break;
    case 'resume':
    case 'application':
      if (isset($vars['view_mode']) && $vars['view_mode'] == 'full') {
        drupal_goto("node/" . CRESCENT_AMERIFLOW_CAREERS_NID);
      }
      break;
    case 'contact':
      $vars['title_prefix'] = array(
        '#type' => 'markup',
        '#markup' => '<span class="bg"></span>',
      );
      break;
    case 'flowback':
      if (isset($vars['field_flowback_tabs']) && !empty($vars['field_flowback_tabs'])) {
        switch ($vars['field_flowback_tabs'][LANGUAGE_NONE][0]['moddelta']) {
          case 'quicktabs:dril':
            $vars['classes_array'][] = 'node-flowback-drill-out';
            break;
          case 'quicktabs:flowback':
            $vars['classes_array'][] = 'node-flowback-support';
            break;
          case 'quicktabs:production':
            $vars['classes_array'][] = 'node-production';
            break;
          case 'quicktabs:well_testing':
            $vars['classes_array'][] = 'node-well-testing';
            break;
        }
      }

      if (isset($vars['field_flowback_gallery']) && !empty($vars['field_flowback_gallery'])) {
        $vars['add_class'] = 'text-slider';
      }
      elseif (isset($vars['field_flowback_list']) && !empty($vars['field_flowback_list'])) {
        $vars['add_class'] = 'text-full';
      }
      break;
    case 'enviroedge_items':
      if (isset($vars['content']['field_ee_items_gallery']) && !empty($vars['content']['field_ee_items_gallery'])) {
        $vars['add_class'] = 'text-slider';
      }

      if (isset($vars['node']->nid) && ($vars['node']->nid == CRESCENT_AMERIFLOW_LAUNCH_ENVIROEDGE_NID)) {
        drupal_add_js('
        function doGo() {
          var URLBase = "https://enviroedge.trihedral.com/";
          finalURL = URLBase + document.getElementById(\'realm\').value;
          location = finalURL;
          return false;
        };', array(
          'type' => 'inline',
          'group' => JS_THEME,
          'weight' => 6
        ));
      }
      break;
  }
}

/**
 * Implements hook_preprocess_block().
 */
function crescent_ameriflow_preprocess_block(&$vars) {
  //kpr($vars);
}


/**
 * Implements hook_form_alter().
 */
function crescent_ameriflow_form_alter(&$form, &$form_state, $form_id) {
  switch ($form_id) {
    case "webform_client_form_15":
      $form['#attributes']['class'][] = "form form-careers";
      $form['actions']['submit']['#prefix'] = '<div class="btn-wrapp"><div class="submit-wrapper">';
      $form['actions']['submit']['#suffix'] = '</div></div>';
      break;
  }
}


/**
 * Build menu.
 */
function crescent_ameriflow_menu_tree_full_data($menu_name) {
  $tree = &drupal_static(__FUNCTION__, array());

  // Check if the active trail has been overridden for this menu tree.
  $active_path = menu_tree_get_path($menu_name);

  // Generate a cache ID(cid) specific for this page
  $item = menu_get_item($active_path);
  $cid = "links:$menu_name:full:{$item['href']}:{$GLOBALS['language']->language}";

  // Did we already build this menu during this request?
  if (isset($tree[$cid])) {
    return $tree[$cid];
  }

  // If the static variable doesn't have the data, check {cache_menu}.
  $cache = cache_get($cid, 'cache_menu');
  if ($cache && isset($cache->data)) {
    $tree_params = $cache->data;
    if (isset($tree_params)) {
      return $tree[$cid] = menu_build_tree($menu_name, $tree_params);
    }
  }

  $tree_params = array(
    'min_depth' => 1,
    'max_depth' => NULL,
  );
  // Parent mlids; used both as key and value to ensure uniqueness.
  // We always want all the top-level links with plid == 0.
  $active_trail = array(0 => 0);

  // Find a menu link corresponding to the current path. If $active_path
  // is NULL, let menu_link_get_preferred() determine the path.
  $active_link = menu_link_get_preferred($active_path, $menu_name);
  // The active link may only be taken into account to build the
  // active trail, if it resides in the requested menu. Otherwise,
  // we'd needlessly re-run _menu_build_tree() queries for every menu
  // on every page.
  if (@$active_link['menu_name'] == $menu_name) {
    // Use all the coordinates, except the last one because there
    // can be no child beyond the last column.
    for ($i = 1; $i < MENU_MAX_DEPTH; $i++) {
      if ($active_link['p' . $i]) {
        $active_trail[$active_link['p' . $i]] = $active_link['p' . $i];
      }
    }
  }

  $parents = $active_trail;
  do {
    $result = db_select('menu_links', NULL, array('fetch' => PDO::FETCH_ASSOC))
      ->fields('menu_links', array('mlid'))
      ->condition('menu_name', $menu_name)
      //->condition('expanded', 1)
      ->condition('has_children', 1)
      ->condition('plid', $parents, 'IN')
      ->condition('mlid', $parents, 'NOT IN')
      ->execute();
    $num_rows = FALSE;
    foreach ($result as $item) {
      $parents[$item['mlid']] = $item['mlid'];
      $num_rows = TRUE;
    }
  } while ($num_rows);
  $tree_params['expanded'] = $parents;
  $tree_params['active_trail'] = $active_trail;

  // Cache the tree building parameters using the page-specific cid.
  cache_set($cid, $tree_params, 'cache_menu');

  // Build the tree using the parameters; the resulting tree will be cached by _menu_build_tree().
  return $tree[$cid] = menu_build_tree($menu_name, $tree_params);
}

function oct_menu_tree_full_data($menu_name) {
  $tree = &drupal_static(__FUNCTION__, array());

  // Check if the active trail has been overridden for this menu tree.
  $active_path = menu_tree_get_path($menu_name);

  // Generate a cache ID(cid) specific for this page
  $item = menu_get_item($active_path);
  $cid = "links:$menu_name:full:{$item['href']}:{$GLOBALS['language']->language}";

  // Did we already build this menu during this request?
  if (isset($tree[$cid])) {
    return $tree[$cid];
  }

  // If the static variable doesn't have the data, check {cache_menu}.
  $cache = cache_get($cid, 'cache_menu');
  if ($cache && isset($cache->data)) {
    $tree_params = $cache->data;
    if (isset($tree_params)) {
      return $tree[$cid] = menu_build_tree($menu_name, $tree_params);
    }
  }

  $tree_params = array(
    'min_depth' => 1,
    'max_depth' => NULL,
  );
  // Parent mlids; used both as key and value to ensure uniqueness.
  // We always want all the top-level links with plid == 0.
  $active_trail = array(0 => 0);

  // Find a menu link corresponding to the current path. If $active_path
  // is NULL, let menu_link_get_preferred() determine the path.
  $active_link = menu_link_get_preferred($active_path, $menu_name);
  // The active link may only be taken into account to build the
  // active trail, if it resides in the requested menu. Otherwise,
  // we'd needlessly re-run _menu_build_tree() queries for every menu
  // on every page.
  if (@$active_link['menu_name'] == $menu_name) {
    // Use all the coordinates, except the last one because there
    // can be no child beyond the last column.
    for ($i = 1; $i < MENU_MAX_DEPTH; $i++) {
      if ($active_link['p' . $i]) {
        $active_trail[$active_link['p' . $i]] = $active_link['p' . $i];
      }
    }
  }

  $parents = $active_trail;
  do {
    $result = db_select('menu_links', NULL, array('fetch' => PDO::FETCH_ASSOC))
      ->fields('menu_links', array('mlid'))
      ->condition('menu_name', $menu_name)
      //->condition('expanded', 1)
      ->condition('has_children', 1)
      ->condition('plid', $parents, 'IN')
      ->condition('mlid', $parents, 'NOT IN')
      ->execute();
    $num_rows = FALSE;
    foreach ($result as $item) {
      $parents[$item['mlid']] = $item['mlid'];
      $num_rows = TRUE;
    }
  } while ($num_rows);
  $tree_params['expanded'] = $parents;
  $tree_params['active_trail'] = $active_trail;

  // Cache the tree building parameters using the page-specific cid.
  cache_set($cid, $tree_params, 'cache_menu');

  // Build the tree using the parameters; the resulting tree will be cached by _menu_build_tree().
  return $tree[$cid] = menu_build_tree($menu_name, $tree_params);
}


/**
 * Theme function to output tablinks for classic Quicktabs style tabs.
 *
 * @ingroup themeable
 */
function crescent_ameriflow_qt_quicktabs_tabset($vars) {
  $variables = array(
    'attributes' => array(
      'class' => 'quicktabs-tabs quicktabs-style-' . $vars['tabset']['#options']['style'],
    ),
    'items' => array(),
  );
  $c = 1;

  foreach (element_children($vars['tabset']['tablinks']) as $key) {
    $item = array();
    if (is_array($vars['tabset']['tablinks'][$key])) {
      $tab = $vars['tabset']['tablinks'][$key];
      if ($key == $vars['tabset']['#options']['active']) {
        $item['class'] = array('active');
      }
      $item['class'][] = 'quicktabs-tabs-item-' . $c;
      $c++;
      $item['data'] = drupal_render($tab);
      $variables['items'][] = $item;
    }
  }
  return theme('item_list', $variables);
}

/**
 * Implements hook_preprocess_views_view().
 */
function crescent_ameriflow_preprocess_views_view(&$vars) {
  if (isset($vars['view']->name) && $vars['view']->name == "contact") {
    crescent_ameriflow_prepare_contact_map($vars);
  }
}

/**
 * Prepare map for contact page.
 * @param $vars
 */
function crescent_ameriflow_prepare_contact_map(&$vars) {
  $results = isset($vars['view']->result) ? $vars['view']->result : array();
  $states = array();
  $term_id = NULL;
  $term = NULL;
  $term_color = NULL;
  $term_map_image = NULL;
  $term_icon = NULL;
  $term_map_icon = NULL;
  //kpr($results);
  foreach ($results as $key => $value) {
    $state_key = isset($value->field_field_location_state[0]['raw']['value']) ? $value->field_field_location_state[0]['raw']['value'] : NULL;
    $state_value = isset($value->field_field_location_state[0]['rendered']['#markup']) ? $value->field_field_location_state[0]['rendered']['#markup'] : NULL;
    $city_value = isset($value->field_field_location_city[0]['rendered']['#markup']) ? $value->field_field_location_city[0]['rendered']['#markup'] : NULL;
    $city_marker_left = isset($value->field_field_location_marker_left[0]['raw']['value']) ? $value->field_field_location_marker_left[0]['raw']['value'] : NULL;
    $city_marker_top = isset($value->field_field_location_marker_top[0]['raw']['value']) ? $value->field_field_location_marker_top[0]['raw']['value'] : NULL;


    $nid = isset($value->nid) ? $value->nid : NULL;
    $tid = isset($value->taxonomy_term_data_node_tid) ? $value->taxonomy_term_data_node_tid : NULL;
    if ($term_id != $tid) {
      $term_id = $tid;
      $term = taxonomy_term_load($term_id);
      $term_color = field_get_items('taxonomy_term', $term, 'field_locations_color');
      $term_bg_color = field_get_items('taxonomy_term', $term, 'field_locations_bg_color');
      $term_map_image = field_get_items('taxonomy_term', $term, 'field_locations_map_image');
      $term_icon = field_get_items('taxonomy_term', $term, 'field_locations_icon');
      $term_map_icon = field_get_items('taxonomy_term', $term, 'field_locations_map_icon');
    }

    if (!empty($state_key) && !empty($state_value) && !empty($city_value) && !empty($nid)) {
      $states[$state_key]['state_name'] = $state_value;
      $states[$state_key]['city'][] = array(
        'nid' => $nid,
        'name' => $city_value,
        'left' => $city_marker_left,
        'top' => $city_marker_top
      );
    }
  }

  $map_color = isset($term_color[0]['rgb']) ? $term_color[0]['rgb'] : NULL;
  $map_bg_color = isset($term_bg_color[0]['rgb']) ? $term_bg_color[0]['rgb'] : NULL;
  $map_image_uri = isset($term_map_image[0]['uri']) ? $term_map_image[0]['uri'] : NULL;
  $map_image = file_create_url($map_image_uri);
  $icon_uri = isset($term_icon[0]['uri']) ? $term_icon[0]['uri'] : NULL;
  $icon = file_create_url($icon_uri);
  $map_icon_uri = isset($term_map_icon[0]['uri']) ? $term_map_icon[0]['uri'] : NULL;
  $map_icon = file_create_url($map_icon_uri);

  $vars['view']->map = array(
    'map_color' => $map_color,
    'map_bg_color' => $map_bg_color,
    'map_image' => $map_image,
    'icon' => $icon,
    'map_icon' => $map_icon,
    'states' => $states
  );
}

/**
 * Implements theme_qt_quicktabs().
 */
function crescent_ameriflow_qt_quicktabs($variables) {
  drupal_add_js(drupal_get_path('theme', 'crescent_ameriflow') . '/js/quicktabs_dlink.js');
  return theme_qt_quicktabs($variables);
}

/**
 * Implements hook_quicktabs_alter().
 */
function crescent_ameriflow_quicktabs_alter($info) {
  $param_name = isset($info->machine_name) ? $info->machine_name : '';
  $parametr = isset($_GET['qt']) ? $_GET['qt'] : '';
  if (is_numeric($parametr) && $param_name) {
    $_GET['qt-' . $param_name] = $parametr;
    unset($_GET['qt']);
  }
}


/**
 * Implements hook_menu_alter().
 */
function crescent_ameriflow_menu_alter(&$items) {
  $items['user/register']['access callback'] = FALSE;
  $items['user/password']['access callback'] = FALSE;
}