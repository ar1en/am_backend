<?php


namespace App\Utils\Helpers\DB\QueryLogger;


use App\Utils\Helpers\JsonData\JsonData;

class JSQLLogQuery extends JsonData {

    public float $time;

    public string $sql;

    public string $bindings;

	/** ApiData start **/

	 //region Generated fromArray() toArray() code

	public function toArray(){
		$arr = [];
		$arr['time'] = $this->time;
		$arr['sql'] = $this->sql;
		$arr['bindings'] = $this->bindings;
		return $arr;
	}


	public function fromArray(array $data){

		// time
		if(array_key_exists('time',$data) && !empty($data['time'])){
		$this->time = $data['time'];
		}


		// sql
		if(array_key_exists('sql',$data) && !empty($data['sql'])){
		$this->sql = $data['sql'];
		}


		// bindings
		if(array_key_exists('bindings',$data) && !empty($data['bindings'])){
		$this->bindings = $data['bindings'];
		}

		return $this;
	}

	//endregion

	/** ApiData end **/
}
