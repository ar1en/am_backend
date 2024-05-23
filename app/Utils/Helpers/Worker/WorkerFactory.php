<?php


namespace App\Utils\Helpers\Worker;


use App\PVU\Exceptions\BPMHandler;
use App\Utils\Helpers\DB\AppDB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

/**
 * Class WorkerFactory
 * @package App\Utils\Helpers\Worker
 */
class WorkerFactory {

    /**
     * Jobs database
     * @var AppDB
     */
    private AppDB $_db;

    /**
     * Processed queue
     * @var array|null
     */
    private ?array $_queue;


    /**
     * WorkerFactory constructor.
     * @param AppDB             $db
     * @param string|array|null $queue
     */
    public function __construct(AppDB $db, $queue = null) {
        $this->_db = $db;
        if (is_string($queue)) {
            $queue = explode(',', $queue);
        }
        $this->_queue = is_array($queue) ? array_filter(array_map(function ($l) { return trim($l); }, $queue)) : null;
    }

    /**
     * @param Throwable $ex
     * @return string
     */
    private static function logException(Throwable $ex) {
        return BPMHandler::reportError($ex);
    }

    /**
     * @param int $state
     * @param int $limit
     * @param int $skip
     * @return DJob[]|Collection
     */
    public function getJobs(int $state,int $limit=10,int $skip=0) {
        return DJob::getList($this->_db, $this->_queue, $state,$limit,$skip);
    }

    /**
     * @param string $id
     * @return DJob|Collection|null
     */
    public function getJob(string $id) {
        return DJob::getById($this->_db, $id);
    }

    /**
     * @param string|array|null $queue
     * @param bool              $block
     * @param string|null       $jobId
     * @return null|DJob
     * @throws Throwable
     */
    public function pullJob($queue, $block,?string $jobId) {
        return DJob::pullJob($this->_db, $queue ? $queue : $this->_queue, $block,$jobId);
    }

    /**
     * @param null $id
     * @param int  $delay
     * @return DJob|Collection|null
     * @throws Throwable
     */
    public function restartJob($id, int $delay) {
        $job = DJob::getById($this->_db, $id);
        if ($job) {
            $job->restartJob($delay);
        }
        return $job;
    }

    /**
     * Add new job
     * @param string $queue -
     * @param int    $delay - delay before start in seconds
     * @param string $workerClass
     * @param mixed  $data
     * @return bool
     * @throws Exception
     */
    public function pushJob(string $queue, int $delay, string $workerClass, $data) {
        return DJob::pushJob($this->_db, $queue, $delay, $workerClass, $data);
    }

    /**
     * Sate cancel state for job
     * @param string $id
     * @return DJob
     * @throws Throwable
     */
    public function cancelJob(string $id) {
        /** @var DJob $job */
        $job = DJob::getById($this->_db, $id);
        if ($job) {
            $job->cancelJob('by User');
        }
        return $job;
    }

    /**
     * @param string[] $idArray
     * @return int
     */
    public function deleteJobs(array $idArray) {
        return DJob::deleteJobs($this->_db, $idArray);
    }

    /**
     * @param $state
     * @return mixed
     */
    public function deleteJobsAll($state) {
        return DJob::deleteJobsAll($this->_db, $state);
    }

    /**
     * @param string[] $queue
     * @param int      $timeout
     * @return int
     * @throws Exception
     */
    public function failByTimeout(array $queue, int $timeout) {
        return DJob::failByTimeout($this->_db, $queue, $timeout);
    }

    /**
     * Process next job at queue
     * @param array       $queue
     * @param int         $tries
     * @param int         $delay
     * @param int         $timeout
     * @param string|null $jobId
     * @return int|void
     * @throws Throwable
     */
    public function processNext(array $queue, int $tries, int $delay, int $timeout,?string $jobId=null) {

        // get next job
        $job = $this->pullJob($queue, true,$jobId);
        if (!$job) {
            return Worker::C_RESULT_NONE;
        }

        // Get worker class
        try {
            /** @var Worker $cls */
            $cls = $job->worker;
            /** @var Worker $worker */
            $worker = new $cls($this, $job, $timeout);
            $worker->handle();
        } catch (Throwable $ex) {
            $log = self::logException($ex);
            $job->failJob($log, $delay, $tries);
            return Worker::C_RESULT_FAIL;
        }

        // check if worker result is fail
        if ($worker->getResultCode() === Worker::C_RESULT_FAIL) {
            $job->failJob($worker->getResultMessage(), $delay, $tries);
        }

        // check if worker result is fail
        if ($worker->getResultCode() === Worker::C_RESULT_CANCEL) {
            $job->cancelJob($worker->getResultMessage());
        }

        // success
        if ($worker->getResultCode() === Worker::C_RESULT_SUCCESS) {
            $job->successJob();
        }

        return $worker->getResultCode();
    }


    /**
     * Return count of records ['all'=>0, 'active'=>0, 'failed'=>0, 'canceled'=>0, 'blocked'=>0, ]
     * @return array
     */
    public function getStatistic(){
        return DJob::getCount($this->_db);
    }



}
