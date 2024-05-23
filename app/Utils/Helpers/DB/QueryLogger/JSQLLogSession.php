<?php


namespace App\Utils\Helpers\DB\QueryLogger;


use App\Utils\Helpers\JsonData\JsonData;

class JSQLLogSession extends JsonData {


    public string $id;

    /**
     * @var string
     */
    public string $login;

    /**
     * @var string
     */
    public string $uri;

    /**
     * @var string
     */
    public string $method;

    /**
     * @var string
     */
    public string $action;


    /**
     * @var string
     */
    public string $date;

    /**
     * @var float
     */
    public float $sqlTime;

    /**
     * @var float
     */
    public float $queryTime;

    /**
     * @var JSQLLogQuery[]
     */
    public array $queries;

	/** ApiData start **/

	 //region Generated fromArray() toArray() code

	public function toArray(){
		$arr = [];
		$arr['id'] = $this->id;
		$arr['login'] = $this->login;
		$arr['uri'] = $this->uri;
		$arr['method'] = $this->method;
		$arr['action'] = $this->action;
		$arr['date'] = $this->date;
		$arr['sqlTime'] = $this->sqlTime;
		$arr['queryTime'] = $this->queryTime;
		$arr['queries'] = is_array($this->queries)
                        ?array_map(function($el){return $el->toArray();},$this->queries)
                        :null;
		return $arr;
	}


	public function fromArray(array $data){

		// id
		if(array_key_exists('id',$data) && !empty($data['id'])){
		$this->id = $data['id'];
		}


		// login
		if(array_key_exists('login',$data) && !empty($data['login'])){
		$this->login = $data['login'];
		}


		// uri
		if(array_key_exists('uri',$data) && !empty($data['uri'])){
		$this->uri = $data['uri'];
		}


		// method
		if(array_key_exists('method',$data) && !empty($data['method'])){
		$this->method = $data['method'];
		}


		// action
		if(array_key_exists('action',$data) && !empty($data['action'])){
		$this->action = $data['action'];
		}


		// date
		if(array_key_exists('date',$data) && !empty($data['date'])){
		$this->date = $data['date'];
		}


		// sqlTime
		if(array_key_exists('sqlTime',$data) && !empty($data['sqlTime'])){
		$this->sqlTime = $data['sqlTime'];
		}


		// queryTime
		if(array_key_exists('queryTime',$data) && !empty($data['queryTime'])){
		$this->queryTime = $data['queryTime'];
		}


		// queries
		if(array_key_exists('queries',$data) && !empty($data['queries'])){

		//
		$this->queries = \App\Utils\Helpers\DB\QueryLogger\JSQLLogQuery::fromArrayArrayStatic($data['queries']);
		}

		return $this;
	}

	//endregion

	/** ApiData end **/
}
