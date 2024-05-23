<?php


namespace App\Utils\Helpers\Http;


use App\Utils\Helpers\Exception\AppException;
use Illuminate\Http\Request;

/**
 * Class HRequestInputTrait
 *
 * Трейт реализует методы для контроллера
 *  request() - возвращает объект запроса Request
 *  in() - доступ к параметрам запроса HRequestInputs
 *
 * @package App\Utils\Helpers\Http
 */
trait HControllerInputTrait {

    /**
     * Input request values driver
     * @var HRequestInputs|null
     */
    private ?HRequestInputs $_input = null;

    /**
     * @var Request|null
     */
    private ?Request $_request = null;


    /**
     * Request input values helper
     * @return HRequestInputs
     * @throws AppException
     */
    public function in() {
        if ($this->_input === null) {
            throw new AppException('Input not defined!');
        }
        return $this->_input;
    }

    /**
     * Return current Request object.
     * @return Request|null
     * @throws AppException
     */
    public function request() {
        if (!$this->_request) {
            throw new AppException('Request not defined!');
        }
        return $this->_request;
    }


    /**
     * @param Request $request
     * @param         $params
     */
    public function setRequest(Request $request, $params) {
        $this->_request = $request;
        $this->_input = new HRequestInputs($request, $params);
    }


}
