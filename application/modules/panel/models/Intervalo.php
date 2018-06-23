<?php

class Panel_Model_Intervalo extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_intervalo';

    public function init() {

    }

    public function save($data) {

        $intervalo_id = $data['intervalo_id'];
        unset($data['intervalo_id'],
                $data['submit'],
                $data['accion'],
                $data['tipovariable_id'],
                $data['txt_tipovariable_id'],
                $data['txt_variable_id']);
        if ($intervalo_id > 0) {
            $data['modificacion'] = new Zend_Db_Expr('NOW()');
            $this->update($data, "intervalo_id = '$intervalo_id'");
        } else {
            $intervalo_id = $this->insert($data);
        }

        return $intervalo_id;
    }

    public function getIntervalo($intervalo_id) {
        return $this->select()
                ->where('intervalo_id = ?', $intervalo_id)
                ->where('estado = ?', 1)
                ->query()->fetch();
    }

    public function delete($where) {
        $data = array(
            'estado' => 0,
            'modificacion' => new Zend_Db_Expr('NOW()')
        );

        $this->update($data, $where);
    }

    public function listar(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('I' => $this->_name))
                        ->join(array(
                            'V' => 'depor_variable'
                                ), 'I.variable_id = V.variable_id', array(
                            'variable' => 'V.descripcion'
                        ))
                        ->where('I.estado = ?', 1);

        if (isset($filter->variable_id) && $filter->variable_id > 0)
            $select->where('I.variable_id = ?', $filter->variable_id);

        # exit($select);

        return $select->query();
    }

    public function existeVariable($variable_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('variable_id=?', $variable_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

}

