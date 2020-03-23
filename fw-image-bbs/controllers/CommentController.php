<?php
class CommentController extends Controller {

    public function getHomeAction() 
    {
        if (!$this->session->isAuthenticated()) {
            $this->forward404(); 
        }

        global $categories;
        //投稿コメント関連のセッション破棄
        $this->session->remove('errors');
        $this->session->remove('comment');
        $this->session->remove('color');
        $this->session->remove('category');
        $this->session->remove('image');

        if (!empty($this->request->getGet('sort'))) {
            $sort = $this->request->getGet('sort');
        } else {
            $sort = 'DESC';
        }

        if (!empty($this->request->getGet('page'))) {
            $page = (int)$this->request->getGet('page'); 
        } else {
            $page = 1;
        }

        $user_id   = $this->session->get('user_id');
        $user_info = $this->db_manager->get('User')->fetchByUserId($user_id);
        $user_name = $user_info['name'];
        $results   = $this->db_manager->get('Comment')->fetchByUserId($user_id, $sort, $page);

        $select_results = [];
        if (!empty($this->request->getGet('select_categories'))) {
            $select_categories = $this->request->getGet('select_categories');
        } elseif(!empty($this->session->get('select_categories'))) {
            $select_categories = $this->session->get('select_categories');
        } else {
            $select_categories = array_keys($categories);
        }
        for ($i = 0; $i < count($results); $i++) {
            if (array_intersect(explode(',', $results[$i]['category']), $select_categories)) {
                //日付のフォーマット変更
                $results[$i]['created_at'] = $this->db_manager->get('Comment')->getDatetimeJp($results[$i]['created_at']);
                //カテゴリを日本語に変更
                $results[$i]['category']   = $this->db_manager->get('Comment')->getCategoryJp($results[$i]['category']);

                $select_results[] = $results[$i];
            }
        }
        $last_page = (int)ceil(count($select_results)/10);

        $this->session->set('select_categories', $select_categories);

        return $this->render([
            '_token'            => $this->generateCsrfToken('my/home'),
            'categories'        => $categories,
            'select_categories' => $select_categories,
            'user_name'         => $user_name,
            'page'              => $page,
            'sort'              => $sort,
            'select_results'    => $select_results,
            'last_page'         => $last_page,
        ]);
    }

    public function getCreateAction() 
    {
        global $categories;
        global $colors;
        return $this->render([
            '_token'     => $this->generateCsrfToken('comment/create'),
            'comment'    => $this->session->get('comment'),
            'color'      => $this->session->get('color'),
            'category'   => $this->session->get('category'),
            'errors'     => $this->session->get('errors'),
            'categories' => $categories,
            'colors'     => $colors,
        ]);
    }

