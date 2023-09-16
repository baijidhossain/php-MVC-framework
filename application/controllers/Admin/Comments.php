<?php

class Comments extends Controller
{

  public function Index()
  {
    $search = 'WHERE bc.status !=2';

    $params = [];

    if (!empty($_GET['article'])) {
      $search   .= empty($search) ? ' WHERE ' : ' AND ';
      $search   .= 'ba.title LIKE  ?';
      $params[] = "%" . $_GET['article'] . "%";
    }

    if (isset($_GET['status']) && in_array($_GET['status'], [0, 1])) {

      $search   .= empty($search) ? ' WHERE ' : ' AND ';
      $search   .= 'bc.status =  ?';
      $params[] = $_GET['status'];
    }

    if (!empty($_GET['user'])) {
      $search   .= empty($search) ? ' WHERE ' : ' AND ';
      $search   .= 'u.id =  ?';
      $params[] = $_GET['user'];
    }

    $comments = $this->model->getComments($search, $params);

    $users = $this->model->getUsers();

    $this->data['page_title'] = 'Comments';

    $this->data['comments'] = $comments;

    $this->data['users'] = $users;

    $this->view = 'comments/index';

    $this->response();
  }

  public function Approve($id = NULL)
  {
    if (!$id) {
      $this->setMessage('error', 'Invalid id');
      Util::redirectBack();
    }

    $commentstatusupdate = $this->model->commentStatusUpdate($id);
    if ($commentstatusupdate) {
      $this->setMessage('success', 'Comment status successfully updated');
    } else {
      $this->setMessage('error', 'Something went wrong');
    }
    Util::redirectBack();
  }

  public function Delete($id = NULL)
  {
    if (!$id) {
      $this->setMessage('error', 'Invalid id');
      Util::redirectBack();
    }

    $deleted =  $this->model->delete($id);

    if ($deleted) {
      $this->setMessage('success', 'Comment successfully deleted');
    } else {
      $this->setMessage('error', 'Something went wrong');
    }
    Util::redirectBack();
  }

  public function Edit($id = NULL)
  {
    if (!$id) {
      $this->setMessage('error', 'Invalid id');
      Util::redirectBack();
    }

    if ($this->request->method == "POST" && $this->request->verified) {
      $this->updateComment($id);
      Util::redirectBack();
    }

    $commentinfo = $this->model->getComment($id);

    $this->data['modal_title'] = 'Edit Comment';

    $this->data['comment'] = $commentinfo;

    $this->view = 'comments/modal';

    $this->response();
  }

  // Extra method_____

  private function UpdateComment($id)
  {
    $comment =  htmlspecialchars(trim($_POST['comment'] ?? ""));
    $status =  htmlspecialchars(trim($_POST['status'] ?? ""));

    if (empty($comment)) {
      $this->setMessage('error', 'Comment field is required');
      return false;
    }

    if (!in_array($status, [0, 1])) {
      $this->setMessage('error', 'Invalid status');
      return false;
    }

    if (strlen($comment) > 500) {
      $this->setMessage('error', 'Comment must be less than 500 characters or equal');
      return false;
    }

    $update = $this->model->updateComment($comment, $status, $id);

    if ($update) {

      $this->setMessage('success', 'Comment successfully updated');
      return true;
    }

	  $this->setMessage('error', 'Something went wrong');

	  return false;

  }
}
