<?php

class Flow_Gallery extends Flow {

    public function action_default() {
        Block_Head::addPageTitle('Галерея');
        
		Block_Flow_Gallery_Menu::process(array(), $this->Output);
		Block_Flow_Gallery_Own::process(array(), $this->Output);

        $action = $this->Input->get(Input_Http::INPUT_ROUTE)->get('action');
        if (intval($action) > 0) {
            $action = 'view';
        }
        
        if (!$this->existsAction($action)) {
            $action = 'list';
        }

        return $this->runChildFlow($action);
    }
    
    public function action_list() {
        Block_Head::addPageTitle('Найновіші');
        
        $Albums = Album_Mapper::getLatest();

//        $Album = Album_Mapper::getById($id);
//
//        if (!$Album) {
//            $this->runChildFlow('noalbum');
//        }
//
//        Block_Head::addPageTitle($Album->getTitle());

        $this->Output->bind('Albums', $Albums);
        return true;
    }

    public function action_view() {
        Block_Head::addPageTitle('Перегляд');

        $action = $this->Input->get(Input_Http::INPUT_ROUTE)->get('action');
        $id = $this->Input->get(Input_Http::INPUT_ROUTE)->get('step');
        if (intval($action) > 0) {
            $id = $action;
            $action = 'view';
        }

        $Album = Album_Mapper::getById($id);

        if (!$Album) {
            $this->runChildFlow('noalbum');
        }

        Block_Head::addPageTitle($Album->getTitle());

        $this->Output->bind('Album', $Album);
        return true;
    }

    public function action_noalbum() {
        return true;
    }

    public function action_noperm() {
        return true;
    }

    public function action_add() {
        Block_Head::addPageTitle('Додати галерею');

        if (!($post = $this->Input->get(Input_Http::INPUT_POST)) || $post->isEmpty()) {
            return true;
        }

        if (!User::curr()->isLogged()) {
            return $this->runChildFlow('noperm');
        }

        $Result = Album::add($post);

        if ($Result->error) {
            $this->Output->bind('errors', (array) $Result->error);
            return true;
        }

        $this->Output->header('Location: /gallery/' . $Result->value);

        return true;
    }

}
