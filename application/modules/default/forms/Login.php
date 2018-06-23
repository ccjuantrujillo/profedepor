<?php
class Default_Form_Login extends Zend_Form {
   public function init(){
       $this->setMethod('post');

       $username = $this->CreateElement('text','login_email')
            ->setLabel('E-mail:')
            ->addValidator('NotEmpty')
            ->setAttrib('class', 'txt_fld')
            ->setDecorators(array('ViewHelper', 'Description', 'Errors',
            # array('HtmlTag', array('tag' => 'div')),
           # array(array('label' => 'HtmlTag'), array('tag' => 'div', 'valign' => 'top')),
           array(array('data' => 'HtmlTag'), array('tag' => 'td', 'valign' => 'top')),
           array('Label', array('tag' => 'td', 'class' => 'lbl_frm')),
           array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
       ));

       $password = $this->CreateElement('text', 'login_password')
           ->setLabel('Password')
            ->setAttrib('class', 'txt_fld')
            ->setDecorators(array('ViewHelper', 'Description', 'Errors',
           array(array('data' => 'HtmlTag'), array('tag' => 'td', 'valign' => 'top')),
           array('Label', array('tag' => 'td', 'class' => 'lbl_frm')),
           array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
       ));

       $this->addElements(array($username, $password));

       $this->setDecorators(array('FormElements', 'Form',
           array(array('data' => 'HtmlTag'), array('tag' => 'table', 'width' => '350', 'border' => 0, 'cellspacing' => 0, 'cellpadding' => 0))
       ));

   }
}
