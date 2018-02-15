<?php

namespace Utils;


class ArrayObj implements \ArrayAccess, \Iterator {
	
	protected  $array = array();
	protected $position = 0;

	public function __construct(array $array = []) {
		$this->array = $array;
		$this->position = 0;
	}

	public function suffle(){
		shuffle($this->array);
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->array[] = $value;
		} else {
			$this->array[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->array[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->array[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->array[$offset]) ? $this->array[$offset] : null;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function current() {
		return $this->array[$this->position];
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function valid() {
		return isset($this->array[$this->position]);
	}
}