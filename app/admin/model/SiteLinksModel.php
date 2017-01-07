<?php
namespace App\Admin\Model;

use Library\Core\PDOModel;

class SiteLinksModel extends PDOModel
{
    public $primaryKey = 'id';
    public $foreignKey = '';


    public function add($data)
    {
        $q = $this->insertInto($this->getTable(), $data)->ignore();
        return $q->execute();
    }

    public function up($data, $id)
    {
        $q = $this->update($this->getTable())->set($data)->where(array('id' => $id));
//        echo $q->getQuery();
        return $q->execute();
    }

    public function del($id)
    {
        $q = $this->delete($this->getTable())->where(array('id' => $id));
        return $q->execute();
    }
}
