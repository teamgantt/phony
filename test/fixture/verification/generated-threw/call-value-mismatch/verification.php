<?php

use Eloquent\Phony\Test\Phony;

// setup
$stub = Phony::stub()->setLabel('label')->setUseTraversableSpies(true);
$stub->with('aardvark')->generates()->throws(new RuntimeException('AARDVARK'));
$stub->with('bonobo')->generates()->throws(new RuntimeException('BONOBO'));
try {
    iterator_to_array($stub('aardvark'));
} catch (RuntimeException $e) {
}
try {
    iterator_to_array($stub('bonobo'));
} catch (RuntimeException $e) {
}

// verification
$stub->lastCall()->generated()->threw(new RuntimeException('DUGONG'));
