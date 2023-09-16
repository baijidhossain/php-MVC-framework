<?php

class Article extends Controller
{

  public function Index()
  {

    //======================================================================
    // ARTICLE SEARCH STARTS HERE
    //======================================================================

    $search = ' WHERE ';
    $params = [];

    if (isset($_GET['status']) && in_array($_GET['status'], ['1', '0', '2'])) {

      $search   .= " a.status = ? ";
      $params[] = trim($_GET['status']);
    } else {

      $search   .= " a.status != ? ";
      $params[] = '2';
    }

    if (!empty($_GET['title'])) {

      $search   .= " AND title LIKE ? ";
      $params[] = "%" . trim($_GET['title']) . "%";
    }

    if (!empty($_GET['category'])) {

      $search   .= " AND a.id IN (SELECT article_id FROM blog_category_relation WHERE category_id = ?) ";
      $params[] = $_GET['category'];
    }

    if (!empty($_GET['tag'])) {

      $search   .= " AND a.id IN (SELECT article_id FROM blog_tag_relation WHERE tag_id = ?) ";
      $params[] = $_GET['tag'];
    }

    //======================================================================
    // ARTICLE SEARCH END
    //======================================================================

    $articles = $this->model->Articles($search, $params);

    $categories = $this->model->getCategories();

    $tags = $this->model->getTags();

    $this->data = [
      'page_title' => 'Article',
      'articles'   => $articles,
      'categories' => $categories,
      'tags'       => $tags,
    ];

    $this->view = "articles/index";

    return $this->response();
  }

  public function Add()
  {

    //add article
    if (!empty($_POST)) {

      $validated = Util::checkPostValues(['title', 'body', 'category', 'published', 'time', 'status']);

      if (!$validated) {

        $this->setMessage('error', 'Fill all the required fields');
        $this->redirect();
      }

      if (mb_strlen($_POST['title']) > 200) {

        $this->setMessage('error', 'Title name is too long');
        $this->redirect();
      }

      if (!empty($_POST['intro']) && mb_strlen($_POST['intro']) > 255) {

        $this->setMessage('error', 'Intro is too long');
        $this->redirect();
      }

      if (!in_array($_POST['status'], [0, 1, 2])) {

        $this->setMessage('error', 'Invalid status');
        $this->redirect();
      }

      if (!Util::validateDate($_POST['published'])) {

        $this->setMessage('error', 'Invalid published');
        $this->redirect();
      }

      if ($_POST['published'] < date("Y-m-d")) {

        $this->setMessage('error', 'Invalid published date');
        $this->redirect();
      }

      $path = Util::stringToPath(empty($_POST['path']) ? $_POST['title'] : $_POST['path']);

      $existingPath = $this->model->getExistsingPaths($path);

      if (in_array($path, $existingPath)) {

        $count = 0;

        while (in_array(($path . '-' . ++$count), $existingPath)) {;
        }

        $path = $path . '-' . $count;
      }

      $data = [
        "title"            => $_POST['title'],
        "intro"            => !empty($_POST['intro']) ? $_POST['intro'] : '',
        "path"             => $path,
        "body"             => $_POST['body'],
        "category"         => $_POST['category'],
        "tag"              => !empty($_POST['tag']) ? $_POST['tag'] : '',
        "published"        => date("Y-m-d H:i:s", strtotime($_POST['published'] . ' ' . $_POST['time'])),
        "meta_keyword"     => !empty($_POST['meta_keyword']) ? trim($_POST['meta_keyword']) : NULL,
        "meta_description" => !empty($_POST['meta_description']) ? trim($_POST['meta_description']) : NULL,
        "status"           => $_POST['status'],
      ];

      $lastId = $this->model->addArticle($data);

      if (!$lastId) {
        $this->setMessage('error', 'Something went wrong, article could not be saved');
        $this->redirect();
      }

      //Start  File upload
      if (isset($_FILES['inputfile']) && $_FILES['inputfile']['tmp_name'] != '') {

        $check = getimagesize($_FILES['inputfile']["tmp_name"]);

        if ($check !== false) {
          $twoMB = 2097152;
          if ($_FILES['inputfile']["size"] < $twoMB) {

            $filename             = $_FILES['inputfile']['name'];
            $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);

            $img_name = $lastId . '_' . Util::stringToPath($filename_without_ext) . '.png';

            $save_path = PUBLIC_PATH . "images/article/thumbnail/" . $img_name;

            if (move_uploaded_file($_FILES['inputfile']["tmp_name"], $save_path)) {

              if (!$this->model->thumbnailUpdate($lastId, $img_name)) {

                $this->setMessage('error', 'Thumbnail could not be saved!');
              }
            } else {

              $this->setMessage('error', 'Thumbnail could not be uploaded');
            }
          } else {

            $this->setMessage('error', 'Sorry, Your file is too large. Upload Size is Maximum 2MB');
          }
        } else {

          $this->setMessage('error', 'Sorry, Your file is not an image.');
        }
      }
      // End file upload

      if ($lastId) {
        $this->setMessage('success', 'Article save was successfully.');
        $this->redirect();
      }
    }
    // end Article

    $tags       = $this->model->getTags();
    $categories = $this->model->getCategories();

    $this->data = [
      "page_title" => "Add Article",
      "tags"       => $tags,
      "categories" => $categories,
    ];

    $this->view = 'articles/add';

