<?php

class TagModel extends Model {

	public function Add( $name ) {
		return $this->db->query( "INSERT INTO blog_tag (name, created) VALUES (?,?)", [ $name, TIMESTAMP ] );
	}

	public function getTags( $search_condition, $params ) {

		$sql = "SELECT t.*,(SELECT COUNT(tr.tag_id) FROM blog_tag_relation AS tr WHERE tr.tag_id = t.id ) AS total_articles FROM blog_tag AS t LEFT JOIN blog_tag_relation AS tr ON t.id = tr.id LEFT JOIN blog_article AS a ON a.id = tr.article_id $search_condition";

		$query = empty( $params ) ? $this->db->dataQuery( $sql ) : $this->db->dataQuery( $sql, $params );

		$pagination = Util::Pagination( $query['item_count'], $query['page_number'], $query['item_limit'] );

		return array_merge( $query, $pagination );
	}

	public function checkArticle( $id ) {
		return $this->db->query( "SELECT * FROM blog_tag_relation WHERE tag_id = ?", [ $id ] )->fetchAll();
	}

	public function Delete( $id ) {
		$query = $this->db->query( "DELETE FROM blog_tag WHERE id = $id" )->affectedRows();

		$this->db->query( "DELETE FROM blog_tag_relation WHERE tag_id = ? ", [ $id ] );

		return $query;
	}

	public function Edit( $id ) {
		$query = $this->db->query( "SELECT * FROM blog_tag WHERE id=?", [ $id ] )->fetchArray();

		return $query;
	}

	public function Update( $data ) {

		$id     = $data['id'];
		$name   = $data['name'];

		return $this->db->query( "UPDATE blog_tag SET name= ?, created= ? WHERE id= ?", [ $name, TIMESTAMP, $id ] );
	}

	public function checkDublicate( $name ) {
		return $this->db->query( "SELECT * FROM blog_tag WHERE name = ?", $name )->numRows();
	}
}
