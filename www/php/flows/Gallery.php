<?php

namespace App\Flow;
use \App\Flow;
use \App\Block\Head;
use \Core\Input;

class Gallery extends Flow {

	public function action_default() {
		Head::addPageTitle('Альбоми');
		Head::addJsLink(Head::JS_GALLERY);

		\App\Block\Flow\Gallery\Menu::process(array(), $this->Output);
		\App\Block\Flow\Gallery\Own::process(array(), $this->Output);

		$action = $this->Input->get(Input\Http::INPUT_ROUTE)->get('action');
		if (intval($action) > 0) {
			$action = 'view';
		}

		if (!$this->existsAction($action)) {
			$action = 'list';
		}

		$this->runChildFlow($action);
	}

	public function action_list() {
		Head::addPageTitle('Найновіші');

		$Albums = Album_Mapper::getLatest();

		$this->Output->bind('Albums', $Albums);

		return true;
	}

	public function action_view() {
		Head::addPageTitle('Перегляд');

		$action = $this->Input->get(Input\Http::INPUT_ROUTE)->get('action');
		$id     = $this->Input->get(Input\Http::INPUT_ROUTE)->get('step');
		if (intval($action) > 0) {
			$id = $action;
		}

		$Album = Album_Mapper::getById($id);

		if (!$Album) {
			$this->runChildFlow('noalbum');
		}

		Head::addPageTitle($Album->getTitle());

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
		Head::addPageTitle('Додати альбом');

		if (!($post = $this->Input->get(Input\Http::INPUT_POST)) || $post->isEmpty()) {
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

	public function action_edit() {
		Head::addPageTitle('Редагування');

		$id = $this->Input->get(Input\Http::INPUT_ROUTE)->get('step');
		if (!$id) {
			$this->Output->header('Location: /gallery');

			return;
		}
		$Album = Album_Mapper::getById($id);

		if (!User::curr()->isLogged() || !$Album || User::curr()->getUserId() != $Album->getUserId()) {
			return $this->runChildFlow('noperm');
		}

		Head::addJsLink(Head::JS_GALLERY_UPLOAD);

		$this->Output->bind('album', $Album);

		if (!($post = $this->Input->get(Input\Http::INPUT_POST)) || $post->isEmpty()) {
			return true;
		}

		$Result = Album::edit($post);

		if ($Result->error) {
			$this->Output->bind('errors', (array) $Result->error);

			return true;
		}

		$this->Output->header('Location: /gallery/' . $Result->value);

		return true;
	}

}
