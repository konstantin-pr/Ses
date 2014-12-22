<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\User")
 * @Table(name="`user`", 
 * uniqueConstraints={@UniqueConstraint(name="email_unique",columns={"email"})},
 * indexes={@Index(name="search_email_idx", columns={"email"})})
 * @HasLifecycleCallbacks
 */
class User extends BaseModerable
{
        /**
         * @Id
         * @Column(type="bigint", nullable=false, name="`id`")
         * @GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @Column(type="string", length=256, nullable=false)
         * @var string
         */
        protected $first_name;

        /**
         * @Column(type="string", length=256, nullable=false)
         * @var string
         */
        protected $last_name;

        /**
         * @Column(type="string", length=254, unique=true, nullable=false)
         * @var string
         */
        //according to RFC 3696 max length of email must be not more than 254
        // http://www.rfc-editor.org/errata_search.php?rfc=3696
        // 
        protected $email;

        /**
         * @Column(type="datetime", nullable=false)
         */
        protected $birth_date;

        /**
         * @Column(type="boolean", nullable=false, options={"default":false})
         * @var boolean
         */
        protected $toc_accepted = false;

        /**
         * @Column(type="boolean", nullable=false, options={"default":false})
         * @var boolean
         */
        protected $rules_accepted;

        /**
         * @Column(type="boolean", nullable=false, options={"default":false})
         * @var boolean
         */
        protected $receive_emails;


        /**
         * @Column(type="boolean", nullable=false, options={"default":false})
         * @var boolean
         */
        protected $is_winner;

        /**
         * @OneToOne(targetEntity="Gift")
         **/
        protected $gift;

        /**
         * @Column(name="`date_win`", type="datetime", nullable=true)
         */
        protected $date_win;

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
        //function __construct($first_name, $last_name, $email, $birth_date, $toc_accepted, $rules_accepted, $receive_emails)
        function __construct()
        {
    	    $this->created = new \DateTime();
    	    $this->updated = new \DateTime();
        }

    /**
     * get id
     *
     * @return bigint
     */
    function getId()
    {
    	return $this->id;
    }

    /**
     * set id
     *
     * @param bigint $value
     * @return User
     */
    function setId($value)
    {
        return $this->id = $value;
        return $this;
    }

    /**
     * get first_name
     *
     * @return string
     */
    function getFirstName()
    {
    	return $this->first_name;
    }

   /**
     * set first_name
     *
     * @param string $value
     * @return User
     */
    function setFirstName($value)
    {
        $this->first_name = $value;
        return $this;
    }


    /**
     * get last_name
     *
     * @return string
     */
    function getLastName()
    {
    	return $this->last_name;
    }

   /**
     * set last_name
     *
     * @param string $value
     * @return User
     */
    function setLastName($value)
    {
        $this->last_name = $value;
        return $this;
    }

    /**
     * get email
     *
     * @return string
     */
    function getEmail()
    {
    	return $this->email;
    }

   /**
     * set email
     *
     * @param string $value
     * @return User
     */
    function setEmail($value)
    {
        $this->email = $value;
        return $this;
    }



    /**
     * get birth_date
     *
     * @return \Entity\datetime
     */
    function getBirthDate()
    {
    	return $this->birth_date;
    }

   /**
     * set birth_date
     *
     * @param datetime $value
     * @return User
     */
    function setBirthDate($value)
    {
        $this->birth_date = $value;
        return $this;
    }


    /**
     * get toc_accepted
     *
     * @return boolean
     */
    function getTocAccepted()
    {
	   return $this->toc_accepted;
    }

   /**
     * set toc_accepted
     *
     * @param boolean $value
     * @return User
     */
    function setTocAccepted($value)
    {
        $this->toc_accepted = $value;
        return $this;
    }


    /**
     * get rules_accepted
     *
     * @return boolean
     */
    function getRulesAccepted()
    {
	   return $this->rules_accepted;
    }

   /**
     * set rules_accepted
     *
     * @param boolean $value
     * @return User
     */
    function setRulesAccepted($value)
    {
        $this->rules_accepted = $value;
        return $this;
    }


    /**
     * get receive_emails
     *
     * @return boolean
     */
    function getReceiveEmails()
    {
	   return $this->receive_emails;
    }


    /**
     * set receive_emails
     *
     * @param boolean $value
     * @return User
     */
    function setReceiveEmails($value)
    {
	   $this->receive_emails = $value;
	   return $this;
    }



    /**
     * get created
     *
     * @return datetime
     */
    function getCreated()
    {
	   return $this->created;
    }


    /**
     * get updated
     *
     * @return datetime
     */
    function getUpdated()
    {
    	return $this->updated;
    }

    /**
     * set updated
     *
     * @param datetime $value
     * @returr User
     */
    function setUpdated($value)
    {
    	$this->updated = $value;
    	return $this;
    }


    /**
     * get date_win
     *
     * @return datetime
     */
    function getDateWin()
    {
        return $this->date_win;
    }

    /**
     * set date_created
     *
     * @param datetime $value
     * @returr User
     */
    function setDateWinn($value)
    {
        $this->date_win = $value;
        return $this;
    }


    /**
     * @PostUpdate
     */
    public function postUpdate()
    {
        $this->setUpdated(new \DateTime());
    }

    /**
     * get winner
     *
     * @return boolean
     */
    function getWinner()
    {
        return $this->winner;
    }

    /**
     * set winner
     *
     * @param boolean $value
     * @return User
     */
    function setWinner($value)
    {
    $this->winner = $value;
    return $this;
    }
}