    return $this->response();
  }

  public function Edit($id = 0)
  {

    if (empty($id)) {
      $this->setMessage('error', 'Invalid ID');
      $this->redirect();
    }

    $article = $this->model->getArticle($id);

    if (empty($article)) {
      $this->setMessage('error', 'Invalid ID');
      $this->redirect();
    }

    $tags             = $this->model->getTags();
    $categories       = $this->model->getCategories();
    $categoryRelation = $this->model->categoryRelation($id);
    $tagRelation      = $this->model->tagrelation($id);

    $this->data = [
      'page_title'       => 'Edit Article',
      'action'           => 'Edit',
      'article'          => $article,
      "categories"       => $categories,
      "categoryRelation" => $categoryRelation,
      "tagRelation"      => $tagRelation,
      "tags"             => $tags,
    ];

    $this->view = "articles/edit";

    return $this->response();
  }

  public function Update()
  {

    if (!empty($_POST)) {

      $validated = Util::checkPostValues(['id', 'title', 'html_body', 'category', 'published', 'time', 'status']);

      if (!$validated) {
        $this->setMessage('error', 'Fill all the required fields');
        $this->redirect();
      } else {

        if (mb_strlen($_POST['title']) > 200) {

          $this->setMessage('error', 'Title name is too long');
          $this->redirect();
        }

        if (!empty($_POST['intro']) && mb_strlen($_POST['intro']) > 255) {

          $this->setMessage('error', 'Intro is too long');
          $this->redirect();
        }

        if (!in_array($_POST['status'], [0, 1, 2])) {

          $this->setMessage('error', 'Invalid status!');
          $this->redirect();
        }


        if (!Util::validateDate($_POST['published'])) {

          $this->setMessage('error', 'Invalid published');
          $this->redirect();
        }


        $path = Util::stringToPath(empty($_POST['path']) ? $_POST['title'] : $_POST['path']);

        if ($this->model->getCurrentPath($_POST['id'])['path'] != $path) {

          $existingPath = $this->model->getExistsingPaths($path);

          if (in_array($path, $existingPath)) {

            $count = 0;

            while (in_array(($path . '-' . ++$count), $existingPath)) {;
            }

            $path = $path . '-' . $count;
          }
        }

        $data = [
          "id"               => $_POST['id'],
          "title"            => trim($_POST['title']),
          "intro"            => !empty($_POST['intro']) ? trim($_POST['intro']) : '',
          "path"             => $path,
          "html_body"        => trim($_POST['html_body']),
          "category"         => $_POST['category'],
          "tag"              => !empty($_POST['tag']) ? $_POST['tag'] : '',
          "published"        => date("Y-m-d H:i:s", strtotime($_POST['published'] . ' ' . $_POST['time'])),
          "meta_keyword"     => !empty($_POST['meta_keyword']) ? trim($_POST['meta_keyword']) : NULL,
          "meta_description" => !empty($_POST['meta_description']) ? trim($_POST['meta_description']) : NULL,
          "status"           => $_POST['status'],
        ];

        $articleupdated = $this->model->update($data);


        //Start  File upload
        if (isset($_FILES['inputfile']) && $_FILES['inputfile']['tmp_name'] != '') {

          $check = getimagesize($_FILES['inputfile']["tmp_name"]);

          if ($check !== false) {
            $twoMB = 2097152;
            if ($_FILES['inputfile']["size"] < $twoMB) {

              $filename             = $_FILES['inputfile']['name'];
              $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);

              $img_name = $_POST['id'] . '_' . Util::stringToPath($filename_without_ext) . '.png';

              $save_path = PUBLIC_PATH . "images/article/thumbnail/" . $img_name;

              if ($articleupdated) {
                move_uploaded_file($_FILES['inputfile']["tmp_name"], $save_path);
                $this->model->thumbnailUpdate($_POST['id'], $img_name);
              }
            } else {
              $this->setMessage(
                'error',
                'Sorry, Your file is too large. Upload Size is Maximum 2MB'
              );
            }
          } else {
            $this->setMessage('error', 'Sorry, Your file is not an image.');
          }
        }
        // End file upload

        if ($articleupdated) {
          $this->setMessage('success', 'Article updated was successfully.');
        }
      }

      $this->redirect();
    }
  }

  public function Delete($id = 0)
  {

    if (!$id) {
      $this->setMessage('error', 'Something went wrong.');
      $this->redirect();
    }

    $article = $this->model->getArticleStatus($id);

    if (empty($article)) {
      $this->setMessage('error', 'Invalid article id');
      $this->redirect();
    }

    if ($article['status'] == 2) {

      if ($this->model->Delete($id)) {

        $files = glob(PUBLIC_PATH . "/images/article/thumbnail/" . $id . "_*.png");

        foreach ($files as $file) {
          unlink($file);
        }

        $this->setMessage('success', 'Article deleted successfully.');
      } else {

        $this->setMessage('error', 'Something went wrong');
      }
    } elseif ($this->model->trashArticle($id)) {

      $this->setMessage('success', 'Article sent to trash successfully.');
    } else {

      $this->setMessage('error', 'Something went wrong');
    }

    $this->redirect();
  }

  public function Images()
  {

    require_once PUBLIC_PATH . 'filebrowser/index.php';

    $dirToScan = PUBLIC_PATH . '/images/'; // change this to your directory

    $fm = new FileManager();

    $fm->path = $dirToScan;

    $fm->mode = 'Images';

    $fm->allowed_ext = ''; //'php|js|css|scss|jpe?g|png|pdf|docx?|txt';

    $fm->run();
  }
}
