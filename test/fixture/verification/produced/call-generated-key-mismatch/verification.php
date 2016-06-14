<?php

use Eloquent\Phony\Test\Phony;

// setup
$stub = Phony::stub()->setLabel('label')->setUseTraversableSpies(true);
$stub->with('aardvark')->generates(array('AARDVARK' => 'MECHA-AARDVARK'));
$stub->with('bonobo')->generates(array('BONOBO' => 'MECHA-BONOBO'));
iterator_to_array($stub('aardvark'));
iterator_to_array($stub('bonobo'));

// verification
$stub->lastCall()->generated()->produced('CHAMELEON', 'MECHA-BONOBO');
