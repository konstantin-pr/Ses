<?php
namespace Library\Analytics\GaParams;

class Events extends GaParams
{
    public $category = null;
    public $action = null;
    public $label = null;
    public $filters = null;

    /**
     * TODO: not uses ?
     */
    public function getCacheKey()
    {
        $key = sprintf('_c_%s_a_%s_l_%s',$this->category,$this->action,$this->label);
        return $key;
    }

    public function setFilterCategory(&$filter)
    {
        if(is_null($this->category)) return false;
        $filter[] = 'eventCategory==' . $this->category;
    }

    public function setFilterAction(&$filter)
    {
        if(is_null($this->action)) return false;
        $filter[] = 'eventAction==' . $this->action;
    }

    public function setFilterLabel(&$filter)
    {
        if(is_null($this->label)) return false;
        $filter[] = 'eventLabel==' . $this->label;
    }


    public function __construct($callParams)
    {
        parent::__construct($callParams);
        $this->category = isset($callParams->category) ? $callParams->category : NULL;
        $this->action = isset($callParams->action) ? $callParams->action : NULL;
        $this->label = isset($callParams->label) ? $callParams->label : NULL;
        $this->filters = isset($callParams->filters) ? $callParams->filters : NULL;
    }

    public function chCategory()
    {
        if (!empty($this->category))
            return 'category';
        return false;
    }

    public function chAction()
    {
        if (!empty($this->action))
            return 'action';
        return false;
    }

    public function chLabel()
    {
        if (!empty($this->label))
            return 'label_'.$this->label;
        return false;
    }
}
