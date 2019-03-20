<?php

namespace Tests\Unit;

use App\Exceptions\RandomException;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;
use App\Exceptions\Handler;

class MockRequest extends Request
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function expectsJson()
    {
        return $this->content;
    }
}

class MockContainer implements Container
{

    /**
     * Determine if the given type has been bound.
     *
     * @param  string $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        // TODO: Implement bound() method.
    }

    /**
     * Alias a type to a different name.
     *
     * @param  string $abstract
     * @param  string $alias
     * @return void
     *
     * @throws \LogicException
     */
    public function alias($abstract, $alias)
    {
        // TODO: Implement alias() method.
    }

    /**
     * Assign a set of tags to a given binding.
     *
     * @param  array|string $abstracts
     * @param  array|mixed ...$tags
     * @return void
     */
    public function tag($abstracts, $tags)
    {
        // TODO: Implement tag() method.
    }

    /**
     * Resolve all of the bindings for a given tag.
     *
     * @param  string $tag
     * @return iterable
     */
    public function tagged($tag)
    {
        // TODO: Implement tagged() method.
    }

    /**
     * Register a binding with the container.
     *
     * @param  string $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bind() method.
    }

    /**
     * Register a binding if it hasn't already been registered.
     *
     * @param  string $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool $shared
     * @return void
     */
    public function bindIf($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bindIf() method.
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string $abstract
     * @param  \Closure|string|null $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        // TODO: Implement singleton() method.
    }

    /**
     * "Extend" an type in the container.
     *
     * @param  string $abstract
     * @param  \Closure $closure
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function extend($abstract, Closure $closure)
    {
        // TODO: Implement extend() method.
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string $abstract
     * @param  mixed $instance
     * @return mixed
     */
    public function instance($abstract, $instance)
    {
        // TODO: Implement instance() method.
    }

    /**
     * Add a contextual binding to the container.
     *
     * @param  string $concrete
     * @param  string $abstract
     * @param  \Closure|string $implementation
     * @return void
     */
    public function addContextualBinding($concrete, $abstract, $implementation)
    {
        // TODO: Implement addContextualBinding() method.
    }

    /**
     * Define a contextual binding.
     *
     * @param  string|array $concrete
     * @return \Illuminate\Contracts\Container\ContextualBindingBuilder
     */
    public function when($concrete)
    {
        // TODO: Implement when() method.
    }

    /**
     * Get a closure to resolve the given type from the container.
     *
     * @param  string $abstract
     * @return \Closure
     */
    public function factory($abstract)
    {
        // TODO: Implement factory() method.
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        // TODO: Implement flush() method.
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     * @param  array $parameters
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function make($abstract, array $parameters = [])
    {
        // TODO: Implement make() method.
    }

    /**
     * Call the given Closure / class@method and inject its dependencies.
     *
     * @param  callable|string $callback
     * @param  array $parameters
     * @param  string|null $defaultMethod
     * @return mixed
     */
    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
        // TODO: Implement call() method.
    }

    /**
     * Determine if the given type has been resolved.
     *
     * @param  string $abstract
     * @return bool
     */
    public function resolved($abstract)
    {
        // TODO: Implement resolved() method.
    }

    /**
     * Register a new resolving callback.
     *
     * @param  \Closure|string $abstract
     * @param  \Closure|null $callback
     * @return void
     */
    public function resolving($abstract, Closure $callback = null)
    {
        // TODO: Implement resolving() method.
    }

    /**
     * Register a new after resolving callback.
     *
     * @param  \Closure|string $abstract
     * @param  \Closure|null $callback
     * @return void
     */
    public function afterResolving($abstract, Closure $callback = null)
    {
        // TODO: Implement afterResolving() method.
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        // TODO: Implement get() method.
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        // TODO: Implement has() method.
    }
}
class HandlerTest extends TestCase
{
    public function test_handler()
    {
        Config::set('app.debug', false);
        $handle = new Handler(new MockContainer());
        $exception= $handle->render(new MockRequest([], [], [], [], [], [], true), new RandomException());
        $response = json_decode($exception->getContent())->message;
        $this->assertEquals("Server Error", $response);
        $this->assertTrue(true);
    }
}
