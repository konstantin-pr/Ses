<?php
namespace Entity;
/**
 * @see https://crs-api.liveworld.com/
 * @see https://crs-api.liveworld.com/LiveWorld_Content_Review_System-API_Reference.pdf
 * @HasLifecycleCallbacks
 */
abstract class LwModerable extends BaseModerable
{

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_WAITING_TO_SEND = 3;

    const UPDATED_BY_USER = 0;
    const UPDATED_BY_ADMIN = 1;
    const UPDATED_BY_LW = 2;
    
    protected $updated_by = array(
        self::UPDATED_BY_ADMIN => 'admin',
        self::UPDATED_BY_USER => 'user',
        self::UPDATED_BY_LW => 'LiveWorld',
    );
    
    public function __construct()
    {
        $this->statuses[self::STATUS_WAITING_TO_SEND] = 'Waiting to send';
    }
    
    /**
     * @Column(type="string", length=128, nullable=true, unique=true)
     */
    protected $lw_mod_id;
    
    /**
     * @Column(type="string", length=128, nullable=true)
     */
    protected $lw_contentId;
    
    /**
     * @Column(type="string", length=128, nullable=true, unique=true)
     */
    protected $lw_trackingId;
    
    /**
     * This field supports text, image or video.
     * @return string image|video|text;
     */
    abstract public function getLwType();
    
    /**
     *  Url on photo | image | video
     */
    abstract public function getLwMediaUrl();
    
    /**
     * Url on post ect
     */
    abstract public function getLwUrl();
    
    /**
     * The body text of the content
     * @return string post test
     */
    abstract public function getLwBody();
    
    /**
     * The subject line of the content
     * @return string
     */
    abstract public function getLwSubject();
    
    /**
     * The author of the content
     * @return string
     */
    abstract public function getLwUserName();
    
    /**
     * The ID for this specific request. If two different people
     * report a message as abusive, they are assigned the
     * same content ID, but different tracking IDs.
     * Note: It is very important that all requests have unique
     * tracking IDs.
     */

    protected $statuses = array(
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_WAITING_TO_SEND => 'Waiting',
    );

    public function getLwTrackingId($new = false)
    {
        if($new == true) {
            $this->lw_trackingId = md5($this->id . time());
        }
        return $this->lw_trackingId;
    }
    
    /**
     * The content identification parameter.
     * @return string
     */
    public function getLwContentId()
    {
        $this->lw_contentId = md5($this->id);
        return $this->lw_contentId;
    }
    
}
