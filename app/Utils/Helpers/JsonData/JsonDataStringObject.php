<?php


namespace App\Utils\Helpers\JsonData;

/**
 * Class JsonDataStringObject
 *
 * Пример объекта, основанного на JsonData
 * Содержит только одно строковое свойство.
 * В этом примере видно , как присутствуют два
 * автоматически сгенерированных метода toArray() fromArray()
 *
 * @package App\Utils\Helpers\JsonData
 */
class JsonDataStringObject extends JsonData {

    public string $value;


	/** ApiData start **/

	 //region Generated fromArray() toArray() code

	public function toArray(){
		$arr = [];
		$arr['value'] = $this->value;
		return $arr;
	}


	public function fromArray(array $data){

		// value
		if(array_key_exists('value',$data) && !empty($data['value'])){
		$this->value = $data['value'];
		}

		return $this;
	}

	//endregion

	/** ApiData end **/
}
