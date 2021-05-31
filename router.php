<?php

class Router {
	
	// private static $dependencies = array();
	private static $endpoints = array('get', 'post', 'put', 'delete');

	// public static function loadDependency($name, $object) {
	// 	self::$dependencies[$name] = $object;
	// }

	public static function get($endpoint, $callback) {
		self::$endpoints['get'][$endpoint] = $callback;
	}

	public static function post($endpoint, $callback) {
		self::$endpoints['post'][$endpoint] = $callback;
	}

	public static function put($endpoint, $callback) {
		self::$endpoints['put'][$endpoint] = $callback;
	}

	public static function delete($endpoint, $callback) {
		self::$endpoints['delete'][$endpoint] = $callback;
	}

	public static function match($uri, $method) {
		$uriParts = explode('/', $uri);
		
		// Loop over all the routes for the method
		foreach (self::$endpoints[$method] as $endpoint => $cb) {
			// Split the endpoint into parts
			$parts = explode('/', $endpoint);
			// While still a valid match
			$match = true;
			// Route params
			$parameters = array();

			// Are there the same amount of parts, otherwise it can't be a match
			if (count($uriParts) == count($parts)) {
				// Loop over the parts
				for ($i = 0; $i < count($parts); $i++) {
					// If the part is a parameter, don't compare, instead add to array
					if (substr($parts[$i], 0, 1) == ':') {
						$parameters[] = $uriParts[$i];
						continue;
					}
					// If the part is not a parameter, compare to the uri
					if ($parts[$i] != $uriParts[$i]) {
						$match = false;
					}
				}
			} else {
				$match = false;
			}

			// If the match is valid, return it
			if ($match) {
				return array('callback' => $cb, 'parameters' => $parameters);
			}
		}

		return null;
	}

	public static function submit() {
		// Get the endpoint and method
		$uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
		$method = strtolower($_SERVER['REQUEST_METHOD']);
		// Get a matching route
		$match = self::match($uri, $method);

		if ($match != null) {
			// Get the callback,and ReflectionFunction object for the callback
			$callback = $match['callback'];
			$refCallback = new ReflectionFunction($callback);
			
			// Initialize array
			// $parameters = array();

			// Loop over the callback parameters
			// foreach ($refCallback->getParameters() as $parameter) {
			// 	// If it doesn't have a class, ignore
			// 	if ($parameter->getClass() == null) continue;
				
			// 	// Find the dependency object, add to parameters array in order
			// 	if (array_key_exists($parameter->getClass()->name, self::$dependencies)) {
			// 		$parameters[] = self::$dependencies[$parameter->getClass()->name];
			// 	} else {
			// 		die('Missing dependency! ' . $parameter->getClass()->name);
			// 	}
			// }

			// Invoke the callback, and include route parameters
			$refCallback->invoke(...$match['parameters']);
		} else {
			http_response_code(404);
		}
	}
}
