<?php

class Tag extends Controller {

	public function Index() {

		$search_condition = "";
		$params           = [];

		if ( ! empty( $_GET['search'] ) ) {
			$search_condition = "WHERE t.name LIKE '%?%' ";
			$params[]         = trim( $_GET['search'] );
		}

		$tags = $this->model->getTags( $search_condition, $params );

		$this->data = [
			'page_title' => 'Tags',
			'tags'       => $tags,
		];


		$this->view = 'tags/index';

		return $this->response();
	}

	public function Add() {

		if ( ! empty( $_POST['name'] ) ) {

			$duplicate = $this->model->checkDublicate( trim( $_POST['name'] ) );

			if ( $duplicate > 0 ) {

				$this->setMessage( 'error', 'Tag name already exist' );
				Util::redirectBack();

			} else {
				$inserted = $this->model->Add( $_POST['name'] );

				if ( $inserted ) {

					$this->setMessage( 'success', 'Tag successfully added' );

				} else {

					$this->setMessage( 'error', 'Failed to insert tag' );
				}
			}


			Util::redirectBack();
		}

		$this->data = [
			'action' => 'Add',
		];

		$this->view = 'tags/modal';

		return $this->response();
	}

	public function Delete( $id = 0 ) {

		if ( ! $id ) {

			$this->setMessage( 'error', 'Something went wrong.' );
			Util::redirectBack();
		}

		$checkArticle = $this->model->checkArticle( $id );

		if ( ! empty( $checkArticle ) ) {

			$this->setMessage( 'error', 'Tag is in use' );

			Util::redirectBack();
		}

		$delete = $this->model->Delete( $id );


		if ( $delete ) {
			$this->setMessage( 'success', 'Tag has been Deleted' );
		} else {
			$this->setMessage( 'error', 'Failed to delete Tag' );
		}

		Util::redirectBack();
	}

	public function Edit( $id ) {

		$edit = $this->model->Edit( $id );

		$this->data = [
			'action' => 'Edit',
			'edit'   => $edit,
		];

		$this->view = 'tags/modal';

		return $this->response();
	}

	public function Update() {

		if ( ! empty( $_POST['name'] ) ) {

			$data = [
				'name' => $_POST['name'],
				'id'   => $_POST['id'],
			];

			$updated = $this->model->Update( $data );

			if ( $updated ) {

				$this->setMessage( 'success', 'Tag successfully Updated' );
			} else {
				$this->setMessage( 'error', 'Failed to update Tag' );
			}

		} else {
			$this->setMessage( 'error', 'Tag name is required' );
		}

		Util::redirectBack();
	}
}
