<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\TabApp")
 * @Table(name="`app`")
 */
class TabApp
{

	/**
	 * @Id
	 * @Column(type="string", length=32, nullable=false)
	 */
	protected $id;
	/**
	 * @Column(type="string", length=32, unique=true, nullable=false)
	 * @var string
	 */
	protected $name;
	/**
	 * @Column(type="string", length=1, unique=true, nullable=true)
	 * @var string
	 */
	protected $zone;
	/**
	 * @Column(type="text", nullable=true)
	 * @var string
	 */
	protected $config;
	/**
	 * @Column(type="text", nullable=true)
	 * @var string
	 */
	protected $theme;

	/**
	 * initialize properties
	 */
	function __construct()
	{
		$this->config = '{}';
		$this->theme = '{}';
	}

	/**
	 * get id
	 *
	 * @return string
	 */
	function getId()
	{
		return $this->id;
	}

	/**
	 * get name
	 *
	 * @return string
	 */
	function getName()
	{
		return $this->name;
	}

	/**
	 * get zone
	 *
	 * @return string
	 */
	function getZone()
	{
		return $this->zone;
	}

	/**
	 * get config
	 *
	 * @return string
	 */
	function getConfig()
	{
		return $this->config;
	}

	/**
	 * get theme
	 *
	 * @return string
	 */
	function getTheme()
	{
		return $this->theme;
	}

	/**
	 * set id
	 *
	 * @param string $value
	 * @return TabApp
	 */
	function setId($value)
	{
		$this->id = $value;
		return $this;
	}

	/**
	 * set name
	 *
	 * @param string $value
	 * @return TabApp
	 */
	function setName($value)
	{
		$this->name = $value;
		return $this;
	}

	/**
	 * set config
	 *
	 * @param string $value
	 * @return TabApp
	 */
	function setConfig($value)
	{
		$this->config = $value;
		return $this;
	}

	/**
	 * set theme
	 *
	 * @param string $value
	 * @return TabApp
	 */
	function setTheme($value)
	{
		$this->theme = $value;
		return $this;
	}

	/**
	 * get string representation
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->name . ' : zone (' . $this->zone . ')';
	}

}
