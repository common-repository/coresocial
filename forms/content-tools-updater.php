<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <div class="d4p-group d4p-group-information d4p-group-updater">
        <h3><?php esc_html_e( 'Update status', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
			<?php

			include CORESOCIAL_PATH . 'forms/setup-database.php';
			include CORESOCIAL_PATH . 'forms/setup-counts.php';

			?>
        </div>
    </div>
</div>
