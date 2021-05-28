<?php

class View {
	static $cachePath = __DIR__ . '/cache/views/';
	static $header = '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL;

	static function make($file, $data = array()) {
		$cachedFile = self::cache($file);
		extract($data, EXTR_SKIP);
		require $cachedFile;
		unlink($cachedFile);
	}

	static function cache($file) {
		if (!file_exists(self::$cachePath)) {
			mkdir(self::$cachePath, 0744, true);
		}

		$cachedFile = self::$cachePath . str_replace(array('/', '.html'), array('_', ''), $file . '.'. uniqid() . '.php');
		
		$code = file_get_contents(__DIR__ . "/$file.php");
		$code = self::compile($code);

		file_put_contents($cachedFile, self::$header . $code);

		return $cachedFile;
	}

	static function compile($code) {
		/* {{ $1 }} => <?php $1 ?> */
		$code = preg_replace('~\{{ *(.+?) *\}}~is', '<?php $1 ?>', $code);
		/* {% $1 %} => <?php echo $$1; ?> */
		$code = preg_replace('~\{ *% *(.+?) *% *}~is', '<?php echo $$1; ?>', $code);
		/* {dump $1} => <?php print_r($$1); ?>*/
		$code = preg_replace('~\{ *dump +(.+?) *\}~is', '<?php print_r($$1); ?>', $code);
		
		/* {for $1 as $2} => <?php foreach($1 as $2) { ?>*/
		$code = preg_replace('~\{ *for +(.+?) *as *(.+?) *\}~is', '<?php foreach($$1 as $$2) { ?>', $code);
		/* {endfor} => <?php } ?>*/
		$code = preg_replace('~\{ *endfor *\}~is', '<?php } ?>', $code);

		return $code;
	}
}
