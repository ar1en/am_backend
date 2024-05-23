<?php


namespace App\Utils\Helpers\Worker\Test;


use App\Utils\Helpers\Exception\AppException;
use App\Utils\Helpers\Worker\DJob;
use App\Utils\Helpers\Worker\Worker;


/**
 * Class WorkerTest
 *
 * Test worker with timeout 100 sec
 *
 * @package App\Utils\Helpers\Worker\Test
 */
class WorkerTest extends Worker {


    /**
     *
     * Execute test work.
     *
     * @return bool
     * @throws AppException
     */
    public function handle() {

        /** @var WorkerTestData $data */
        $data = $this->getData();
        print_r($data);
        echo "Worker do sleep {$data->time} seconds...";

        for($i=0;$i<$data->time;$i++){
            $this->progress($i,$data->time);
            echo "\r\n\tNext step ".$this->getJob()->progress."% with state ".$this->getJob()->state;
            if ( $this->getJob()->state!==DJob::C_STATE_BLOCKED ){
                echo "\r\n!Canceled";
                throw new AppException('Canceled');
            }
            sleep(1);
        }

        echo "\r\nFinished success!";

        return true;

    }

}
