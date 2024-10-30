<?php

use Dev4Press\v50\Core\UI\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p>
    <label for="coresocial_settings_twitter_hashtags"><?php esc_html_e( 'Hashtags', 'coresocial' ); ?></label>
	<?php Elements::instance()->select( array(
		'merge' => __( 'Use global hashtags and, add hashtags listed here', 'coresocial' ),
		'local' => __( 'Use only hashtags listed here', 'coresocial' ),
	), array(
		'id'       => 'coresocial_settings_twitter_hashtags',
		'name'     => 'coresocial_settings[twitter_hashtags]',
		'class'    => 'widefat',
		'selected' => $meta_data['twitter_hashtags'],
	) ); ?>
</p>
<p>
    <label for="coresocial_settings_twitter_hashtags_list"><?php esc_html_e( 'Hashtags list', 'coresocial' ); ?></label>
    <input name="coresocial_settings[twitter_hashtags_list]" id="coresocial_settings_twitter_hashtags_list" value="<?php echo esc_attr( $meta_data['twitter_hashtags_list'] ) ?>" class="widefat" type="text"/>
    <em><?php esc_html_e( 'Hashtags should be comma separated.', 'coresocial' ); ?></em>
</p>
<p>
    <label for="coresocial_settings_twitter_account"><?php esc_html_e( 'Account', 'coresocial' ); ?></label>
    <input name="coresocial_settings[twitter_account]" id="coresocial_settings_twitter_account" value="<?php echo esc_attr( $meta_data['twitter_account'] ) ?>" class="widefat" type="text"/>
    <em><?php esc_html_e( 'Leave empty to use Twitter account set globally.', 'coresocial' ); ?></em>
</p>
