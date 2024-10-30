<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_count_key  = $_count_key ?? 'internal';
$_hide_empty = $_hide_empty ?? false;

?>
<div class="coresocial-overall-networks">
	<?php

	foreach ( $networks['networks'] as $network => $data ) {
		if ( $_hide_empty && $data[ $_count_key ] == 0 ) {
			continue;
		}

		$width = $networks[ 'max_' . $_count_key ] == 0 ? 0 : ( $data[ $_count_key ] / $networks[ 'max_' . $_count_key ] ) * 100;

		?>

        <div class="coresocial-network coresocial-network-<?php echo esc_attr( $network ); ?>">
            <div style="background: rgb(var(--coresocial-color-<?php echo esc_attr( $network ); ?>-primary));" class="__label">
                <i class="coresocial-icon coresocial-icon-<?php echo esc_attr( $network ); ?> coresocial-mod-fw"></i><span><?php echo esc_html( $data['label'] ); ?></span></div>
            <div class="__bar">
                <div class="__inner" style="width: <?php echo esc_attr( $width ); ?>%; background: rgb(var(--coresocial-color-<?php echo esc_attr( $network ); ?>-primary));"></div>
            </div>
            <div class="__count"><?php echo esc_html( $data[ $_count_key ] ); ?></div>
        </div>

		<?php
	}

	?>
</div>
