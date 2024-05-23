<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Helpers\Worker;


use App\Utils\Helpers\DB\AppDB;
use App\Utils\Helpers\DB\HDB;
use App\Utils\Helpers\DB\Model\MBase;
use App\Utils\Helpers\Formats\HDateTime;
use App\Utils\Helpers\UUID\UUID;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

/**
 * Class DJob
 * @property string   queue
 * @property string   worker
 * @property string   data
 * @property string   log
 * @property int      state
 * @property DateTime dt_start
 * @property DateTime dt_finish
 * @property DateTime dt_available
 * @property DateTime dt_create
 * @property int      try_count
 * @property float    progress
 * @package App\Utils\Helpers\Worker
 */
class DJob extends MBase {

    public const F_DT_CREATE = 'dt_create';
    public const F_QUEUE = 'queue';
    public const F_WORKER = 'worker';
    public const F_DATA = 'data';
    public const F_LOG = 'log';
    public const F_STATE = 'state';  // 0 - new, 1 - blocked (working), 2 - finish, 3 - fail
    public const F_DT_START = 'dt_start';
    public const F_DT_FINISH = 'dt_finish';
    public const F_DT_AVAILABLE = 'dt_available';
    public const F_TRY_COUNT = 'try_count';
    public const F_PROGRESS = 'progress';

    public const C_DATE_TIME_FORMAT_LOG = 'Y-m-d H:i:s';

    public const C_STATE_ACTIVE = 0;
    public const C_STATE_BLOCKED = 1;
    public const C_STATE_FINISHED = 2;
    public const C_STATE_FAILED = 3;
    public const C_STATE_CANCELED = 4;


    protected $table = 'jobs';

    protected $visible = [
        self::F_QUEUE,
        self::F_WORKER,
        self::F_DATA,
        self::F_LOG,
        self::F_STATE,     // 0 - new, 1 - blocked (working), 2 - finish, 3 - fail
        self::F_DT_CREATE,
        self::F_DT_START,
        self::F_DT_FINISH,
        self::F_DT_AVAILABLE,
        self::F_TRY_COUNT,
        self::F_PROGRESS,
    ];

    protected $fillable = [
        self::F_QUEUE,
        self::F_WORKER,
        self::F_DATA,
        self::F_LOG,
        self::F_STATE,     // 0 - new, 1 - blocked (working), 2 - finish, 3 - fail
        self::F_DT_CREATE,
        self::F_DT_START,
        self::F_DT_FINISH,
        self::F_DT_AVAILABLE,
        self::F_TRY_COUNT,
        self::F_PROGRESS,
    ];

    /**
     * Get titles of states
     * @return string[]
     */
    public static function getStateTitles() {
        return [
            self::C_STATE_ACTIVE   => 'Active',
            self::C_STATE_BLOCKED  => 'Blocked',
            self::C_STATE_FINISHED => 'Finished',
            self::C_STATE_FAILED   => 'Failed',
            self::C_STATE_CANCELED => 'Canceled',

        ];
    }

    /**
     * @param int $diffSeconds - shift by seconds
     * @return DateTime
     * @throws Exception
     */
    private static function getDateTime(int $diffSeconds) {
        $aDateTime = new DateTime();
        return $aDateTime->add(new DateInterval('PT' . $diffSeconds . 'S'));
    }

    /**
     * @param AppDB      $db
     * @param array|null $queue
     * @param int|null   $state
     * @param int        $limit
     * @param int        $skip
     * @return DJob[]|Collection
     */
    public static function getList(AppDB $db, ?array $queue, ?int $state, int $limit = 10, int $skip = 0) {
        $q = DJob::q($db);
        if ($queue !== null) {
            $q->whereIn(self::F_QUEUE, $queue);
        }
        if ($state !== null) {
            $q->where(self::F_STATE, $state);
        }
        $q->orderBy(self::F_DT_CREATE, 'desc');
        $cols   = array_filter(self::s_getVisible(), function ($el) {
            return $el !== self::F_LOG && $el !== self::F_DATA;
        });
        $cols[] = self::F_ID;
        // array_filter()
        return $q->limit($limit)->skip($skip)->get($cols);
    }

    /**
     * @param AppDB $db
     * @param       $id
     * @return DJob|Collection|null
     */
    public static function getById(AppDB $db, $id) {
        return DJob::q($db)->find($id);
    }

    /**
     * Add new job
     * @param AppDB  $db
     * @param string $queue
     * @param int    $delay
     * @param string $workerClass
     * @param        $data
     * @return bool
     * @throws Exception
     */
    public static function pushJob(AppDB $db, string $queue, int $delay, string $workerClass, $data) {

        $aDateTime = self::getDateTime($delay);

        $dataStr = serialize($data);

        $arr = [
            self::F_ID           => UUID::newV4(),
            self::F_QUEUE        => $queue,
            self::F_WORKER       => $workerClass,
            self::F_DATA         => $dataStr,
            self::F_LOG          => "Created: " . date(self::C_DATE_TIME_FORMAT_LOG),
            self::F_STATE        => self::C_STATE_ACTIVE,
            self::F_DT_CREATE    => new DateTime(), // HDateTime::toSQLDateTime(),
            self::F_DT_START     => null,
            self::F_DT_FINISH    => null,
            self::F_DT_AVAILABLE => $aDateTime, // HDateTime::toSQLDateTime(),
            self::F_TRY_COUNT    => 0,
            self::F_PROGRESS     => 0,
        ];


        return DJob::q($db)->insert($arr);
    }


