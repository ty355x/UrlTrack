CREATE TABLE `app_store` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_key` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `entry_value` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_APP_STORE_ENTRY_KEY` (`entry_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE `tracked_url_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tracked_url_id` int NOT NULL,
  `image_path` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `image_filename` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_TRACKED_URL_IMAGES_TRACKED_URL_ID_idx` (`tracked_url_id`),
  CONSTRAINT `FK_TRACKED_URL_IMAGES_TRACKED_URL_ID` FOREIGN KEY (`tracked_url_id`) REFERENCES `tracked_urls` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE `tracked_urls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(2048) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `url_hash` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_by` int NOT NULL,
  `is_disabled` tinyint NOT NULL DEFAULT '0',
  `update_method` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `update_time` time DEFAULT NULL,
  `update_interval_mins` int DEFAULT NULL,
  `is_updating` tinyint NOT NULL DEFAULT '0',
  `update_attempts` smallint NOT NULL DEFAULT '0',
  `last_updated_at` datetime DEFAULT NULL,
  `last_update_result` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_TRACKED_URLS_CREATED_BY_URL_HASH` (`created_by`,`url_hash`),
  CONSTRAINT `FK_TRACKED_URLS_USERS` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `api_token` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `api_token_created_at` datetime DEFAULT NULL,
  `role` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `is_disabled` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_USERS_EMAIL` (`email`),
  UNIQUE KEY `UNIQUE_USERS_API_TOKEN` (`api_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
