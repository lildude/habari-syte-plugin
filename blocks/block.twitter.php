<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); }
if (! isset( $content->tweets ) ) { echo "<!--- Ignore Me: This is a fudge cos I haven't found a better way of loading block content in a theme via AJAX and declare it in the theme -->"; return; } ?>

<div class="profile twitter modal fade" id="twitter-profile">

  <div class="profile-info">
    <button class="close" data-dismiss="modal">×</button>
    <a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>" class="profile-avatar">
      <img src="<?php echo $content->tweets[0]->user->profile_image_url; ?>" alt="<?php echo $content->tweets[0]->user->name; ?>" />
    </a>
    <div class="profile-name">
      <h2><a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>"><?php echo $content->tweets[0]->user->name; ?></a></h2>
      <h3><a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>">@<?php echo $content->tweets[0]->user->screen_name; ?></a></h3>
    </div>
    <p class="profile-description"><?php echo Syte::linkify( $content->tweets[0]->user->description ); ?></p>
    <p class="profile-location-url">
      <?php if ( $content->tweets[0]->user->location ) : ?>
      <span><?php echo $content->tweets[0]->user->location; ?></span>
      <span class="divider">·</span>
      <?php endif; ?>
      <?php if ( $content->tweets[0]->user->url ) : ?>
      <span><a href="<?php echo $content->tweets[0]->user->url; ?>"><?php echo $content->tweets[0]->user->url; ?></a></span>
      <?php endif; ?>
    </p>
  </div>
  <ul class="profile-stats">
    <li><a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>"><strong><?php echo $content->tweets[0]->user->statuses_count; ?></strong> tweets</a></li>
    <li><a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>/following"><strong><?php echo $content->tweets[0]->user->friends_count; ?></strong> following</a></li> 
    <li><a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>/followers"><strong><?php echo $content->tweets[0]->user->followers_count; ?></strong> followers</a></li>
  </ul>
  <div class="profile-info-footer">
    <a href="http://twitter.com/#!/<?php echo $content->tweets[0]->user->screen_name; ?>" class="btn">Follow on Twitter</a>
  </div>

  <ul class="profile-tweets">
    <?php foreach ( $content->tweets as $tweet ) : ?>
      <li>
        <a href="http://twitter.com/#!/<?php echo $tweet->user->screen_name; ?>" class="tweet-title">
          <img src="<?php echo $tweet->user->profile_image_url; ?>" alt="<?php echo $content->tweets[0]->user->name; ?>" />
          <strong><?php echo $tweet->user->name; ?></strong>
          <span>@<?php echo $tweet->user->screen_name; ?></span>
        </a>
        <p class="tweet-text">
        <?php echo Syte::linkify( $tweet->text ); ?>
        </p>
        <p class="tweet-date">
          <?php $date = HabariDateTime::date_create( $tweet->created_at );
			echo $date->friendly(1); ?>
        </p>
      </li>
    <?php endforeach; ?>
  </ul>
</div>