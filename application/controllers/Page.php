<?php

class Page extends Controller {

	public function Index( $path = '' ) {

		$page             = [];
		$title            = '';
		$meta_keywords    = '';
		$meta_description = '';

		if ( ! empty( $path ) ) {

			$page = $this->db->query( "SELECT pages.*,du.* FROM pages JOIN dynamic_url AS du ON du.item_id = pages.id WHERE du.controller = 'Page' AND du.method = 'Index' AND pages.status = 1 AND path = ?", urlencode( $path ) )->fetchArray();

			if ( ! empty( $page ) ) {

				$title = $page['title'];

				$meta_keywords = $page['meta_keyword'];

				$meta_description = $page['meta_description'];
			}
		}

		$this->data = [
			'page_title'       => $title,
			'page'             => $page,
			'meta_keywords'    => $meta_keywords,
			'meta_description' => $meta_description,

		];

		$this->view = "Page";

		$this->response();
	}
}
