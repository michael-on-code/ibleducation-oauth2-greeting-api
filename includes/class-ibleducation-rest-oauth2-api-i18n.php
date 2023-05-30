<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.animashaunmichael.com
 * @since      1.0.0
 *
 * @package    Ibleducation_Rest_Oauth2_Api
 * @subpackage Ibleducation_Rest_Oauth2_Api/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ibleducation_Rest_Oauth2_Api
 * @subpackage Ibleducation_Rest_Oauth2_Api/includes
 * @author     Michael ANIMASHAUN <michaeloncode@gmail.com>
 */
class Ibleducation_Rest_Oauth2_Api_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ibleducation-rest-oauth2-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
