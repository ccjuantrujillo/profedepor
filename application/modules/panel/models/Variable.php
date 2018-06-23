<?php

class Panel_Model_Variable extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_variable';

    public function init(){}

    public function save($data){        
        $variable_id = $data['variable_id'];
        unset($data['variable_id'], 
                $data['submit'],
                $data['accion'],
                $data['txt_tipovariable_id']);
        if($variable_id > 0){
            $data['modificacion'] = new Zend_Db_Expr('NOW()');
            $this->update($data, "variable_id = '$variable_id'");
        } else {
            $data['anterior'] = 0;
            $variable_id = $this->insert($data);
        }

        return $variable_id;
    }

    public function getVariable($variable_id){
        return $this->select()
            ->where('variable_id = ?', $variable_id)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }
    
    public function getVariableDefault($tipovariable_id){
         $select = $this->select()
                 ->where('estado=?',1)
                 ->where('tipovariable_id=?',$tipovariable_id)
                 ->limit(1);
         return $select->query()->fetch();
    }

    public function deleteVariable($where){
        $data = array(
            'modificacion' => new Zend_Db_Expr('NOW()'),
            'estado' => '0'
        );
        $this->update($data, $where);
    }

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('V' => $this->_name))
            ->join(array(
                'TV' => 'depor_tipovariable'
            ), 'V.tipovariable_id = TV.tipovariable_id', array(
                'tipovariable' => 'TV.descripcion'
            ))
            ->where('V.estado = ?', 1);

        if(isset($filter->tipovariable_id) && $filter->tipovariable_id > 0)
            $select->where('V.tipovariable_id = ?', $filter->tipovariable_id);

        return $select->query();
    }

    public function listarOpciones(stdClass $filter = null){
        $select = $this->select()
            ->from($this->_name, array('key' => 'variable_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);
        if(isset($filter->tipovariable_id) && $filter->tipovariable_id > 0)
            $select->where('tipovariable_id = ?', $filter->tipovariable_id);
        # exit($select);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }
}

