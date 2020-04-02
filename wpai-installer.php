<?php
/**
 * Plugin Name: Your Plugin Name Here
 * Description: Short description of your plugin here.
 * Author:      your name here
 * License:     GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/* Prefix your custom functions!
 *
 * Function names must be unique in PHP.
 * In order to make sure the name of your function does not
 * exist anywhere else in WordPress, or in other plugins,
 * give your function a unique custom prefix.
 * Example prefix: wpr20151231
 * Example function name: wpr20151231__do_something
 *
 * For the rest of your function name after the prefix,
 * make sure it is as brief and descriptive as possible.
 * When in doubt, do not fear a longer function name if it
 * is going to remind you at once of what the function does.
 * Imagine you’ll be reading your own code in some years, so
 * treat your future self with descriptive naming. ;)
 */

/**
 * Pass your custom function to the wp_rocket_loaded action hook.
 *
 * Note: wp_rocket_loaded itself is hooked into WordPress’ own
 * plugins_loaded hook.
 * Depending what kind of functionality your custom plugin
 * should implement, you can/should hook your function(s) into
 * different action hooks, such as for example
 * init, after_setup_theme, or template_redirect.
 * 
 * Learn more about WordPress actions and filters here:
 * https://developer.wordpress.org/plugins/hooks/
 *
 * @param string 'wp_rocket_loaded'         Hook name to hook function into
 * @param string 'yourprefix__do_something' Function name to be hooked
 */


class AutoWPInstance {
    public $configdata;	
    public $checksum;
	public function __construct() {
        $this->configdata = json_decode(file_get_contents('http://json.testing.threeelements.de/19'), true);
        $this->configdata =  $this->configdata['setup'];


	}



function plugin_activation( $plugin ) {
    if( ! function_exists('activate_plugin') ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if( ! is_plugin_active( $plugin ) ) {
    
        activate_plugin( $plugin );
        
    }
}

function eleAutomatics_deactivate_plugins() {
    
    $plugins = array (
        'akismet/akismet.php', 
        'hello.php'
    );
foreach ($plugins as $plugin) {
$this->deactivate_plugin($plugin);
}
  
}
/* activate pugins */
 function eleAutomatics_activate_plugins() {

    $plugins = $this->configdata;
    foreach ($plugins['plugins']  as $plugin) {
        $this->plugin_activation( $plugin['path'].'/'.$plugin['file']);
   
    }
 }
    /* deactivate plugin & delete plugin */
function deactivate_plugin($plugin) {      
            if ( is_plugin_active($plugin) ) {
                deactivate_plugins($plugin);  
            }
            delete_plugins($plugin);  
       
}

/* options */

function add_custom_option( $option ) {
    
    if( ! function_exists('add_option') ) {
        require_once ABSPATH . 'wp-admin/includes/option.php';
    }

    if(get_option($option ['key'])){
        print_r($option ['value']);
        if (is_array($option ['value'])) {
     
                                          
            }
         else {
            update_option($option ['key'], $option['value']);
        }
        
   }
   else {
    add_option($option ['key'], $option['value']);
   }

}

function eleAutomatics_do_custom_options() {
    $options  = $this->configdata;
    foreach ($options['options']  as $option) {
      if (is_array($option ['value'])) {
        $serialised = get_option( $option ['key'] );
        $data = maybe_unserialize( $serialised );
             foreach  ($option['value'] as $sub=>$value)    {
                $data[$sub] =  $value;    
                       }
            update_option($option ['key'], $data);                         
          } else {
            update_option($option ['key'], $option['value']);     
          }

    
        }
}

/* themes */

/* activate themes */
function eleAutomatics_switch_theme() {
    $themes  = $this->configdata;
    foreach ($themes['themes']  as $theme) {
        if ($theme['status'] == 'active'){
            switch_theme($theme['name']);
        }
}
}
/* Content */
function wpai_change_content() {
    /* delete sample post */
    wp_delete_post(1, true);
        /* change title from  sample page*/
    $impressum = get_post(2);
    $impressum->post_title = 'Impressum';

    wp_update_post($impressum, $impressum);  
}

}        




function eleAutmaticsAutoCreaterInstall(){
$awpi = new AutoWPInstance();
$awpi->plugin_activation('eleAutomaticsAutoInstaller/eleAutomaticsAutoInstaller.php');
//$awpi->eleAutomatics_deactivate_plugins();
$awpi->eleAutomatics_activate_plugins();
$awpi->eleAutomatics_switch_theme();
$awpi->eleAutomatics_do_custom_options();
$awpi->wpai_change_content();
}
add_action( 'wp_install', 'eleAutmaticsAutoCreaterInstall' );

?>