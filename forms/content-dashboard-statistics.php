<?php

use Dev4Press\Plugin\coreSocial\Basic\Statistics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$weekly = Statistics::instance()->weekly();

?>

<div class="d4p-group d4p-dashboard-card d4p-card-double">
    <h3><?php esc_html_e( 'Last 7 days Statistics', 'coresocial' ); ?></h3>
    <div class="d4p-group-inner">
        <div class="coresocial-weekly-chart">
			<?php

			foreach ( $weekly['days'] as $day => $data ) {
				$height = $weekly['max'] == 0 ? 0 : ( ( $weekly['totals'][ $day ] / $weekly['max'] ) * 100 );
				$empty  = 100 - $height;

				?>

                <div class="coresocial-weekly-day">
                    <div class="__count"><?php echo esc_html( $weekly['totals'][ $day ] ); ?></div>
                    <div class="__chart">
                        <div class="__empty" style="height: <?php echo esc_attr( $empty ); ?>%;"></div>
						<?php

						foreach ( $data as $network => $count ) {
							$h = ( $count / $weekly['max'] ) * 100;

							?>
                            <div title="<?php echo esc_attr( strtoupper( $network ) ) . ': ' . esc_attr( $count ); ?>" class="__network" style="height: <?php echo esc_attr( $h ); ?>%; background: rgb(var(--coresocial-color-<?php echo esc_attr( $network ); ?>-primary));"></div>
							<?php
						}

						?>
                    </div>
                    <div class="__day"><?php echo esc_html( $day ); ?></div>
                </div>

				<?php
			}

			?>
        </div>
    </div>
</div>
