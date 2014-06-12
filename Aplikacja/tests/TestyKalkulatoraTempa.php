<?php

class TestyKalkulatoraTempa extends PHPUnit_Framework_TestCase
{ 
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_0()
    {
	    $my_activities = new my_activities();
		
	    $this->assertNotEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(10, 0, 0, 1));
	    $this->assertNotEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(500, 20, 10, 22));
	    $this->assertNotEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(42.195, 3, 23, 30));
	    $this->assertNotEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(21.195, 1, 32, 04));
    }    
    
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_1()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa("abvc", 10, 20, 20));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa("1a", 10, 20, 20));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa("a1", 10, 20, 20));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa("Ala ma kota", 10, 20, 20));
    }
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_2()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(42.195, "abvc", 23, 23));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(21.195, "1a", 23, 23));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(10,    "a1", 23, 23));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(22.45, "Ala ma kota", 23, 23));
    }
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_3()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(42.195,  23,"abvc", 23));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(21.195, 23 ,"1a",  23));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(10,    23  ,"a1",  23));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(22.45,  23,"Ala ma kota", 23));
    }
/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_4()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(42.195,  23, 23,"abvc"));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(21.195, 23,  23 ,"1a"));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(10,    23 ,  23 ,"a1"));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(22.45,  23, 2,"Ala ma kota"));
    }

/** 
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_kalkulatorTempa_11_4()
    {
	    $my_activities = new my_activities();
		
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(42.195,  23, 23,"abvc"));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(21.195, 23,  23 ,"1a"));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(10,    23 ,  23 ,"a1"));
	    $this->assertEquals("WprowadŸ prawid³owe wartoœci!", $my_activities->kalkulatorTempa(22.45,  23, 2,"Ala ma kota"));
    }

}
?>