<?php

class Test_my_validDate extends PHPUnit_Framework_TestCase
{
/**  Te linijki muszą zawsze wystąpić przed badaną funkcją.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function testCzyWykrywaPolskieZnaki()
    {
        $this->assertEquals(0, my_validDate::polskie(array("Śliwka")));
        $this->assertEquals(0, my_validDate::polskie(array("żandarm")));
        $this->assertEquals(0, my_validDate::polskie(array("śnieżka")));
        $this->assertEquals(0, my_validDate::polskie(array("Gdańsk")));
        $this->assertEquals(0, my_validDate::polskie(array("Łukassz")));
        $this->assertEquals(0, my_validDate::polskie(array("Miłosz")));
        $this->assertEquals(0, my_validDate::polskie(array("Jarosław")));
        $this->assertEquals(1, my_validDate::polskie(array("Polska")));
        $this->assertEquals(1, my_validDate::polskie(array("Szczecin")));
        $this->assertEquals(1, my_validDate::polskie(array("WiZUT")));
        $this->assertEquals(1, my_validDate::polskie(array("Tomasz")));
        $this->assertEquals(1, my_validDate::polskie(array("bieganie")));
        $this->assertEquals(1, my_validDate::polskie(array("ultramaraton 147")));
    }
    
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function testPoprawnosciEmaili()
    {
        $this->assertEquals(0, my_validDate::email(array("Śliwka")));
        $this->assertEquals(0, my_validDate::email(array("żandarm")));
        $this->assertEquals(0, my_validDate::email(array("Śliwka")));
        $this->assertEquals(1, my_validDate::email(array("lstaniszczak@wi.zut.edu.pl","mszewczyk@wi.zut.edu.pl","so2@zut.edu.pl")));
    }
    
        
/**  Test, czy funkcja zwraca poprawne nazwy sportów dla danych id.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_getSport()
    {
	    $my_simpleDbCheck = new my_simpleDbCheck();
	    $my_activities = new my_activities();
	    foreach($my_simpleDbCheck->getSports() as $sport){
	   		$this->assertEquals($sport['nazwa_sportu'], $my_activities->getSport($sport['id_sportu']));
		    
		}
        
        $this->assertEquals(0, $my_activities->getSport(-2));
        $this->assertEquals(0, $my_activities->getSport(55));
    }
/*
    public function testObjectCanBeConstructedForValidConstructorArguments()
    {
        $m = new Money(0, new Currency('EUR'));

        $this->assertInstanceOf('SebastianBergmann\\Money\\Money', $m);

        return $m;
    }

    public function testAmountCanBeRetrieved(Money $m)
    {
        $this->assertEquals(0, $m->getAmount());
    }

    public function testCurrencyCanBeRetrieved(Money $m)
    {
        $this->assertEquals(new Currency('EUR'), $m->getCurrency());
    }

    public function testAnotherMoneyObjectWithSameCurrencyCanBeAdded()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $c = $a->add($b);

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
        $this->assertEquals(3, $c->getAmount());
    }

    public function testExceptionIsThrownForOverflowingAddition()
    {
        $a = new Money(PHP_INT_MAX, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $a->add($b);
    }

    public function testExceptionIsRaisedForIntegerOverflow()
    {
        $a = new Money(PHP_INT_MAX, new Currency('EUR'));
        $a->multiply(2);
    }

    public function testExceptionIsRaisedWhenMoneyObjectWithDifferentCurrencyIsAdded()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('USD'));

        $a->add($b);
    }

    public function testAnotherMoneyObjectWithSameCurrencyCanBeSubtracted()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $c = $b->subtract($a);

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
        $this->assertEquals(1, $c->getAmount());
    }

    public function testExceptionIsThrownForOverflowingSubtraction()
    {
        $a = new Money(-PHP_INT_MAX, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $a->subtract($b);
    }

    public function testExceptionIsRaisedWhenMoneyObjectWithDifferentCurrencyIsSubtracted()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('USD'));

        $b->subtract($a);
    }

    public function testCanBeNegated()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = $a->negate();

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(-1, $b->getAmount());
    }

    public function testCanBeMultipliedByAFactor()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = $a->multiply(2);

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
    }

    public function testExceptionIsRaisedWhenMultipliedUsingInvalidRoundingMode()
    {
        $a = new Money(1, new Currency('EUR'));
        $a->multiply(2, null);
    }

    public function testCanBeAllocatedToNumberOfTargets()
    {
        $a = new Money(99, new Currency('EUR'));
        $r = $a->allocateToTargets(10);

        $this->assertEquals(
            array(
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(9, new Currency('EUR'))
            ),
            $r
        );
    }

    public function testExceptionIsRaisedWhenTryingToAllocateToInvalidNumberOfTargets()
    {
        $a = new Money(0, new Currency('EUR'));
        $a->allocateToTargets(null);
    }

    public function testCanBeAllocatedByRatios()
    {
        $a = new Money(5, new Currency('EUR'));
        $r = $a->allocateByRatios(array(3, 7));

        $this->assertEquals(
            array(
                new Money(2, new Currency('EUR')),
                new Money(3, new Currency('EUR'))
            ),
            $r
        );
    }
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));

        $this->assertEquals(-1, $a->compareTo($b));
        $this->assertEquals(1, $b->compareTo($a));
        $this->assertEquals(0, $a->compareTo($a));
    }

    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency2()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));

        $this->assertFalse($a->greaterThan($b));
        $this->assertTrue($b->greaterThan($a));
    }

    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency3()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));

        $this->assertFalse($b->lessThan($a));
        $this->assertTrue($a->lessThan($b));
    }

    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency4()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(1, new Currency('EUR'));

        $this->assertEquals(0, $a->compareTo($b));
        $this->assertEquals(0, $b->compareTo($a));
        $this->assertTrue($a->equals($b));
        $this->assertTrue($b->equals($a));
    }

    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency5()
    {
        $a = new Money(2, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $c = new Money(1, new Currency('EUR'));

        $this->assertTrue($a->greaterThanOrEqual($a));
        $this->assertTrue($a->greaterThanOrEqual($b));
        $this->assertTrue($a->greaterThanOrEqual($c));
        $this->assertFalse($c->greaterThanOrEqual($a));
    }

    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency6()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(1, new Currency('EUR'));
        $c = new Money(2, new Currency('EUR'));

        $this->assertTrue($a->lessThanOrEqual($a));
        $this->assertTrue($a->lessThanOrEqual($b));
        $this->assertTrue($a->lessThanOrEqual($c));
        $this->assertFalse($c->lessThanOrEqual($a));
    }

    public function testExceptionIsRaisedWhenComparedToMoneyObjectWithDifferentCurrency()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('USD'));

        $a->compareTo($b);
    }
    */
}
?>