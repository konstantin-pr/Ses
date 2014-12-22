<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\Gift")
 * @Table(name="`gift`")
 */
class Gift extends BaseModerable
{
    /**
     * @Id
     * @Column(type="bigint", nullable=false, name="`id`")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(name="`winning_time`", type="datetime", nullable=false)
     */
    protected $winning_time;


    /**
     * @OneToOne(targetEntity="User", inversedBy="gift")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;


    /**
     * @Column(name="`created`", type="datetime")
     */
    protected $created;

    /**
     * @Column(name="`updated`", type="datetime")
     */
    protected $updated;


    /**
	 * initialize properties
	 */
	function __construct($winning_time)
	{
		$this->winning_time = $winning_time;
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
	}

}