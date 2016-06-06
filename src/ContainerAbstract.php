<?php
namespace N8G\Utils;

use N8G\Utils\Exceptions\ContainerException;

/**
 * This class holds all the elements that are required to create a container that can be utilised through any
 * application. The basic functionality is all created within the class with other custom functionality availiable in a
 * child class within the application itself.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
abstract class ContainerAbstract
{
    /**
     * Holder for all elements to be held.
     * @var array
     */
    private $elements = array();

    /**
     * Abstract method that must be created within the application itself. This is where all the key elements will be
     * created and added to the container on initialisation.
     *
     * @param  array|object $config Any config that needs to be passed to create anything.
     * @return void
     */
    public abstract function populate($config = null);

    /**
     * Gets an element from the container. The key of the element must be passed so that it can be successfully
     * retrieved as expected.
     *
     * @param  string $key The key of the element to get.
     * @return mixed       The element stored in the container.
     */
    public function get($key)
    {
        if (in_array($key, array_keys($this->elements))) {
            return $this->elements[$key];
        }
        throw new ContainerException(sprintf('Element (%s) could not be found.', $key));
    }

    /**
     * Adds a new element to the container. The key of the element and the element itself is passed to the function.
     * Nothing is returned.
     *
     * @param  string $key     The key of the element to be added to the container.
     * @param  mixed  $element The element to be added to the container.
     * @return void
     */
    public function add($key, $element)
    {
        $this->elements[$key] = $element;
    }

    /**
     * Checks if an element has been set in the container with a specific key. A boolean is returned indicating whether
     * the key has been set in the array.
     *
     * @param  string  $key The key to check.
     * @return boolean      Whether the key is set in the container.
     */
    public function keyExists($key)
    {
        return array_key_exists($key, $this->elements);
    }
}
