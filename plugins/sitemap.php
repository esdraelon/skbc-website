<?php

/*
Plugin Name: Sitemap
Description: Show a sitemap of your pages
Version: 1.0.1
Author: Rogier Koppejan
Author URI: http://rxgr.nl/getsimple/
*/


# get correct id for plugin
$thisfile = basename(__FILE__, '.php');

# register plugin
register_plugin(
  $thisfile,
  'Sitemap',
  '1.0.1',
  'Rogier Koppejan',
  'http://rxgr.nl/getsimple/',
  'Show a sitemap of your pages'
);

# hooks
add_filter('content', 'sm_filter');


/*******************************************************
 * @function sm_filter
 * @returns content with sitemap inserted, if requested
 */
function sm_filter($content) {
  if (preg_match('/\(%\s*sitemap\s*%\)/', $content)) {
    $pages = sm_get_pages();
    $sitemap = sm_generate($pages);
    $replacement = sm_to_html($sitemap);
    $content = preg_replace('/\(%\s*sitemap\s*%\)/', $replacement, $content);
  }
  return $content;
}


/*******************************************************
 * @function sm_show
 * @action generates and prints the sitemap to stdout
 */
function sm_show() {
  $pages = sm_get_pages();
  $sitemap = sm_generate($pages);
  echo sm_to_html($sitemap);
}


/*******************************************************
 * @function sm_get_pages
 * @return 1-D array of pages, ordered by menu priority
 */
function sm_get_pages() {
  $pages = array();
  $files = getFiles(GSDATAPAGESPATH);
  foreach ($files as $file) {
    if (isFile($file, GSDATAPAGESPATH, 'xml')) {
      $data = getXML(GSDATAPAGESPATH . $file);
      if ($data->private != 'Y') {
        $slug = strval($data->url);
        $pages[$slug]['parent'] = strval($data->parent);
        $pages[$slug]['order'] = intval($data->menuOrder);
        $pages[$slug]['url'] = find_url($slug, $data->parent);
        if (!empty($data->menu))
          $pages[$slug]['title'] = stripslashes($data->menu);
        else
          $pages[$slug]['title'] = cl($data->title);
      }
    }
  }
  sm_sort($pages);
  return $pages;
}


/*******************************************************
 * @function sm_sort
 * @param $pages page array
 * @action sort the page array by menu priority
 */
function sm_sort(&$pages) {
  $tmp = array();
  foreach($pages as $slug=>$data)
    $tmp[$slug] = $data['order'];
  asort($tmp);
  foreach(array_keys($tmp) as $slug)
    $tmp[$slug] = $pages[$slug];
  $pages = $tmp;
}


/*******************************************************
 * @function sm_generate
 * @param $pages page array
 * @return n-D array of pages and their children
 */
function sm_generate($pages) {
  $sitemap = array();
  foreach ($pages as $slug=>$page) {
    if ($page['parent'] == '') {
      $children = sm_get_children($slug, $pages);
      $sitemap[sm_get_link($page)] = $children;
    }
  }
  return $sitemap;
}


/*******************************************************
 * @function sm_get_children
 * @param $parent current page
 * @param $pages page array
 * @return a list of children for the current page (recursive)
 */
function sm_get_children($parent, $pages) {
  $children = array();
  foreach ($pages as $slug=>$page) {
    if ($page['parent'] == $parent)
      $children[sm_get_link($page)] = sm_get_children($slug, $pages);
  }
  return $children;
}


/*******************************************************
 * @function sm_get_link
 * @param $page page data
 * @return link to page on front-end site
 */
function sm_get_link($page) {
  $url = $page['url'];
  $title = $page['title'];
  return "<a href=\"$url\">$title</a>";
}


/*******************************************************
 * @function sm_to_html
 * @param $sitemap sitemap array
 * @return current sitemap in html format
 */
function sm_to_html($sitemap) {
  $html = '';
  sm_add_list($sitemap, $html);
  return "<div id=\"sitemap\">$html</div>";
}


/*******************************************************
 * @function sm_add_list
 * @param $pages page array
 * @param $html string
 * @action add pages to html string (recursive)
 */
function sm_add_list($pages, &$html) {
  $html .= '<ul>';
  foreach ($pages as $parent=>$children)
    sm_add_item($parent, $children, $html);
  $html .= '</ul>';
}


/*******************************************************
 * @function sm_add_item
 * @param $parent current page
 * @param $children sub pages
 * @param $html string
 * @action add parent to html string and traverse children (recursive)
 */
function sm_add_item($parent, $children, &$html) {
  $html .= "<li>$parent";
  if (!empty($children))
    sm_add_list($children, $html);
  $html .= '</li>';
}


?>
