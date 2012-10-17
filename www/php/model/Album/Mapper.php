<?php

/**
 * Description of Album_Mapper
 *
 * @author Виталик
 */
class Album_Mapper {

    const TBL = 'albums';
    const LATEST_N = 10;

    protected static $tblFields = array('album_id', 'title', 'date_created', 'date_modified', 'user_id');

    protected function __construct() {
        
    }

    /**
     *
     * @var Album_Mapper
     */
    protected static $instance = null;

    /**
     * 
     * @return Album_Mapper
     */
    protected static function i() {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function add(Input $Data) {
        $values = array(
            'title' => trim($Data->get('title', '')),
            'user_id' => User::curr()->getUserId(),
        );

        $id = Fw_Db::i()->query()->insert(self::TBL, $values)->fetchRow();
        return $id;
    }

    /**
     * 
     * @param int $n
     * @param array $params
     * @return array
     */
    protected static function getList($n = self::LATEST_N, $params = array()) {
        $db = Fw_Db::i();
        $q = $db->query()->select()->from(self::TBL, self::$tblFields)->limit($n)->orderBy(array('date_created' => false));
        if (!empty($params['__where__'])) {
            foreach ($params['__where__'] as $where => $value) {
                $q->where($where, $value);
            } 
        }
        $list = $q->fetch();

        $result = array();
        foreach ($list as $row) {
            $result[] = new Album($row, self::i());
        }

        return $result;
    }

    public static function getLatest($n = self::LATEST_N) {
        return self::getList($n);
    }

    public static function getOwnN($n = self::LATEST_N) {
        return self::getList($n, array('__where__' => array('user_id = ?' => User::curr()->getUserId())));
    }

    /**
     * 
     * @param int $id
     * @return \Album|null
     */
    public static function getById($id) {
        $row = Fw_Db::i()->query()->select()->from(self::TBL, self::$tblFields)->where('album_id = ?', $id)->fetchRow();

        if ($row) {
            return new Album($row, new self());
        }

        return null;
    }

}
