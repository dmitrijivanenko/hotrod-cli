<?php

namespace HotRodCli;

use PHPUnit\Framework\TestCase;

class Foo
{
    protected $bar;
    protected $baz;

    public function __construct(Bar $bar, Baz $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }
}

class Bar
{
}

class Baz
{
    protected $qux;

    public function __construct(Qux $qux)
    {
        $this->qux = $qux;
    }
}

class Qux
{
    protected $norf;

    public function __construct(Norf $norf)
    {
        $this->norf = $norf;
    }
}

class Norf
{
}

abstract class AbstractTest
{
}

class AppContainerTest extends TestCase
{
    protected $appContainer;

    public function setUp()
    {
        $this->appContainer = new AppContainer();
    }

    /**
     * @test
     */
    public function it_can_resolve_the_class_without_dependencies()
    {
        $class = $this->appContainer->resolve(Norf::class);

        $this->assertInstanceOf(Norf::class, $class);
    }

    /**
     * @test
     */
    public function it_throws_the_exception_when_the_class_is_abstract()
    {
        $this->expectExceptionMessage('[' . AbstractTest::class . '] is not instantiable');

        $this->appContainer->resolve(AbstractTest::class);
    }

    /**
     * @test
     */
    public function it_resolves_class_from_registry()
    {
        $class = new Norf();

        $this->appContainer->bind(Norf::class, $class);

        $this->assertEquals($class, $this->appContainer->resolve(Norf::class));
    }

    /**
     * @test
     */
    public function it_resolves_the_class_with_dependencies()
    {
        $class = $this->appContainer->resolve(Baz::class);

        $this->assertInstanceOf(Baz::class, $class);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_dependency_is_null()
    {
        $parameter = $this->prophesize(\ReflectionParameter::class);

        $parameter->getClass()->shouldBeCalled()->willReturn(null);
        $parameter->isDefaultValueAvailable()->shouldBeCalled()->willReturn(false);

        $this->expectExceptionMessage('Cannot resolve the unknown');

        $this->appContainer->getDependencies([$parameter->reveal()]);
    }

    /**
     * @test
     */
    public function it_gets_the_default_value_when_it_exists()
    {
        $parameter = $this->prophesize(\ReflectionParameter::class);

        $parameter->isDefaultValueAvailable()->shouldBeCalled()->willReturn(true);
        $parameter->getDefaultValue()->shouldBeCalled()->willReturn('test');

        $result = $this->appContainer->resolveNonClass($parameter->reveal());

        $this->assertEquals('test', $result);
    }

    /**
     * @test
     */
    public function it_can_registry_the_entity()
    {
        $this->appContainer->bind('test', 'test');

        $this->assertEquals('test', $this->appContainer->get('test'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_the_entity_is_not_exists()
    {
        $this->expectExceptionMessage('No fail is bound in the container.');

        $this->appContainer->get('fail');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_we_try_to_register_the_duplication()
    {
        $this->appContainer->bind('test-second', 'test');

        $this->expectExceptionMessage('test-second is already bound in the container.');

        $this->appContainer->bind('test-second', 'test2');
    }
}
