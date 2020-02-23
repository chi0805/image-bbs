<?php

class CommentRepository extends DbRepository {
    public function insert($user_id, $comment, $color, array $category, $image, $sort) {
        $category = implode(',', $category);
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

    public function update($comment_id,  $comment, $color, $category) {
        $category = implode(',', $category);
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

    public function fetchByUserId($user_id, $sort) {
        $sql = "SELECT id, comment, image, color, category, created_at FROM fw_image_bbs WHERE user_id = :user_id ORDER BY created_at $sort";
        $params = [
            ':user_id' => $user_id,
        ];

        $results = $this->fetchAll($sql, $params);
        return $results;
    }

    public function fetchByCommentid($comment_id) {
        $sql = "SELECT id, user_id, comment, image, color, category, created_at FROM fw_image_bbs WHERE id = :id";
        $params = [
            ':id' => $comment_id,
        ];

        $result = $this->fetch($sql, $params);
        return $result;
    }

    public function deleteComment($comment_id) {
        $sql = "DELETE FROM fw_image_bbs WHERE id = :comment_id";
        $params = [
            ':comment_id' => $comment_id,
        ];

        $stmt = $this->execute($sql, $params);
    }

    public function getCategoryJp(string $categories_num) {
        $categories_num = explode(',', $categories_num);
        $category = ['料理', '景色', '動物', 'スポーツ', 'ゲーム', 'その他'];
        $categories_jp = [];

        foreach ($categories_num as $category_num) {
            $categories_jp[] = $category[$category_num];
        }

        return implode(', ', $categories_jp);
    }

    public function getDatetimeJp($datetime) {
        $week = array( "日", "月", "火", "水", "木", "金", "土" );
        $datetime = date('Y年m月d日(', strtotime($datetime)).$week[date('w', strtotime($datetime))].date(')H時i分', strtotime($datetime));

        return $datetime;
    }

}
