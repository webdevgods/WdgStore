<?php
use WdgStore\Form;

return array(
    'factories' => array(
        'wdgstore_product_add_form' => function(\Zend\Form\FormElementManager $sm){
            $UserService    = $sm->getServiceLocator()->get("zfcuseradmin_mapper");
            $form           = new Form\Store\Product\Add($UserService);
            
            $form->setInputFilter(new \WdgStore\Filter\Store\Product\Add());

            return $form;
        },
        'wdgstore_product_edit_form' => function(\Zend\Form\FormElementManager $sm){
            $UserService    = $sm->getServiceLocator()->get("zfcuseradmin_mapper");
            $form           = new Form\Store\Product\Edit($UserService);
            
            $form->setInputFilter(new \WdgStore\Filter\Store\Product\Edit());

            return $form;
        },
        'wdgstore_category_add_form' => function(){
            
            $form = new Form\Store\Category\Add();
            
            $form->setInputFilter(new \WdgStore\Filter\Store\Category\Add());

            return $form;
        },
        'wdgstore_category_edit_form' => function(){
            $form = new Form\Store\Category\Edit();
            
            $form->setInputFilter(new \WdgStore\Filter\Store\Category\Edit());

            return $form;
        },
        'wdgstore_product_category_form' => function(\Zend\Form\FormElementManager $sm){
            $StoreService   = $sm->getServiceLocator()->get("wdgstore_service_store");
            $form           = new Form\Store\Product\Category($StoreService->getAllCategories());
            
            $form->setInputFilter(new \WdgStore\Filter\Store\Product\Category());

            return $form;
        },
        'wdgstore_product_add_image_form' => function(\Zend\Form\FormElementManager $sm){
            $form = new Form\Store\Product\AddImage();
            
            $form->setInputFilter(new \WdgStore\Filter\Store\Product\AddImage());

            return $form;
        },
    )
);