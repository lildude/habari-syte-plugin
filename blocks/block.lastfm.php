<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>

<div class="profile lastfm modal fade" id="lastfm-profile">
  <div class="profile-info">
    <button class="close" data-dismiss="modal">×</button>
    <?php if ( $content->user_info->image[1]->text ) : ?>
    <a href="<?php echo $content->user_info->url; ?>" class="profile-avatar">
      <img src="<?php echo $content->user_info->image[1]->text; ?>" alt="<?php echo $content->user_info->name; ?>" />
    </a>
    <?php endif; ?>
    <div class="profile-name">
	<?php if ( $content->user_info->realname ) : ?>
      <h2><a href="<?php echo $content->user_info->url; ?>"><?php echo $content->user_info->realname; ?></a></h2>
	<?php endif; ?>
      <h3><a href="<?php echo $content->user_info->url; ?>"><?php echo $content->user_info->name; ?></a></h3>
    </div>
    <p class="profile-location-url">
      <?php if ( $content->user_info->country ) : ?><span>Country - <?php echo $content->user_info->country; ?></span><?php endif; ?>
    </p>
  </div>
  <ul class="profile-stats">
    <li><a href="<?php echo $content->user_info->url; ?>"><strong><?php echo $content->user_info->playcount; ?></strong> plays</a></li>
    <li><a href="<?php echo $content->user_info->url; ?>/playlists"><strong><?php echo $content->user_info->playlists; ?></strong> playlists</a></li>
    <li><a href="<?php echo $content->user_info->url; ?>"><strong><?php echo date( 'm/d/Y', $content->user_info->registered->unixtime ); ?></strong> registered</a></li>
  </ul>
  <div class="profile-info-footer">
    <a href="<?php echo $content->user_info->url; ?>" class="btn">View Last.fm Profile</a>
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