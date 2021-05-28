# Php site  
Just working on this small site / framework to learn a bit about php, with no real plans for expanding it out into something big. 
the index file sent out to browsers is `public\index.php`, which kickstarts the app using `server.php`.    
There is no database migration system currently, the constructor for the database class setups tables, and database connection details are set in the .env file (copy .env.example).  

Feedback about good / bad practices, or security flaws are greatly appreciated!

## Routing  
Routes are defined with a path/callback structure currently.  
To register a route, you can call `Router::get($path, $callback)`
Other than `get`, you are also able to define `post`, `put` and `delete` endpoints. Routes will only match the respecting request method.
### Route variables
Inside a path you can specify a `:` (ex `/users/:id/profile`) to register a router paramater. Variables will match anything, and its content is passed to the callback as a parameter.

```php
Router::get('/users/:id/profile', function($id) {
    // Here you can use the $id
});
```
### dependency injection
If you need access to a controller inside a routes callback, you can simply add it as a parameter
```php
Router::post('/auth/login`, function(Auth $auth) {
  // $auth is now available
});
```
To register new dependencies, you must first call `Router::loadDependency($className, $object)` (ex `Router::loadDependency('Auth', $auth);`) and then they will be available to use.
When using both dependencies and route parameters, the dependencies must be listed first, followed by the route parameters in order
```php
Router::get('/users/:id/profile', function(Auth $auth, $id) {
    // Here you can use the $id
});
```
Dependency order does not matter, route parameter order does.  

## Views & template rendering
Views can be rendered with `View::make($file, $variables)` where `$file` is the filename, without extensions, in folder `views/`.  
`$variables` is an array of variables that will be available inside the view file.  
Syntax:
tag | turns into
--- | --- 
{% dump VAR %} | `<?php print_r($var); ?>` used to easily dump variables for debugging
{% for VAR as INDEX %} | `<?php foreach($var as $index) { ?>` used for easier for-loops
{% endfor %} | `<?php } ?>` close for loops
{{ VAR }} | `<?php VAR ?>` used to execute direct php code
{ VAR } | `<?php echo $var; ?>` used to display variables
{% extends FILE%} | With this, you can extend other layout files and build upon them
{% block NAME} CODE {% block %} | Allows you to define blocks, which fill up the corresponding yield in a template
{% yield NAME %} | Place this in a template to fill it with a block
{% include FILE %} | Import another file, without extending it

## Models and controllers
Currently there is no model/controller system yet, this will be added later