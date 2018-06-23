<?php

class Panel_Form_Login extends Zend_Form
{
    private $_defaultDecorators = array(
       'ViewHelper',
       'Description',
       'Errors',
       array(array('data'=>'HtmlTag'), array('tag' => 'td')),
       array('Label', array('tag' => 'td')),
       array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
    );

   public function init(){
       $this->setMethod('post');

       $username = $this->createElement('text', 'username')
                        ->setLabel('Usuario ');

       $username->setRequired(true)
        ->setDecorators($this->_defaultDecorators);

       $password = $this->createElement('password', 'password')
                        ->setLabel('Clave');

       $password->setRequired(true)
        ->setDecorators($this->_defaultDecorators);

       $submit=$this->CreateElement('submit', 'submit')
                    ->setLabel('Login ')
                    ->setAttrib('class', 'aceptarlog');

       $submit->setDecorators(array(
           'ViewHelper',
           'Description',
           'Errors', array(array('data'=>'HtmlTag'), array('tag' => 'td',
           'colspan'=>'2','class'=>'tr_login')),
           array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
       ));

       $this->addElements(array(
           $username,
           $password,
           $submit
       ));

       $this->setDecorators(array(
           'FormElements',
           array(array('data'=>'HtmlTag'),array('tag'=>'table')),
           'Form'
       ));

   }

}