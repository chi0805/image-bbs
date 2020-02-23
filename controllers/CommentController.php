<?php
class CommentController extends Controller {
    public $categories;
    public $colors;

    public function setConst() {
        $this->categories = ['料理', '景色', '動物', 'スポーツ', 'ゲーム', 'その他'];
        $this->colors = [ 
            'red'    => '赤',
            'blue'   => '青',
            'yellow' => '黄色',
            'green'  => '緑',
            'pink'   => 'ピンク',
            'black'  => '黒',
        ];

        return ['categories' => $this->categories, 'colors' => $this->colors];
    }

    public function getHomeAction() {
        //投稿コメント関連のセッション破棄
        $this->session->remove('errors');
        $this->session->remove('comment');
        $this->session->remove('color');
        $this->session->remove('category');

        if (!empty($this->request->getGet('sort'))) {
            $sort = $this->request->getGet('sort');
        } else {
            $sort = 'DESC';
        }

        $user_id   = $this->session->get('user_id');
        $user_info = $this->db_manager->get('User')->fetchByUserId($user_id);
        $user_name = $user_info['name'];
        $results   = $this->db_manager->get('Comment')->fetchByUserId($user_id, $sort);

        $i = 0;
        foreach ($results as $result) {
            //カテゴリを日本語に変更
            $result['created_at']      = $this->db_manager->get('Comment')->getDatetimeJp($result['created_at']);
            //日付のフォーマット変更
            $result['category']        = $this->db_manager->get('Comment')->getCategoryJp($result['category']);
            $results[$i]['created_at'] = $result['created_at'];
            $results[$i]['category']   = $result['category'];
            $i++;
        }

        return $this->render([
            '_token'    => $this->generateCsrfToken('my/home'),
            'user_name' => $user_name,
            'results'   => $results,
        ]);
    }

    public function getCreateAction() {
        return $this->render([
            '_token'     => $this->generateCsrfToken('comment/create'),
            'comment'    => $this->session->get('comment'),
            'color'      => $this->session->get('color'),
            'category'   => $this->session->get('category'),
            'errors'     => $this->session->get('errors'),
            'categories' => $this->setConst()['categories'],
            'colors'     => $this->setConst()['colors'],
        ]);
    }

    public function postConfirmAction() {
        $this->session->remove('errors');
        
        if (!$this->request->isPost()) {
           $this->forward404(); 
        }

        $token      = $this->request->getPost('_token');
        $action     = $this->request->getPost('action');
        $comment    = $this->request->getPost('comment');
        $color      = $this->request->getPost('color');
        $category   = $this->request->getPost('category');
        $comment_id = $this->request->getPost('comment_id');

        if (!$this->checkCsrfToken("comment/{$action}", $token)) {
            if ($action === "create") {
                return $this->redirect('/my/comment/create');
            } else if ($action === "edit") {
                return $this->redirect("/my/comment/edit/{$comment_id}");
            }
        }

        if ($action === 'create') {
            $image = $this->request->getFilePath('image', './images');
        } else if ($action === 'edit') {
            $image = $this->request->getPost('image');
        }    

        $errors = [];

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

        if ($action === 'create' ) {
            if (empty($image)) {
                $errors['image'] = '画像を選択してください';
            } else {
                $image_error = $this->request->getFile('image')['error'];
                switch($image_error) {
                    case 0:
                        $ext = getimagesize($image)['mime'];
                        $ext = str_replace('image/', '', $ext);
                        if (!in_array($ext, ['png', 'PNG', 'jpg', 'JPG', 'gif'])) {
                            $errors['image'] = '画像の形式が間違っています';
                        }
                        break;
                    case 1:
                    case 2:
                        $errors['image'] = '画像サイズが大きすぎます';
                        break;
                    case 4:
                        $errors['image'] = '画像を選択してください';
                        break;
                }
            }
        }

        if (!empty($errors)) {
            $this->session->set('comment_id', $comment_id);
            $this->session->set('comment', $comment);
            $this->session->set('color', $color);
            $this->session->set('category', $category);
            $this->session->set('errors', $errors);

            if ($action === 'create' ) {
                return $this->redirect('/my/comment/create');
            } else if ($action === 'edit') {
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
            'categories' => $this->setConst()['categories'],
            'colors'     => $this->setConst()['colors'],
        ]);
    }

    public function postSaveAction() {
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
        } else if ($action === 'edit') {
            $this->db_manager->get('Comment')->update($comment_id, $comment, $color, $category);
        }

        //コメント関連セッション破棄
        $this->session->remove('comment');
        $this->session->remove('color');
        $this->session->remove('category');
        $this->session->remove('image');
        $this->session->remove('errors');

        if ($this->checkCsrfToken("comment/confirm", $token)) {
            return $this->redirect('/my/home');
        }
    }

    public function postDeleteAction() {
        $token = $this->request->getPost('_token');
        if ($this->checkCsrfToken("my/home", $token)) {
            $comment_id = $this->request->getPost('comment_id');
            $this->db_manager->get('Comment')->deleteComment($comment_id);

            return $this->redirect('/my/home');
        }
    }

    public function getEditAction() {
        $comment_id = $this->session->get('comment_id');
        if (empty($this->session->get('errors'))) {
            $result = $this->db_manager->get('Comment')->fetchByCommentId($comment_id);
            $user_info = $this->db_manager->get('User')->fetchByUserId($result['user_id']);
        
        
            return $this->render([
                '_token'      => $this->generateCsrfToken('comment/edit'),
                'comment_id'  => $comment_id,
                'user_name'   => $user_info['name'],
                'comment'     => $result['comment'],
                'image'       => $result['image'],
                'color'       => $result['color'],
                'category'    => explode(',', $result['category']),
                'created_at'  => $result['created_at'],
            ]);
        } else {
            return $this->render([
                '_token'     => $this->generateCsrfToken('comment/create'),
                'comment_id' => $comment_id,
                'comment'    => $this->session->get('comment'),
                'image'      => $this->session->get('image'),
                'color'      => $this->session->get('color'),
                'category'   => $this->session->get('category'),
                'errors'     => $this->session->get('errors'),
            ]);
        
        }

    }
}
