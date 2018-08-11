<?php
/*
 * Excerpts - Simply add pages excerpts in your pages.
 * 
 * @version 0.1.0
 * @author Noah "megiddo" smith
 * @link http://www.metzohanian.com
 *
 * From 0.2.1
 * @author Wojciech "yojoe" Jodła
 * @link http://jodla.ayz.pl
 * @author Nicolas "p01" Liautaud
 * @link http://nliautaud.fr
 */

# register plugin
$thisfile=basename(__FILE__, ".php");
register_plugin(
	$thisfile, 	# ID of plugin, should be filename minus php
	'Include Pages Fields', # Title of plugin
	'0.1.0',    # Version of plugin
	'Noah Smith',	   # Author of plugin
	'http://www.metzohanian.com', # Author URL
	'Simply add other page\'s fields in your pages.', # Page type of plugin
	''  	    # Function that displays content
);

add_filter('content','parse_customfields_tags');

/* Parse a string and replace tags by pages excerpts
 * @param string $contents the string to parse
 * @return string the modified string
 */
function parse_customfields_tags($contents)
{
  $pattern = '`(?<!<code>)\(%\s*customfield\s*:\s*([^, ]*)\s*';
  $pattern.= '(?:,\s*([^, ]*)\s*)?'; // field name
  $pattern.= '(?:,\s*(text|html)\s*)?'; // text or html
  $pattern.= '(?:,\s*([^, ]*)\s*)'; // link blurb
  $pattern.= '?%\)`';
  return preg_replace_callback($pattern, 'callback_to_customfields', $contents);
}

/* Call page_excerpt from expreg with good parameters.
 * @param string $mask the pattern result
 * @return string the page excerpt
 */
function callback_to_customfields($mask)
{
  $mask['2'] = isset($mask['2']) ? $mask['2'] : 'excerpt';
  $mask['3'] = isset($mask['3']) ? $mask['3'] : 'text';
  $mask['4'] = isset($mask['4']) ? $mask['4'] : null;
  return page_customfield($mask['1'], $mask['2'], $mask['3'], $mask['4']);
}

/* Return the excerpt of a defined page.
 * @param string $name the page name
 * @param int $chars the excerpt length (200 by default)
 * @param string $type output type : text or html (text by default)
 * @return string the excerpt
 */
function page_customfield($name, $field, $type='text', $link_blurb=null)
{ 
  $file_url = GSDATAPAGESPATH . $name . '.xml';
  if(file_exists($file_url))
  {
    $file = file_get_contents($file_url);
    $page = simplexml_load_string($file);
    $page_len = strlen($page->$field);
    $customfield = $page->$field;
    
    if($type == 'text') {
      $customfield = htmlspecialchars_decode($customfield);
      $customfield = trim(cf_strip_html_tags($customfield));
    } else {
      $customfield = html_entity_decode($customfield);
    }
    if(strlen($link_blurb) > 0) {
      $customfield .= '<a href="' . $name . '" class="custom-field-link-blurb">' . str_replace('-', ' ', $link_blurb) . '</a>';
    }
    return $customfield;
  } 
  return '<p>Custom Fields plugin error : declared page cannot be found.</p>';
}


/* Strip html tags and remove invisible html tags content.
 *
 * PHP's strip_tags() function will remove tags, but it
 * doesn't remove scripts, styles, and other unwanted
 * invisible text between tags.  Also, as a prelude to
 * tokenizing the text, we need to insure that when
 * block-level tags (such as <p> or <div>) are removed,
 * neighboring words aren't joined.
*/
function cf_strip_html_tags($text)
{
  $text = preg_replace(
    array(
      // Remove invisible content
      '@<head[^>]*?>.*?</head>@siu',
      '@<style[^>]*?>.*?</style>@siu',
      '@<script[^>]*?.*?</script>@siu',
      '@<object[^>]*?.*?</object>@siu',
      '@<embed[^>]*?.*?</embed>@siu',
      '@<applet[^>]*?.*?</applet>@siu',
      '@<noframes[^>]*?.*?</noframes>@siu',
      '@<noscript[^>]*?.*?</noscript>@siu',
      '@<noembed[^>]*?.*?</noembed>@siu',

      // Add line breaks before & after blocks
      '@<((br)|(hr))@iu',
      '@</?((address)|(blockquote)|(center)|(del))@iu',
      '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
      '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
      '@</?((table)|(th)|(td)|(caption))@iu',
      '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
      '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
      '@</?((frameset)|(frame)|(iframe))@iu',
    ),
    array(
      ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
      "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
      "\n\$0", "\n\$0",
    ),
    $text);

  // Remove all remaining tags and comments and return.
  return strip_tags($text);
}
?>
