<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\Copy")
 * @Table(name="copy_backup", uniqueConstraints={@UniqueConstraint(name="backup_time_indx", columns={"backup_time"})})
 * @HasLifecycleCallbacks
 */
class CopyBackup extends Base
{
	/**
	 * @Id @Column(type="bigint")
	 * @GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	protected $id;


	/**
	 * @Column(type="datetime")
	 * @var datetime $updated
	 */
    protected $backup_time;

    /**
     * * @Column(type="text")
     * @var string $data
     */
    protected $data;

	/**
	 * initialize entity
     * @gedmo:Timestampable(on="update")
	 */
	function __construct()
	{
		$this->backup_time = date_create();
	}

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Entity\datetime
     */
    public function getBackupTime()
    {
        return $this->backup_time;
    }

    /**
     * @param \Entity\datetime $backup_time
     */
    public function setBackupTime($backup_time)
    {
        $this->backup_time = $backup_time;
    }


}