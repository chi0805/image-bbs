<?php
class AccountController extends Controller {
    public function getIndexAction() {
        return $this->redirect('/home');
    }

    public function getHomeAction() {
        if ($this->session->isAuthenticated() === false) {
            return $this->render([]);
        } else {
            return $this->redirect('/my/home');
        }
    }

    public function getSignupAction() {
        return $this->render([
            '_token' => $this->generateCsrfToken('account/signup')
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
        }

        //正常時
        if (count($errors) === 0) {
            //認証情報をセットしてログイン画面へリダイレクト
            $this->db_manager->get('User')->insert($user_name, $password);
            return $this->redirect('/account/signin');
        }
        //エラーのときは、同じ画面にエラー表示してリダイレクト
        return $this->render([
            'user_name' => $user_name,
            'password'  => $password,
            'errors'    => $errors,
            '_token'    => $this->generateCsrfToken('account/signup'),
        ], 'getSignup');

    }

    public function getSigninAction() {
        return $this->render([
            '_token'    => $this->generateCsrfToken('account/signin'),
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
                return $this->redirect('/my/home');
            }
        }

        $error = "ユーザー名またはパスワードか間違っています";
        return $this->render([
            'user_name' => $user_name,
            'password'  => $password,
            'error'     => $error,
            '_token'    => $this->generateCsrfToken('account/signin'),
        ], 'getSignin');
    }

    public function postSignoutAction(){
        if ($this->session->isAuthenticated() === true) {
//            $this->session->setAuthenticated(false);
            $this->session->clear();

            return $this->redirect('/home');
        }
    }
}
