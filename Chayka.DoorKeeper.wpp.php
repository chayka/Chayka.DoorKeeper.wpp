<?php
/**
 * Plugin Name: Chayka.DoorKeeper
 * Plugin URI: git@github.com:chayka/Chayka.DoorKeeper.wpp.git
 * Description: WP plugin to restrict site access to registered users only
 * Version: 0.0.1
 * Author: Boris Mossounov <borix@tut.by>
 * Author URI: http://anotherguru.me/
 * License: proprietary
 */

require_once 'vendor/autoload.php';

if(!class_exists("Chayka\\WP\\Plugin")){
    add_action( 'admin_notices', function () {
?>
    <div class="error">
        <p>Chayka Framework functionality is not available</p>
    </div>
<?php
	});
}else{
    require_once dirname(__FILE__).'/Plugin.php';
	add_action('init', array("Chayka\\DoorKeeper\\Plugin", "init"));
}
