<?php

/*
 * This file is part of Rocketeer
 *
 * (c) Maxime Fabre <ehtnam6@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rocketeer\Tasks\Subtasks;

use Rocketeer\Dummies\DummyBeforeAfterNotifier;
use Rocketeer\Dummies\DummyBeforeAfterArrayNotifier;
use Rocketeer\Dummies\DummyArrayNotifier;
use Rocketeer\TestCases\RocketeerTestCase;

class NotifyTest extends RocketeerTestCase
{
    public function testDoesntSendTheSameNotificationTwice()
    {
        $this->swapConfig([
            'rocketeer::hooks' => [],
        ]);

        $this->tasks->plugin(new DummyBeforeAfterNotifier($this->app));

        $this->expectOutputString('before_deployafter_deploy');
        $_SERVER['USER'] = 'Jean Eude';

        $this->task('Deploy')->fireEvent('before');
        $this->task('Deploy')->fireEvent('after');
    }

    public function testCanSendTheArrayFromCommandEvent()
    {
        $this->swapConfig([
            'rocketeer::hooks' => [],
        ]);

        $this->tasks->plugin(new DummyBeforeAfterArrayNotifier($this->app));

        $this->expectOutputString('dummy_array_notifierdummy_array_notifier');

        $this->task('Deploy')->fireEvent('before');
        $this->task('Deploy')->fireEvent('after');
    }
}
