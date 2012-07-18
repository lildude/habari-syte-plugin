<?php 

class Syte extends Plugin
{
	const INSTAGRAM_CLIENT_ID = '4390b77a28d64147bdaa39130d22c3d7';
	const LASTFM_API_KEY = 'e258bec284029b2586ae001fcd42673e';
	
	public function action_init()
	{
		// TODO: These aren't actually used at the moment, but are needed to stop Habari reporting errors.
		$this->add_template( 'block.syte_twitter', dirname( __FILE__ ) . '/blocks/block.twitter.php' );
		$this->add_template( 'block.syte_github', dirname( __FILE__ ) . '/blocks/block.github.php' );
		$this->add_template( 'block.syte_dribbble', dirname( __FILE__ ) . '/blocks/block.dribbble.php' );
		$this->add_template( 'block.syte_instagram', dirname( __FILE__ ) . '/blocks/block.instagram.php' );
		$this->add_template( 'block.syte_lastfm', dirname( __FILE__ ) . '/blocks/block.lastfm.php' );
		
		$this->add_template( 'syte_text', dirname( __FILE__ ) . '/formcontrols/text.php' );
		$this->load_text_domain( 'syte' );
	}
	
	public function action_plugin_activation( )
	{
		
	}
	
	public function action_plugin_deactivation( )
	{
		
	}
	
	/**
     * Add custom Javascript to "Configure" page
     *
     * This needs to be defined at the top for some reason.
     *
     * @access public
     * @param object $theme
     * @return void
     */
    public function action_admin_header( $theme )
    {
        if ( Controller::get_var( 'configure' ) == $this->plugin_id ) {
            Stack::add( 'admin_header_javascript', URL::get_from_filesystem( __FILE__ ) . '/js/admin.js', 'syte-admin', 'jquery' );
		}
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
		$actions['configure'] = _t( 'Configure' );
		return $actions;
    }
	
