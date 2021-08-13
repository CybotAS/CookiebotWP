<?php
/** @var array $notices */
?>
<div class="update-nag cookiebot-admin-notice">
    <div class="cookiebot-notice-logo"></div>
    <p class="cookiebot-notice-title"><?php echo $notices['title']; ?></p>
    <p class="cookiebot-notice-body"><?php echo $notices['msg']; ?></p>
    <ul class="cookiebot-notice-body wd-blue"><?php echo $notices['link']; ?></ul>
    <a href="<?php echo $notices['later_link']; ?>" class="dashicons dashicons-dismiss"></a>
</div>