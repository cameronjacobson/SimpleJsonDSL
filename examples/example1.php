<?php

// Taken from : http://www.elasticsearch.org/guide/reference/query-dsl/bool-filter/
// as POC

require_once(dirname(__DIR__).'/vendor/autoload.php');

use SimpleJsonDSL\SimpleJsonDSL as simple;
class myDSL
{

	public static $dsl;
	public static $debug = false;
	public static function getDSL(){
		return function($fn) {
			function filtered(){
				return array('filtered'=>simple::args_array_as_keyvalue(func_get_args()));
			}
			function filter(){
				return array('filter'=>simple::args_array_as_keyvalue(func_get_args()));
			}
			function _bool(){
				return array('bool'=>simple::args_array_as_keyvalue(func_get_args()));
			}
			function query(){
				return array('query'=>simple::args_array_as_keyvalue(func_get_args()));
			}
			function queryString(){
				$args = func_get_args();
				return array('queryString'=>$args[0]);
			}
			function must(){
				return array('must'=>simple::args_array_as_keyvalue_or_value(func_get_args()));
			}
			function must_not(){
				return array('must_not'=>simple::args_array_as_keyvalue_or_value(func_get_args()));
			}
			function should(){
				return array('should'=>simple::args_array_as_keyvalue_or_value(func_get_args()));
			}
			function term(){
				$args = func_get_args();
				return array('term'=>array($args[0]=>$args[1]));
			}
			function _range(){
				$args = func_get_args();
				return array('range'=>array($args[0]=>array('from'=>$args[1],'to'=>$args[2])));
			}
			return json_encode(call_user_func($fn),self::$debug ? JSON_PRETTY_PRINT : null);
		};
	}

	public static function exec($fn){
		$dsl = self::getDSL();
		return $dsl($fn);
	}
}

myDSL::$debug = true;

echo myDSL::exec(function(){
	return filtered(
		query(
			queryString(array(
				'default_field'=>'message',
				'query'=>'elasticsearch'
			))
		),
		filter(
			_bool(
				must(
					term('tag','wow')
				),
				must_not(
					_range('age',10,20)
				),
				should(
					term('tag','sometag'),
					term('tag','sometagtag')
				)
			)
		)
	);
});

/**

Generates the following JSON:

{
	"filtered" : {
		"query" : {
			"queryString" : { 
				"default_field" : "message", 
				"query" : "elasticsearch"
			}
		},
		"filter" : {
			"bool" : {
				"must" : {
					"term" : { "tag" : "wow" }
				},
				"must_not" : {
					"range" : {
						"age" : { "from" : 10, "to" : 20 }
					}
				},
				"should" : [
					{
						"term" : { "tag" : "sometag" }
					},
					{
						"term" : { "tag" : "sometagtag" }
					}
				]
			}
		}
	}
}

 */
