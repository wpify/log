<?php

namespace Wpify\Log;

class RotatingFileHandler extends \Monolog\Handler\RotatingFileHandler {
	public function get_glob_pattern(): string {
		return $this->getGlobPattern();
	}
}