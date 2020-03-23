<?php
 
class ImageBbsApplication extends Application {
    protected $login_action = ['account', 'signin'];

    public function getRootDir() {
        return dirname(__FILE__);
    }

    protected function registerRoutes() {
        return [
            '/'
                => ['controller' => 'account', 'action' => 'getIndex'],
            '/home'
                => ['controller' => 'account', 'action' => 'getHome'],
            '/account/signup'
                => ['controller' => 'account', 'action' => 'getSignup'],
            '/account/signup/post'
                => ['controller' => 'account', 'action' => 'postSignup'],
            '/account/signin'
                => ['controller' => 'account', 'action' => 'getSignin'],
            '/account/signin/post'
                =>['controller'  => 'account', 'action' => 'postSignin'],
            '/account/signout/post'
                => ['controller' => 'account', 'action' => 'postSignout'],
            '/my/home'
                => ['controller' => 'comment', 'action' => 'getHome'], 
            'my/comment/create' 
                => ['controller' => 'comment', 'action' => 'getCreate'],
            'my/comment/confirm' 
                => ['controller' => 'comment', 'action' => 'postConfirm'],
            'my/comment/save/post' 
                => ['controller' => 'comment', 'action' => 'postSave'],
            'my/comment/delete/post' 
                => ['controller' => 'comment', 'action' => 'postDelete'],
            'my/comment/edit/:id' 
                => ['controller' => 'comment', 'action' => 'getEdit'],
        ];
    }

    protected function configure() {
        $this->db_manager->connect('master', [
            'dsn'      => 'mysql:dbname=study;host=db',
            'user'     => 'user',
            'password' => 'password',
        ]);
    }

}
