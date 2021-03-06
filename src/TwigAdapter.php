<?php
/**
 * TwigAdapter
 * TwigAdapter.php
 *
 * @author    Marc Heuker of Hoek <me@marchoh.com>
 * @copyright 2016 - 2019 All rights reserved
 * @license   MIT
 * @created   17-7-16
 * @version   1.0
 */

namespace Opera\Adapter\Twig;


use Opera\Component\Authentication\UserInterface;
use Opera\Component\Template\RenderInterface;
use Opera\Component\Template\TemplateException;
use Opera\Component\WebApplication\Context;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigAdapter implements RenderInterface
{
    /**
     * @var Environment
     */
    private $twig;

    private $templateFile = null;
    /**
     * @var Context
     */
    private $context;

    /**
     * TwigAdapter constructor.
     *
     * @param Context $context
     * @param string|null $basePath
     * @param Environment|null $twig
     */
    public function __construct(Context $context, string $basePath = null, Environment $twig = null)
    {
        $this->context = $context;
        $this->twig = $twig ?? $this->getDefaultTwigEnvironment($basePath);

        $this->init();
    }

    protected function init()
    {
        // Add ACL access through the twig template files
        $context = $this->context;
        $this->twig->addFunction(new TwigFunction('has_permission',  function (string $permission) use($context) {
            $acl = $context->getAccessControlList();
            $auth = $context->getAuthentication();
            $user = $auth->getUser();

            if ($user instanceof UserInterface) {
                return $acl->hasAccess($user, $permission);
            }

            return false;
        }));
    }

    /**
     * Add a variable that will be globally accessible
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value)
    {
        $this->twig->addGlobal($key, $value);
    }

    public function loadFile(string $file)
    {
        $this->templateFile = $file . '.twig';
    }

    public function render(array $data = []) : string
    {
        if ($this->templateFile === null) {
            throw new TemplateException('No Twig template file loaded');
        }

        return $this->twig->render($this->templateFile, $data);
    }

    public function getTwig() : Environment
    {
        return $this->twig;
    }

    private function getDefaultTwigEnvironment(string $basePath = null) : Environment
    {
        $loader = new FilesystemLoader($basePath ?? '.');
        return new Environment($loader);
    }
}
