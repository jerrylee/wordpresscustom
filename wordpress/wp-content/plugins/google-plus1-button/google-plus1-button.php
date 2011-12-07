<?php
/*
Plugin Name: Google +1 Button
Plugin URI: http://marbu.org
Description: Adds a the google +1 button
Version: 1.1
Author: Marco Buttarini
Author URI: http://marbu.org
*/

function gplus_options() {
  add_management_page('Google +1 Button', 'Google +1 Button', 8, basename(__FILE__), 'gplus_options_page'); 
}

/**
* Build up all the params for the button
*/
function gplus_get_options() {
  $gplus_array=get_option('gplus_options');
  return $gplus_array;
}

function gplus_update_options(){
  $arrpost=($_POST);
  update_option('gplus_options',$arrpost);
}

/**
* Generate the button
*/
function gplus_generate_button() {
  $options=gplus_get_options();
  if($options['display_counter'])
    $count="true";
  else
    $count="false";
  
  $button='
<div style="'.$options['style'].'">
<g:plusone size="'.$options['size'].'" count="'.$count.'"></g:plusone>
</div>
';

  return $button;
}


/**
* Gets run when the content is loaded in the loop
*/
function gplus_update($content) {
  global $post;
  if (is_archive()) {    return $content;  } 
  if (is_feed()) {    return $content;  } 

  $options=gplus_get_options(); 
  $button=gplus_generate_button();

  if (!$options['enable_plugin']){  return $content;  }
  // add the manual option
  if ($options['where'] == 'manual') {    return $content;  }
  // is it a page
  if ($options['display_page'] == null && is_page()) {    return $content;  }
  // are we on the front page
  if (is_home()) {    return $content;  }
  // are we just using the shortcode
  if ($options['shortcode'] != '') {     return str_replace('[gplusbutton]', $button, $content);  }

  if ($options['where'] == 'beforeandafter') {
    // adding it before and after
    return $button . $content . $button;
  } else if ($options['where'] == 'before') {
    // just before
    return $button . $content;
  } else if ($options['where'] == 'after'){
    // just after
    return $content . $button;
  }
  
  return $content;
}

// Manual output
function gplusbutton() {
  $options=gplus_get_options();
  if ($options['where'] == 'manual') {
    return gplus_generate_button();
  } else {
    return false;
  }
}

// Remove the filter excerpts
function gplus_remove_filter($content) {
  if (!is_feed()) {
    remove_action('the_content', 'gplus_update');
  }
  return $content;
}

