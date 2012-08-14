<?php

class Flow_Gallery extends Flow {

    public function action_default() {
        Block_Head::addPageTitle('Галерея');

        $action = $this->Input->get(Input_Http::INPUT_ROUTE)->get('action');
        if (intval($action) > 0) {
            $action = 'view';
        }

        if ($action) {
            return $this->runChildFlow($action);
        }

//        $list = 
//        Album_Factory::f();

        $this->Output->bind('result', 'OK');

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
            return true;
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
