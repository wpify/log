<?php

namespace Wpify\Log;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
class RotatingFileLog extends Log {
	/**
	 * @param string                  $channel
	 * @param string                  $path
	 * @param FormatterInterface|null $formatter
	 * @param array                   $menu_args
	 */
	public function __construct( string $channel, string $path = '', ?FormatterInterface $formatter = null, array $menu_args = [] ) {
		$filename = sprintf( 'wpify_log_%s', $channel );
		if ( ! $path ) {
			$dir  = wp_get_upload_dir();
			$path = trailingslashit( $dir['basedir'] ) . 'logs' . DIRECTORY_SEPARATOR . $filename;
			$key  = $channel;
			if ( defined( 'NONCE_KEY' ) ) {
				$key = $channel . NONCE_KEY;
			}
			$path = sprintf( '%s_%s.log', $path, hash( 'md5', $key ) );
		}

		$max_files = get_option('wpify_logs_max_files', 5);
		$handler = new RotatingFileHandler($path, $max_files, Logger::DEBUG, \true, 0660);
		$handler->setFormatter( $formatter ?? new JsonFormatter() );
		parent::__construct( $channel, [ $handler ], $menu_args );
	}
}