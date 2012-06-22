<?php 

class Syte extends Plugin
{
	public function action_init()
	{

	}
	
	public function action_plugin_activation( )
	{
		
	}
	
	public function action_plugin_deactivation( )
	{
		
	}
	
	/**
     * Add the Configure, Authorize and De-Authorize options for the plugin
     *
     * @access public
     * @param array $actions
     * @param string $plugin_id
     * @return array
     */
    public function filter_plugin_config( $actions, $plugin_id )
    {
		//$actions['authorize'] = _t( 'Authorize' );
		$actions['configure'] = _t( 'Configure' );
		//$actions['deauthorize'] = _t( 'De-Authorize' );
		return $actions;
    }
	
	/**
     * Plugin UI - Displays the 'authorize' config option
	 * 
	 * @access public
	 * @return void
     */
    public function action_plugin_ui_authorize()
    {
		
	}
	
	/**
     * Plugin UI - Displays the 'confirm' config option.
     *
     * @access public
     * @return void
     */
	public function action_plugin_ui_confirm()
	{
		
	}
	
	/**
     * Plugin UI - Displays the 'deauthorize' config option.
     *
     * @access public
     * @return void
     */
	public function action_plugin_ui_deauthorize() 
	{
		
	}
	
	/**
	 * Configure each component.
	 * 
	 * @todo: Come up with a way such that users don't have to register their own apps.
	 */
	public function action_plugin_ui_configure()
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$fs = $ui->append( 'fieldset', 'fs_twitter', _t( 'Twitter Authentication', 'syte' ) );
			$fs->append( 'static', 'twitter_help', _t( '<p>To get started create a new application on twitter 
				for your website by going to <a href="https://dev.twitter.com/apps/new" target="_blank">https://dev.twitter.com/apps/new</a>. 
				Once you are done creating your application you will be taken to your application page on twitter, there you already have two 
				pieces of the puzzle, the `Consumer key` and the `Consumer secret` make sure you save those.</p>

<p>Next you will need your access tokens, on the bottom of that page there is a link called **Create my access token** click on that. 
Once you are done you will be given the other two pieces of the puzzle, the `Access token` and the `Access token secret` make sure you save those as well.</p>

Once you have those four items from twitter you have to enter them below.</p>') );
			$fs->append( 'text', 'twitter_consumer_key', __CLASS__ . '__twitter_consumer_key', _t( 'Consumer Key', 'syte' ) );
			$fs->append( 'text', 'twitter_consumer_secret', __CLASS__ . '__twitter_consumer_secret', _t( 'Consumer Secret', 'syte' ) );
			$fs->append( 'text', 'twitter_user_key', __CLASS__ . '__twitter_user_key', _t( 'User Key', 'syte' ) );
			$fs->append( 'text', 'twitter_user_secret', __CLASS__ . '__twitter_user_secret', _t( 'User Secret', 'syte' ) );
		
		$fs = $ui->append( 'fieldset', 'fs_instagram', _t( 'Instagram Authentication', 'syte' ) );
		
		$fs = $ui->append( 'fieldset', 'fs_github', _t( 'GitHub Authentication', 'syte' ) );
		

		$ui->append( 'submit', 'save', _t( 'Save' ) );
		$ui->set_option( 'success_message', _t( 'Options saved', 'syte' ) );
		$ui->on_success( array( $this, 'enable_integrations' ) );
		$ui->out();
	}
	
	// These need to go into the plugin as the theme can't provide them. :-(
	public function filter_rewrite_rules( $rules )
	{
		$rules[] = new RewriteRule(array(
				'name' => 'syte_twitter',
				'parse_regex' => '%^twitter/(?P<username>\w+)/?$%i',
				'build_str' => 'twitter/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_twitter',
				'priority' => 7,
				'is_active' => 1,
		));
		
		$rules[] = new RewriteRule(array(
				'name' => 'syte_github',
				'parse_regex' => '%^github/(?P<username>\w+)/?$%i',
				'build_str' => 'github/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_github',
				'priority' => 7,
				'is_active' => 1,
		));
		
		$rules[] = new RewriteRule(array(
				'name' => 'syte_instagram',
				'parse_regex' => '%^instagram/?$%i',
				'build_str' => 'instagram',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_instagram',
				'priority' => 5,
				'is_active' => 1,
		));
		
		$rules[] = new RewriteRule(array(
				'name' => 'syte_instagram',
				'parse_regex' => '%^instagram/(?P<max_id>\w+)?/?$%i',
				'build_str' => 'instagram/{$max_id}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_instagram',
				'priority' => 7,
				'is_active' => 1,
		));

		return $rules;
	}
	
	// TODO: I think I'm going to need to bring the blocks into the plugin or come up with a better way to configure the blocks so I can access them here.
	public function action_handler_syte_twitter( $handler_vars )
	{
		require_once dirname( __FILE__ ) . '/lib/twitteroauth.php';
		$url = "http://api.twitter.com/1/statuses/user_timeline.json?include_rts=false&exclude_replies=true&screen_name={$handler_vars['username']}";
		
		// These won't change
		$con_key = 'Y304HN6NFTf3EN4evHiQ';
		$con_sec = 'BkyMAGcBoS9oNTfjMCC4s0bvrKQNw3jDDB2mS3DPs';
		
		// These will
		$acc_tok = '8812362-gekHnplD2HTSYFiocJ0BkHtOKT1zfvnHT5RpZYMJeY';
		$acc_tok_sec = 'X03oRlAJDvHFsXFvzIJaJIibKdCrUoPNxijbm7Pwx9Y';
		
		$oauth = new TwitterOAuth( $con_key, $con_sec, $acc_tok, $acc_tok_sec );
		$oauth->decode_json = false;
		$resp = $oauth->get( 'statuses/user_timeline', array( 'screen_name' => $handler_vars['username'] ) );
		
		echo $resp;
		exit();
	}
	
	public function action_handler_syte_github( $handler_vars )
	{
		// We don't actually need authentication to get public repos.
		// Grab the user info
		$r = '{"user":';
		$r .= RemoteRequest::get_contents( 'https://api.github.com/users/'.$handler_vars['username'] );
		$r .= ', "repos":';
		// Grab the repos info
		$r .= RemoteRequest::get_contents( 'https://api.github.com/users/'.$handler_vars['username'].'/repos' );
		$r .= '}';
		echo $r;
		exit();
	}
	
	// TODO: As Instagram are now owned by Twitter, I believe we can use the same methodology here.
	public function action_handler_syte_instagram( $handler_vars )
	{
		// These won't change
		$client_id = '4390b77a28d64147bdaa39130d22c3d7';
		$client_sec = '0285b652402f4148ab1405405c36a330';	// We don't want to include this is we can help it.
		
		// TODO: We can probably grab the access token using the implicit grant method in a hidden iframe.
		// http://stackoverflow.com/questions/2670626/get-url-from-iframe-and-update-hash-in-browser-url

		/*
		 * {"access_token":"45852486.4390b77.541c78c57e694dfda9379c5911580f68","user":{"username":"lildoodlil","bio":"","website":"http:\/\/colinseymour.co.uk","profile_picture":"http:\/\/images.instagram.com\/profiles\/anonymousUser.jpg","full_name":"Colin","id":"45852486"}}%    
		 */
		// These will
		$acc_tok = '45852486.4390b77.541c78c57e694dfda9379c5911580f68';
		$user_id = '45852486';
		
		// We don't actually need authentication to get public repos.
		// https://api.instagram.com/v1/users/45852486?access_token=45852486.4390b77.541c78c57e694dfda9379c5911580f68
		// Grab the user info
		$user = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/?access_token='.$acc_tok );
		$user = json_decode( $user );
		$user = json_encode( $user->data );
		
		// Gram media info
		if ( ! isset( $handler_vars['max_id'] ) ) {
			$media = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$acc_tok );
		} else {
			$media = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$acc_tok.'&max_id='.$handler_vars['max_id'] );
		}
		$media_uenc = json_decode( $media );
		$media = json_encode( $media_uenc->data );
		
		// Pagination
		$pagination = json_encode( $media_uenc->pagination );
		
		$r = '{"user":'.$user.', "media":'.$media.', "pagination":'.$pagination.'}';
		echo $r;
		exit();
	}
}
?>