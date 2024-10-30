<?php

use Dev4Press\Plugin\coreSocial\Basic\InstallDB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-install-block">
    <h4>
		<?php esc_html_e( 'Additional database tables', 'coresocial' ); ?>
    </h4>
    <div>
		<?php

		$db = InstallDB::instance();

		$list_db = $db->install();

		if ( ! empty( $list_db ) ) {
			echo '<h5>' . esc_html__( 'Database Upgrade Notices', 'coresocial' ) . '</h5>';
			echo join( '<br/>', $list_db ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<h5>' . esc_html__( 'Database Tables Check', 'coresocial' ) . '</h5>';
		$check = $db->check();

		$msg = array();
		foreach ( $check as $table => $data ) {
			if ( $data['status'] == 'error' ) {
				$_proceed  = false;
				$_error_db = true;
				$msg[]     = '<span class="gdpc-error">[' . esc_html__( 'ERROR', 'coresocial' ) . '] - <strong>' . esc_html( $table ) . '</strong>: ' . $data['msg'] . '</span>';
			} else {
				$msg[] = '<span class="gdpc-ok">[' . esc_html__( 'OK', 'coresocial' ) . '] - <strong>' . esc_html( $table ) . '</strong></span>';
			}
		}

		echo join( '<br/>', $msg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		?>
    </div>
</div>
