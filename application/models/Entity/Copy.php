<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\Copy")
 * @Table(name="`copy`", uniqueConstraints={@UniqueConstraint(name="copy_messageid_locale_idx", columns={"messageid", "locale"})})
 * @HasLifecycleCallbacks
 */
class Copy extends Base
{
	/**
	 * @Id @Column(name="`id`", type="bigint")
	 * @GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	protected $id;

	/**
	 * @Column(name="`messageid`",type="string", length=100, nullable=false)
	 * @var string
	 */
    protected $messageid;

	/**
     * @Column(name="`message`", type="text", nullable=true)
	 * @var text
	 */
    protected $message;

	/**
	 * @Column(name="`locale`", type="string", length=5, nullable=true)
	 * @var string $locale
	 */
    protected $locale;

	/**
	 * @Column(name="`updated`", type="datetime")
	 * @var datetime $updated
	 */
    protected $updated;

	/**
	 * initialize entity
     * @gedmo:Timestampable(on="update")
	 */
	function __construct()
	{
		$this->updated = date_create();
	}

	/**
	 * Set copyKey
	 *
	 * @param string $value
	 */
	public function setMessageId($value)
	{
		$this->messageid = $value;
	}

	/**
	 * Get copyKey
	 *
	 * @return string
	 */
	public function getMessageid()
	{
		return $this->messageid;
	}

	/**
	 * Set copyText
	 *
	 * @param text $value
	 */
	public function setMessage($value)
	{
		$this->message = $value;
	}

	/**
	 * Get copyText
	 *
	 * @return text
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Set locale
	 *
	 * @param string $locale
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
	}

	/**
	 * Get locale
	 *
	 * @return string $locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @PreUpdate
	 * @PrePersist
	 */
	public function preUpdate()
	{
		$this->updated = date_create();
	}

	/**
	 * Get updated
	 *
	 * @return datetime
	 */
	public function getUpdated()
	{
		return $this->update;
	}

	/**
	 * Get id
	 *
	 * @return bigint $id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * get readable string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->messageid;
	}
}
