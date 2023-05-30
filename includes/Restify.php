<?php


class Restify {

	private $_dbGreetingKey = "ibl_restify_key";
	private $_dbGreetingHasBeenSet = "ibl_restify_is_set";
	private $_defaultClientID = "michaeloncode";
	private $_defaultClientSecret = "michaeloncode";

	public function createDatabase() {
		$siteUrl = site_url();
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		// set the default character set and collation for the table
		// Check that the table does not already exist before continuing
		$sql = "CREATE TABLE IF NOT EXISTS oauth_clients (
  client_id             VARCHAR(80)   NOT NULL,
  client_secret         VARCHAR(80),
  redirect_uri          VARCHAR(2000),
  grant_types           VARCHAR(80),
  scope                 VARCHAR(4000),
  user_id               VARCHAR(80),
  PRIMARY KEY (client_id)
);";

		dbDelta( $sql );


		$sql = "CREATE TABLE IF NOT EXISTS oauth_access_tokens (
  access_token         VARCHAR(40)    NOT NULL,
  client_id            VARCHAR(80)    NOT NULL,
  user_id              VARCHAR(80),
  expires              TIMESTAMP      NOT NULL,
  scope                VARCHAR(4000),
  PRIMARY KEY (access_token)
);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS oauth_authorization_codes (
  authorization_code  VARCHAR(40)     NOT NULL,
  client_id           VARCHAR(80)     NOT NULL,
  user_id             VARCHAR(80),
  redirect_uri        VARCHAR(2000),
  expires             TIMESTAMP       NOT NULL,
  scope               VARCHAR(4000),
  id_token            VARCHAR(1000),
  PRIMARY KEY (authorization_code)
);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS oauth_refresh_tokens (
  refresh_token       VARCHAR(40)     NOT NULL,
  client_id           VARCHAR(80)     NOT NULL,
  user_id             VARCHAR(80),
  expires             TIMESTAMP       NOT NULL,
  scope               VARCHAR(4000),
  PRIMARY KEY (refresh_token)
);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS oauth_users (
  username            VARCHAR(80),
  password            VARCHAR(80),
  first_name          VARCHAR(80),
  last_name           VARCHAR(80),
  email               VARCHAR(80),
  email_verified      BOOLEAN,
  scope               VARCHAR(4000),
  PRIMARY KEY (username)
);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS oauth_scopes (
  scope               VARCHAR(80)     NOT NULL,
  is_default          BOOLEAN,
  PRIMARY KEY (scope)
);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS oauth_jwt (
  client_id           VARCHAR(80)     NOT NULL,
  subject             VARCHAR(80),
  public_key          VARCHAR(2000)   NOT NULL
);";
		dbDelta( $sql );
		$sql = "SELECT COUNT(*) as nbr FROM oauth_clients where client_id = '{$this->_defaultClientID}'";
		$result = $wpdb->get_row($sql);
		if(self::maybeNullOrEmpty($result, 'nbr')==0){
			$sql = "INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES
('{$this->_defaultClientID}', '$this->_defaultClientID', '$siteUrl');";
			dbDelta( $sql );
		}
		/*$is_error = empty( $wpdb->last_error);
		var_dump($is_error);exit;*/
	}

	public function run(){
		add_action('wp_dashboard_setup', [$this, 'my_custom_dashboard_widgets']);
	}

	public function my_custom_dashboard_widgets(){
		$greentingHasBeenSet = get_option($this->_dbGreetingHasBeenSet);
		if(!!$greentingHasBeenSet){
			global $wp_meta_boxes;
			wp_add_dashboard_widget('custom_help_widget', 'WP Rest API Greeting', [$this, "greeting_dashboard_show"]);
		}

	}

	public function greeting_dashboard_show() {
		$greetingMsg = get_option($this->_dbGreetingKey);
		echo '<p>'.$greetingMsg.'</p>';
	}



	public function regestering_route() {
		register_rest_route( 'greetingbot/v1', '/login', [
			'methods'  => 'POST',
			'callback' => [ $this, 'request_generate_token' ],
		] );
		register_rest_route( 'greetingbot/v1', '/send', array(
			'methods'  => 'POST',
			'callback' => [ $this, 'save_greetings' ],
		) );
	}

	public function request_access_token_validator(){
		$server = $this->OAuth2Server();
		if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
			$server->getResponse()->send();
			die;
		}
		return true;
	}

	public function OAuth2Server(){
		require_once plugin_dir_path(__FILE__)."OAuth2/Autoloader.php";
		\OAuth2\Autoloader::register();

		$dsn      = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
		$username = DB_USER;
		$password = DB_PASSWORD;
		$storage  = new OAuth2\Storage\Pdo( array( 'dsn' => $dsn, 'username' => $username, 'password' => $password ) );

		$server = new OAuth2\Server( $storage );
		$server->addGrantType( new OAuth2\GrantType\ClientCredentials( $storage ) );
		$server->addGrantType( new OAuth2\GrantType\AuthorizationCode( $storage ) );
		return $server;

	}

	public function request_generate_token(WP_REST_Request $request) {
		$server = $this->OAuth2Server();

		return $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
	}

	public function save_greetings( WP_REST_Request $request ) {
		if($this->request_access_token_validator()){
			$parameters             = [];
			$requestBody = json_decode( $request->get_body() );
			$greeting =  self::maybeNullOrEmpty($requestBody, 'greeting');
			$sanitizedGreeting = sanitize_text_field($greeting);
			update_option($this->_dbGreetingKey, $sanitizedGreeting);
			update_option($this->_dbGreetingHasBeenSet, '1');
			return $this->getSuccessResponse( [ 'message' => "Greeting Message Updated Successfully",
				"status"=>true] );
		}

	}

	private function getErrorResponse( $data, $statusCode = 404 ) {
		$data['status'] = false;

		return new WP_Error( $statusCode, '', array( $data ) );
	}

	private function getSuccessResponse( $data, $status = true ) {
		$data['status'] = $status;

		return rest_ensure_response( $data );
	}

	static function maybeNullOrEmpty( $element, $property, $defaultValue = "" ) {
		if ( is_object( $element ) ) {
			$element = (array) $element;
		}
		if ( isset( $element[ $property ] ) ) {
			return $element[ $property ];
		} else {
			return $defaultValue;
		}

	}
}