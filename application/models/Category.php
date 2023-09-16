<?php

class CategoryModel extends Model {

	public function getCategory( $path ) {
		return $this->db->query( "SELECT c.id, c.name,du.meta_keyword, du.meta_description FROM dynamic_url AS du JOIN blog_category AS c ON du.item_id = c.id WHERE du.controller = 'Category'AND du.method='Details' AND du.path = ?", [ $path ] )->fetchArray();
	}

	public function getChildCategories( $parent_id ) {

		return $this->db->query( "SELECT ac.id ,ac.name, du.path FROM blog_category AS ac JOIN dynamic_url AS du ON ac.id=du.item_id WHERE ac.parent_id=? AND ac.status = 1 AND du.controller = 'Category' AND du.method = 'Details'", [ $parent_id ] )->fetchAll();
	}

	public function getCategoryArticles( $cat_id ) {

		$this->db->data_limit = 10;

		$data =  $this->db->dataQuery( "SELECT a.title,
										       a.thumb,
										       a.published,
										       a.intro,
										       SUBSTRING(ExtractValue(a.body, '//text()'), 1, 200) AS body,
										       du.path,
											   u.name
										FROM blog_article AS a
										JOIN dynamic_url AS du ON du.item_id = a.id
										JOIN user as u ON u.id = a.created_by
										WHERE a.status=1
										  AND du.controller = 'Article'
										  AND du.method = 'Details'
										  AND a.id IN (SELECT article_id FROM `blog_category_relation` WHERE category_id = ?)
										  AND a.published <= ?
										ORDER BY a.published DESC",
			[ $cat_id, TIMESTAMP ] );

		$pagination = Util::Pagination($data['item_count'], $data['page_number'], $data['item_limit']);

		return array_merge($data, $pagination);
	}
}
