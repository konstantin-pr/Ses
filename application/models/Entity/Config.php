<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\Config")
 * @Table(name="`config`")
 * @HasLifecycleCallbacks
 */
class Config
{

	/**
	 * @Id
	 * @Column(name="`name`", type="string", length=32)
	 */
	protected $name;
	/**
	 * @Column(name="`value`", type="string", length=255, nullable=true)
	 */
	protected $value;
	/**
	 * @Column(name="`created`", type="datetime")
	 */
	private $created;

	/**
	 * initialize entity
	 */
	function __construct()
	{
		$this->created = date_create();
	}

	/**
	 * Set value
	 *
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * Get value
	 *
	 * @return string $value
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Get created
	 *
	 * @return datetime $created
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * Get name
	 *
	 * @return string $name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * get readable string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->name;
	}

}
