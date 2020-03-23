create table `bbs` ( 
    `id` INTEGER NOT NULL AUTO_INCREMENT, 
    `name` VARCHAR(200), 
    `comment` VARCHAR(700), 
    `created_at` DATETIME,
    `color` enum('red', 'blue', 'yellow', 'green', 'pink', 'black') NOT NULL,
    `category` int NOT NULL, 
    `gender` enum('male', 'female', 'other') NOT NULL,
    `image` varchar(255),
    `password` varchar(60),
    `deleted_at` datetime,
    `is_deleted` boolean NOT NULL DEFAULT false,
    `updated_at` datetime,
    PRIMARY KEY(id)
) ENGINE = INNODB;
