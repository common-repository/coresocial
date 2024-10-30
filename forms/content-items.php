<?php

use Dev4Press\Plugin\coreSocial\Table\Items;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <input type="hidden" name="page" value="coresocial-items"/>
    <input type="hidden" name="coresocial_handler" value="getback"/>

	<?php

	$_grid = new Items();
	$_grid->prepare_items();

	$_grid->display();

	?>
</div>
