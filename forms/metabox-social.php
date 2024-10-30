<?php

use Dev4Press\Plugin\coreSocial\Sharing\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_tabs = apply_filters( 'coresocial_admin_metabox_social_tabs', array(
	'stats'   => array(
		'label' => __( 'Statistics', 'coresocial' ),
		'icon'  => 'chart-bar',
	),
	'online'  => array(
		'label' => __( 'Online Counts', 'coresocial' ),
		'icon'  => 'chart-line',
	),
	'inline'  => array(
		'label' => __( 'Inline', 'coresocial' ),
		'icon'  => 'menu-alt',
	),
	'twitter' => array(
		'label' => __( 'Twitter', 'coresocial' ),
		'icon'  => 'twitter',
	),
) );

if ( ! coresocial_settings()->get( 'active', 'inline' ) ) {
	unset( $_tabs['inline'] );
}

global $post_ID;

$meta_data = Post::instance( $post_ID )->get_meta();

?>
<div class="d4plib-v50-meta-box-wrapper">
    <input type="hidden" name="coresocial_social_settings" value="edit"/>
    <input type="hidden" name="coresocial_social_nonce" value="<?php echo wp_create_nonce( 'coresocial-post-' . $post_ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>)>"/>

    <ul class="wp-tab-bar">
		<?php

		$active = true;
		foreach ( $_tabs as $_tab => $obj ) {
			$label = $obj['label'];
			$icon  = $obj['icon'];

			echo '<li class="' . ( $active ? 'wp-tab-active' : '' ) . '"><button type="button" data-tab="coresocial-meta-' . esc_attr( $_tab ) . '">';
			echo '<span aria-hidden="true" aria-labelledby="coresocial-social-metatab-' . esc_attr( $_tab ) . '" class="dashicons dashicons-' . esc_attr( $icon ) . '" title="' . esc_attr( $label ) . '"></span>';
			echo '<span id="coresocial-social-metatab-' . esc_attr( $_tab ) . '" class="d4plib-metatab-label">' . esc_html( $label ) . '</span>';
			echo '</button></li>';

			$active = false;
		}

		?>
    </ul>
	<?php

	$active = true;
	foreach ( $_tabs as $_tab => $label ) {
		echo '<div id="coresocial-meta-' . esc_attr( $_tab ) . '" class="wp-tab-panel ' . ( $active ? 'tabs-panel-active' : 'tabs-panel-inactive' ) . '">';

		do_action( 'coresocial_admin_metabox_social_meta_content_' . $_tab, $post_ID, $meta_data );

		echo '</div>';

		$active = false;
	}

	?>
</div>
