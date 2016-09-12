The Opera Twig Adapter
======================

This adapter allows you to easily use the Twig template engine for rendering
your views.

Installation
------------
Run `composer require theopera/twig-adapter` to get a copy of the adapter.
To make the adapter active add the following code to the `MyContext``class.

```php
public function getTemplateEngine() : RenderInterface
{
    return new TwigAdapter();
}
````

This will override the default PhpEngine template engine. Be sure to check import statement.