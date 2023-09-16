<?php

class ArticleModel extends Model {

	public function Articles( $search, $params ) {
		$data = $this->db->dataQuery(
			"SELECT a.*, (SELECT GROUP_CONCAT(c.name) FROM `blog_category_relation` AS r JOIN blog_category AS c ON r.category_id = c.id WHERE r.article_id = a.id) AS categories FROM blog_article AS a $search ORDER BY a.id DESC",
			$params
		);

		$pagination = Util::pagination( $data['item_count'], $data['page_number'], $data['item_limit'] );

		return array_merge( $data, $pagination );
	}

	public function addArticle( $data ) {
		try {

			$this->db->beginTransaction();

			$article       = [ $data['title'], $data['intro'], $data['body'], $data['status'], $_SESSION['userid'], $data['published'], TIMESTAMP ];
			$sql           = $this->db->query( "INSERT INTO blog_article (title,intro,body,status,created_by,published,created) VALUES (?,?,?,?,?,?,?)", $article );
			$articleLastId = $sql->lastInsertID();

			//Multiple Category Article relation
			if ( $articleLastId ) {
				foreach ( $data['category'] as $categoryID ) {
					$ccr = $this->db->query( "INSERT INTO blog_category_relation (category_id,article_id) VALUES (?,?)", [ $categoryID, $articleLastId ] );
				}
			} //End Multiple Category Article relation

			//Multiple tag relation
			if ( ! empty( $data['tag'] ) ) {
				foreach ( $data['tag'] as $tagID ) {
					$tc = $this->db->query( "INSERT INTO blog_tag_relation (tag_id,article_id) VALUES (?,?)", [ $tagID, $articleLastId ] );
				}
			} //end Multiple tag relation

			//dynamic url

			$this->db->query( "INSERT INTO dynamic_url (item_id, meta_keyword, meta_description, controller,method,path) VALUES (?,?,?,?,?,?)", [ $articleLastId, $data['meta_keyword'], $data['meta_description'], 'Article', 'Details', $data['path'] ] );

			//dynamic url

			$this->db->commit();

			return $articleLastId;
		} catch ( Exception $e ) {

			$this->db->Rollback();

			return false;
		}
	}

	public function categoryRelation( $id ) {
		return $this->db->query( "SELECT acr.category_id,acr.article_id FROM blog_category AS ac JOIN blog_category_relation AS acr ON ac.id=acr.category_id WHERE acr.article_id=?", [ $id ] )->fetchAll();
	}

	public function tagRelation( $id ) {
		return $this->db->query( "SELECT * FROM blog_tag as t JOIN blog_tag_relation AS tr ON t.id = tr.tag_id WHERE tr.article_id=?", [ $id ] )->fetchAll();
	}

	public function update( $data ) {

		try {
			$this->db->beginTransaction();

			$editdata = [ $data['title'], $data['intro'], $data['html_body'], $data['status'], $data['published'], TIMESTAMP, $data['id'] ];
			$this->db->query( "UPDATE blog_article SET title=?,intro=?,body=?,status=?,published=?,updated=? WHERE id =?", $editdata );

			//Multiple Category Article relation
			$this->db->query( "DELETE FROM blog_category_relation WHERE article_id = ?", [ $data['id'] ] );

			foreach ( $data['category'] as $categoryId ) {
				$this->db->query( "INSERT INTO blog_category_relation (category_id,article_id) VALUES (?,?)", [ $categoryId, $data['id'] ] );
			}

			//Multiple tag relation
			$this->db->query( "DELETE FROM blog_tag_relation WHERE article_id = ?", [ $data['id'] ] );

			if ( ! empty( $data['tag'] ) ) {
				foreach ( $data['tag'] as $tagId ) {
					$this->db->query( "INSERT INTO blog_tag_relation (tag_id,article_id) VALUES (?,?)", [ $tagId, $data['id'] ] );
				}
			}

			// dynamic url

			$this->db->query( "UPDATE dynamic_url SET meta_keyword=?,meta_description=?, path=? WHERE item_id=? AND controller = 'Article' ", [ $data['meta_keyword'], $data['meta_description'], $data['path'], $data['id'] ] );

			$this->db->commit();

			return true;
		} catch ( Exception $e ) {

			$this->db->Rollback();
		}

		return false;
	}

	public function thumbnailUpdate( $addArticleLastId, $img_name ) {
		return $this->db->query( "UPDATE blog_article SET thumb=? WHERE id =?", [ $img_name, $addArticleLastId ] )->affectedRows();
	}

	public function getCategories() {

		return $this->db->query( "SELECT * FROM blog_category WHERE status !=2" )->fetchAll();
	}

	public function getArticle( $id ) {

		return $this->db->query( "SELECT a.*, du.path, du.meta_keyword, du.meta_description FROM blog_article AS a LEFT JOIN dynamic_url AS du ON a.id = du.item_id WHERE du.controller = 'Article' AND du.method = 'Details' AND a.id = ?", [ $id ] )->fetchArray();

	}

	public function getTags() {
		return $this->db->query( "SELECT * FROM blog_tag " )->fetchAll();
	}

	public function getCurrentPath( $id ) {
		return $this->db->query( "SELECT path FROM dynamic_url WHERE item_id=? AND controller = 'Article'", [ $id ] )->fetchArray();
	}

	public function getExistsingPaths( $path ) {

		$query = $this->db->Query( "SELECT path FROM dynamic_url WHERE path LIKE ?", $path . '%' );

		return $query->numRows() ? array_column( $query->fetchAll(), 'path' ) : [];
	}

	public function getArticleStatus( $id ) {
		return $this->db->query( "SELECT status FROM blog_article WHERE id =?", [ $id ] )->fetchArray();
	}

	public function trashArticle( $id ) {
		return $this->db->query( "UPDATE blog_article SET status = '2' WHERE id = ?", [ $id ] );
	}

	public function Delete( $id ) {

		try {
			$this->db->beginTransaction();

			$delete = $this->db->query( "DELETE FROM blog_article  WHERE id = ?", [ $id ] );
			$this->db->query( " DELETE FROM blog_category_relation WHERE article_id =?", [ $id ] );
			$this->db->query( "DELETE FROM blog_tag_relation WHERE article_id =?", [ $id ] );
			$this->db->query( "DELETE FROM blog_comment WHERE article_id =?", [ $id ] );
			$this->db->query( "DELETE FROM dynamic_url WHERE item_id = ?  AND controller='Article' ", [ $id ] );

			$this->db->commit();

			return $delete;

		} catch ( Exception $e ) {

			$this->db->Rollback();

			return false;
		}
	}

}
