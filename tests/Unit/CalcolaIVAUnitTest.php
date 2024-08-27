<?php

namespace Tests\Unit;

use App\Http\Controllers\api\v1\CalcolaIVA;
use PHPUnit\Framework\TestCase;

class CalcolaIVAUnitTest extends TestCase
{

    /** @test */
    public function calcolaTest(){
        $a = 1;
        $a_controllo = true;

        $a_ricevuto = CalcolaIVA::calcola($a);
        $this->assertEquals($a_controllo, $a_ricevuto);

        $a=rand(2,100);
        $a_controllo = false;
        $a_ricevuto = CalcolaIVA::calcola($a);
        $this->assertEquals($a_controllo, $a_ricevuto);

        $a=rand(2,100);
        $a_controllo = true;
        $a_ricevuto = CalcolaIVA::calcola($a);
        $this->assertNotEquals($a_controllo, $a_ricevuto);


        $a = rand(1,100);
        $a_controllo = ($a == 1) ? true : false;
        $this->assertEquals($a_controllo, $a_ricevuto);
    }
}
