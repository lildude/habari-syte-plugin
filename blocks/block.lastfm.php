<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } 
if (! isset( $content->user ) ) { echo "<!--- Ignore Me: This is a fudge cos I haven't found a better way of loading block content in a theme via AJAX and declare it in the theme -->"; return; } ?>

<div class="profile lastfm modal fade" id="lastfm-profile">
  <div class="profile-info">
    <button class="close" data-dismiss="modal">×</button>
    <?php if ( $content->user->image[1]->text ) : ?>
    <a href="<?php echo $content->user->url; ?>" class="profile-avatar">
      <img src="<?php echo $content->user->image[1]->text; ?>" alt="<?php echo $content->user->name; ?>" />
    </a>
    <?php endif; ?>
    <div class="profile-name">
	<?php if ( $content->user->realname ) : ?>
      <h2><a href="<?php echo $content->user->url; ?>"><?php echo $content->user->realname; ?></a></h2>
	<?php endif; ?>
      <h3><a href="<?php echo $content->user->url; ?>"><?php echo $content->user->name; ?></a></h3>
    </div>
    <p class="profile-location-url">
      <?php if ( $content->user->country ) : ?><span>Country - <?php echo $content->user->country; ?></span><?php endif; ?>
    </p>
  </div>
  <ul class="profile-stats">
    <li><a href="<?php echo $content->user->url; ?>"><strong><?php echo $content->user->playcount; ?></strong> plays</a></li>
    <li><a href="<?php echo $content->user->url; ?>/playlists"><strong><?php echo $content->user->playlists; ?></strong> playlists</a></li>
    <li><a href="<?php echo $content->user->url; ?>"><strong><?php echo date( 'm/d/Y', $content->user->registered->unixtime ); ?></strong> registered</a></li>
  </ul>
  <div class="profile-info-footer">
    <a href="<?php echo $content->user->url; ?>" class="btn">View Last.fm Profile</a>
  </div>

  <ul class="profile-tracks">
    <?php foreach ( $content->recent_tracks as $track ) : ?>
      <li>
        <a href="<?php echo $track->url; ?>" class="track-image">
          <img src="<?php echo $track->image[0]->text; ?>" alt="<?php echo $track->artist->text; ?> - <?php echo $track->name; ?>" />
        </a>
        <p class="track-detail">
          <a href="<?php echo $track->url; ?>"><?php echo $track->artist->text; ?> - <?php echo $track->name; ?></a>
        </p>
        <p class="track-date">
          <?php if ( $track->date ) :
			$date = HabariDateTime::date_create( $track->date->uts );
			echo $date->friendly(1);
		  else:
			  _e( "Playing now" );
		  endif; ?>
        </p>
      </li>
    <?php endforeach; ?>
  </ul>
</div>