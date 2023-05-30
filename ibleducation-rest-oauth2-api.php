<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.animashaunmichael.com
 * @since             1.0.0
 * @package           Ibleducation_Rest_Oauth2_Api
 *
 * @wordpress-plugin
 * Plugin Name:       IBL Education Rest OAuth2 API
 * Plugin URI:        https://www.animashaunmichael.com
 * Description:       A plugin that exposes an API that is secured behind an OAuth2 layer displaying infos on the WordPress Admin screen
 * Version:           1.0.0
 * Author:            Michael ANIMASHAUN
 * Author URI:        https://www.animashaunmichael.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ibleducation-rest-oauth2-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IBLEDUCATION_REST_OAUTH2_API_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ibleducation-rest-oauth2-api-activator.php
 */
function activate_ibleducation_rest_oauth2_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ibleducation-rest-oauth2-api-activator.php';
	Ibleducation_Rest_Oauth2_Api_Activator::activate();
	//TODO create tables
	require_once plugin_dir_path(__FILE__).'includes/Restify.php';
	$restifyer = new Restify();
	$restifyer->createDatabase();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ibleducation-rest-oauth2-api-deactivator.php
 */
function deactivate_ibleducation_rest_oauth2_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ibleducation-rest-oauth2-api-deactivator.php';
	Ibleducation_Rest_Oauth2_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ibleducation_rest_oauth2_api' );
register_deactivation_hook( __FILE__, 'deactivate_ibleducation_rest_oauth2_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ibleducation-rest-oauth2-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ibleducation_rest_oauth2_api() {

	$plugin = new Ibleducation_Rest_Oauth2_Api();
	$plugin->run();


}
run_ibleducation_rest_oauth2_api();
