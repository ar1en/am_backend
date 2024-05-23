<?php


namespace App\Utils\Helpers\Exception;


use App\Utils\Helpers\Debug\HDebug;
use App\Utils\Helpers\JsonData\JsonData;
use Throwable;

/**
 * Типовой ответ приложения json с ошибкой
 *
 * @package App\Utils\Helpers\Exception
 */
class ExceptionJsonResponse extends JsonData {

    /**
     * Типовой ответ сервера долже соедржать поле result
     * В нем содержится базовый ответ Error или Ok
     * Это избыточно, но оставляем для совместимости.
     * Для определения, что вернулась ошибка - достаточно проверять httpStatus==200
     * @var string
     */
    public string $result = "Error"; // else "ok"

    /**
     * Код ошибки. Тот что приходит вместе с Throwable.
     *
     * @var int
     */
    public int $code = 500;

    /**
     * Сообщение об ошибке, которое увидит обычный пользователь.
     *
     * @var string
     */
    public string $message = "";

    /**
     * Детальная информация об ошибке для debug режима
     * @var
     */
    public $debug;


    /**
     * ExceptionJsonResponse constructor.
     *
     * @param Throwable $exception
     */
    public function __construct(Throwable $exception) {
        $this->code    = $this->getHttpStatusCode($exception);
        $this->message = $this->getHttpMessage($exception);
        if (HDebug::isDebug()) {
            $this->debug = ExceptionRender::renderJson($exception);
        }
    }

    /**
     * Make http status code.
     *
     * @param Throwable $exception
     * @return int
     */
    public function getHttpStatusCode(Throwable $exception) {
        $code = $exception->getCode();
        if ($code >= 100 && $code <= 700) {
            return $code;
        }
        return 522;
    }

    /**
     * @param Throwable $exception
     * @return string
     */
    public function getHttpMessage(Throwable $exception) {
        if ($exception instanceof AppExceptionViewable) {
            return $exception->getMessage();
        }
        return "Системная ошибка. Необходимо обратиться в службу поддержки.";
    }

	/** ApiData start **/

	 //region Generated fromArray() toArray() code

	public function toArray(){
		$arr = [];
		$arr['result'] = $this->result;
		$arr['code'] = $this->code;
		$arr['message'] = $this->message;
		$arr['debug'] = $this->debug;
		return $arr;
	}


	public function fromArray(array $data){

		// result
		if(array_key_exists('result',$data) && !empty($data['result'])){
		$this->result = $data['result'];
		}


		// code
		if(array_key_exists('code',$data) && !empty($data['code'])){
		$this->code = $data['code'];
		}


		// message
		if(array_key_exists('message',$data) && !empty($data['message'])){
		$this->message = $data['message'];
		}


		// debug
		if(array_key_exists('debug',$data) && !empty($data['debug'])){
		$this->debug = $data['debug'];
		}

		return $this;
	}

	//endregion

	/** ApiData end **/
}
