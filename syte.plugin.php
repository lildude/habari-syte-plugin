<?php 

class Syte extends Plugin
{
	const INSTAGRAM_CLIENT_ID = '4390b77a28d64147bdaa39130d22c3d7';
	const LASTFM_API_KEY = 'e258bec284029b2586ae001fcd42673e';
	
	public function action_init()
	{
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
	 */
	public function action_plugin_ui_configure()
	{	
		$ui = new FormUI( strtolower( __CLASS__ ) );
		/**** Twitter ****/
		$ui->append( 'checkbox', 'twitter_int', __CLASS__ . '__enable_twitter', _t( 'Enable Twitter Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_twitter', _t( 'Twitter Authentication', 'syte' ) );
			if ( Options::get( __CLASS__ . '__twitter_user_secret' == '' ) ) {
				$fs->append( 'static', 'twitter_help', '<p>To get started, create a new application on Twitter 
					for your website by going to <a href="https://dev.twitter.com/apps/new" target="_blank">https://dev.twitter.com/apps/new</a>. 
					Once you are done creating your application you will be taken to your application page on Twitter, there you already have two 
					pieces of the puzzle, the `Consumer key` and the `Consumer secret` make sure you save those.</p>

					<p>Next you will need your access tokens. On the bottom of that page there is a link called <strong>>Create my access token</strong> click on that. 
					Once you are done you will be given the other two pieces of the puzzle, the `Access token` and the `Access token secret` make sure you save those as well.</p>

					<p>Once you have those four items from Twitter, enter them below.</p>' );
			}
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
					<p>Clicking the button below will open a new window and ask you to login to Instagram and authorize this application.  It will then redirect you to a bogus page. 
					This is intentional until such time as I can find a way all browsers like to do this without you having to register your own app.  
					When that page loads, copy and paste everything after "response_token=" from the URL into the box below.</p>
					<p><a style="margin-left:21%" href="https://instagram.com/oauth/authorize/?client_id=' . Syte::INSTAGRAM_CLIENT_ID . '&redirect_uri=http://127.0.0.1:8000/&response_type=token" target="_blank">Get Client Token</a></p>
					');
			}
			$fs->append( 'text', 'instagram_access_token', __CLASS__ . '__instagram_access_token', _t( 'Access Token', 'syte' ), 'syte_text' );
			// Instagram doesn't actually offer a profile page yet, but one day it might.
			$fs->append( 'hidden', 'instagram_url', __CLASS__ . '__instagram_url', _t( 'Instagram URL' ), 'syte_text' );
			
		/**** Github ****/
		$ui->append( 'checkbox', 'github_int', __CLASS__ . '__enable_github', _t( 'Enable GitHub Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_github', _t( 'GitHub Authentication', 'syte' ) );
			$fs->append( 'static', 'github_auth', '
				<p>GitHub doesn\'t need authentication in order to use the API to view public repos, so all we need your Github profile URL.</p>
				');
			$fs->append( 'text', 'github_url', __CLASS__ . '__github_url', _t( 'Github URL' ), 'syte_text' );
			
		/**** Last.fm ****/
		$ui->append( 'checkbox', 'lastfm_int', __CLASS__ . '__enable_lastfm', _t( 'Enable last.fm Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_lastfm', _t( 'last.fm Authentication', 'syte' ) );
			$fs->append( 'static', 'lastfm_auth', '
				<p>No authentication is needed for Last.fm, just enter your Last.fm profile URL below.</p>
				');
			$fs->append( 'text', 'lastfm_url', __CLASS__ . '__lastfm_url', _t( 'last.fm URL' ), 'syte_text' );	
				
		/**** Dribbble ****/
		$ui->append( 'checkbox', 'dribbble_int', __CLASS__ . '__enable_dribbble', _t( 'Enable Dribble Integration' ) );
		$fs = $ui->append( 'fieldset', 'fs_dribbble', _t( 'Dribbble Authentication', 'syte' ) );
			$fs->append( 'static', 'dribbble_auth', '
				<p>No authentication is needed for Dribbble, just enter your Dribble URL below.</p>
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
	 */
	public function enable_integrations( $ui )
	{
		// Save our form before we do anything else.
		$ui->save();
		
		// Get the blocks: I think we need a has() function for blocks to make this easier.
		// Get current active theme
		$active_theme = Themes::get_active_data( true );
		// Create a theme instance so we can query the configured blocks.
		$new_theme = Themes::create();
		// Get the currently configured blocks.
		$blocks = $new_theme->get_blocks( 'sidebar', 0, $active_theme );

		// Parse the blocks and grab just the types into an array
		$blocks_types = array();
		foreach( $blocks as $block ) {
			$block_types[] = $block->type;
		}

		// Check if we have the requested block enabled or not. If not, enable it.
		foreach( $ui->controls as $component ) {
			if ( strpos( $component->name, '_int' ) ) {
				$comp_name = explode( '_', $component->name );
				$block_name = $comp_name[0];
				if ( $component->value === true ) {
					// If we don't have a block already, add it.
					if ( !in_array( 'syte_' . $block_name, $block_types ) ) {
						$block = new Block( array(
							'title' => ucfirst( $block_name ),
							'type' => 'syte_' . $block_name,
							'data' => serialize( array( '_show_title' => 1, 'url' => Options::get( __CLASS__ . '__' . $block_name . '_url' ) ) )
						) );

						$block->add_to_area( 'sidebar' );
						Session::notice( _t( 'Added ' . ucfirst( $block_name ) . ' block to sidebar area.' ) );
					} 
					// TODO: If we do have a block, just update its url as this is all that is likely to have changed.
					else {
						
					}
				} 
				else {
					// TODO: remove block if deactivated
				}
			}
		}
	}
	
	/**
	 * Add the rewrite rules required for the "ajax" functionality
	 * 
	 * These need to go into the plugin as the theme can't provide them. :-(
	 */
	public function filter_rewrite_rules( $rules )
	{
		// twitter/username
		$rules[] = new RewriteRule(array(
				'name' => 'syte_twitter',
				'parse_regex' => '%^twitter/(?P<username>\w+)/?$%i',
				'build_str' => 'twitter/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_twitter',
				'priority' => 7,
				'is_active' => 1,
		));
		// github/username
		$rules[] = new RewriteRule(array(
				'name' => 'syte_github',
				'parse_regex' => '%^github/(?P<username>\w+)/?$%i',
				'build_str' => 'github/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_github',
				'priority' => 7,
				'is_active' => 1,
		));
		// instagram
		$rules[] = new RewriteRule(array(
				'name' => 'syte_instagram',
				'parse_regex' => '%^instagram/?$%i',
				'build_str' => 'instagram',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_instagram',
				'priority' => 5,
				'is_active' => 1,
		));
		// instagram/number
		$rules[] = new RewriteRule(array(
				'name' => 'syte_instagram',
				'parse_regex' => '%^instagram/(?P<max_id>\w+)?/?$%i',
				'build_str' => 'instagram/{$max_id}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_instagram',
				'priority' => 7,
				'is_active' => 1,
		));
		// lastfm/username
		$rules[] = new RewriteRule(array(
				'name' => 'syte_lastfm',
				'parse_regex' => '%^lastfm/(?P<username>\w+)?/?$%i',
				'build_str' => 'lastfm/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_lastfm',
				'priority' => 7,
				'is_active' => 1,
		));
		// dribbble/username
		$rules[] = new RewriteRule(array(
				'name' => 'syte_dribbble',
				'parse_regex' => '%^dribbble/(?P<username>\w+)?/?$%i',
				'build_str' => 'dribbble/{$username}',
				'handler' => 'UserThemeHandler',
				'action' => 'syte_dribbble',
				'priority' => 7,
				'is_active' => 1,
		));

		return $rules;
	}
	
	/**
	 * Handle the twitter/username rewrite rule
	 * 
	 */
	public function action_handler_syte_twitter( $handler_vars )
	{		
		if ( Cache::has( 'syte_twitter' ) ) {
			$resp = Cache::get( 'syte_twitter' );
		} 
		else {
			require_once dirname( __FILE__ ) . '/lib/twitteroauth.php';

			$consumer_key = Options::get( __CLASS__ . '__twitter_consumer_key' );
			$consumer_secret = Options::get( __CLASS__ . '__twitter_consumer_secret' );
			$user_key = Options::get( __CLASS__ . '__twitter_user_key' );
			$user_key_secret = Options::get( __CLASS__ . '__twitter_user_secret' );

			$oauth = new TwitterOAuth( $consumer_key, $consumer_secret, $user_key, $user_key_secret );
			$oauth->decode_json = true;
			$resp = $oauth->get( 'statuses/user_timeline', array( 'screen_name' => $handler_vars['username'] ) );
		
			// Cache the response for 60 seconds to keep from hammering API endpoints
			Cache::set( 'syte_twitter', $resp, 60 );
		}
		
		list( $block, $new_theme ) = $this->get_block( 'syte_twitter' );
		$block->tweets = $resp;
		
		echo $block->fetch( $new_theme );
	}
	
	/**
	 * Handle the github/username rewrite rule
	 * 
	 */
	public function action_handler_syte_github( $handler_vars )
	{
		if ( Cache::has( 'syte_github' ) ) {
			extract( Cache::get( 'syte_github' ) );
		}
		else {
			// Grab the user info
			$user = RemoteRequest::get_contents( 'https://api.github.com/users/'.$handler_vars['username'] );
			// Grab the repos info
			$repos = RemoteRequest::get_contents( 'https://api.github.com/users/'.$handler_vars['username'].'/repos' );
		
			// Cache the response for 60 seconds to keep from hammering API endpoints
			Cache::set( 'syte_github', array( 'user' => $user, 'repos' => $repos ), 60 );
		}
		list( $block, $new_theme ) = $this->get_block( 'syte_github' );
		$block->user = json_decode( $user );
		$block->repos = json_decode( $repos );

		echo $block->fetch( $new_theme );
	}
	
	/**
	 * Handle the instagram rewrite rule
	 * 
	 */
	public function action_handler_syte_instagram( $handler_vars )
	{	
		if ( Cache::has( 'syte_instagram' ) ) {
			extract( Cache::get( 'syte_instagram' ) );
		}
		else {
			$access_token = Options::get( __CLASS__ . '__instagram_access_token' );
			$access_parts = explode( '.', $access_token );
			$user_id = $access_parts[0];
			// Grab user info
			$user = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/?access_token='.$access_token );
			$user = json_decode( $user );
			// Grab media info
			$media = RemoteRequest::get_contents( 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$access_token );
			$media = json_decode( $media );

			// Cache the response for 60 seconds to keep from hammering API endpoints
			Cache::set( 'syte_instagram', array( 'user' => $user, 'media' => $media ), 60 );
		}

		list( $block, $new_theme ) = $this->get_block( 'syte_instagram' );
		$block->user = $user->data;
		$block->media = $media->data;

		echo $block->fetch( $new_theme );
	}
	
	/**
	 * Handle the lastfm/username rewrite rule
	 * 
	 */
	public function action_handler_syte_lastfm( $handler_vars )
	{
		if ( Cache::has_group( 'syte_lastfm' ) ) {
			extract( Cache::get_group( 'syte_lastfm' ) );
		}
		else {
			$user = RemoteRequest::get_contents( 'http://ws.audioscrobbler.com/2.0/?method=user.getinfo&user=' . $handler_vars['username'] . '&api_key=' . Syte::LASTFM_API_KEY . '&format=json');
			$tracks = RemoteRequest::get_contents( 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=' . $handler_vars['username'] . '&api_key=' . Syte::LASTFM_API_KEY . '&format=json' );

			// Remove the # the results place in front of the '#text' object
			$user = json_decode( str_replace( '#text', 'text', $user ) );
			$tracks = json_decode( str_replace( array( '#text', '@attr' ), array( 'text', 'attr' ), $tracks ) );

			// Cache the response for 60 seconds to keep from hammering API endpoints
			Cache::set( 'syte_lastfm', array( 'user' => $user, 'tracks' => $tracks ), 60 );
		}
		
		list( $block, $new_theme ) = $this->get_block( 'syte_lastfm' );
		$block->user = $user->user;
		$block->recent_tracks = $tracks->recenttracks->track;
		
		echo $block->fetch( $new_theme );
	}
	
	/**
	 * Handle the dribbble/username rewrite rule
	 * 
	 */
	public function action_handler_syte_dribbble( $handler_vars )
	{
		if ( Cache::has( 'syte_dribbble' ) ) {
			extract( Cache::get( 'syte_dribbble' ) );
		}
		else {
			$user = RemoteRequest::get_contents( 'http://api.dribbble.com/players/' . $handler_vars['username'] );
			$user = json_decode( $user );
			$shots = RemoteRequest::get_contents( 'http://api.dribbble.com/players/' . $handler_vars['username'] . '/shots' );
			$shots = json_decode( $shots );
			
			// Cache the response for 60 seconds to keep from hammering API endpoints
			Cache::set( 'syte_dribbble', array( 'user' => $user, 'shots' => $shots ), 60 );
		}
		
		list( $block, $new_theme ) = $this->get_block( 'syte_dribbble' );
		$block->user = $user;
		$block->shots = $shots->shots;

		echo $block->fetch( $new_theme );
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
	 */
	public function action_block_form_syte_twitter( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'Twitter URL', 'syte' ) );
	}
	
	/**
	 * Configure the github block
	 * 
	 */
	public function action_block_form_syte_github( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'GitHub URL', 'syte' ) );
	}
	
	/**
	 * Configure the dribbble block
	 * 
	 */
	public function action_block_form_syte_dribbble( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'Dribbble URL', 'syte' ) );
	}
	
	/**
	 * Configure the instagram block
	 * 
	 */
	public function action_block_form_syte_instagram( $form, $block )
	{
		$form->append( 'text', 'url', $block, _t( 'Instagram URL', 'syte' ) );
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
	 * Gets a specific block and a corresponding theme object
	 * 
	 * This should probably be a function built into Habari. Would need enhancing first.
	 * 
	 * @param String $name The name of the block
	 * @return Array Array containing the block object and a new theme object
	 */
	public function get_block( $name )
	{
		// Get current active theme
		$active_theme = Themes::get_active_data( true );
		// Create a theme instance so we can query the configured blocks.
		$new_theme = Themes::create();
		// Get the currently configured blocks.
		$blocks = $new_theme->get_blocks( 'sidebar', 0, $active_theme );
		
		foreach( $blocks as $block ) {
			if ( $block->type == $name ) {
				return array( $block, $new_theme );
			} 
			else {
				continue;
			}
		}
	}
	
	/**
	 * Replace URLs, hash tags and twitter names with links.
	 * 
	 * @param String $str The string to linkify
	 */
	public static function linkify( $str )
	{
		$str = preg_replace( '/(https?:\/\/\S+)/i', "<a href=\"$1\">$1</a>", $str );
		$str = preg_replace( '/(^|) @(\w+)/i', " <a href=\"http://twitter.com/$2\">@$2</a>", $str );
		$str = preg_replace( '/(^|) #(\w+)/i', " <a href=\"https://twitter.com/#!/search/%23$2\">#$2</a>", $str );
		return $str;
	}
}
?>