    public function postConfirmAction() 
    {
        global $categories;
        global $colors;

        $action = $this->request->getPost('action');
        $this->session->remove('errors');
        if (!$this->request->isPost() || !in_array($action, ['create', 'edit'])) {
           $this->forward404(); 
        }

        $token      = $this->request->getPost('_token');
        $comment    = $this->request->getPost('comment');
        $color      = $this->request->getPost('color');
        $comment_id = $this->request->getPost('comment_id');
        $category   = $this->request->getPost('category');
        if (is_null($category)) {
            $category = [];
        }

        if (!$this->checkCsrfToken("comment/{$action}", $token)) {
            if ($action === "create") {
                return $this->redirect('/my/comment/create');
            } else if ($action === "edit") {
                return $this->redirect("/my/comment/edit/{$comment_id}");
            }
        }

        $errors = [];

        if ($action === 'create') {
            $tmp_name = $_FILES['image']['tmp_name'];
            $image_error = $this->db_manager->get('Comment')->checkImageError($tmp_name, 'image');
            if (!empty($image_error)) {
                $errors['image'] = $image_error;
            } else {
                $image = $this->db_manager->get('Comment')->getImagePath($tmp_name, 'image', './images');
            }
        } else if ($action === 'edit') {
            $image = $this->request->getPost('image');
        } 

        if (empty($comment)) {
            $errors['comment'] = 'コメントを入力してください';
        } else if (mb_strlen($comment) > 200){
            $errors['comment'] = 'コメントは200文字以内で入力してください';
        }

        if (empty($color)) {
            $errors['color'] = '色を選択してください';
        }

        if (empty($category)) {
            $errors['category'] = 'カテゴリを選択してください';
        } else if (count($category) > 3) {
            $errors['category'] = 'カテゴリは3つまで選択できます';
        }


        if (!empty($errors)) {
            $this->session->set('comment_id', $comment_id);
            $this->session->set('comment', $comment);
            $this->session->set('color', $color);
            $this->session->set('category', $category);
            $this->session->set('errors', $errors);

            if ($action === 'create' ) {
                return $this->redirect('/my/comment/create');
            } elseif ($action === 'edit') {
                $this->session->set('image', $image);
                return $this->redirect("/my/comment/edit/{$comment_id}");
            }
        }
 
        return $this->render([
            '_token'     => $this->generateCsrfToken('comment/confirm'),
            'comment_id' => $comment_id,
            'comment'    => $comment,
            'color'      => $color,
            'category'   => $category,
            'image'      => $image,
            'action'     => $action,
            'categories' => $categories,
            'colors'     => $colors,
        ]);
    }

    public function postSaveAction() 
    {
        if (!$this->request->isPost()) {
           $this->forward404(); 
        }

        $token      = $this->request->getPost('_token');
        $comment_id = $this->request->getPost('comment_id');
        $user_id    = $this->session->get('user_id');
        $comment    = $this->request->getPost('comment');
        $color      = $this->request->getPost('color');
        $category   = $this->request->getPost('category');
        $image      = $this->request->getPost('image');
        $action     = $this->request->getPost('action');

        if ($action === 'create') {
            $this->db_manager->get('Comment')->insert($user_id, $comment, $color, $category, $image);
        } elseif ($action === 'edit') {
            $this->db_manager->get('Comment')->update($comment_id, $comment, $color, $category);
        }

        //コメント関連セッション破棄
        $this->session->remove('comment');
        $this->session->remove('color');
        $this->session->remove('category');
        $this->session->remove('image');
        $this->session->remove('errors');
        $this->session->remove('select_categories');

        if ($this->checkCsrfToken("comment/confirm", $token)) {
            return $this->redirect('/my/home');
        }
    }

    public function postDeleteAction() 
    {
        if (!$this->request->isPost()) {
           $this->forward404(); 
        }

        $token = $this->request->getPost('_token');
        if ($this->checkCsrfToken("my/home", $token)) {
            $comment_id = $this->request->getPost('comment_id');
            $this->db_manager->get('Comment')->deleteComment($comment_id);

            return $this->redirect('/my/home');
        }
    }

    public function getEditAction() 
    {
        global $categories;
        global $colors;
        if (empty($this->session->get('errors'))) {
            $comment_id = $this->request->getGet('comment_id');
            $result = $this->db_manager->get('Comment')->fetchByCommentId($comment_id);
            $user_info = $this->db_manager->get('User')->fetchByUserId($result['user_id']);
        
            return $this->render([
                '_token'      => $this->generateCsrfToken('comment/edit'),
                'comment_id'  => $comment_id,
                'comment'     => $result['comment'],
                'image'       => $result['image'],
                'color'       => $result['color'],
                'category'    => explode(',', $result['category']),
                'categories'  => $categories,
                'colors'      => $colors,
            ]);
        } else {
            return $this->render([
                '_token'     => $this->generateCsrfToken('comment/edit'),
                'comment_id' => $this->session->get('comment_id'),
                'comment'    => $this->session->get('comment'),
                'image'      => $this->session->get('image'),
                'color'      => $this->session->get('color'),
                'category'   => $this->session->get('category'),
                'errors'     => $this->session->get('errors'),
                'categories' => $categories,
                'colors'     => $colors,
            ]);
        
        }

    }
}
