<?php

class Album {

    /**
     * 
     * @param Input $data
     * @return Result
     */
    public static function add(Input $data) {
        if (($Result = static::validate($data)) && $Result->error) {
            return $Result;
        }

        if ($id = Album_Mapper::add($Result->value)) {
            return new Result($id);
        }

        return new Result(null, 'Виникла помилка. Спробуйте, будь ласка, пізніше');
    }

    /**
     * 
     * @param Input $Data
     * @return \Result
     */
    protected static function validate(Input $Data) {
        if (!($title = $Data->get('title')) || !trim($title)) {
            return new Result(NULL, array('Поле "Назва" пусте'));
        }

        return new Result($Data);
    }

    /**
     *
     * @var array
     */
    protected $data;
    
    /**
     *
     * @var Album_Mapper
     */
    protected $Mapper;

    public function __construct($data, Album_Mapper $Mapper) {
        $this->data = new Input($data);
        $this->Mapper = $Mapper;
    }
    
    public function getTitle() {
        return $this->data->get('title');
    }

}