<?php

class ArticleModel extends Model
{

  public function Articles($search, $params)
  {

    $this->db->data_limit = 10;

    $params = array_merge([TIMESTAMP], $params);

    $sql = "SELECT a.title,
				       a.thumb,
				       a.published,
				       a.intro,
				       SUBSTRING(ExtractValue(a.body, '//text()'), 1, 280) AS body,
				       du.path,
               u.name
				FROM blog_article AS a
				JOIN dynamic_url AS du ON item_id = a.id
        JOIN user as u ON a.created_by = u.id
				WHERE du.controller = 'Article'
				  AND du.method = 'Details'
				  AND a.status = 1
				  AND a.published <= ? 
				  $search
				ORDER BY a.id DESC";

    $query = $this->db->dataQuery($sql, $params);

    $pagination = Util::Pagination($query['item_count'], $query['page_number'], $query['item_limit']);

    return array_merge($query, $pagination);
  }

  public function getArticle($path)
  {

    return $this->db->query(
      "SELECT a.*,
								       du.path,
								       du.meta_keyword,
								       du.meta_description,
                       u.name
								FROM blog_article AS a
								JOIN dynamic_url AS du ON a.id = du.item_id
                JOIN user as u ON a.created_by = u.id
								WHERE du.path = ?
								  AND du.controller ='Article'
								  AND du.method ='Details'",
      [$path]
    )->fetchArray();
  }

  public function getTags($id)
  {

    return $this->db->query("SELECT t.* FROM blog_tag AS t JOIN blog_tag_relation AS tr ON tr.tag_id = t.id WHERE tr.article_id = $id")->fetchAll();
  }

  public function getTagArticlesId($tagName)
  {

    return $this->db->query("SELECT tr.article_id FROM `blog_tag_relation` AS tr JOIN blog_tag AS t ON t.id = tr.tag_id WHERE t.name LIKE ?", $tagName)->fetchAll();
  }

  public function updateArticleHits($article_id)
  {

    return $this->db->query("UPDATE blog_article SET hits = hits + 1 WHERE id = $article_id")->affectedRows();
  }

  public function getArticleCategory($blog_id)
  {
    return $this->db->Query(
      "SELECT c.id, c.name, du.path FROM blog_category AS c JOIN dynamic_url AS du ON du.item_id = c.id JOIN blog_category_relation AS cr ON c.id = cr.category_id WHERE du.controller = 'Category' AND du.method = 'Details' AND cr.article_id = ?",
      [$blog_id]
    )->fetchAll();
  }

  public function getPopularPosts($article_id)
  {

    return $this->db->Query(
      "SELECT a.*, du.path
									FROM blog_article AS a
									JOIN dynamic_url AS du 
									    ON du.item_id = a.id
									WHERE a.id != ?
									  AND du.controller = ?
									  AND du.method = ?
									ORDER BY hits DESC
									LIMIT 5",
      [$article_id, 'Article', 'Details']
    )->fetchAll();
  }

  public function getReadMorePosts($article_id, $ArticleCategoriesId)
  {
    return $this->db->Query(
      "SELECT a.*, du.path FROM `blog_article` AS a
      JOIN dynamic_url AS du ON du.item_id = a.id
      WHERE a.id != ?
      AND a.id IN (SELECT article_id FROM blog_category_relation WHERE category_id IN(?))
      AND du.controller = 'Article'
      AND du.method = 'Details' ORDER BY a.id DESC LIMIT 3",
      [$article_id, $ArticleCategoriesId]
    )->fetchAll();
  }

  public function getArticleComments($article_id)
  {
    return $this->db->Query("SELECT c.*,u.name,u.photo FROM blog_comment AS c JOIN user AS u ON u.id = c.uid WHERE article_id = ? AND c.status = 1", [$article_id])->fetchAll();
  }

  public function getComment($comment_id)
  {
    return $this->db->Query("SELECT * FROM blog_comment WHERE id = ? AND status = '1'", [$comment_id])->fetchArray();
  }

  public function addComment($comment, $article_id)
  {

    return $this->db->Query(
      "INSERT INTO blog_comment (uid,parent_id,article_id,status,comment,created) VALUES (?,?,?,?,?,?)",
      [$_SESSION['userid'], '0', $article_id, '0', $comment, TIMESTAMP]
    )->affectedRows();
  }

  public function updateComment($comment, $comment_id)
  {
    // todo if admin then status = 1 else status = 0
    return $this->db->Query("UPDATE blog_comment SET comment = ?, status = '1', updated = ? WHERE id = ?", [$comment, TIMESTAMP, $comment_id]);
  }

  public function replyComment($replyComment, $comment_id, $article_id)
  {

    return $this->db->Query(
      "INSERT INTO blog_comment (uid,parent_id,article_id,comment, status = '1',created) VALUES (?,?,?,?,?)",
      [$_SESSION['userid'], $comment_id, $article_id, $replyComment, TIMESTAMP]
    )->affectedRows();
  }
}
