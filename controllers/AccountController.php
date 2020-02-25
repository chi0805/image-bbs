<?php
class AccountController extends Controller {
    public function getIndexAction() {
        return $this->redirect('/home');
    }

    public function getHomeAction() {
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

        global $categories;
        $results = $this->db_manager->get('Comment')->fetchAllComments($sort);
        $last_page = (int)ceil(count($results)/10);
        $select_results = [];
        if (!empty($this->request->getGet('select_categories'))) {
            $select_categories = $this->request->getGet('select_categories');
        } elseif(!empty($this->session->get('select_categories'))) {
            $select_categories = $this->session->get('select_categories');
        } else {
            $select_categories = array_keys($categories);
        }

        $i = 0;
        foreach ($results as $result) {
            if (array_intersect(explode(',', $result['category']), $select_categories)) {
                //ユーザーIDをユーザー情報取得
                $user_info = $this->db_manager->get('User')->fetchByUserId($result['user_id']);
                //カテゴリを日本語に変更
                $result['created_at']      = $this->db_manager->get('Comment')->getDatetimeJp($result['created_at']);
                //日付のフォーマット変更
                $result['category']        = $this->db_manager->get('Comment')->getCategoryJp($result['category']);
                $results[$i]['user_name']  = $user_info['name'];
                $results[$i]['created_at'] = $result['created_at'];
                $results[$i]['category']   = $result['category'];

                $select_results[] = $results[$i];
            }
            $i++;
        }

        if ($this->session->isAuthenticated() === false) {
            $this->session->set('select_categories', $select_categories);
            return $this->render([
                'categories'        => $categories,
                'select_categories' => $select_categories,
                'sort'              => $sort,
                'select_results'    => $select_results,
                'page'              => $page,
                'last_page'         => $last_page,
            ]);
        } else {
            return $this->redirect('/my/home');
        }
    }

    public function getSignupAction() {
        $user_name = $this->session->get('user_name');
        $password  = $this->session->get('password');
        $errors    = $this->session->get('errors');

        $this->session->clear('user_name');
        $this->session->clear('password');
        $this->session->clear('errors');

        return $this->render([
            '_token'    => $this->generateCsrfToken('account/signup'),
            'user_name' => $user_name,
            'password'  => $password,
            'errors'    => $errors,
        ]);
    }

    public function postSignupAction() {
        if (!$this->request->isPost()) {
           $this->forward404(); 
        }
        
        $token = $this->request->getPost('_token');
        
        if (!$this->checkCsrfToken('account/signup', $token)) {
            return $this->redirect('account/signup');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = [];
    
        if (empty($user_name)) {
            $errors['user_name'] = "名前を入力してください";
        } else if (mb_strlen($user_name) > 40) {
            $errors['user_name'] = "名前は40文字以内で入力してください";
        }

        if (!mb_strlen($password)) {
            $errors['password'] = "パスワードを入力してください";
        } elseif (mb_strlen($password) < 8 || mb_strlen($password) > 20){
            $errors['password'] = "パスワードは8〜20文字以下にしてください";
        } elseif (!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,20}$/', $password)) {
            $errors[('password')] = "パスワードは半角英大文字・小文字・数字・記号を含む8〜20文字にしてください";
        }

        //正常時
        if (count($errors) === 0) {
            //認証情報をセットしてログイン画面へリダイレクト
            $this->db_manager->get('User')->insert($user_name, $password);
            return $this->redirect('/account/signin');
        } else {
        //エラーのときは、同じ画面にエラー表示してリダイレクト
            $this->session->set('user_name', $user_name);
            $this->session->set('password', $password);
            $this->session->set('errors', $errors);

            return $this->redirect('/account/signup');
        }

    }

    public function getSigninAction() {
        $user_name = $this->session->get('user_name');
        $password  = $this->session->get('password');
        $error     = $this->session->get('error');

        return $this->render([
            '_token'    => $this->generateCsrfToken('account/signin'),
            'user_name' => $user_name,
            'password'  => $password,
            'error'    => $error,
        ]);
    }

    public function postSigninAction() {
        if (!$this->request->isPost()) {
           $this->forward404(); 
        }

        $token = $this->request->getPost('_token');

        if (!$this->checkCsrfToken('account/signin', $token)) {
            return $this->redirect('account/signin');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $results = $this->db_manager->get('User')->fetchByUsername($user_name);
        if (!empty($results)){
            if ($this->db_manager->get('User')->isPasswordCorrect($results['password'], $password, $results['salt'])) {
                $user_id = $results['id'];
                $this->session->setAuthenticated(true);
                $this->session->set('user_id', $user_id);
                $this->session->remove('select_categories');

                return $this->redirect('/my/home');
            }
        } {
            $error = "ユーザー名またはパスワードか間違っています";
            $this->session->set('user_name', $user_name);
            $this->session->set('password', $password);
            $this->session->set('error', $error);

            return $this->redirect('/account/signin');
        }
    }

    public function postSignoutAction(){
        if ($this->session->isAuthenticated() === true) {
            $this->session->clear();

            return $this->redirect('/home');
        }
    }
}
