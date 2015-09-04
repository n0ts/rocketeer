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

use Rocketeer\Dummies\DummyAfterNotifier;
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

        $this->expectOutputString('before_deployafter_deployafter_rollback');
        $_SERVER['USER'] = 'Jean Eude';

        $this->task('Deploy')->fireEvent('before');
        $this->task('Deploy')->fireEvent('after');
        $this->task('Rollback')->fireEvent('after');
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

    public function testCanSendTheAfterAfterNotication()
    {
        $this->swapConfig([
            'rocketeer::hooks' => [],
        ]);

        $this->tasks->plugin(new DummyAfterNotifier($this->app));
        $branch     = $this->connections->getRepositoryBranch();
        $connection = $this->connections->getConnection();
        $release_path = $this->releasesManager->getCurrentReleasePath();
        $this->expectOutputString("Jean Eude finished deploying \"{$branch}\" on \"{$connection}\" at \"{$release_path}\"");
        $_SERVER['USER'] = 'Jean Eude';

        $this->task('After')->fireEvent('after');
    }
}
