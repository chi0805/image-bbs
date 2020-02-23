<?php
class DbManager {
        protected $connections = array();
        protected $repository_connection_map = array();
        //DbRepositoryクラスのインスタンスを一度作成すれば、それ以降インスタンスを生成する必要がないよう実装するため、
        //すべてのインスタンスをDbManagerクラスで管理するため、それらを保持する$repositoriesプロパティを定義する。
        protected $repositories = array();

        //connect()メソッドで接続を行う。
        //$nameは接続を特定するための名前で$connectionsプロパティのキーになる値。
        //$paramsはDBの指定やユーザー、パスワードなど接続に必要な情報の配列。
        public function connect ($name, $params) {
                //後ほど、$paramsから値を取り出す際にキーが存在するかをチェックしなくて済むようにarray_merge()関数を使っている。
                $params = array_merge(array(
                        'dsn' => null,
                        'user' => '',
                        'password' => '',
                        'options' => array(),
                ), $params);

                //PDOクラスのインスタンス作成。
                $con = new PDO(
                        $params['dsn'],
                        $params['user'],
                        $params['password'],
                        $params['options']
                );

                //PDO::ATTR_ERRMODE属性をPDO::ERRMODE_EXCEPTIONに設定している。
                //PDOの内部でエラーが起きた場合、例外を発生させるようにするため。
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $this->connections[$name] = $con;

        }

        public function getConnection($name = null) {
                //引数の指定がなければ最初に作成したコネクションを取得する。
                if (is_null($name)) {
                        return current($this->connections);
                }                    

                return $this->connections[$name];
        }

        public function setRepositoryConnectionMap($repository_name, $name) {
                $this->repository_connection_map[$repository_name] = $name;
        }

        public function getConnectionForRepository($repository_name) {
                if (isset($this->repository_connection_map[$repository_name])) {
                        $name = $this->repository_connection_map[$repository_name];
                        $con = $this->getConnection($name);
                } else {
                        $con = $this->getConnection();
                }
                
                return $con;
        }

        //実際にインスタンスの生成を行う。
        //頻繁に扱うのでget()という短い名前にする。
        public function get($repository_name) {
                //指定されたRepository名が$repositoriesに入っていない場合のみ作成。
                if (!isset($this->repositories[$repository_name])) {
                        $repository_class = $repository_name . 'Repository';
                        //コネクションを取得
                        $con = $this->GetConnectionForRepository($repository_name);

                        $repository = new $repository_class($con);

                        //作成したインスタンスを保持するために、$repositoriesに格納。
                        $this->repositories[$repository_name] = $repository;

                }

                return $this->repositories[$repository_name];
        }

        //接続を開放する。
        public function __destruct() {
                //PDOを使用している場合、PDOのインスタンスが破棄されると接続を閉じるようになっている。
                //Repository内でも接続情報を参照しているため、先にRepositoryｎｏのインスタンスを破棄する。
                //(参照情報が残っていると破棄できない。)
                foreach ($this->repositories as $repository) {
                        unset($repository);
                }

                foreach ($this->connections as $con) {
                        unset($con);
                }
        }

}
?>
