<?php

class ZF_Acl_Panel extends Zend_Acl {

    public function __construct(){
        # modulos
        $model_modulo = new Panel_Model_Modulo();
        $resources = $model_modulo->listar();
        while($resource = $resources->fetch())
            $this->add(new Zend_Acl_Resource($resource->modulo_id));

        # roles
        $model_rol = new Panel_Model_Rol();
        $roles = $model_rol->listar();

        while($role = $roles->fetch())
            $this->addRole(new Zend_Acl_Role($role->rol_id));

        # rol modulos
        $model_rol_modulo = new Panel_Model_RolModulo();
        $rol_modulos = $model_rol_modulo->listar();

        while($rol_modulo = $rol_modulos->fetch())
            $this->allow($rol_modulo->rol_id, $rol_modulo->modulo_id);
    }
}

?>