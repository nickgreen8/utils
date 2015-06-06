<?php
namespace N8G\Utils\Tests;

use N8G\Utils\Validation;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
	// Tests

	/**
	 * This function is used to determine whether e-mail addresses are validated correctly. Instead
	 * of using multipul data providers, only one is used to check both valid and invalid e-mails.
	 * This is indicated by the second argument which is the expected boolean result. The first
	 * argument passed is the e-mail address to be validated.
	 *
	 * @test
	 * @dataProvider emailProvider
	 *
	 * @param  string  $email    The e-mail address to be evaluated
	 * @param  boolean $expected Indicates the expected result
	 * @return void
	 */
	public function testEmail($email, $expected)
	{
		//Make the function call and store the result
		$result = Validation::isEmail($email);

		//Assert the result is as expected
		$this->assertEquals($expected, $result);
	}

	/**
	 * This function is used to determine whether dates are validated correctly. Instead of using
	 * multipul data providers, only one is used to check both valid and invalid dates. This is
	 * indicated by the third argument which is the expected boolean result. The first argument
	 * passed is the date address to be validated. The second argument is the date format to be
	 * tested.
	 *
	 * @test
	 * @dataProvider dateProvider
	 *
	 * @param  string  $date     The date to be evaluated
	 * @param  string  $format   The date format to be used
	 * @param  boolean $expected Indicates the expected result
	 * @return void
	 */
	public function testDate($date, $format, $expected)
	{
		//Make the function call and store the result
		$result = Validation::isDate($date, $format);

		//Assert the result is as expected
		$this->assertEquals($expected, $result);
	}

	// Data providers

	/**
	 * E-mail validation tests
	 *
	 * @return array Data for the e-mail validation tests
	 */
	public function emailProvider()
	{
		return array(
			array(
				'email'  =>	'test@test.com',
				'result' =>	true
			),
			array(
				'email'  =>	'test@test.co.uk',
				'result' =>	true
			),
			array(
				'email'  =>	'test@test.com.au',
				'result' =>	true
			),
			array(
				'email'  =>	'test@thisisatest.com',
				'result' =>	true
			),
			array(
				'email'  =>	'this.is.a.test@test.co.uk',
				'result' =>	true
			),
			array(
				'email'  =>	'test',
				'result' =>	false
			),
			array(
				'email'  =>	'test@@test.com',
				'result' =>	false
			),
			array(
				'email'  =>	'test@test.thisisatest',
				'result' =>	false
			),
			array(
				'email'  =>	'this.is.a.test',
				'result' =>	false
			),
			array(
				'email'  =>	'test@this.is.a.test',
				'result' =>	false
			)
		);
	}

	/**
	 * Date validation tests
	 *
	 * @return array Data for the date validation tests
	 */
	public function dateProvider()
	{
		return array(
			array(
				'date'   =>	'01/01/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'01/01/00',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-00',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.00',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'00/01/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'32/01/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/00/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/13/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'29/02/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'29/02/2001',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'30/04/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'31/04/2000',
				'format' => 'dd/mm/yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/2000',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/00',
				'format' => 'dd/mm/yy',
				'result' =>	true
			),
			array(
				'date'   =>	'01-01-2000',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.2000',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'00/01/00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'32/01/00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/00/00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/13/00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'29/02/00',
				'format' => 'dd/mm/yy',
				'result' =>	true
			),
			array(
				'date'   =>	'29/02/01',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'30/04/00',
				'format' => 'dd/mm/yy',
				'result' =>	true
			),
			array(
				'date'   =>	'31/04/00',
				'format' => 'dd/mm/yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/00',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'01-01-00',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.00',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'00-01-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'32-01-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-00-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-13-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'29-02-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'29-02-2001',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'30-04-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'31-04-2000',
				'format' => 'dd-mm-yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/2000',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-2000',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-00',
				'format' => 'dd-mm-yy',
				'result' =>	true
			),
			array(
				'date'   =>	'01.01.2000',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'00-01-00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'32-01-00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-00-00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-13-00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'29-02-00',
				'format' => 'dd-mm-yy',
				'result' =>	true
			),
			array(
				'date'   =>	'29-02-01',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'30-04-00',
				'format' => 'dd-mm-yy',
				'result' =>	true
			),
			array(
				'date'   =>	'31-04-00',
				'format' => 'dd-mm-yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/00',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-00',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'01.01.00',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'00.01.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'32.01.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.00.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.13.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'29.02.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'29.02.2001',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'30.04.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	true
			),
			array(
				'date'   =>	'31.04.2000',
				'format' => 'dd.mm.yyyy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/2000',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01/01/00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-2000',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01-01-00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.2000',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.01.00',
				'format' => 'dd.mm.yy',
				'result' =>	true
			),
			array(
				'date'   =>	'00.01.00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'32.01.00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.00.00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'01.13.00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'29.02.00',
				'format' => 'dd.mm.yy',
				'result' =>	true
			),
			array(
				'date'   =>	'29.02.01',
				'format' => 'dd.mm.yy',
				'result' =>	false
			),
			array(
				'date'   =>	'30.04.00',
				'format' => 'dd.mm.yy',
				'result' =>	true
			),
			array(
				'date'   =>	'31.04.00',
				'format' => 'dd.mm.yy',
				'result' =>	false
			)
		);
	}
}