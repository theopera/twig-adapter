The Opera Twig Adapter
======================

[![Build Status](https://travis-ci.org/theopera/twig-adapter.svg?branch=master)](https://travis-ci.org/theopera/framework)


This adapter allows you to easily use the Twig template engine for rendering
your views.

Installation
------------
Run `composer require theopera/twig-adapter` to get a copy of the adapter.
To make the adapter active, add the following code, to the `MyContext` class.

```php
public function getTemplateEngine() : RenderInterface
{
    if($this->render === null){
        $this->render = new TwigAdapter($this);
    }
    
    return $this->render;
}
````

This will override the default PhpEngine template engine. Be sure to check your import statements.