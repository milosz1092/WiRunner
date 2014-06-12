<?php
class TestyOgolne extends PHPUnit_Framework_TestCase
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
        $this->assertEquals(0, my_validDate::email(array("ktow.wp.pl")));
        $this->assertEquals(1, my_validDate::email(array("lstaniszczak@wi.zut.edu.pl","mszewczyk@wi.zut.edu.pl","so2@zut.edu.pl")));
    }
    
/** Test wykrywanie znakow specjalnych.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_specjalne()
    {
        $this->assertEquals(1, my_validDate::specjalne(array("miłoszszewczyk")));
        $this->assertEquals(1, my_validDate::specjalne(array("Domowykot")));
        $this->assertEquals(1, my_validDate::specjalne(array("takietamjakieś2141")));
        $this->assertEquals(0, my_validDate::specjalne(array("f#gh", "ds3 f%s", "tekst ze spacjami")));
    }

/** Test porownywania ciagow znakow.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_porownaj()
    {
        $this->assertEquals(1, my_validDate::porownaj(array("dfwcf2", "dfwcf2")));
        $this->assertEquals(1, my_validDate::porownaj(array("takietam122", "takietam122")));
        $this->assertEquals(0, my_validDate::porownaj(array("jakies123", "jaies123")));
    	$this->assertEquals(0, my_validDate::porownaj(array("takie1", "2takie1")));
    }

/** Test minimalna dlugosc.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_minDlugosc()
    {
        $this->assertEquals(1, my_validDate::dlugoscmin(array("sdfsd11"), 3));
        $this->assertEquals(1, my_validDate::dlugoscmin(array("user3342"), 6));
        $this->assertEquals(0, my_validDate::dlugoscmin(array("dfs3"), 6));
    	$this->assertEquals(0, my_validDate::dlugoscmin(array("ciagznakow"), 12));
    }

/** Test czy uzytkownik jest w bazie danych (po e-mail'u).
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_userIssetMAIL()
    {
    	$my_simpleDbCheck = new my_simpleDbCheck();
        $this->assertEquals(1, $my_simpleDbCheck->userIssetFromMail('miszewczyk@wi.zut.edu.pl'));
        $this->assertEquals(1, $my_simpleDbCheck->userIssetFromMail('syntia.porwisz@gmail.com'));
		$this->assertEquals(0, $my_simpleDbCheck->userIssetFromMail('unknownuser@wp.pl'));
		$this->assertEquals(0, $my_simpleDbCheck->userIssetFromMail('2324mdfsn@xs.en'));
    }

/** Test czy uzytkownik jest w bazie danych (po id).
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_userIssetID()
    {
    	$my_simpleDbCheck = new my_simpleDbCheck();
        $this->assertEquals(1, $my_simpleDbCheck->userIssetFromId(1));
        $this->assertEquals(1, $my_simpleDbCheck->userIssetFromId(28));
		$this->assertEquals(0, $my_simpleDbCheck->userIssetFromId(453));
		$this->assertEquals(0, $my_simpleDbCheck->userIssetFromId(0));
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

/**  Test, czy funkcja poprawnie blokuje i odblokowywyje uzytkownikow.
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_lockUnlock()
    {
    	$my_simpleDbCheck = new my_simpleDbCheck();
	    $my_userAction = new my_userAction();

	    foreach($my_simpleDbCheck->getUsersList() as $row){
	    	if ($row['blokada'] == 0)
	   			$this->assertEquals(1, $my_userAction->lockToggle(1, $row['id_uzytkownika']));
			else if ($row['blokada'] == 1)
				$this->assertEquals(1, $my_userAction->lockToggle(0, $row['id_uzytkownika']));
		}

	    foreach($my_simpleDbCheck->getUsersList() as $row){
	    	if ($row['blokada'] == 0)
	   			$this->assertEquals(1, $my_userAction->lockToggle(1, $row['id_uzytkownika']));
			else if ($row['blokada'] == 1)
				$this->assertEquals(1, $my_userAction->lockToggle(0, $row['id_uzytkownika']));
		}
    }

/**  Test, czy nie jest mozliwe zalogowanie przy uzyciu hasel z bazy danych (zabezpieczenie hasel)
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function test_userLogin()
    {
    	$my_simpleDbCheck = new my_simpleDbCheck();
	    $my_userAction = new my_userAction();

	    foreach($my_simpleDbCheck->getUsersList() as $row){
	   		$this->assertEquals(0, $my_userAction->login(array('email' => $row['email'], 'haslo' => $row['haslo'])));
		}
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
}
?>
