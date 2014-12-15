<?php
namespace Entity;

abstract class BaseModerable extends Base
{

    const STATUS_PENDING = 0;

    const STATUS_APPROVED = 1;

    const STATUS_REJECTED = 2;

    protected $statuses = array(
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected'
    );

    /**
     * @Column(type="string", length=1)
     */
    protected $status = self::STATUS_PENDING;

    function offsetSet($offset, $value)
    {
        if ($offset == 'status') {
            if (!array_key_exists($value, $this->statuses)) {
                throw new \InvalidArgumentException('Incorrect approved status passed');
            }
        }
        $this->{$offset} = $value;
    }

    public function getModerationStatus()
    {
        if (! array_key_exists((int) $this->status, $this->statuses)) {
            return 'Unknown';
        }
        return $this->statuses[(int) $this->status];
    }
}