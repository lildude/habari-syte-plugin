<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } if (! isset($content->user) ) { echo "<!--- Foo -->"; return; } ?>
<div class="profile dribbble modal fade-large" id="dribbble-profile">
  <div class="profile-info">
    <button class="close" data-dismiss="modal">×</button>
    <a href="http://dribbble.com/<?php echo $content->user->username; ?>" class="profile-avatar">
      <img src="<?php echo $content->user->avatar_url; ?>" alt="<?php echo $content->user->name; ?>" />
    </a>
    <div class="profile-name">
      <h2><a href="http://dribbble.com/<?php echo $content->user->username; ?>"><?php echo $content->user->name; ?></a></h2>
      <h3><a href="http://dribbble.com/<?php echo $content->user->username; ?>">@<?php echo $content->user->username; ?></a></h3>
    </div>
    <p class="profile-location-url">
      <?php if ( $content->user->location ) : ?>
      <span><?php echo $content->user->location; ?></span>
      <span class="divider">·</span>
      <?php endif; ?>
      <?php if ( $content->user->website_url ) : ?>
      <span><a href="<?php echo $content->user->website_url; ?>"><?php echo $content->user->website_url; ?></a></span>
      <?php endif; ?>
    </p>
    <a href="http://dribbble.com/<?php echo $content->user->username; ?>" class="btn">Follow on Dribbble</a>
  </div>
  <ul class="profile-stats">
    <li><a href="http://dribbble.com/<?php echo $content->user->username; ?>"><strong><?php echo $content->user->shots_count; ?></strong> shots</a></li>
    <li><a href="http://dribbble.com/<?php echo $content->user->username; ?>" class="shots-likes-received"><strong><?php echo $content->user->likes_received_count; ?></strong> likes received</a></li>
    <li><a href="http://dribbble.com/<?php echo $content->user->username; ?>" class="shots-likes-given"><strong><?php echo $content->user->likes_count; ?></strong> likes given</a></li>
    <li><a href="http://dribbble.com/<?php echo $content->user->username; ?>/following"><strong><?php echo $content->user->following_count; ?></strong> following</a></li> 
    <li><a href="http://dribbble.com/<?php echo $content->user->username; ?>/followers"><strong><?php echo $content->user->followers_count; ?></strong> followers</a></li>
  </ul>
  <ul class="profile-shots">
    <?php foreach ( $content->shots as $shot ) : ?>
    <li>
      <a href="<?php echo $shot->url; ?>" class="profile-shot"><img src="<?php echo $shot->image_url; ?>" alt="<?php echo $shot->title; ?>" /></a>
      <span class="profile-shot-title"><?php echo $shot->title; ?></span>
      <ul class="profile-shot-stats">
        <li><a href="" class="profile-watchers"><?php echo $shot->views_count; ?></a></li>
        <li><a href="" class="profile-comments"><?php echo $shot->comments_count; ?></a></li>
        <li><a href="" class="profile-likes"><?php echo $shot->likes_count; ?></a></li>
      </ul>
    </li>
    <?php endforeach; ?>
  </ul>
</div>