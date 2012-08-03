<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
<div class="profile instagram modal fade-large" id="instagram-profile">
  <div class="profile-info">
    <button class="close" data-dismiss="modal">×</button>
    <span class="profile-avatar">
      <img src="<?php echo $content->user->profile_picture; ?>" alt="<?php echo $content->user->full_name; ?>" />
    </span>
    <div class="profile-name">
      <h2><?php echo $content->user->full_name; ?></h2>
    </div>
    <?php if ( $content->user->bio ) : ?><p class="profile-description"><?php echo $content->user->bio; ?></p><?php endif; ?>
    <p class="profile-location-url">
      <?php if ( $content->user->website ) : ?>
      <span><a href="<?php echo $content->user->website; ?>"><?php echo $content->user->website; ?></a></span>
      <?php endif; ?>
    </p>
  </div>
  <ul class="profile-stats">
    <li><span><strong><?php echo $content->user->counts->media; ?></strong> pictures</span></li>
    <li><span><strong><?php echo $content->user->counts->follows; ?></strong> following</span></li>
    <li><span><strong><?php echo $content->user->counts->followed_by; ?></strong> followers</span></li>
  </ul>

  <ul class="profile-shots">
    <?php foreach( $content->media as $media ) : ?>
    <li>
      <a href="<?php echo $media->link; ?>" class="profile-shot"><img src="<?php echo $media->images->low_resolution->url; ?>" alt="Instagram Picture" /></a>
       <span class="profile-shot-title">
      <?php if ( $media->caption ) :
        echo $media->caption->text;
      else: ?>
      Untitled
      <?php endif; ?>
      </span>
      <ul class="profile-shot-stats">
        <li><span class="profile-likes"><?php echo $media->likes->count; ?></span></li>
        <li class="profile-shot-date"><?php $date = HabariDateTime::date_create( $media->created_time );
			echo $date->friendly(1); ?></li>
      </ul>
    </li>
    <?php endforeach; ?>
  </ul>
	<?php /*
  {{#if pagination}}
  {{#with pagination}}
  <button class="load-more-button" id="load-more-pics" data-control-next="{{ next_max_id }}">Load more...</button>
  {{/with}}
  {{/if}}*/?>
</div>