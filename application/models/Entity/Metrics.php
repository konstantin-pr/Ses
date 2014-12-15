<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\Metrics")
 * @Table(name="metrics",
 * indexes={@index(name="metrics_created_idx", columns={"created"})},
 * uniqueConstraints={@UniqueConstraint(name="created_name_idx", columns={"created", "name"})}
 * )
 */
class Metrics extends Base
{
	/**
	 * @Id @Column(type="bigint")
	 * @GeneratedValue
	 */
	protected $id;
	/**
	 * @Column(type="datetime")
	 */
	protected $created;
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $name;
	/**
	 * @Column(type="decimal", scale=2, nullable=true)
	 */
	protected $countTotal;
	/**
	 * @Column(type="decimal", scale=2, nullable=true)
	 */
	protected $countUnique;
}
