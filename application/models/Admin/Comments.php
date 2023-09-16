<?php

class CommentsModel extends Model
{

  public function getComments($search, $params)
  {

    $data = $this->db->dataQuery(
      "SELECT bc.id,
								       u.name,
								       bc.uid,
								       bc.comment,
								       ba.title AS article_title,
								       du.path,
								       bc.status,
								       bc.updated,
								       bc.created
								FROM blog_comment AS bc
								JOIN user AS u ON u.id = bc.uid
								JOIN blog_article AS ba ON ba.id = bc.article_id
								JOIN dynamic_url AS du ON du.item_id = ba.id
								AND du.controller = 'Article'
								AND du.method = 'Details'
								$search
								ORDER BY bc.updated DESC, bc.created DESC",
      $params
    );

    $pagination = Util::pagination($data['item_count'], $data['page_number'], $data['item_limit']);

    return array_merge($data, $pagination);
  }

  public function getUsers()
  {
    return $this->db->Query("SELECT * FROM user")->fetchAll();
  }

  public function commentStatusUpdate($id)
  {
    return $this->db->Query("UPDATE blog_comment SET status = 1 WHERE id = ? ", [$id]);
  }

  public function getComment($id)
  {
    return $this->db->Query("SELECT id,comment,status FROM blog_comment WHERE id = ? ", [$id])->fetchArray();
  }

  public function updateComment($comment, $status, $id)
  {
    return $this->db->Query("UPDATE blog_comment SET comment = ?,status = ? ,updated = ? WHERE id = ?", [$comment, $status, TIMESTAMP, $id]);
  }

  public function delete($id)
  {
    return $this->db->Query(" UPDATE blog_comment SET status = 2 WHERE id = ? ", [$id]);
  }
}
