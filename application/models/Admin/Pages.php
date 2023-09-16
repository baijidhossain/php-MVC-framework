<?php

class PagesModel extends Model {

	public function getPages() {
		$data = $this->db->dataQuery( "SELECT p.*,(SELECT du.path FROM dynamic_url AS du   WHERE du.item_id = p.id AND du.controller='Page') AS path FROM pages AS p" );

		$pagination = Util::Pagination( $data['item_count'], $data['page_number'], $data['item_limit'] );

		return array_merge( $data, $pagination );
	}

	public function add( $data ) {
		try {
			$this->db->beginTransaction();

			// Page
			$pageLastId = $this->db->query( "INSERT INTO pages (title,body,status,created)VALUES(?,?,?,?)", [ $data['title'], $data['body'], $data['status'], TIMESTAMP ] )->lastInsertID();

			// dynamic url
			$this->db->query( "INSERT INTO dynamic_url (item_id,meta_keyword, meta_description ,controller,method,path) VALUES (?,?,?,?,?,?)", [ $pageLastId, $data['meta_keyword'], $data['meta_description'], 'Page', 'Index', $data['path'] ] );


			$this->db->commit();

			return $pageLastId;
		} catch ( Exception $e ) {

			$this->db->Rollback();

			return false;
		}
	}

	public function checkExists( $path ) {
		return $this->db->query( "SELECT id FROM dynamic_url WHERE path = ?", [ $path ] )->numRows();
	}

	public function getPage( $id ) {
		return $this->db->query( "SELECT p.*, du.id AS dynamic_id, du.path, du.meta_keyword, du.meta_description FROM pages AS p JOIN dynamic_url AS du ON du.item_id = p.id WHERE du.controller = 'Page' AND du.method = 'Index' AND p.id = ?", [ $id ] )->fetchArray();
	}

	public function update( $data ) {

		try {
			$this->db->beginTransaction();

			// Page
			$pageUpdate = $this->db->query( "UPDATE pages SET title=?,body=?,status=? WHERE id = ?", [ $data['title'], $data['body'], $data['status'], $data['id'] ] );

			// dynamic url
			$this->db->query( "UPDATE dynamic_url SET meta_keyword=?,meta_description=?, path=? WHERE item_id=? AND controller = 'Page' ", [ $data['meta_keyword'], $data['meta_description'], $data['path'], $data['id'] ] );

			$this->db->commit();

			return $pageUpdate;
		} catch ( Exception $e ) {

			$this->db->Rollback();

			return false;
		}
	}

	public function delete( $id ) {

		try {

			$this->db->beginTransaction();

			$delete = $this->db->query( "DELETE FROM pages WHERE id = ?", [ $id ] );

			$this->db->query( "DELETE FROM dynamic_url WHERE item_id =? AND controller = 'Page'", [ $id ] );

			$this->db->commit();

			return $delete;
		} catch ( Exception $e ) {

			$this->db->Rollback();

			return false;
		}
	}

}
