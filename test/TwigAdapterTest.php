<?php

/**
 * TwigAdapter
 * TwigAdapterTest.php
 *
 * @author Marc Heuker of Hoek <me@marchoh.com>
 * @copyright 2016 - 2016 All rights reserved
 * @license MIT
 * @created 22-9-16
 * @version 1.0
 */

namespace Opera\Adapter\Twig;


use Opera\Component\Authorization\AccessControlListInterface;
use Opera\Component\WebApplication\Context;
use PHPUnit\Framework\TestCase;
use Twig_Environment;

class TwigAdapterTest extends TestCase
{

    public function testBasicRenderingGood()
    {
        $adapter = $this->setupTwig('sample');
        self::assertEquals($adapter->render(['name' => 'John']), 'Hello John');
    }

    /**
     * @expectedException Opera\Component\Template\TemplateException
     */
    public function testNoFileLoadedBad()
    {
        $context = $this->getMockBuilder(Context::class)->getMock();
        $adapter = new TwigAdapter($context);

        $adapter->render();
    }

    public function testGetTwigGood()
    {
        $adapter = $this->setupTwig('sample');

        self::assertInstanceOf(Twig_Environment::class, $adapter->getTwig());
    }

    public function testAddGlobalGood()
    {
        $adapter = $this->setupTwig('global');
        $adapter->addGlobal('global', 'hello');

        self::assertEquals($adapter->render(), 'hello');
    }

    public function testBasicAclGood()
    {
        $context = $this->getMockBuilder(Context::class)->getMock();
        $acl = $this->getMockBuilder(AccessControlListInterface::class)->getMock();
        $acl->method('hasAccess')->willReturn(true);
        $context->method('getAccessControlList')->willReturn($acl);


        $adapter = $this->setupTwig('acl', $context);
        self::assertEquals($adapter->render(), 'hello');
    }

    public function testBasicAclBad()
    {
        $context = $this->getMockBuilder(Context::class)->getMock();
        $acl = $this->getMockBuilder(AccessControlListInterface::class)->getMock();
        $acl->method('hasAccess')->willReturn(false);
        $context->method('getAccessControlList')->willReturn($acl);


        $adapter = $this->setupTwig('acl', $context);
        self::assertEquals($adapter->render(), 'bye');
    }

    private function setupTwig(string $filename, Context $context = null) : TwigAdapter
    {
        $context = $context ?? $this->getMockBuilder(Context::class)->getMock();

        $adapter = new TwigAdapter($context, __DIR__ . '/resource');
        $adapter->loadFile($filename);

        return $adapter;
    }

}
