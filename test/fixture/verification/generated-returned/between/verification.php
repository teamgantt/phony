<?php

use Eloquent\Phony\Test\Facade\Phony;

// setup
$stub = Phony::stub()->setLabel('label')->setUseIterableSpies(true);
$stub->with('aardvark')->generates()->returns('AARDVARK');
iterator_to_array($stub('aardvark'));
iterator_to_array($stub('aardvark'));

// verification
$stub->generated()->between(3, 4)->returned('AARDVARK');
