<?php

namespace SimpleJsonDSL;

class SimpleJsonDSL
{
	public static function _string($string){
		return (string)$string;
	}

	public static function _int($int){
		return (int)$int;
	}

	public static function _float($float){
		return (double)$float;
	}

	public static function _array($array){
		return (array)$array;
	}

	public static function _object(array $object){
		return (object)$object;
	}

	public static function _bool($bool){
		return (bool)$bool;
	}

	public static function _null(){
		return null;
	}

	public static function args_as_keyvalue(){
		$args = func_get_args();
		return self::args_array_as_keyvalue($args);
	}

	public static function args_array_as_keyvalue(array $args){
		$return = array();
		foreach($args as $arg){
			$key = key($arg);
			$return[$key] = $arg[$key];
		}
		return $return;
	}

	public static function args_as_value(){
		$args = func_get_args();
		return self::args_array_as_value($args);
	}

	public static function args_array_as_value(array $args){
		return $args;
	}

	public static function args_as_keyvalue_or_value(){
		$args = func_get_args();
		if(func_num_args() === 1){
			return self::args_array_as_keyvalue($args);
		}
		return self::args_array_as_value($args);
	}

	public static function args_array_as_keyvalue_or_value(array $args){
		if(count($args) === 1){
			return self::args_array_as_keyvalue($args);
		}
		return self::args_array_as_value($args);
	}
}
