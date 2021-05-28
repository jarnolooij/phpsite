<?php

class View {
	// Path to store files while being rendered
	static $cachePath = __DIR__ . '/cache/views/';
	// View header
	static $header = '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL;
	// Array of found {% block %}'s
	static $blocks = array();

	// Show a view
	static function make($file, $data = array()) {
		// Load the file
		$cachedFile = self::cache($file);
		// Load variables
		extract($data, EXTR_SKIP);
		// Include the file
		require $cachedFile;
		// Delete the file after its done
		unlink($cachedFile);
	}

	static function cache($file) {
		// Make sure the cache folder exists
		if (!file_exists(self::$cachePath)) {
			mkdir(self::$cachePath, 0744, true);
		}

		// Get the file name
		$cachedFile = self::$cachePath . str_replace(array('/', '.html'), array('_', ''), $file . '.'. uniqid() . '.php');
		
		// Load the initial file, and all extend blocks
		$code = self::includeFiles($file, 'extends{0,1}');
		// Load in blocks
		$code = self::loadBlocks($code);
		// Render the yields
		$code = self::includeYields($code);
		// Load the included files
		$code = self::includeFiles($file, 'include', $code);
		// Finish compiling
		$code = self::compile($code);

		// Put the rendered code in the cache file, and return this
		file_put_contents($cachedFile, self::$header . $code);

		return $cachedFile;
	}

	static function includeFiles($file, $mode, $code = null) {
		// If the target view file hasn't been loaded yet, load it in
		if ($code == null) {
			$code = file_get_contents(__DIR__ . "/views/$file.php");
		}
		
		// Find either all the extends, or includes depending on the mode
		preg_match_all('~\{% *(' . $mode . ') (.+?) *%}~is', $code, $matches, PREG_SET_ORDER);
		
		// Load all these files in
		foreach ($matches as $value) {
			$code = str_replace($value[0], self::includeFiles($value[2], $mode), $code);
		}

		// Cleanup placeholders
		$code = preg_replace('~\{% *(' . $mode . ') (.+?) *%}~is', '', $code);
		
		return $code;
	}

	static function loadBlocks($code) {
		// Match all regex { block NAME } CODE { block } 
		preg_match_all('~\{% *block (.+?) *%}(.+?){% *block *%}~is', $code, $matches, PREG_SET_ORDER);
		
		// Put all the NAME=>CODE pairs in the $blocks array
		foreach ($matches as $value) {
			self::$blocks[$value[1]] = $value[2];
			// Remove the blocks from code
			$code = str_replace($value[0], '', $code);
		}

		return $code;
	}

	static function includeYields($code) {
		// Loop over all the blocks
		foreach (self::$blocks as $block => $content) {
			// regex replace {% yield BOCKNAME %} with the blocks content
			$code = preg_replace('~{% yield ' . $block . ' %}~is', $content, $code);
		}

		return $code;
	}

	static function compile($code) {
		/* replace {% dump VAR %} with <?php print_r($var); ?> */
		// Used to dump variables easily
		$code = preg_replace('~{% dump (.+?) %}~is', '<?php print_r($$1); ?>', $code);
		
		/* replace {% for VAR as INDEX } with <?php foreach($var as $index( { ?> */
		// Used for easy for loops
		$code = preg_replace('~{% for (.+?) as (.+?) %}~is', '<?php foreach($$1 as $$2) { ?>', $code);
		/* replace {% endfor %} with <?php } ?> */
		$code = preg_replace('~{% endfor %}~is', '<?php } ?>', $code);
		
		/* Replace {{ VAR }} with <?php VAR ?> */
		// Used to easily execute raw php code
		$code = preg_replace('~{{ (.+?) }}~is', '<?php $1 ?>', $code);
		
		/* replace { VAR } with <?php echo $var; ?>*/
		// used to easily show variables
		$code = preg_replace('~{ (.+) }~i', '<?php echo $$1; ?>', $code);

		return $code;
	}
}
