<?php

class TestyRejestracji extends PHPUnit_Framework_TestCase
{
/**  Te linijki muszą zawsze wystąpić przed badaną funkcją.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function testRejestracji_1_1() {
	    $my_userAction = new my_userAction();
        $this->assertEquals(0, $my_userAction->register(array('email' => "anowak@gmail.com", 'haslo' => "user1", 'eqhaslo' => "user1", 'plec' => "k", 'zgoda' => "true")));	
    }

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    
    public function testRejestracji_1_2() {
	    $my_userAction = new my_userAction();
        $this->assertEquals(-1, $my_userAction->register(array('email' => "anowak", 'haslo' => "user1", 'eqhaslo' => "user1", 'plec' => "k", 'zgoda' => "true")));	
    }
    


/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    
    public function testRejestracji_1_3() {
	    $my_userAction = new my_userAction();
        $this->assertEquals(-1, $my_userAction->register(array('email' => "anowak@gmail.com", 'haslo' => "user1", 'eqhaslo' => "user1111", 'plec' => "k", 'zgoda' => "true")));	
    }
    

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    
    public function testRejestracji_1_4() {
	    $my_userAction = new my_userAction();
        $this->assertEquals(-1, $my_userAction->register(array('email' => "anowak@gmail.com", 'haslo' => "admin#1", 'eqhaslo' => "admin1", 'plec' => "k", 'zgoda' => "true")));	
    }

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    
    public function testRejestracji_1_5() {
	    $my_userAction = new my_userAction();
        $this->assertEquals(-1, $my_userAction->register(array('email' => "kkowalski@gmail.com", 'haslo' => "\$admin", 'eqhaslo' => "123", 'plec' => "m", 'zgoda' => "true")));	
    }
    
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    
    public function testRejestracji_1_6() {
	    $my_userAction = new my_userAction();
        $this->assertEquals(-1, $my_userAction->register(array('email' => "kkowalski@gmail.com", 'haslo' => "haslo123", 'eqhaslo' => "haslo123", 'plec' => NULL, 'zgoda' => "true")));	
    }
    
}
?>
