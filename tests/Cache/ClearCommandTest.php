<?php

use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Foundation\Application;
use Mockery as m;

class ClearCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testClearWithNoStoreOption()
    {
        $command = new ClearCommandTestStub(
            $cacheManager = m::mock('Illuminate\Cache\CacheManager')
        );

        $cacheRepository = m::mock('Illuminate\Contracts\Cache\Repository');

        $app = new Application();
        $command->setLaravel($app);

        $cacheManager->shouldReceive('store')->once()->with(null)->andReturn($cacheRepository);
        $cacheRepository->shouldReceive('flush')->once();

        $this->runCommand($command);
    }

    public function testClearWithStoreOption()
    {
        $command = new ClearCommandTestStub(
            $cacheManager = m::mock('Illuminate\Cache\CacheManager')
        );

        $cacheRepository = m::mock('Illuminate\Contracts\Cache\Repository');

        $app = new Application();
        $command->setLaravel($app);

        $cacheManager->shouldReceive('store')->once()->with('foo')->andReturn($cacheRepository);
        $cacheRepository->shouldReceive('flush')->once();

        $this->runCommand($command, ['store' => 'foo']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testClearWithInvalidStoreOption()
    {
        $command = new ClearCommandTestStub(
            $cacheManager = m::mock('Illuminate\Cache\CacheManager')
        );

        $cacheRepository = m::mock('Illuminate\Contracts\Cache\Repository');

        $app = new Application();
        $command->setLaravel($app);

        $cacheManager->shouldReceive('store')->once()->with('bar')->andThrow('InvalidArgumentException');
        $cacheRepository->shouldReceive('flush')->never();

        $this->runCommand($command, ['store' => 'bar']);
    }

    protected function runCommand($command, $input = [])
    {
        return $command->run(new Symfony\Component\Console\Input\ArrayInput($input), new Symfony\Component\Console\Output\NullOutput());
    }
}

class ClearCommandTestStub extends ClearCommand
{
    public function call($command, array $arguments = [])
    {
        //
    }
}
