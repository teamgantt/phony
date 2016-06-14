<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2016 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

use Eloquent\Phony\Phony;

error_reporting(-1);

describe('Phony', function () {
    beforeEach(function () {
        $this->handle = Phony::mock('Eloquent\Phony\Test\TestClassA');
        $this->mock = $this->handle->mock();
    });

    it('should record passing mock assertions', function () {
        $this->mock->testClassAMethodA('aardvark', 'bonobo');

        $this->handle->testClassAMethodA->calledWith('aardvark', 'bonobo');
    });

    it('should record failing mock assertions', function () {
        $this->mock->testClassAMethodA('aardvark', array('bonobo', 'capybara', 'dugong'));
        $this->mock->testClassAMethodA('armadillo', array('bonobo', 'chameleon', 'dormouse'));

        $this->handle->testClassAMethodA->calledWith('aardvark', array('bonobo', 'chameleon', 'dugong'));
    });
});
