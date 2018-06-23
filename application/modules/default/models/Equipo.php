<?php

class Default_Model_Equipo extends Zend_Db_Table {

    protected $_name = "depor_equipo";

    public function getEquipo($id) {
        $id = (int) $id;
        $row = $this->fetchRow('equipo_id=' . $id);
        if (!$row) {
            throw new Exception("No se encuentra la fila $id");
        }
        return $row->toArray();
    }

    public function listarOpciones(stdClass $filter = null, $required = true) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('de' => 'depor_equipo'), array('key' => 'equipo_id'))
                        ->join(array('dc' => 'depor_club'), 'de.club_id=dc.club_id', array('value' => 'dc.descripcion'))
                        ->where('de.estado = ?', 1);
        if (isset($filter->torneo_id) && $filter->torneo_id > 0)
            $select->where("de.torneo_id=?", $filter->torneo_id);
        $all = $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
        if ($required)
            array_unshift($all, array('key' => 0, 'value' => 'SELECCIONE'));
        return $all;
    }

    public function addEquipo($torneo, $club) {
        $data = array(
            'torneo_id' => $torneo,
            'club_id' => $fase
        );
        $this->insert($data);
    }

    public function updateEquipo($id, $torneo, $club) {
        $data = array(
            'torneo_id' => $torneo,
            'club_id' => $club
        );
        $this->update($data, 'equipo_id=' . (int) $id);
    }

    public function deleteEquipo($id) {
        $this->delete('equipo_id=' . (int) $id);
    }

}