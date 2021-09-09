<?php

namespace Wpify\Log;

use Monolog\Handler\RotatingFileHandler;

class RotatingFileLog extends Log {
	public function __construct( string $channel, string $path = '' ) {
		if (!$path) {
			$dir = wp_get_upload_dir();
			die(var_dump($dir));
			$path = '';
		}
		$handler = new RotatingFileHandler( sprintf( 'wpify_log_%s', $channel ) );
		parent::__construct( $channel, [ $handler ] );
	}
}