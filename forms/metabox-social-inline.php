<?php

use Dev4Press\v50\Core\UI\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_locations = array(
	'inherit' => __( 'Inherit global location', 'coresocial' ),
	'top'     => __( 'Top, before the post content', 'coresocial' ),
	'bottom'  => __( 'Bottom, after the post content', 'coresocial' ),
	'both'    => __( 'Both', 'coresocial' ),
	'hide'    => __( 'Do not add', 'coresocial' ),
);

?>

<p>
    <label for="coresocial_settings_inline_location"><?php esc_html_e( 'Location', 'coresocial' ); ?></label>
	<?php

	Elements::instance()->select( $_locations, array(
		'id'       => 'coresocial_settings_inline_location',
		'name'     => 'coresocial_settings[inline_location]',
		'class'    => 'widefat',
		'selected' => $meta_data['inline_location'],
	) );

	?>
</p>
