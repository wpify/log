<?php

namespace Wpify\Log;

class Tools {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
	}

	public function add_menu_page() {
		add_submenu_page( 'tools.php', __( 'WPify Logs', 'wpify-log' ), __( 'WPify Logs', 'wpify-log' ), 'read', 'wpify-logs', [ $this, 'logs_screen' ] );
	}

	public function logs_screen() {
		$logs  = apply_filters( 'wpify_logs', [] );
		$files = [];
		foreach ( $logs as $log ) {
			foreach ( $log->getHandlers() as $handler ) {
				if ( ! method_exists( $handler, 'get_glob_pattern' ) ) {
					continue;
				}

				$log_files = \glob( $handler->get_glob_pattern() );
				$files     = array_merge( $files, $log_files );
			}
		}

		?>
        <div class="wrap">
            <h2><?php _e( 'WPify Logs', 'wpify-log' ); ?></h2>
            <form action="">
                <select name="log-file" id="log-file" style="max-width: 300px;">
                    <option value=""></option>
					<?php foreach ( $files as $file ) { ?>
                    <option value="<?php echo $file; ?>" <?php echo selected( $file, !empty($_GET['log-file']) ? $_GET['log-file'] : '' ); ?>><?php echo basename( $file ); ?></option>
					<?php } ?>
                </select>
                <input type="hidden" name="page" value="wpify-logs"/>
                <input type="submit" value="Display log"/>
            </form>
        </div>

		<?php
		if ( ! empty( $_GET['log-file'] ) ) {
			if ( ! in_array( $_GET['log-file'], $files ) ) { ?>
                <p><?php _e( 'File not found, cheating, huh?', 'wpify-log' ); ?></p>
			<?php } else { ?>
                <div style="margin-top: 40px;">
                    <code>
						<?php echo file_get_contents( $_GET['log-file'] ); ?>
                    </code>
                </div>
			<?php }
		}
	}
}