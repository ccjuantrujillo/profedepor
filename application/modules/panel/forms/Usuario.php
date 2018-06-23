<?php
class Panel_Form_Usuario extends Zend_Form
{
    public function post($isUpdate, $model_usuario, $usuario = null, array $data){
        $elements = array('email' => 'HasUsuarioEmail', 'login' => 'HasUsuario');
        foreach($elements as $element => $validator){
            $validate = ( ! $isUpdate);

            if(isset($usuario)){
                if($usuario->{$element} != $data[$element])
                    $validate = true;
            }

            if($validate && $validator){
                $class = 'ZF_Validate_' . ucfirst($validator);
                $this->getElement($element)
                    ->addValidator(new $class($model_usuario));
            }
        }

        $element = 'password';
        $required_password = true;

        if($isUpdate)
            $required_password = strlen($data[$element]) > 0;

        $this->getElement($element)
            ->setRequired($required_password);

        return $this;
    }

    public function init()
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'usuario_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('text', 'nombre', array(
            'label'    => 'Nombres:',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $this->addElement('text', 'apellido', array(
            'label'    => 'Apellidos:',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $this->addElement('text', 'login', array(
            'label'    => 'Usuario:',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $this->addElement('password', 'password', array(
            'label'    => 'Clave:',
            'filters'  => array('StringTrim'),
            'validators' => array(array('StringLength', false, 6))
        ));

        $this->addElement('text', 'email', array(
            'label'    => 'Email:',
            'required' => true,
            'filters'  => array('StringTrim'),
            'validators' => array('EmailAddress')
        ));

        $model_rol = new Panel_Model_Rol();

        $this->addElement('MultiCheckbox', 'roles', array(
            'label'    => 'Roles:',
            'required' => true,
            'multioptions' => $model_rol->listarOpcion(),
            'filters'  => array('StringTrim')
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class' => 'aceptarlog3')
        ));
    }
}
?>
