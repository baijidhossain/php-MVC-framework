<?php

class Article extends Controller {

	public function Index( $path = '' ) {

		if ( ! empty( $path ) ) {

			return $this->Details( urldecode( $path ) );
		}

		$search = '';
		$params = [];

		if ( ! empty( $_GET['search'] ) ) {

			$search .= " AND a.title LIKE ? ";

			$params[] = "%" . $_GET['search'] . "%";
		}

		if ( ! empty( $_GET['tag'] ) ) {

			$articlesId = $this->model->getTagArticlesId( $_GET['tag'] );

			$IDS = ! empty( $articlesId ) ? array_column( $articlesId, 'article_id' ) : [ 0 ];

			$search .= " AND a.id IN (" . implode( ',', $IDS ) . ") ";
		}

		$articles = $this->model->Articles( $search, $params );

		$this->data = [
			'articles'   => $articles,
			'page_title' => "Articles",
		];

		$this->view = "articles/index";

		return $this->response();
	}

	public function EditComment( $comment_id = 0 ) {

		if ( ! $comment_id ) {
			$this->redirect();
		}

		$comment = htmlspecialchars( trim( $_POST['comment'] ?? "" ) );

		if ( empty( $comment ) ) {
			$this->redirect();
		}

		$commentInfo = $this->model->getComment( $comment_id );

		if ( empty( $commentInfo ) ) {
			$this->redirect();
		}

		if ( $commentInfo['uid'] !== $_SESSION['userid'] ) {
			$this->redirect();
		}

		$update = $this->model->updateComment( $comment, $comment_id );

		if ( $update ) {
			$this->setMessage( 'success', 'Comment successfully updated' );
		} else {
			$this->setMessage( 'error', 'Something went wrong' );
		}

		$this->redirect();
	}

	// public function ReplyComment( $comment_id = 0, $article_id = 0 ) {
	// 	if ( ! $comment_id || ! $article_id ) {
	// 		$this->redirect();
	// 	}
	//
	// 	$replyComment = htmlspecialchars( trim( $_POST['reply'] ?? "" ) );
	//
	// 	if ( empty( $replyComment ) ) {
	//
	// 		$this->redirect();
	// 	}
	//
	// 	$replay = $this->model->replyComment( $replyComment, $comment_id, $article_id );
	//
	// 	if ( $replay ) {
	//
	// 		$this->setMessage( 'success', 'Comment replied successfully' );
	// 	} else {
	//
	// 		$this->setMessage( 'error', 'Something went wrong' );
	// 	}
	//
	// 	$this->redirect();
	// }

	private function Details( $path = '' ) {

		$articleDetails   = [];
		$title            = "";
		$meta_keywords    = "";
		$meta_description = "";
		$tags             = [];

		if ( ! empty( $path ) ) {

			$articleDetails = $this->model->getArticle( $path );

			if ( empty( $articleDetails ) ) {
				$this->redirect();
			}

			$tags = $this->model->getTags( $articleDetails['id'] );

			if ( ! isset( $_SESSION['article_hits'] ) || ! in_array( $articleDetails['id'], $_SESSION['article_hits'] ) ) {

				$this->model->updateArticleHits( $articleDetails['id'] );

				$_SESSION['article_hits'][] = $articleDetails['id'];
			}

			$title            = $articleDetails['title'];
			$meta_keywords    = $articleDetails['meta_keyword'];
			$meta_description = $articleDetails['meta_description'];

			// handle post comments
			if ( $this->request->method == "POST" && $this->request->verified ) {

				// edit comment
				if ( ! empty( $_POST['comment_id'] ) ) {

					$this->EditComment( $_POST['comment_id'] );
				} else {

					$this->addComment( $articleDetails['id'] );
				}
			}
		}

		$articleCategory = $this->model->getArticleCategory( $articleDetails['id'] );

		$popularPosts = $this->model->getPopularPosts( $articleDetails['id'] );

		$ArticleCategoriesId = implode( ',', array_column( $articleCategory, 'id' ) );

		$readMorePosts = $this->model->getReadMorePosts( $articleDetails['id'], $ArticleCategoriesId );

		$articleComments = $this->model->getArticleComments( $articleDetails['id'] );

		$comments = [];

		if ( ! empty( $articleComments ) ) {

			foreach ( $articleComments as $comment ) {
				if ( $comment['parent_id'] == 0 ) {
					$comments[ $comment['id'] ] = $comment;
				}
			}

			foreach ( $articleComments as $comment ) {
				if ( $comment['parent_id'] != 0 ) {
					$comments[ $comment['parent_id'] ]['replies'][] = $comment;
				}
			}
		}

		$this->data = [
			'page_title'       => $title,
			'articleDetails'   => $articleDetails,
			'popularPosts'     => $popularPosts,
			'readMorePosts'    => $readMorePosts,
			'articleCategory'  => $articleCategory,
			'comments'         => $comments,
			'tags'             => $tags,
			'meta_keywords'    => $meta_keywords,
			'meta_description' => $meta_description,
		];

		$this->view = 'articles/details';

		return $this->response();
	}

	private function addComment( $article_id = NULL ) {

		if ( empty( $_SESSION['userid'] ) ) {
			// set http referer
			$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];

			Util::redirect( APP_URL . '/account/login/' );
		}

		$comment = htmlspecialchars( trim( $_POST['comment'] ?? "" ) );

		if ( empty( $comment ) ) {
			$this->setMessage( 'error', 'Comment field is required' );
			$this->redirect();
		}

		$addcomment = $this->model->addComment( $comment, $article_id );

		if ( $addcomment ) {
			$this->setMessage( 'success', 'Your comment is awaiting moderation. your comment will be visible after it has been approved.' );
		} else {
			$this->setMessage( 'error', 'Something went wrong' );
		}

		$this->redirect();
	}
}
