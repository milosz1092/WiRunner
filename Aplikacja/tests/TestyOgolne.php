<?php

class TestyOgolne extends PHPUnit_Framework_TestCase
{
/**  Te linijki muszą zawsze wystąpić przed badaną funkcją.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function testCzyWykrywaPolskieZnaki()
    {
        $this->assertFalse(my_validDate::polskie(array("Śliwka")));
        $this->assertFalse(my_validDate::polskie(array("żandarm")));
        $this->assertFalse(my_validDate::polskie(array("śnieżka")));
        $this->assertFalse(my_validDate::polskie(array("Gdańsk")));
        $this->assertFalse(my_validDate::polskie(array("Łukassz")));
        $this->assertFalse(my_validDate::polskie(array("Miłosz")));
        $this->assertFalse(my_validDate::polskie(array("Jarosław")));
        
        $this->assertTrue(my_validDate::polskie(array("Polska")));
        $this->assertTrue(my_validDate::polskie(array("Szczecin")));
        $this->assertTrue(my_validDate::polskie(array("WiZUT")));
        $this->assertTrue(my_validDate::polskie(array("Tomasz")));
        $this->assertTrue(my_validDate::polskie(array("bieganie")));
        $this->assertTrue(my_validDate::polskie(array("ultramaraton 147")));
    }
    
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function testPoprawnosciEmaili()
    {
        $this->assertFalse(my_validDate::email(array("Śliwka")));
        $this->assertFalse(my_validDate::email(array("żandarm")));
        $this->assertFalse(my_validDate::email(array("Śliwka")));
        $this->assertTrue (my_validDate::email(array("lstaniszczak@wi.zut.edu.pl","mszewczyk@wi.zut.edu.pl","so2@zut.edu.pl")));
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
    
    
/**   Sprawdzamy, czy dane pobrane przez dwie różne funkcje są równe...
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_getUserInfo()
    {
	    $my_simpleDbCheck = new my_simpleDbCheck();

	   	foreach($my_simpleDbCheck->getUsersInfo() as $user){
		   	$biezace = $my_simpleDbCheck->getUserInfo($user['id_usera']);
	   		$this->assertEquals($user['imie'] . $user['nazwisko'] . $user['miejscowosc'] , $biezace['imie'] . $biezace['nazwisko'] . $biezace['miejscowosc']  );  
		}
    }
    
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_0()
    {
	    $my_activities = new my_activities();
		
	    $this->assertNotEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(10, 0, 0, 1));
	    $this->assertNotEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(500, 20, 10, 22));
	    $this->assertNotEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(42.195, 3, 23, 30));
	    $this->assertNotEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(21.195, 1, 32, 04));
    }    
    
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_1()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa("abvc", 10, 20, 20));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa("1a", 10, 20, 20));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa("a1", 10, 20, 20));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa("Ala ma kota", 10, 20, 20));
    }
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_2()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(42.195, "abvc", 23, 23));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(21.195, "1a", 23, 23));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(10,    "a1", 23, 23));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(22.45, "Ala ma kota", 23, 23));
    }
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_3()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(42.195,  23,"abvc", 23));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(21.195, 23 ,"1a",  23));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(10,    23  ,"a1",  23));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(22.45,  23,"Ala ma kota", 23));
    }
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_4()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(42.195,  23, 23,"abvc"));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(21.195, 23,  23 ,"1a"));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(10,    23 ,  23 ,"a1"));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(22.45,  23, 2,"Ala ma kota"));
    }

/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_4()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(42.195,  23, 23,"abvc"));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(21.195, 23,  23 ,"1a"));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(10,    23 ,  23 ,"a1"));
	    $this->assertEquals("Wprowadź prawidłowe wartości!", $my_activities->kalkulatorTempa(22.45,  23, 2,"Ala ma kota"));
    }

}
?>