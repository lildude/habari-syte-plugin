<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); }
if (! isset( $content->user ) ) { echo "<!--- Ignore Me: This is a fudge cos I haven't found a better way of loading block content in a theme via AJAX and declare it in the theme -->"; return; } ?>
<div class="profile github modal fade" id="github-profile">
  <div class="profile-info">
    <button class="close" data-dismiss="modal">×</button>
    <a href="http://github.com/<?php echo $content->user->login; ?>" class="profile-avatar">
      <img src="http://gravatar.com/avatar/<?php echo $content->user->gravatar_id; ?>" alt="<?php echo $content->user->name; ?>" />
    </a>
    <div class="profile-name">
      <h2><a href="http://github.com/<?php echo $content->user->login; ?>"><?php echo $content->user->name; ?></a></h2>
      <h3><a href="http://github.com/<?php echo $content->user->login; ?>"><?php echo $content->user->login; ?></a></h3>
    </div>
   <p class="profile-location-url">
      <?php if ( $content->user->location ) : ?>
      <span><?php echo $content->user->location; ?></span>
      <span class="divider">·</span>
      <?php endif; ?>
      <?php if ( $content->user->name ) : ?>
      <span><a href="<?php echo $content->user->blog; ?>"><?php echo $content->user->blog; ?></a></span>
      <?php endif; ?>
    </p>
  </div>
  <ul class="profile-stats">
    <li><a href="http://github.com/<?php echo $content->user->login; ?>"><strong><?php echo $content->user->public_repos; ?></strong> repos</a></li>
    <li><a href="http://github.com/<?php echo $content->user->login; ?>/following"><strong><?php echo $content->user->following; ?></strong> following</a></li>
    <li><a href="http://github.com/<?php echo $content->user->login; ?>/followers"><strong><?php echo $content->user->followers; ?></strong> followers</a></li>
  </ul>
  <div class="profile-info-footer">
    <a href="http://github.com/<?php echo $content->user->login; ?>" class="btn">Follow on Github</a>
  </div>

  <ul class="profile-repos">
    <?php foreach ( $content->repos as $repo ) : ?>
    <li>
    <a href="<?php echo $repo->html_url; ?>" class="profile-repo-name"><?php echo $repo->name; ?></a>
    <p class="profile-repo-text">
      <?php echo $repo->description; ?>
    </p>
    <ul class="profile-repo-stats">
      <li><?php echo $repo->language; ?></li>
      <li><a href="<?php echo $repo->html_url; ?>/watchers" class="profile-watchers"><?php echo $repo->watchers; ?></a></li>
      <li><a href="<?php echo $repo->html_url; ?>/network" class="profile-forks"><?php echo $repo->forks; ?></a></li>
    </ul>
    </li>
    <?php endforeach; ?>
  </ul>
</div>