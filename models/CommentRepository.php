<?php

class CommentRepository extends DbRepository {

    public function insert($user_id, $comment, $color, $category, $image) 
    {
        $now = new DateTime();

        $sql = "INSERT INTO fw_image_bbs(`user_id`, `comment`, `image`, `category`, `color`, `created_at`) VALUES (:user_id, :comment, :image, :category, :color, :created_at)";
        $params = [
            ':user_id'    => $user_id,
            ':comment'    => $comment,
            ':image'      => $image,
            ':category'   => $category,
            ':color'      => $color,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ];
        $stmt = $this->execute($sql, $params);
    }

    public function update($comment_id,  $comment, $color, $category) 
    {
        $now = new DateTime();
        $sql = "UPDATE fw_image_bbs SET `comment` = :comment, `color` = :color, `category` = :category, `updated_at` = :updated_at WHERE `id` = :comment_id";
        $params = [
            'comment_id' => $comment_id,
            'comment'    => $comment,
            ':color'     => $color,
            ':category'  => $category,
            ':updated_at' => $now->format('Y-m-d H:i:s'),
        ];
        $stmt = $this->execute($sql, $params);

    }

    public function fetchAllComments($sort): array
    {
        $sql = "SELECT id, user_id, comment, image, color, category, created_at FROM fw_image_bbs ORDER BY created_at $sort";

        $results = $this->fetchAll($sql);

        return $results;
    }

    public function fetchByUserId($user_id, $sort): array 
    {
        $sql = "SELECT id, comment, image, color, category, created_at FROM fw_image_bbs WHERE user_id = :user_id ORDER BY created_at $sort";
        $params = [
            ':user_id' => $user_id,
        ];

        $results = $this->fetchAll($sql, $params);

        return $results;
    }

    public function fetchByCommentid($comment_id): array
    {
        $sql = "SELECT id, user_id, comment, image, color, category, created_at FROM fw_image_bbs WHERE id = :id";
        $params = [
            ':id' => $comment_id,
        ];

        $result = $this->fetch($sql, $params);

        return $result;
    }

    public function deleteComment($comment_id) 
    {
        $sql = "DELETE FROM fw_image_bbs WHERE id = :comment_id";
        $params = [
            ':comment_id' => $comment_id,
        ];

        $stmt = $this->execute($sql, $params);
    }

    public function getCategoryJp(string $categories_num) 
    {
        global $categories;

        $categories_num = explode(',', $categories_num);
        $categories_jp = [];

        foreach ($categories_num as $category_num) {
            $categories_jp[] = $categories[$category_num];
        }

        return implode(', ', $categories_jp);
    }

    public function getDatetimeJp($datetime) 
    {
        $week = array( "日", "月", "火", "水", "木", "金", "土" );
        $datetime = date('Y年m月d日(', strtotime($datetime)).$week[date('w', strtotime($datetime))].date(')H時i分', strtotime($datetime));

        return $datetime;
    }

    public function checkImageError($image, $name, $error = []) 
    {
        $image_error = $_FILES[$name]['error'];
        switch($image_error) {
            case 0:
                $ext = getimagesize($image)['mime'];
                $ext = str_replace('image/', '', $ext);
                if (!in_array($ext, ['png', 'PNG', 'jpg', 'JPG', 'gif'])) {
                    $error = '画像の形式が間違っています';
                }
                break;
            case 1:
            case 2:
                $error = '画像サイズが大きすぎます';
                break;
            case 4:
                $error = '画像を選択してください';
                break;
        }

        return $error;
    }

    public function getImagePath($tmp_name, $name, $file_dir) 
    {
        $file_name = uniqid();
        $file_path = $file_dir . '/' . $file_name;
        move_uploaded_file($_FILES[$name]['tmp_name'], $file_path);

        return "/images/{$file_name}";
    }

}