    /**
     * @param AppDB $_db
     * @return array
     */
    public static function getCount(AppDB $_db) {
        $result = $_db->connection()->table(self::table())->select([
            self::raw("count(*) as cnt"),
            self::raw("sum( case when state=0 then 1 else 0 end ) as state0"),
            self::raw("sum( case when state=1 then 1 else 0 end ) as state1"),
            self::raw("sum( case when state=2 then 1 else 0 end ) as state2"),
            self::raw("sum( case when state=3 then 1 else 0 end ) as state3"),
            self::raw("sum( case when state=4 then 1 else 0 end ) as state4"),
        ])->get();
        return ((array)$result)[0];
    }


    /**
     * Start job again
     * @param int $delay
     * @throws Throwable
     */
    public function restartJob(int $delay) {
        $this->getConnection()->transaction(function () use ($delay) {
            $this->refresh();
            if ($this->state != self::C_STATE_BLOCKED) {
                $this->state        = 0;
                $this->dt_available = self::getDateTime($delay);
                $this->progress     = 0;
                $this->log          .= 'Restarted ' . date('Y-m-d H:i:s') . "\r\n";
                $this->save();
            }
        });
    }

    /**
     * Get next free job
     * @param AppDB       $db
     * @param array|null  $queue
     * @param bool        $block
     * @param string|null $jobId
     * @return DJob|null
     * @throws Throwable
     */
    public static function pullJob(AppDB $db, ?array $queue, $block = true, ?string $jobId = null) {

        $result = null;

        $db->transaction(function () use ($db, $queue, $block, &$result, $jobId) {

            // find next free object
            $q = DJob::q($db);
            if ($queue) {
                $q->whereIn(self::F_QUEUE, $queue);
            }
            if ($jobId) {
                $q->where(self::F_ID, $jobId);
            }
            $q->where(self::F_STATE, 0);
            $q->where(self::F_DT_AVAILABLE, '<', HDateTime::toSQLDateTime(new DateTime()));
            $q->orderBy(self::F_DT_AVAILABLE);
            /** @var DJob $job */
            $job = $q->first();

            if (!$job) {
                return null;
            }

            $result = $job;

            if ($block) {
                // write new state
                $job->state = self::C_STATE_BLOCKED;
                $job->try_count++;
                $job->dt_start = new DateTime();
                $job->log      .= 'Pull ' . date('Y-m-d H:i:s') . "\r\n";
                $job->save();
            }

        });

        return $result;

    }

    /**
     * Flash long time blocked working objects
     * @param AppDB    $db
     * @param string[] $queue
     * @param int      $timeoutSec
     * @return int
     * @throws Exception
     */
    public static function failByTimeout(AppDB $db, array $queue, int $timeoutSec) {

        $dt = self::getDateTime(-$timeoutSec);

        $q = DJob::q($db);
        if ($queue) {
            $q->whereIn(self::F_QUEUE, $queue);
        }
        $q->where(self::F_STATE, self::C_STATE_BLOCKED);
        $q->where(self::F_DT_START, '<', HDateTime::toSQLDateTime($dt));

        return $q->update([
            self::F_STATE        => 1,
            self::F_PROGRESS     => 0,
            self::F_DT_AVAILABLE => new DateTime(),
            self::F_LOG          => HDB::raw("concat(log,'Timeout " . date(self::C_DATE_TIME_FORMAT_LOG) . "',chr(13),chr(10))"),
        ]);
    }

    /**
     * Write success
     */
    public function successJob() {
        $this->state     = 2;
        $this->dt_finish = new DateTime();
        $this->progress  = 100;
        $this->log       .= "Success " . date(self::C_DATE_TIME_FORMAT_LOG);
        $this->save();
    }

    /**
     * Write as fail
     * @param string $message
     * @param int    $pauseStart
     * @param int    $maxTries
     * @return bool
     * @throws Exception
     */
    public function failJob(string $message, int $pauseStart, int $maxTries) {

        if ($this->try_count >= $maxTries) {
            // save as failed
            $this->state     = self::C_STATE_FAILED;
            $this->dt_finish = new DateTime();
            $this->log       .= "Fail " . date(self::C_DATE_TIME_FORMAT_LOG) . ": " . $message;
            return $this->save();
        } else {
            // save as active delayed
            $aDateTime          = self::getDateTime($pauseStart);
            $this->state        = self::C_STATE_ACTIVE;
            $this->dt_finish    = new DateTime();
            $this->dt_available = $aDateTime;
            $this->try_count++;
            $this->log .= "Fail " . date(self::C_DATE_TIME_FORMAT_LOG) . ": " . $message;
            return $this->save();
        }

    }

    /**
     * @param string $message
     * @throws Throwable
     */
    public function cancelJob(string $message) {

        $this->getConnection()->transaction(function () use ($message) {
            $this->refresh();
            if ($this->state <= 1) {  // active or blocked
                $this->state     = DJob::C_STATE_CANCELED;
                $this->dt_finish = new DateTime();
                $this->log       .= "Canceled " . date(self::C_DATE_TIME_FORMAT_LOG) . ": {$message}\r\n";
                $this->save();
            }
        });

    }

    /**
     * @param float $value
     * @return bool
     */
    public function setProgress(float $value) {
        $this->progress = $value;
        return $this->save();
    }

    /**
     * @param AppDB    $db
     * @param string[] $idArray
     * @return int
     */
    public static function deleteJobs(AppDB $db, array $idArray) {
        $q = DJob::q($db);
        $q->whereIn(self::F_ID, $idArray);
        return $q->delete();
    }

    /**
     * @param AppDB $db
     * @param       $state
     * @return mixed
     */
    public static function deleteJobsAll(AppDB $db, $state) {
        $q = DJob::q($db);
        $q->where(self::F_STATE, $state);
        return $q->delete();
    }


}
