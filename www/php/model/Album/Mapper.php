<?php

/**
 * Description of Album_Mapper
 *
 * @author Виталик
 */
class Album_Mapper {

    const TBL = 'albums';

    protected static $tblFields = array('album_id', 'title', 'date_created', 'date_modified', 'user_id');

    protected function __construct() {
        
    }

    public static function add(Input $Data) {
        $values = array(
            'title' => trim($Data->get('title', '')),
            'user_id' => User::curr()->getUserId(),
        );

        $id = Fw_Db::i()->query()->insert(self::TBL, $values)->fetchRow();
        return $id;
    }

    public static function getLastN($n, $params = array()) {
        $db = Fw_Db::i();
        $db->query()->select()->from(self::TBL, self::$tblFields)->where()->limit(2);
    }

    public static function getById($id) {
        $row = Fw_Db::i()->query()->select()->from(self::TBL, self::$tblFields)->where('album_id = ?', $id)->fetchRow();

        if ($row) {
            return new Album($row, new self());
        }

        return null;
    }

}
