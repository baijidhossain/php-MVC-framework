<?php

class Category extends Controller {

	public function Index( $path = "" ) {

		if ( ! empty( $path ) ) {
			return $this->details( urldecode( $path ) );
		}

		$this->data = [
			'page_title' => 'Categories',
		];

		$this->view = "category/index";

		return $this->response();
	}

	private function details( $path = "" ) {

		$category = $this->model->getCategory( $path );

		if ( empty( $category ) ) {

			Util::redirectBack();
		}

		$categoryArticles = $this->model->getCategoryArticles( $category['id'] );

	

		$this->data = [
			'page_title'       => $category['name'],
			'category'         => $category,
			'articles'         => $categoryArticles,
			'meta_keywords'    => $category['meta_keyword'],
			'meta_description' => $category['meta_description'],

		];

		$this->view = 'category/articles';

		$this->response();
	}
}