function gplus_options_page() {
  if($_POST['action']=="save"){
    gplus_update_options();
  }
  $options=gplus_get_options();

  ?>
<div class="wrap">
<div class="icon32" id="icon-options-general"><br/></div><h2>Google +1 Settings</h2>
<div style="background-color:#dcdcdc; float:left; padding:6px; color:#000000;-moz-border-radius: 5px; border-radius: 5px; margin-top:4px;font-size:10px;">
<a href="http://twitter.com/webgrafia" class="twitter-follow-button"  data-text-color="#000000" data-link-color="#004A6C">Follow @webgrafia</a>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script> 
<br />
for update on plugin development
</div>
<br style="clear:both;">
<p>Configure the Google +1 Button </p>

<form method="post" >
<input type="hidden" name="page" value="<? echo basename(__FILE__); ?>">
<input type="hidden" name="action" value="save">
<table class="form-table">
<tr>
           <th scope="row" valign="top">
             Enable Plugin
             </th>
             <td>
             <input type="checkbox" value="1" <?php if ($options['enable_plugin']) echo 'checked="checked"'; ?> name="enable_plugin" id="enable_plugin" group="gplus_display"/>
             <label for="enable_plugin">Activate plugin</label>
             </td>
             </tr>

	     <tr>
	     <th scope="row" valign="top">
	     Position
	     </th>
	     <td>
	     <select name="where">
	     <option <?php if ($options['where'] == 'before') echo 'selected="selected"'; ?> value="before">Before</option>
             <option <?php if ($options['where'] == 'after') echo 'selected="selected"'; ?> value="after">After</option>
	      <option <?php if ($options['where'] == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter">Before and After</option>
       		<option <?php if ($options['where'] == 'shortcode') echo 'selected="selected"'; ?> value="shortcode">Shortcode [gplusbutton]</option>
     		<option <?php if (($options['where'] == 'manual')||($options['where']=="")) echo 'selected="selected"'; ?> value="manual">Manual</option>
               	</select>
               </td>
            </tr>
            <tr>
                <th scope="row" valign="top">
                    Display
                </th>
                <td>
                    <input type="checkbox" value="1" <?php if ($options['display_page'] == '1') echo 'checked="checked"'; ?> name="display_page" id="display_page" group="gplus_display"/>
                    <label for="display_page">Display the button on pages</label>
                </td>
            </tr>

            <tr>
                <th scope="row" valign="top">
	                    Display Counter
                </th>
                <td>
                    <input type="checkbox" value="1" <?php if ($options['display_counter'] == '1') echo 'checked="checked"'; ?> name="display_counter" id="display_counter" group="gplus_display"/>
                    <label for="display_counter">Display the counter</label>
                   
	                </td>
            </tr>

	            <tr>
                <th scope="row" valign="top">
           		 Style	
                </th>
                <td>
                    <input type="text" value="<?php echo ($options['style']); ?>" name="style" id="style" />
                    <label for="style">css rules to apply to container div (example: float:right; margin:4px;)</label>	                    
                </td>
            </tr>

	            <tr>
                <th scope="row" valign="top">
           		 Size	
                </th>
                <td>

<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
<table>
<tr><td>
 <input  <? if($options['size']=="small") echo 'checked="checked"'; ?> group="plusone-size" id="plusone-size-small" name="size" value="small" type="radio"> <label for="plusone-size-small">Small (15px)</label>
</td><td>
<g:plusone size="small" href="http://marbu.org/marbu/"></g:plusone>
</td></tr>
<tr><td>
<input <? if($options['size']=="standard") echo 'checked="checked"'; ?> group="plusone-size" id="plusone-size-standard" name="size" value="standard" type="radio"> <label for="plusone-size-standard">Standard (24px)</label>
</td><td>
<g:plusone size="standard" href="http://marbu.org/marbu/"></g:plusone>
</td></tr>

<tr><td>
<input  <? if($options['size']=="medium") echo 'checked="checked"'; ?> group="plusone-size" id="plusone-size-medium" name="size" value="medium" type="radio"> <label for="plusone-size-medium">Medium (20px)</label>
</td><td>
<g:plusone size="medium" href="http://marbu.org/marbu/"></g:plusone>
</td></tr>

<tr><td>
<input  <? if($options['size']=="tall") echo 'checked="checked"'; ?> group="plusone-size" id="plusone-size-tall" name="size" value="tall" type="radio"> <label for="plusone-size-tall">Tall (60px)</label>
</td><td>
<g:plusone size="tall" href="http://marbu.org/marbu/"></g:plusone>
</td></tr>
</table>

	                </td>
	            </tr>




        </table>
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
    </div>



<?php
}


function gplus_init(){

}


function gplus_header(){
  $langwp= get_bloginfo('language');
  $arrl=explode("-",$langwp);
  $langbutton=$arrl[0];
  ?>
<!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="http://apis.google.com/js/plusone.js">
  {lang: \''.$langbutton.'\'}
</script>
<?
    }


// Only all the admin options if the user is an admin
if(is_admin()){
    add_action('admin_menu', 'gplus_options');
    add_action('admin_init', 'gplus_init');
}
add_action('wp_head', 'gplus_header');
add_filter('the_content', 'gplus_update');
//add_filter('the_excerpt', 'gplus_update');
add_filter('get_the_excerpt', 'gplus_remove_filter', 9);
?>