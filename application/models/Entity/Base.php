<?php
namespace Entity;

abstract class Base implements \ArrayAccess
{
	function offsetExists($offset)
	{
		if(property_exists($this, $offset)){
			return true;
		}
		return false;
	}

	function offsetGet($offset)
	{
		return $this->{$offset};
	}

	function offsetSet($offset, $value)
	{
		$this->{$offset} = $value;
	}

	function offsetUnset($offset)
	{
		$this->{$offset} = null;
	}

}