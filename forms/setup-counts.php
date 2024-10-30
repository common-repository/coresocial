<?php

use Dev4Press\Plugin\coreSocial\Basic\DB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-install-block">
    <h4>
		<?php esc_html_e( 'Update counts table', 'coresocial' ); ?>
    </h4>
    <div>
		<?php

		$insert = DB::instance()->insert_network_counts();
		$update = DB::instance()->update_network_counts();

		echo '<span class="gdpc-ok">[' . esc_html__( 'Added', 'coresocial' ) . '] - <strong>' . esc_attr( $insert ) . '</strong></span><br/>';
		echo '<span class="gdpc-ok">[' . esc_html__( 'Updated', 'coresocial' ) . '] - <strong>' . esc_attr( $update ) . '</strong></span>';

		?>
    </div>
</div>
