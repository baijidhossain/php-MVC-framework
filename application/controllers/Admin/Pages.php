<?php

class Pages extends Controller {

	public function Index() {

		$pages = $this->model->getPages();

		$this->data = [
			'page_title' => 'Pages',
			'pages'      => $pages,
		];

		$this->view = 'pages/index';

		return $this->response();
	}

	public function Add() {
		// Add page
		if ( ! empty( $_POST ) ) {

			$path = $this->validatePage();

			if ( $this->model->checkExists( $path ) ) {
				$this->setMessage( 'error', 'Page already exists' );
				Util::redirectBack();
			}

			$data = [
				'title'            => trim( $_POST['title'] ),
				'body'             => trim( $_POST['body'] ),
				'path'             => trim( $path ),
				'meta_keyword'     => ! empty( $_POST['meta_keyword'] ) ? trim( $_POST['meta_keyword'] ) : '',
				'meta_description' => ! empty( $_POST['meta_description'] ) ? trim( $_POST['meta_description'] ) : '',
				'status'           => $_POST['status'],
			];

			$addPage = $this->model->add( $data );

			if ( $addPage ) {
				$this->setMessage( 'success', 'New Page added successfully' );
			} else {
				$this->setMessage( 'error', 'Something went wrong' );
			}

			Util::redirectBack();
		}


		$this->data = [
			'page_title' => 'Add New Page',
		];

		$this->view = 'pages/add';

		return $this->response();
	}

	private function validatePage(): string {

		$validated = Util::checkPostValues( [ 'title', 'body', 'path', 'status' ] );

		if ( ! $validated ) {
			$this->setMessage( 'error', 'Fill all the required fields' );
			Util::redirectBack();
		}

		if ( ! in_array( $_POST['status'], [ 0, 1 ] ) ) {
			$this->setMessage( 'error', 'Invalid Status' );
			Util::redirectBack();
		}

		return Util::stringToPath( empty( $_POST['path'] ) ? trim($_POST['title']) : trim($_POST['path']) );
	}

	public function Edit( $id ) {
		if ( empty( $id ) ) {
			$this->setMessage( 'error', 'Invalid ID' );
			Util::redirectBack();
		}

		$page = $this->model->getPage( $id );

		if ( empty( $page ) ) {
			$this->setMessage( 'error', 'Invalid ID' );
			Util::redirectBack();
		}

		$this->data = [
			'page_title' => 'Edit Page',
			'page'       => $page,
		];

		$this->view = 'pages/edit';

		$this->response();
	}

	public function Update() {
		// update page
		if ( ! empty( $_POST ) ) {

			$path = $this->validatePage();

			$data = [
				'id'               => $_POST['id'],
				'title'            => trim( $_POST['title'] ),
				'body'             => trim( $_POST['body'] ),
				'path'             => trim( $path ),
				'meta_keyword'     => ! empty( $_POST['meta_keyword'] ) ? trim( $_POST['meta_keyword'] ) : '',
				'meta_description' => ! empty( $_POST['meta_description'] ) ? trim( $_POST['meta_description'] ) : '',
				'status'           => $_POST['status'],
			];

			$updatePage = $this->model->update( $data );

			if ( $updatePage ) {
				$this->setMessage( 'success', 'Page updated successfully' );
			} else {
				$this->setMessage( 'error', 'Something went wrong' );
			}

			Util::redirectBack();
		}
	}

	public function Delete( $id = 0 ) {

		if ( ! $id ) {
			$this->setMessage( 'error', 'Invalid id' );
			Util::redirectBack();
		}

		$delete = $this->model->delete( $id );

		if ( $delete ) {
			$this->setMessage( 'success', 'Page deleted successfully' );
		} else {
			$this->setMessage( 'error', 'Something went wrong' );
		}

		Util::redirectBack();
	}
}
