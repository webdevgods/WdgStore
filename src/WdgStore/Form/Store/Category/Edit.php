<?php
namespace WdgStore\Form\Store\Category;

class Edit extends Base
{
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));
    }
}