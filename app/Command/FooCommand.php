<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Test;
use App\Model\User;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command
 */
class FooCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('test');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {


        $argument = $this->input->getArgument('name') ?? 'World';
//
//
        echo '开始';
        co(function(){
            $channel = new \Swoole\Coroutine\Channel();
            co(function() use($channel){
                sleep(2);
                $user = User::all();
                var_dump(date('Y-m-d H:i:s').'查询user完成');
            });

            co(function() use($channel){
               sleep(2);
               $test = Test::all();
               var_dump(date('Y-m-d H:i:s').'查询test完成');

            });

        });
//        co(function() use($argument){
//
//                $channel = new \Swoole\Coroutine\Channel();
//                for($i=1;$i<=50;$i++){
//                    co(function() use($channel,$argument,$i){
//                            sleep(1);
//    //                        $channel->push("Hello $argument $i!");
//                          $this->line("Hello $argument $i!", 'info');
//
//                    });
//                }
////                $this->line($channel->pop());
//
//
//        });


    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, '这里是对这个参数的解释']
        ];
    }


    /*
     * 我好想说
     */
    protected function tell(){

        //todo
        $tell = "其实,没有人生来就是天生一对。都是通过时间来慢慢磨合。在14亿人中其实相遇已经算是缘分了,";
    }
}
