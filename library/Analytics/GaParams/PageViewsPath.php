<?php
namespace Library\Analytics\GaParams;

class PageViewsPath extends GaParams
{
    /**
     * TODO: not uses ?
     * TODO: update this...
     */
    public function getCacheKey()
    {
    }


    public function __construct($callParams)
    {
        parent::__construct($callParams);
    }

    public function getFilterGoals()
    {
        //print_r(static::$parcesXmlParams); die;
        if(is_null($this->category)) return false;
        if(is_null($this->action) && is_null($this->label)) {
            $filter = '';
//            foreach (static::$parcesXmlParams['event'] as $event) {
//                if(!isset($event['action']) && !isset($event['label']))
                    $filter.=\Zend_Gdata_Analytics_DataQuery::DIMENSION_EVENT_CATEGORY . '==' . $event['category'].',';
//            }
            return rtrim($filter,',');

        }
        return \Zend_Gdata_Analytics_DataQuery::DIMENSION_EVENT_CATEGORY . '==' . $this->category;
    }
}