	/**
	 * Configure each component.
	 * 
	 * @todo: Come up with a way such that users don't have to register their own apps.
	 * @todo: Validate that the fields are not empty when a section is active
	 */
	public function action_plugin_ui_configure()
	{	
		$ui = new FormUI( strtolower( __CLASS__ ) );
		/**** Twitter ****/
		$ui->append( 'checkbox', 'twitter_int', __CLASS__ . '__enable_twitter', _t( 'Enable Twitter Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_twitter', _t( 'Twitter Authentication', 'syte' ) );
			$fs->append( 'static', 'twitter_help', _t( '<p>To get started create a new application on twitter 
				for your website by going to <a href="https://dev.twitter.com/apps/new" target="_blank">https://dev.twitter.com/apps/new</a>. 
				Once you are done creating your application you will be taken to your application page on twitter, there you already have two 
				pieces of the puzzle, the `Consumer key` and the `Consumer secret` make sure you save those.</p>

<p>Next you will need your access tokens, on the bottom of that page there is a link called <strong>>Create my access token</strong> click on that. 
Once you are done you will be given the other two pieces of the puzzle, the `Access token` and the `Access token secret` make sure you save those as well.</p>

<p>Once you have those four items from twitter you have to enter them below.</p>') );
			$fs->append( 'text', 'twitter_url', __CLASS__ . '__twitter_url', _t( 'Twitter URL', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'twitter_consumer_key', __CLASS__ . '__twitter_consumer_key', _t( 'Consumer Key', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'twitter_consumer_secret', __CLASS__ . '__twitter_consumer_secret', _t( 'Consumer Secret', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'twitter_user_key', __CLASS__ . '__twitter_user_key', _t( 'User Key', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'twitter_user_secret', __CLASS__ . '__twitter_user_secret', _t( 'User Secret', 'syte' ), 'syte_text' );
		
		/**** Instagram ****/
		$ui->append( 'checkbox', 'instagram_int', __CLASS__ . '__enable_instagram', _t( 'Enable Instagram Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_instagram', _t( 'Instagram Authentication', 'syte' ) );
			if ( Options::get( __CLASS__ . '__instagram_access_token' ) == '' ) {
				$fs->append( 'static', 'instagram_auth', '
					<p>Clicking the button below will open a new window and ask you to login to Instagram and authorize this application.  It will then redirect you to a bogus page. This is intentional until such time as I can find a way all browsers like to do this without you having to register your own app.  When that page loads, copy and paste everything after "response_token=" from the URL into the box below.</p>
					<p><a style="margin-left:21%" href="https://instagram.com/oauth/authorize/?client_id=' . Syte::INSTAGRAM_CLIENT_ID . '&redirect_uri=http://127.0.0.1:8000/&response_type=token" target="_blank">Get Client Token</a></p>
					');
			}
			$fs->append( 'text', 'instagram_access_token', __CLASS__ . '__instagram_access_token', _t( 'Access Token', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'instagram_url', __CLASS__ . '__instagram_url', _t( 'Instagram URL' ), 'syte_text' );
			
		/**** Github ****/
		$ui->append( 'checkbox', 'github_int', __CLASS__ . '__enable_github', _t( 'Enable GitHub Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_github', _t( 'GitHub Authentication', 'syte' ) );
			$fs->append( 'static', 'github_auth', '
				<p>GitHub doesn\'t actually need authentication in order to use the API to view public repos, but for presentation purposes we need your Github profile URL.</p>
				');
			$fs->append( 'text', 'github_url', __CLASS__ . '__github_url', _t( 'Github URL' ), 'syte_text' );
			
		/**** Last.fm ****/
		$ui->append( 'checkbox', 'lastfm_int', __CLASS__ . '__enable_lastfm', _t( 'Enable last.fm Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_lastfm', _t( 'last.fm Authentication', 'syte' ) );
			$fs->append( 'static', 'lastfm_auth', '
				<p>The Last.fm integration does not make any authenticated calls so setting it up only requires that you register an application with Last.fm and get an API key.</p>
				<p>To get an API key simply follow the <a href="http://www.last.fm/api" target="_blank">Getting started instructions</a>.  You can then view your API Key from <a href="http://www.last.fm/api/account" target="_blank">your api account page</a> and enter it below.</p>
				');
			$fs->append( 'text', 'lastfm_url', __CLASS__ . '__lastfm_url', _t( 'last.fm URL' ), 'syte_text' );	
				
		/**** Dribbble ****/
		$ui->append( 'checkbox', 'dribbble_int', __CLASS__ . '__enable_dribbble', _t( 'Enable Dribble Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_dribbble', _t( 'Dribbble Authentication', 'syte' ) );
			$fs->append( 'static', 'dribbble_auth', '
				<p>Coming soon</p>
				');
			$fs->append( 'text', 'dribbble_url', __CLASS__ . '__dribbble_url', _t( 'Dribbble URL' ), 'syte_text' );	
			
		$ui->append( 'submit', 'save', _t( 'Save' ) );
		$ui->set_option( 'success_message', _t( 'Options saved', 'syte' ) );
		$ui->on_success( array( $this, 'enable_integrations' ) );
		$ui->out();
	}
	
	/**
	 * Add the blocks for those integrations that have been enabled to the active theme.
	 * 
	 * @todo We need to tie this plugin to the theme so these blocks are only added to the Syte theme.
	 */
	public function enable_integrations( $ui )
	{
		// Save our form before we do anything else.
		$ui->save();
		
		// Get current active theme
		$active_theme = Themes::get_active_data( true );
		// Create a theme instance so we can query the configured blocks.
		$new_theme = Themes::create();
		// Get the currently configured blocks.
		$blocks = $new_theme->get_blocks( 'sidebar', 0, $active_theme );
		
		// I think we need a has() function for blocks to make this easier.
		// Parse the blocks and grab just the types into an array
		$blocks_types = array();
		foreach( $blocks as $block ) {
			$block_types[] = $block->type;
		}

		// Check if we have the requested block enabled or not. If not, enable it.
		// TODO: Do we want to remove the block if the config form has the field unchecked?
		foreach( $ui->controls as $component ) {
			if ( strpos( $component->name, '_int' ) ) {
				$comp_name = explode( '_', $component->name );
				$block_name = $comp_name[0];
				if ( $component->value === true && !in_array( 'syte_' . $block_name, $block_types ) ) {
					$block = new Block( array(
						'title' => ucfirst( $block_name ),
						'type' => 'syte_' . $block_name,
						'data' => serialize( array( '_show_title' => 1, 'url' => Options::get( __CLASS__ . '__' . $block_name . '_url' ) ) )
					) );
					
					$block->add_to_area( 'sidebar' );
					Session::notice( _t( 'Added ' . ucfirst( $block_name ) . ' block to sidebar area.' ) );
				}
			}
		}
		

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
		
		$rules[] = new RewriteRule(array(
				'name' => 'syte_lastfm',
				'parse_regex' => '%^lastfm/(?P<username>\w+)?/?$%i',
				'build_str' => 'lastfm/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_lastfm',
				'priority' => 7,
				'is_active' => 1,
		));

		return $rules;
	}
	
	public function action_handler_syte_twitter( $handler_vars )
	{
		require_once dirname( __FILE__ ) . '/lib/twitteroauth.php';

		$consumer_key = Options::get( __CLASS__ . '__twitter_consumer_key' );
		$consumer_secret = Options::get( __CLASS__ . '__twitter_consumer_secret' );
		$user_key = Options::get( __CLASS__ . '__twitter_user_key' );
		$user_key_secret = Options::get( __CLASS__ . '__twitter_user_secret' );
		
		$oauth = new TwitterOAuth( $consumer_key, $consumer_secret, $user_key, $user_key_secret );
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
	
	public function action_handler_syte_instagram( $handler_vars )
	{	
		$access_token = Options::get( __CLASS__ . '__instagram_access_token' );
		if ( $access_token != '' ) {
			$access_parts = explode( '.', $access_token );
			$user_id = $access_parts[0];
		
			$user = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/?access_token='.$access_token );
			$user = json_decode( $user );
			$user = json_encode( $user->data );
		
			// Gram media info
			if ( ! isset( $handler_vars['max_id'] ) ) {
				$media = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$access_token );
			} else {
				$media = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$access_token.'&max_id='.$handler_vars['max_id'] );
			}
			$media_uenc = json_decode( $media );
			$media = json_encode( $media_uenc->data );
			
			// Pagination
			$pagination = json_encode( $media_uenc->pagination );
			
			$r = '{"user":'.$user.', "media":'.$media.', "pagination":'.$pagination.'}';
			
		} else {
			$r = '';
		}
		echo $r;
		exit();
	}
	
	public function action_handler_syte_lastfm( $handler_vars )
	{
		$r = '{"user_info":';
		$r .= RemoteRequest::get_contents( 'http://ws.audioscrobbler.com/2.0/?method=user.getinfo&user=' . $handler_vars['username'] . '&api_key=' . Syte::LASTFM_API_KEY . '&format=json');
		$r .= ', "recenttracks":';
		$r .= RemoteRequest::get_contents( 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=' . $handler_vars['username'] . '&api_key=' . Syte::LASTFM_API_KEY . '&format=json' );
		$r .= '}';
		echo $r;
		exit();
	}
	/**
	 * Add the blocks to the list of selectable blocks
	 */
	public function filter_block_list( $block_list )
	{
		$block_list[ 'syte_twitter' ] = _t( 'Syte - Twitter Integration', 'syte' );
		$block_list[ 'syte_github' ] = _t( 'Syte - Github Integration', 'syte' );
		$block_list[ 'syte_dribbble' ] = _t( 'Syte - dribbble Integration', 'syte' );
		$block_list[ 'syte_instagram' ] = _t( 'Syte - Instagram Integration', 'syte' );
		$block_list[ 'syte_lastfm' ] = _t( 'Syte - Last.fm Integration', 'syte' );
		return $block_list;
	}
	
	/**
	 * Configure the twitter block
	 * 
	 * @todo: Implement Twitter authentication as used by the Twitter plugin. For the moment everything is hard coded.
	 */
	public function action_block_form_syte_twitter( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'Twitter URL', 'syte' ) );
	}
	
	/**
	 * Populate the twitter block with some content
	 **/
	public function action_block_content_syte_twitter( $block, $theme )
	{
	
	}
	
	/**
	 * Configure the github block
	 * 
	 * @todo: See if we can obtain this information like we can with Twitter
	 */
	public function action_block_form_syte_github( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'GitHub URL', 'syte' ) );
	}
	
	/**
	 * Populate the github block with some content
	 **/
	public function action_block_content_syte_github( $block, $theme )
	{

	}
	
	/**
	 * Configure the dribbble block
	 * 
	 */
	public function action_block_form_syte_dribbble( $form, $block )
	{

	}
	
	/**
	 * Populate the dribbble block with some content
	 **/
	public function action_block_content_syte_dribbble( $block, $theme )
	{
		
	}
	
	/**
	 * Configure the instagram block
	 * 
	 * @todo: See if we can obtain this information like we can with Twitter
	 */
	public function action_block_form_syte_instagram( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'Instagram URL', 'syte' ) );
	}
	
	/**
	 * Populate the instagram block with some content
	 **/
	public function action_block_content_syte_instagram( $block, $theme )
	{
		
	}
	
	/**
	 * Configure the last.fm block
	 * 
	 */
	public function action_block_form_syte_lastfm( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'Last.fm URL', 'syte' ) );
	}
	
	/**
	 * Populate the instagram block with some content
	 **/
	/*public function action_block_content_syte_lastfm( $block, $theme )
	{
		
	}
	
	/**
	 * Add a configuration option to set keywords
	 */
	public function filter_admin_option_items( $option_items )
	{
		$option_items[_t( 'Name & Tagline' )]['keywords'] = array(
				'label' => _t( 'Site Keywords' ),
				'type' => 'text',
				'helptext' => _t( 'Comma separated list of default site keywords.' ),
				);
		return $option_items;
	}
}
?>