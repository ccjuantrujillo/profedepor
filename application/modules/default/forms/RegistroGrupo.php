<?php

class Default_Form_RegistroGrupo extends Zend_Form
{

    private function rename(Zend_Form_Element $file){
        if( ! $file->isUploaded())
            return false;

        $name = $this->_helper->UniqId();
        $name .= '.' . array_pop(explode('.', $file->getFileName()));

        return $name;
    }

    public function init()
    {
        $path = Zend_Registry::get('path');

        $this->setName('registro')
            ->setAttrib('enctype', 'multipart/form-data');

        $descripcion = new Zend_Form_Element_Text('nombre');
        $descripcion->setLabel('Nombre:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $datos = new Zend_Form_Element_Text('descripcion');
        $datos->setLabel('Descripcion:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $foto = new Zend_Form_Element_File('foto');
        $foto->setLabel('Foto:')
              ->setDestination($path->destino_foto)
              ->addFilter('Rename', array('source' => $this->foto, 'target' => $this->rename($foto), 'overwrite' => true))
              ->addValidator('Count', false, 1)
              ->addValidator('Size', false, 307200) # 102400
              # ->setValueDisabled(true)
              # ->setMultiFile(1)
              ->addValidator('Extension', false, 'jpg,png,gif');

        $tipo = new Zend_Form_Element_Radio('tipo');
        $tipo->setLabel('Tipo:')
                 ->setRequired(true)
                 ->addMultiOption('0', 'Publico')
                 ->addMultiOption('1', 'Privado');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Crear Grupo');

        $this->addElements(array($descripcion, $datos, $foto, $tipo, $submit));
    }


}

