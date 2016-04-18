<?php
namespace N8G\Utils;

use N8G\Utils\ContainerAbstract;

/**
 * Unit tests for the Container class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
	// Tests

	/**
	 * Tests the add method of the container before checking the get method of the class.
	 *
	 * @test
	 * @dataProvider getAddDataProvider
	 *
	 * @param  string $key     The key of the element to get.
	 * @param  mixed  $element The element to be added to the container.
	 * @return void
	 */
	public function testAddGet($key, $element)
	{
		// //Create instance of the container
		// $container = new ContainerAbstract;

		// //Add to the container
		// $container->add($key, $element);

		// //Check that you can get the element
		// $actual = $container->get($key);

		// //Check the outcome
		// $this->assertEquals($element, $actual);
	}

	// Data providers

	/**
	 * Provider for both the get and add unit tests.
	 *
	 * @return array Provider data.
	 */
	public function getAddDataProvider()
	{
		return array(
			array(
				'key' => 'test',
				'element' => 'test'
			)
		);
	}
}
