<?php

class Zend_View_Helper_ComboUbigeo extends Zend_View_Helper_Abstract
{
    public function ComboUbigeo(Zend_Db_Statement_Pdo $rs, $selected = null){
        $html = '';
        if(0 >= $rs->rowCount())
            return '<option value="00" label="---">---</option>';

        while($item = $rs->fetch()){
            $html .= '<option value="' . $item['key'] . '" label="' . $item['value'] . '"';

            if($item['key'] == $selected)
                $html .= ' selected="true"';

            $html .= '>' . $item['value'] . '</option>';
        }

        return $html;
    }
}