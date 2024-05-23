<?php


namespace App\Utils\Helpers\Worker;


use App\Utils\Helpers\JsonData\JsonData;
use Throwable;

/**
 * Class Worker
 *
 * Base class for workers.
 *
 * @package App\Utils\Helpers\Worker
 */
abstract class Worker {

    const C_RESULT_NONE    = 0;
    const C_RESULT_SUCCESS = 1;
    const C_RESULT_FAIL    = 2;
    const C_RESULT_CANCEL  = 3;


    private WorkerFactory $_factory;

    /**
     * Current job object
     * @var DJob
     */
    private DJob $_job;

    /**
     * Current data object
     * @var JsonData|mixed
     */
    private JsonData $_data;


    /**
     * Result of handle, $this->success():0 $this->fail():1 $this->cancel():2
     *
     * @var int
     */
    private int $_result;

    /**
     * @var string
     */
    private string $_resultMessage = '';

    /**
     * Time to break process with progress
     * @var int
     */
    private int $timeout=-1;

    /**
     * Time of last update = time()
     * @var float|int
     */
    private float $updateTime  = 0;

    /**
     * Progress update min time - seconds
     * @var float|int
     */
    private float $updateDelay = 3000;

    /**
     * Progress value diff for update at DB
     * @var float|int
     */
    private float $updateDelta = 1; // %


    /**
     * Worker constructor.
     * @param WorkerFactory $factory
     * @param DJob          $job
     * @param int           $timeout - no limit <=0
     */
    private function __construct(WorkerFactory $factory,DJob $job,int $timeout) {
        $this->_factory = $factory;
        $this->_job = $job;
        $this->_data = unserialize($job->data);
        $this->setTimeout($timeout);
        $this->_result = self::C_RESULT_SUCCESS;
    }

    /**
     * @return DJob
     */
    public function getJob() {
        return $this->_job;
    }

    /**
     * @return JsonData
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * @return int
     */
    public function getResultCode() {
        return $this->_result;
    }

    /**
     * @return string
     */
    public function getResultMessage() {
        return $this->_resultMessage;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout) {
        $this->timeout = $timeout>0?time()+$timeout:-1;
    }

    /**
     * Process worker
     */
    public abstract function handle();

    /**
     * Do cancel this job.
     * @param string $message
     */
    public function cancel(string $message) {
        $this->_result = self::C_RESULT_CANCEL;
        $this->_resultMessage = $message;
    }

    /**
     * Do fail this job
     * @param string $message
     */
    public function fail(string $message) {
        $this->_result = self::C_RESULT_FAIL;
        $this->_resultMessage = $message;
    }

    /**
     * Do success this job
     * @param string|null $message
     */
    public function success(?string $message) {
        $this->_result = self::C_RESULT_SUCCESS;
        $this->_resultMessage = $message ? $message : '';
    }



    /**
     * Return true when job is active, false - canceled
     * @param float     $value
     * @param float|int $max
     * @return bool
     * @throws Throwable
     */
    public function progress(float $value,float $max = 100){

        $job = $this->getJob();

        // set progress value
        $newValue = $value/$max;
        if ( abs($newValue-$job->progress)>$this->updateDelta ){
            $job->setProgress($newValue);
        }

        // check timeout
        if ( $this->timeout>0 && $this->timeout<time() ){
            $this->_factory->cancelJob($job->id);
        }

        // refresh job data
        $time = microtime(true);
        if ( $time-$this->updateTime > $this->updateDelay ){
            $job->refresh();
        }


        return  ( $job->state == DJob::C_STATE_BLOCKED );
    }



}
