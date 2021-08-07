<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReadOnlyBase
{
	protected $data = [];

	public function all(){

		return $this->data;
	}

	public function get($id){

		return $this->data[$id];
	}

	public function getID($name){

		foreach ($this->data as $key => $value){

			if($key == $name)
				return $value;
		}

		return '';

	}

	public function getIDCaseInsensitive($name){

		foreach ($this->data as $key => $value){

			if( strtoupper($key) == strtoupper($name) )
				return $value;
		}

		return '';

	}


	public function getNameById($id){

		foreach ($this->data as $key => $value){

			if($value == $id)
				return $key;
		}

		return '';

	}



}
