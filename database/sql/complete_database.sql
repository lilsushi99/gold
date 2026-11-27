-- Orisa CMS - complete database (structure + initial data)
-- Import into an EMPTY MySQL 5.7+/MariaDB 10.3+ database (utf8mb4) via phpMyAdmin > Import.
-- Equivalent to: php artisan migrate --seed. Generated from the Laravel migrations.
SET NAMES utf8mb4; SET FOREIGN_KEY_CHECKS=0; SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(60) NOT NULL,
  `subject_type` varchar(60) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_action_index` (`action`),
  KEY `activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `contact_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `form` varchar(40) NOT NULL DEFAULT 'contact',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_submissions_created_at_index` (`created_at`),
  KEY `contact_submissions_form_index` (`form`),
  KEY `contact_submissions_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `contact_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_submissions` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(80) NOT NULL DEFAULT 'general',
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `disk` varchar(30) NOT NULL DEFAULT 'public',
  `path` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(120) NOT NULL,
  `extension` varchar(12) NOT NULL,
  `kind` varchar(20) NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_path_unique` (`path`),
  KEY `media_uploaded_by_foreign` (`uploaded_by`),
  KEY `media_original_name_index` (`original_name`),
  KEY `media_kind_index` (`kind`),
  CONSTRAINT `media_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `label` varchar(120) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'url',
  `page_id` bigint(20) unsigned DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `target` varchar(10) NOT NULL DEFAULT '_self',
  `css_class` varchar(120) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  KEY `menu_items_page_id_foreign` (`page_id`),
  KEY `menu_items_menu_id_parent_id_position_index` (`menu_id`,`parent_id`,`position`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `type`, `page_id`, `url`, `target`, `css_class`, `position`, `is_active`, `created_at`, `updated_at`) VALUES (1,1,NULL,'Home','page',2,NULL,'_self',NULL,0,1,'2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `location` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_location_unique` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` (`id`, `name`, `location`, `created_at`, `updated_at`) VALUES (1,'Primary navigation','primary','2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000001_create_cache_table',1),
(2,'2026_01_01_000001_create_auth_tables',1),
(3,'2026_01_01_000002_create_media_table',1),
(4,'2026_01_01_000003_create_pages_tables',1),
(5,'2026_01_01_000004_create_seo_metadata_table',1),
(6,'2026_01_01_000005_create_menus_tables',1),
(7,'2026_01_01_000006_create_blog_tables',1),
(8,'2026_01_01_000007_create_content_modules_tables',1),
(9,'2026_01_01_000008_create_contact_submissions_table',1),
(10,'2026_01_01_000009_create_settings_redirects_logs_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `page_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`snapshot`)),
  `note` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_revisions_created_by_foreign` (`created_by`),
  KEY `page_revisions_page_id_created_at_index` (`page_id`,`created_at`),
  CONSTRAINT `page_revisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `page_revisions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `page_revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_revisions` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `page_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `type` varchar(60) NOT NULL,
  `label` varchar(120) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`content`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_sections_page_id_position_index` (`page_id`,`position`),
  CONSTRAINT `page_sections_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` (`id`, `page_id`, `type`, `label`, `position`, `is_visible`, `content`, `created_at`, `updated_at`) VALUES (1,1,'global_search','Search overlay',0,1,'{\"slots\":{\"s1\":\"\\/\",\"s2\":\"assets\\/imgs\\/template\\/logo\\/favicon.svg\",\"s3\":\"Orisa\",\"s4\":\"Orisa\",\"s5\":\"Find what you need\\u2026\",\"s6\":\"Search\",\"s7\":\"Search\",\"s8\":\"Popular searches\"},\"groups\":{\"g1\":[{\"t1\":\"UI \\/ UX Design\"},{\"t1\":\"Photography\"},{\"t1\":\"Digital Marketing\"},{\"t1\":\"Creative\"},{\"t1\":\"Innovative\"},{\"t1\":\"Visionary\"},{\"t1\":\"Disruptive\"},{\"t1\":\"Adaptive\"},{\"t1\":\"Reliable\"},{\"t1\":\"Scalable\"},{\"t1\":\"Impactful\"},{\"t1\":\"Dynamic\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(2,1,'global_header','Header',1,1,'{\"slots\":{\"s1\":\"\\/\",\"s2\":\"assets\\/imgs\\/template\\/logo\\/favicon-dark.svg\",\"s3\":\"Orisa\",\"s4\":\"Orisa\",\"s5\":\"MENU\"},\"groups\":[]}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(3,1,'global_offcanvas','Side menu panel',2,1,'{\"slots\":{\"s1\":\"\\/\",\"s2\":\"assets\\/imgs\\/template\\/logo\\/favicon.svg\",\"s3\":\"Orisa\",\"s4\":\"Orisa\",\"s5\":\"Howdy!\",\"s6\":\"We blend strategy, creativity, and technology to help brands grow, connect, and stand out in an ever-evolving digital world.\",\"s7\":\"Get in touch\",\"s8\":\"245 Fifth Avenue, Suite 1800\",\"s9\":\"New York, NY 10016, USA\",\"s10\":\"Follow Us\",\"s11\":\"\\/\",\"s12\":\"assets\\/imgs\\/template\\/logo\\/favicon.svg\",\"s13\":\"Orisa\",\"s14\":\"Orisa\",\"s15\":\"CLOSE\"},\"groups\":{\"g1\":[{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-10.webp\",\"t2\":\"orisa\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-11.webp\",\"t2\":\"orisa\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-12.webp\",\"t2\":\"orisa\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-13.webp\",\"t2\":\"orisa\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-14.webp\",\"t2\":\"orisa\"}],\"g2\":[{\"t1\":\"tel:2125557398\",\"t2\":\"(212) 555-7398\"},{\"t1\":\"mailto:hello@aleric.com\",\"t2\":\"hello@nullphpscript.com\"}],\"g3\":[{\"t1\":\"Twitter\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 16 15\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                <path fill-rule=\\\"evenodd\\\" clip-rule=\\\"evenodd\\\" d=\\\"M0 0H5.25L8.98421 5.21053L13.5 0H15.5L9.8895 6.47368L16 15H10.75L7.01579 9.7895L2.5 15H0.5L6.11053 8.52632L0 0ZM11.5204 13.5L2.92043 1.5H4.47957L13.0796 13.5H11.5204Z\\\" fill=\\\"currentColor\\\" \\/>\\n                            <\\/svg>\",\"t3\":\"Twitter\"},{\"t1\":\"Facebook\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 18 18\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                <path d=\\\"M12.4024 18V11.0344H14.7347L15.0838 8.3265H12.4024V6.59765C12.4024 5.81364 12.62 5.27934 13.7443 5.27934L15.1783 5.27867V2.85676C14.9302 2.82382 14.0791 2.75006 13.0888 2.75006C11.0213 2.75006 9.606 4.01198 9.606 6.32952V8.3265H7.2677V11.0344H9.606V18H1C0.44772 18 0 17.5523 0 17V1C0 0.44772 0.44772 0 1 0H17C17.5523 0 18 0.44772 18 1V17C18 17.5523 17.5523 18 17 18H12.4024Z\\\" fill=\\\"currentColor\\\" \\/>\\n                            <\\/svg>\",\"t3\":\"Facebook\"},{\"t1\":\"LinkedIn\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 18 18\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                <path d=\\\"M15.3352 15.339H12.6697V11.1622C12.6697 10.1662 12.6495 8.8845 11.2807 8.8845C9.89102 8.8845 9.67872 9.9683 9.67872 11.0887V15.339H7.01322V6.75H9.57372V7.9207H9.60822C9.96602 7.2457 10.836 6.53325 12.1357 6.53325C14.8365 6.53325 15.336 8.3108 15.336 10.6245L15.3352 15.339ZM4.00275 5.57475C3.14475 5.57475 2.4555 4.88025 2.4555 4.026C2.4555 3.1725 3.1455 2.47875 4.00275 2.47875C4.85775 2.47875 5.55075 3.1725 5.55075 4.026C5.55075 4.88025 4.857 5.57475 4.00275 5.57475ZM5.33925 15.339H2.66625V6.75H5.33925V15.339ZM16.6687 0H1.32825C0.594 0 0 0.5805 0 1.29675V16.7033C0 17.4202 0.594 18 1.32825 18H16.6665C17.4 18 18 17.4202 18 16.7033V1.29675C18 0.5805 17.4 0 16.6665 0H16.6687Z\\\" fill=\\\"currentColor\\\" \\/>\\n                            <\\/svg>\",\"t3\":\"Linkedin\"},{\"t1\":\"Instagram\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 18 18\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                <path d=\\\"M9.92475 0C10.9376 0.00167406 11.4509 0.00703828 11.8945 0.0202418L12.0692 0.025948C12.2709 0.0331213 12.47 0.0421215 12.7101 0.0533719C13.6678 0.0976265 14.3211 0.249129 14.8949 0.471887C15.4881 0.700648 15.9892 1.00966 16.4894 1.50993C16.9889 2.0102 17.298 2.51271 17.5275 3.10448C17.7494 3.67751 17.901 4.33153 17.946 5.28931C17.9567 5.52933 17.9653 5.72841 17.9725 5.93024L17.9781 6.10492C17.9913 6.5484 17.9973 7.06177 17.9992 8.07471L17.9999 8.74577C18 8.82777 18 8.91237 18 8.99967L17.9999 9.25357L17.9994 9.92472C17.9977 10.9376 17.9923 11.451 17.9791 11.8944L17.9734 12.0691C17.9662 12.271 17.9572 12.4701 17.946 12.7101C17.9017 13.6679 17.7494 14.3211 17.5275 14.8949C17.2987 15.4882 16.9889 15.9892 16.4894 16.4895C15.9892 16.989 15.4859 17.298 14.8949 17.5275C14.3211 17.7495 13.6678 17.901 12.7101 17.946C12.47 17.9567 12.2709 17.9654 12.0692 17.9725L11.8945 17.9781C11.4509 17.9914 10.9376 17.9973 9.92475 17.9993L9.25359 18C9.1716 18 9.087 18 8.99969 18H8.7458L8.07464 17.9994C7.06175 17.9977 6.54839 17.9924 6.1049 17.9791L5.93022 17.9734C5.72839 17.9662 5.5293 17.9572 5.2893 17.946C4.33151 17.9018 3.67899 17.7495 3.10448 17.5275C2.51195 17.2988 2.01018 16.989 1.50992 16.4895C1.00965 15.9892 0.701394 15.4859 0.471886 14.8949C0.249129 14.3211 0.0983735 13.6679 0.053372 12.7101C0.0426796 12.4701 0.0339582 12.271 0.026893 12.0691L0.0212318 11.8944C0.00806435 11.451 0.00206123 10.9376 0.000117163 9.92472L0 8.07471C0.00167406 7.06177 0.00702925 6.5484 0.0202327 6.10492L0.0259479 5.93024C0.0331212 5.72841 0.0421216 5.52933 0.053372 5.28931C0.0976175 4.33078 0.249129 3.67825 0.471886 3.10448C0.700638 2.51196 1.00965 2.0102 1.50992 1.50993C2.01018 1.00966 2.51271 0.701396 3.10448 0.471887C3.67825 0.249129 4.33077 0.0983735 5.2893 0.0533719C5.5293 0.0426885 5.72839 0.0339673 5.93022 0.026902L6.1049 0.0212407C6.54839 0.00806417 7.06175 0.00206102 8.07464 0.000116949L9.92475 0ZM8.99969 4.49953C6.51298 4.49953 4.49952 6.51518 4.49952 8.99967C4.49952 11.4864 6.51517 13.4998 8.99969 13.4998C11.4864 13.4998 13.4998 11.4842 13.4998 8.99967C13.4998 6.513 11.4841 4.49953 8.99969 4.49953ZM8.99969 6.2996C10.491 6.2996 11.6998 7.50805 11.6998 8.99967C11.6998 10.4909 10.4913 11.6998 8.99969 11.6998C7.50843 11.6998 6.29958 10.4914 6.29958 8.99967C6.29958 7.50841 7.50798 6.2996 8.99969 6.2996ZM13.7249 3.14949C13.1045 3.14949 12.5998 3.65342 12.5998 4.27376C12.5998 4.89411 13.1037 5.39882 13.7249 5.39882C14.3452 5.39882 14.8499 4.89489 14.8499 4.27376C14.8499 3.65342 14.3443 3.14871 13.7249 3.14949Z\\\" fill=\\\"currentColor\\\" \\/>\\n                            <\\/svg>\",\"t3\":\"Instagram\"},{\"t1\":\"Youtube\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"13\\\" viewBox=\\\"0 0 18 13\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                <path fill-rule=\\\"evenodd\\\" clip-rule=\\\"evenodd\\\" d=\\\"M16.0322 0.388283C16.8071 0.602295 17.4162 1.23059 17.6237 2.03009C17.9985 3.47775 18 6.49997 18 6.49997C18 6.49997 18 9.52214 17.6237 10.9698C17.4162 11.7694 16.8071 12.3977 16.0322 12.6116C14.6291 13 8.99997 13 8.99997 13C8.99997 13 3.37092 13 1.96772 12.6116C1.1928 12.3977 0.583804 11.7694 0.376354 10.9698C-3.83173e-08 9.52214 0 6.49997 0 6.49997C0 6.49997 -3.83173e-08 3.47775 0.376354 2.03009C0.583804 1.23059 1.1928 0.602295 1.96772 0.388283C3.37092 1.18595e-07 8.99997 0 8.99997 0C8.99997 0 14.6291 1.18595e-07 16.0322 0.388283ZM11.8751 6.50027L7.19879 9.2855V3.71499L11.8751 6.50027Z\\\" fill=\\\"currentColor\\\" \\/>\\n                            <\\/svg>\",\"t3\":\"Youtube\"}],\"g4\":[{\"t1\":\"Twitter\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 16 15\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                    <path fill-rule=\\\"evenodd\\\" clip-rule=\\\"evenodd\\\" d=\\\"M0 0H5.25L8.98421 5.21053L13.5 0H15.5L9.8895 6.47368L16 15H10.75L7.01579 9.7895L2.5 15H0.5L6.11053 8.52632L0 0ZM11.5204 13.5L2.92043 1.5H4.47957L13.0796 13.5H11.5204Z\\\" fill=\\\"currentColor\\\" \\/>\\n                                <\\/svg>\",\"t3\":\"Twitter\"},{\"t1\":\"Facebook\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 18 18\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                    <path d=\\\"M12.4024 18V11.0344H14.7347L15.0838 8.3265H12.4024V6.59765C12.4024 5.81364 12.62 5.27934 13.7443 5.27934L15.1783 5.27867V2.85676C14.9302 2.82382 14.0791 2.75006 13.0888 2.75006C11.0213 2.75006 9.606 4.01198 9.606 6.32952V8.3265H7.2677V11.0344H9.606V18H1C0.44772 18 0 17.5523 0 17V1C0 0.44772 0.44772 0 1 0H17C17.5523 0 18 0.44772 18 1V17C18 17.5523 17.5523 18 17 18H12.4024Z\\\" fill=\\\"currentColor\\\" \\/>\\n                                <\\/svg>\",\"t3\":\"Facebook\"},{\"t1\":\"LinkedIn\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 18 18\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                    <path d=\\\"M15.3352 15.339H12.6697V11.1622C12.6697 10.1662 12.6495 8.8845 11.2807 8.8845C9.89102 8.8845 9.67872 9.9683 9.67872 11.0887V15.339H7.01322V6.75H9.57372V7.9207H9.60822C9.96602 7.2457 10.836 6.53325 12.1357 6.53325C14.8365 6.53325 15.336 8.3108 15.336 10.6245L15.3352 15.339ZM4.00275 5.57475C3.14475 5.57475 2.4555 4.88025 2.4555 4.026C2.4555 3.1725 3.1455 2.47875 4.00275 2.47875C4.85775 2.47875 5.55075 3.1725 5.55075 4.026C5.55075 4.88025 4.857 5.57475 4.00275 5.57475ZM5.33925 15.339H2.66625V6.75H5.33925V15.339ZM16.6687 0H1.32825C0.594 0 0 0.5805 0 1.29675V16.7033C0 17.4202 0.594 18 1.32825 18H16.6665C17.4 18 18 17.4202 18 16.7033V1.29675C18 0.5805 17.4 0 16.6665 0H16.6687Z\\\" fill=\\\"currentColor\\\" \\/>\\n                                <\\/svg>\",\"t3\":\"Linkedin\"},{\"t1\":\"Instagram\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"18\\\" viewBox=\\\"0 0 18 18\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                    <path d=\\\"M9.92475 0C10.9376 0.00167406 11.4509 0.00703828 11.8945 0.0202418L12.0692 0.025948C12.2709 0.0331213 12.47 0.0421215 12.7101 0.0533719C13.6678 0.0976265 14.3211 0.249129 14.8949 0.471887C15.4881 0.700648 15.9892 1.00966 16.4894 1.50993C16.9889 2.0102 17.298 2.51271 17.5275 3.10448C17.7494 3.67751 17.901 4.33153 17.946 5.28931C17.9567 5.52933 17.9653 5.72841 17.9725 5.93024L17.9781 6.10492C17.9913 6.5484 17.9973 7.06177 17.9992 8.07471L17.9999 8.74577C18 8.82777 18 8.91237 18 8.99967L17.9999 9.25357L17.9994 9.92472C17.9977 10.9376 17.9923 11.451 17.9791 11.8944L17.9734 12.0691C17.9662 12.271 17.9572 12.4701 17.946 12.7101C17.9017 13.6679 17.7494 14.3211 17.5275 14.8949C17.2987 15.4882 16.9889 15.9892 16.4894 16.4895C15.9892 16.989 15.4859 17.298 14.8949 17.5275C14.3211 17.7495 13.6678 17.901 12.7101 17.946C12.47 17.9567 12.2709 17.9654 12.0692 17.9725L11.8945 17.9781C11.4509 17.9914 10.9376 17.9973 9.92475 17.9993L9.25359 18C9.1716 18 9.087 18 8.99969 18H8.7458L8.07464 17.9994C7.06175 17.9977 6.54839 17.9924 6.1049 17.9791L5.93022 17.9734C5.72839 17.9662 5.5293 17.9572 5.2893 17.946C4.33151 17.9018 3.67899 17.7495 3.10448 17.5275C2.51195 17.2988 2.01018 16.989 1.50992 16.4895C1.00965 15.9892 0.701394 15.4859 0.471886 14.8949C0.249129 14.3211 0.0983735 13.6679 0.053372 12.7101C0.0426796 12.4701 0.0339582 12.271 0.026893 12.0691L0.0212318 11.8944C0.00806435 11.451 0.00206123 10.9376 0.000117163 9.92472L0 8.07471C0.00167406 7.06177 0.00702925 6.5484 0.0202327 6.10492L0.0259479 5.93024C0.0331212 5.72841 0.0421216 5.52933 0.053372 5.28931C0.0976175 4.33078 0.249129 3.67825 0.471886 3.10448C0.700638 2.51196 1.00965 2.0102 1.50992 1.50993C2.01018 1.00966 2.51271 0.701396 3.10448 0.471887C3.67825 0.249129 4.33077 0.0983735 5.2893 0.0533719C5.5293 0.0426885 5.72839 0.0339673 5.93022 0.026902L6.1049 0.0212407C6.54839 0.00806417 7.06175 0.00206102 8.07464 0.000116949L9.92475 0ZM8.99969 4.49953C6.51298 4.49953 4.49952 6.51518 4.49952 8.99967C4.49952 11.4864 6.51517 13.4998 8.99969 13.4998C11.4864 13.4998 13.4998 11.4842 13.4998 8.99967C13.4998 6.513 11.4841 4.49953 8.99969 4.49953ZM8.99969 6.2996C10.491 6.2996 11.6998 7.50805 11.6998 8.99967C11.6998 10.4909 10.4913 11.6998 8.99969 11.6998C7.50843 11.6998 6.29958 10.4914 6.29958 8.99967C6.29958 7.50841 7.50798 6.2996 8.99969 6.2996ZM13.7249 3.14949C13.1045 3.14949 12.5998 3.65342 12.5998 4.27376C12.5998 4.89411 13.1037 5.39882 13.7249 5.39882C14.3452 5.39882 14.8499 4.89489 14.8499 4.27376C14.8499 3.65342 14.3443 3.14871 13.7249 3.14949Z\\\" fill=\\\"currentColor\\\" \\/>\\n                                <\\/svg>\",\"t3\":\"Instagram\"},{\"t1\":\"Youtube\",\"t2\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"18\\\" height=\\\"13\\\" viewBox=\\\"0 0 18 13\\\" fill=\\\"none\\\" aria-hidden=\\\"true\\\">\\n                                    <path fill-rule=\\\"evenodd\\\" clip-rule=\\\"evenodd\\\" d=\\\"M16.0322 0.388283C16.8071 0.602295 17.4162 1.23059 17.6237 2.03009C17.9985 3.47775 18 6.49997 18 6.49997C18 6.49997 18 9.52214 17.6237 10.9698C17.4162 11.7694 16.8071 12.3977 16.0322 12.6116C14.6291 13 8.99997 13 8.99997 13C8.99997 13 3.37092 13 1.96772 12.6116C1.1928 12.3977 0.583804 11.7694 0.376354 10.9698C-3.83173e-08 9.52214 0 6.49997 0 6.49997C0 6.49997 -3.83173e-08 3.47775 0.376354 2.03009C0.583804 1.23059 1.1928 0.602295 1.96772 0.388283C3.37092 1.18595e-07 8.99997 0 8.99997 0C8.99997 0 14.6291 1.18595e-07 16.0322 0.388283ZM11.8751 6.50027L7.19879 9.2855V3.71499L11.8751 6.50027Z\\\" fill=\\\"currentColor\\\" \\/>\\n                                <\\/svg>\",\"t3\":\"Youtube\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(4,1,'global_footer','Footer',3,1,'{\"slots\":{\"s1\":\"assets\\/imgs\\/template\\/logo\\/favicon-dark.svg\",\"s2\":\"Orisa\",\"s3\":\"Let\\u2019s Shape\",\"s4\":\"Your Next Idea\",\"s5\":\"205 North Michigan Avenue, Suite 810\",\"s6\":\"Chicago, 60601, USA\",\"s7\":\"(212) 555-7398\",\"s8\":\"hello@orisa.com\",\"s9\":\"Navigation\",\"s10\":\"Orisa Studio\",\"s11\":\"\\u00ae\",\"s12\":\"Orisa \\u00a9 2026\",\"s13\":\"[ Since 2012 ]\"},\"groups\":{\"g1\":[{\"t1\":\"Twitter\"},{\"t1\":\"Facebook\"},{\"t1\":\"Instagram\"},{\"t1\":\"Linkedin\"},{\"t1\":\"Behance\"},{\"t1\":\"Dribbble\"}],\"g2\":[{\"t1\":\"\\/\",\"t2\":\"Home\"},{\"t1\":\"\\/about\",\"t2\":\"About\"},{\"t1\":\"\\/portfolio\",\"t2\":\"Works\"},{\"t1\":\"\\/blog\",\"t2\":\"Blog\"},{\"t1\":\"\\/contact\",\"t2\":\"Contact\"}],\"g3\":[{\"t1\":\"#\",\"t2\":\"Shop\"},{\"t1\":\"\\/pricing\",\"t2\":\"Pricing\"},{\"t1\":\"#\",\"t2\":\"Cart\"},{\"t1\":\"#\",\"t2\":\"Checkout\"}],\"g4\":[{\"t1\":\"Web Development\",\"t2\":\"Web Development\"},{\"t1\":\"Motion Graphics\",\"t2\":\"Motion Graphics\"},{\"t1\":\"Brand Strategy\",\"t2\":\"Brand Strategy\"},{\"t1\":\"Product Design\",\"t2\":\"Product Design\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(5,2,'home_hero','Hero',0,1,'{\"slots\":{\"s1\":\"assets\\/imgs\\/pages\\/bg-img.webp\",\"s2\":\"assets\\/imgs\\/pages\\/img-1.webp\",\"s3\":\"orisa\",\"s4\":\"mailto:hello@orisa.com\",\"s5\":\"assets\\/imgs\\/icons\\/badge-1.svg\",\"s6\":\"orisa\",\"s7\":\"Let\\u2019s Talk\",\"s8\":\"assets\\/imgs\\/pages\\/img-2.webp\",\"s9\":\"assets\\/imgs\\/video\\/video-1.mp4\",\"s10\":\"#\",\"s11\":\"How we work\",\"s12\":\"How we work\",\"s13\":\"We partner with brands to create digital design that drives conversions and commands attention.\",\"s14\":\"B2B Marketing Agency\",\"s15\":\"Designing with\",\"s16\":\"imagination,\",\"s17\":\"driven by purpose.\",\"s18\":\"\\/portfolio\",\"s19\":\"Explore All Work\",\"s20\":\"Explore All Work\",\"s21\":\"\\/contact\",\"s22\":\"Get a free quote\",\"s23\":\"Get a free quote\",\"s24\":\"mailto:hello@orisa.com\",\"s25\":\"hello@nullphpscript.com\"},\"groups\":{\"g1\":[{\"t1\":\"Web Development\",\"t2\":\"Web Development\"},{\"t1\":\"Motion Graphics\",\"t2\":\"Motion Graphics\"},{\"t1\":\"Brand Strategy\",\"t2\":\"Brand Strategy\"},{\"t1\":\"Product Design\",\"t2\":\"Product Design\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(6,2,'home_about','Introduction / Expertise',1,1,'{\"slots\":{\"s1\":\"About Us\",\"s2\":\"About Us\",\"s3\":\"We shape animated stories that inspire and engage, uniting thoughtful design, fluid motion, and digital craftsmanship.\",\"s4\":\"\\/contact\",\"s5\":\"GET IN TOUCH\",\"s6\":\"GET IN TOUCH\",\"s7\":\"We build\",\"s8\":\"bold\",\"s9\":\", resilient brands designed to leave a lasting\",\"s10\":\"mark on the world.\",\"s11\":\"\\/team\\/details\",\"s12\":\"assets\\/imgs\\/template\\/avatar\\/avatar-1.webp\",\"s13\":\"orisa\",\"s14\":\"\\/team\\/details\",\"s15\":\"assets\\/imgs\\/template\\/avatar\\/avatar-2.webp\",\"s16\":\"orisa\",\"s17\":\"\\/team\\/details\",\"s18\":\"assets\\/imgs\\/template\\/avatar\\/avatar-3.webp\",\"s19\":\"orisa\",\"s20\":\"15\",\"s21\":\"+\",\"s22\":\"assets\\/imgs\\/pages\\/img-3.webp\",\"s23\":\"orisa\",\"s24\":\"Creative Expertise\",\"s25\":\"With over a decade of design expertise, we create tailored solutions that engage audiences, build meaningful connections, and elevate brands with creativity and intent.\",\"s26\":\"Experience & Innovation\",\"s27\":\"Backed by a decade of creative experience, we craft visual experiences that bring together strategy, design, and technology to grow brands, inspire audiences, and create meaningful impact.\",\"s28\":\"assets\\/imgs\\/pages\\/img-4.webp\",\"s29\":\"orisa\"},\"groups\":{\"g1\":[{\"t1\":\"\\/team\\/details\",\"t2\":\"assets\\/imgs\\/template\\/avatar\\/avatar-4.webp\",\"t3\":\"orisa\"},{\"t1\":\"\\/team\\/details\",\"t2\":\"assets\\/imgs\\/template\\/avatar\\/avatar-5.webp\",\"t3\":\"orisa\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(7,2,'home_brands','Client logos',2,1,'{\"slots\":[],\"groups\":{\"g1\":[{\"t1\":\"assets\\/imgs\\/icons\\/brand-1.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-2.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-3.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-4.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-5.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-6.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-1.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-2.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-3.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-5.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-6.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-1.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-2.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-3.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-4.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-5.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-6.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-1.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-2.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-3.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-4.webp\",\"t2\":\"logo-brand\"},{\"t1\":\"assets\\/imgs\\/icons\\/brand-5.webp\",\"t2\":\"logo-brand\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(8,2,'home_services','Services',3,1,'{\"slots\":{\"s1\":\"OUR SOLUTIONS\",\"s2\":\"OUR SOLUTIONS\",\"s3\":\"Since 2012\",\"s4\":\"38\",\"s5\":\"+\",\"s6\":\"Completed projects\",\"s7\":\"\\/contact\",\"s8\":\"Get a free quote\",\"s9\":\"Get a free quote\"},\"groups\":{\"g1\":[{\"t1\":\"assets\\/imgs\\/pages\\/img-5.webp\",\"t2\":\"Service Image\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-6.webp\",\"t2\":\"Service Image\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-7.webp\",\"t2\":\"Service Image\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-8.webp\",\"t2\":\"Service Image\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-9.webp\",\"t2\":\"Service Image\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-10.webp\",\"t2\":\"Service Image\"}],\"g2\":[{\"t1\":\"\\/services\\/details\",\"t2\":\"[01]\",\"t3\":\"UI\\/UX Design\",\"t4\":\"We help you build successful products by\",\"t5\":\"understanding your market and users.\",\"t6\":\"assets\\/imgs\\/pages\\/img-5-sm.webp\",\"t7\":\"orisa\"},{\"t1\":\"\\/services\\/details\",\"t2\":\"[02]\",\"t3\":\"Branding\",\"t4\":\"We help you build successful products by\",\"t5\":\"understanding your market and users.\",\"t6\":\"assets\\/imgs\\/pages\\/img-6-sm.webp\",\"t7\":\"orisa\"},{\"t1\":\"\\/services\\/details\",\"t2\":\"[03]\",\"t3\":\"Development\",\"t4\":\"We help you build successful products by\",\"t5\":\"understanding your market and users.\",\"t6\":\"assets\\/imgs\\/pages\\/img-7-sm.webp\",\"t7\":\"orisa\"},{\"t1\":\"\\/services\\/details\",\"t2\":\"[04]\",\"t3\":\"3D Animations\",\"t4\":\"We help you build successful products by\",\"t5\":\"understanding your market and users.\",\"t6\":\"assets\\/imgs\\/pages\\/img-8-sm.webp\",\"t7\":\"orisa\"},{\"t1\":\"\\/services\\/details\",\"t2\":\"[05]\",\"t3\":\"Marketing\",\"t4\":\"We help you build successful products by\",\"t5\":\"understanding your market and users.\",\"t6\":\"assets\\/imgs\\/pages\\/img-9-sm.webp\",\"t7\":\"orisa\"},{\"t1\":\"\\/services\\/details\",\"t2\":\"[06]\",\"t3\":\"Graphics\",\"t4\":\"We help you build successful products by\",\"t5\":\"understanding your market and users.\",\"t6\":\"assets\\/imgs\\/pages\\/img-10-sm.webp\",\"t7\":\"orisa\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(9,2,'home_portfolio','Featured work',4,1,'{\"slots\":{\"s1\":\"Selected work we\\u2019re proud of\",\"s2\":\"A curated selection of projects where strategy, creativity, and craftsmanship come together to build meaningful and enduring brand experiences.\",\"s3\":\"\\/portfolio\",\"s4\":\"\\/portfolio\",\"s5\":\"View latest projects\",\"s6\":\"\\/portfolio\",\"s7\":\"\\/portfolio\\/details\",\"s8\":\"assets\\/imgs\\/pages\\/img-14.webp\",\"s9\":\"Lumen\",\"s10\":\"\\/portfolio\\/details\",\"s11\":\"Lumen\",\"s12\":\"Branding system for modern startups\"},\"groups\":{\"g1\":[{\"t1\":\"\\/portfolio\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-11.webp\",\"t3\":\"Noirform\",\"t4\":\"\\/portfolio\\/details\",\"t5\":\"Noirform\",\"t6\":\"Brand art direction & visual identity\",\"t7\":\"Branding\",\"t8\":\"Web Design\",\"t9\":\"Web development\"},{\"t1\":\"\\/portfolio\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-12.webp\",\"t3\":\"Nebula\",\"t4\":\"\\/portfolio\\/details\",\"t5\":\"Nebula\",\"t6\":\"UI\\/UX & product design for digital platforms\",\"t7\":\"Branding\",\"t8\":\"Web Design\",\"t9\":\"Web development\"},{\"t1\":\"\\/portfolio\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-13.webp\",\"t3\":\"Voidline\",\"t4\":\"\\/portfolio\\/details\",\"t5\":\"Voidline\",\"t6\":\"3D animation & motion branding\",\"t7\":\"Branding\",\"t8\":\"Web Design\",\"t9\":\"Web development\"}],\"g2\":[{\"t1\":\"brand strategy\"},{\"t1\":\"visual identity\"},{\"t1\":\"startup branding\"},{\"t1\":\"design system\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(10,2,'home_testimonials','Testimonials',5,1,'{\"slots\":{\"s1\":\"Trusted by Clients\",\"s2\":\"Real client experiences that speak to the strength of our work.\",\"s3\":\"mailto:hello@orisa.com\",\"s4\":\"assets\\/imgs\\/icons\\/badge-2.svg\",\"s5\":\"orisa\",\"s6\":\"Client\",\"s7\":\"Stories\"},\"groups\":{\"g1\":[{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-6.webp\",\"t2\":\"orisa\",\"t3\":\"assets\\/imgs\\/icons\\/brand-7.webp\",\"t4\":\"orisa\",\"t5\":\"\\u201cThey delivered not just a design, but a complete brand experience. Strategic, creative, and incredibly detail-oriented.\\u201d\",\"t6\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"101\\\" height=\\\"16\\\" viewBox=\\\"0 0 101 16\\\" fill=\\\"none\\\">\\n                            <path\\n                                d=\\\"M14.9062 6.87488L11.8019 9.91088L12.535 14.1989C12.5507 14.2908 12.5405 14.3853 12.5054 14.4717C12.4704 14.5581 12.4119 14.633 12.3367 14.6879C12.2615 14.7427 12.1725 14.7753 12.0797 14.782C11.987 14.7887 11.8942 14.7693 11.8119 14.7259L7.97358 12.7013L4.13575 14.7253C4.05345 14.7688 3.96068 14.7882 3.86794 14.7815C3.7752 14.7748 3.68618 14.7421 3.61095 14.6873C3.53572 14.6325 3.47728 14.5577 3.44223 14.4713C3.40718 14.3849 3.39693 14.2903 3.41264 14.1983L4.14572 9.91035L1.04096 6.87488C0.974323 6.80974 0.927186 6.72714 0.904899 6.63654C0.882606 6.54591 0.886041 6.45083 0.914826 6.36206C0.943611 6.27329 0.99659 6.19438 1.06776 6.13426C1.13893 6.07414 1.22546 6.0352 1.31755 6.02187L5.60789 5.39687L7.52653 1.49587C7.6945 1.15437 8.25266 1.15437 8.42058 1.49587L10.3392 5.39687L14.6296 6.02187C14.7215 6.03556 14.8078 6.07468 14.8787 6.13484C14.9496 6.19501 15.0024 6.27383 15.0312 6.36245C15.0599 6.45107 15.0634 6.54598 15.0413 6.6365C15.0193 6.72701 14.9725 6.80954 14.9062 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M36.1694 6.87488L33.0651 9.91088L33.7982 14.1989C33.8139 14.2908 33.8037 14.3853 33.7686 14.4717C33.7336 14.5581 33.6751 14.633 33.5999 14.6879C33.5247 14.7427 33.4357 14.7753 33.3429 14.782C33.2502 14.7887 33.1574 14.7693 33.0751 14.7259L29.2368 12.7013L25.3989 14.7253C25.3166 14.7688 25.2239 14.7882 25.1311 14.7815C25.0384 14.7748 24.9494 14.7421 24.8741 14.6873C24.7989 14.6325 24.7405 14.5577 24.7054 14.4713C24.6704 14.3849 24.6601 14.2903 24.6758 14.1983L25.4089 9.91035L22.3041 6.87488C22.2375 6.80974 22.1904 6.72714 22.1681 6.63654C22.1458 6.54591 22.1492 6.45083 22.178 6.36206C22.2068 6.27329 22.2598 6.19438 22.331 6.13426C22.4021 6.07414 22.4886 6.0352 22.5807 6.02187L26.871 5.39687L28.7897 1.49587C28.9577 1.15437 29.5159 1.15437 29.6838 1.49587L31.6024 5.39687L35.8928 6.02187C35.9847 6.03556 36.071 6.07468 36.1419 6.13484C36.2128 6.19501 36.2656 6.27383 36.2943 6.36245C36.3231 6.45107 36.3266 6.54598 36.3045 6.6365C36.2825 6.72701 36.2357 6.80954 36.1694 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M57.4325 6.87488L54.3283 9.91088L55.0614 14.1989C55.0771 14.2908 55.0669 14.3853 55.0318 14.4717C54.9968 14.5581 54.9383 14.633 54.8631 14.6879C54.7879 14.7427 54.6988 14.7753 54.6061 14.782C54.5134 14.7887 54.4206 14.7693 54.3383 14.7259L50.5 12.7013L46.6621 14.7253C46.5798 14.7688 46.4871 14.7882 46.3943 14.7815C46.3015 14.7748 46.2126 14.7421 46.1373 14.6873C46.0621 14.6325 46.0037 14.5577 45.9686 14.4713C45.9336 14.3849 45.9233 14.2903 45.939 14.1983L46.6721 9.91035L43.5673 6.87488C43.5007 6.80974 43.4535 6.72714 43.4313 6.63654C43.409 6.54591 43.4124 6.45083 43.4412 6.36206C43.47 6.27329 43.523 6.19438 43.5942 6.13426C43.6653 6.07414 43.7518 6.0352 43.8439 6.02187L48.1342 5.39687L50.0529 1.49587C50.2209 1.15437 50.779 1.15437 50.9469 1.49587L52.8656 5.39687L57.156 6.02187C57.2479 6.03556 57.3341 6.07468 57.405 6.13484C57.476 6.19501 57.5288 6.27383 57.5575 6.36245C57.5862 6.45107 57.5898 6.54598 57.5677 6.6365C57.5456 6.72701 57.4989 6.80954 57.4325 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M78.6956 6.87488L75.5918 9.91088L76.3248 14.1989C76.34 14.2908 76.3301 14.3853 76.2949 14.4717C76.2596 14.5581 76.2018 14.633 76.1261 14.6879C76.051 14.7427 75.962 14.7753 75.8696 14.782C75.7766 14.7887 75.6835 14.7693 75.6018 14.7259L71.7631 12.7013L67.9251 14.7253C67.8427 14.7688 67.7504 14.7882 67.6574 14.7815C67.565 14.7748 67.476 14.7421 67.4002 14.6873C67.3251 14.6325 67.2666 14.5577 67.2321 14.4713C67.1969 14.3849 67.1862 14.2903 67.2022 14.1983L67.9351 9.91035L64.8306 6.87488C64.7639 6.80974 64.7167 6.72714 64.6945 6.63654C64.6721 6.54591 64.6756 6.45083 64.7044 6.36206C64.7331 6.27329 64.7862 6.19438 64.8573 6.13426C64.9285 6.07414 65.015 6.0352 65.1071 6.02187L69.3976 5.39687L71.316 1.49587C71.4841 1.15437 72.0422 1.15437 72.2103 1.49587L74.1287 5.39687L78.4192 6.02187C78.5109 6.03556 78.5973 6.07468 78.6684 6.13484C78.7394 6.19501 78.7919 6.27383 78.8205 6.36245C78.8498 6.45107 78.8531 6.54598 78.8311 6.6365C78.8086 6.72701 78.762 6.80954 78.6956 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M99.9588 6.87488L96.855 9.91088L97.588 14.1989C97.6032 14.2908 97.5933 14.3853 97.5581 14.4717C97.5228 14.5581 97.465 14.633 97.3893 14.6879C97.3142 14.7427 97.2252 14.7753 97.1328 14.782C97.0398 14.7887 96.9467 14.7693 96.865 14.7259L93.0263 12.7013L89.1883 14.7253C89.106 14.7688 89.0136 14.7882 88.9206 14.7815C88.8282 14.7748 88.7392 14.7421 88.6634 14.6873C88.5883 14.6325 88.5299 14.5577 88.4953 14.4713C88.4601 14.3849 88.4495 14.2903 88.4654 14.1983L89.1983 9.91035L86.0939 6.87488C86.0268 6.80974 85.9803 6.72714 85.9577 6.63654C85.9351 6.54591 85.9391 6.45083 85.9676 6.36206C85.9962 6.27329 86.0494 6.19438 86.1205 6.13426C86.1916 6.07414 86.278 6.0352 86.3703 6.02187L90.6608 5.39687L92.5792 1.49587C92.7473 1.15437 93.3054 1.15437 93.4735 1.49587L95.3919 5.39687L99.6824 6.02187C99.7741 6.03556 99.8605 6.07468 99.9316 6.13484C100.003 6.19501 100.055 6.27383 100.084 6.36245C100.113 6.45107 100.116 6.54598 100.094 6.6365C100.072 6.72701 100.025 6.80954 99.9588 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                        <\\/svg>\",\"t7\":\"Amelia Wright\",\"t8\":\"Head of Marketing\",\"t9\":\"London United Kingdom\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-7.webp\",\"t2\":\"orisa\",\"t3\":\"assets\\/imgs\\/icons\\/brand-8.webp\",\"t4\":\"orisa\",\"t5\":\"\\u201cThe collaboration was seamless from start to finish. Their UX decisions significantly improved our product engagement.\\u201d\",\"t6\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"101\\\" height=\\\"16\\\" viewBox=\\\"0 0 101 16\\\" fill=\\\"none\\\">\\n                            <path\\n                                d=\\\"M14.9062 6.87488L11.8019 9.91088L12.535 14.1989C12.5507 14.2908 12.5405 14.3853 12.5054 14.4717C12.4704 14.5581 12.4119 14.633 12.3367 14.6879C12.2615 14.7427 12.1725 14.7753 12.0797 14.782C11.987 14.7887 11.8942 14.7693 11.8119 14.7259L7.97358 12.7013L4.13575 14.7253C4.05345 14.7688 3.96068 14.7882 3.86794 14.7815C3.7752 14.7748 3.68618 14.7421 3.61095 14.6873C3.53572 14.6325 3.47728 14.5577 3.44223 14.4713C3.40718 14.3849 3.39693 14.2903 3.41264 14.1983L4.14572 9.91035L1.04096 6.87488C0.974323 6.80974 0.927186 6.72714 0.904899 6.63654C0.882606 6.54591 0.886041 6.45083 0.914826 6.36206C0.943611 6.27329 0.99659 6.19438 1.06776 6.13426C1.13893 6.07414 1.22546 6.0352 1.31755 6.02187L5.60789 5.39687L7.52653 1.49587C7.6945 1.15437 8.25266 1.15437 8.42058 1.49587L10.3392 5.39687L14.6296 6.02187C14.7215 6.03556 14.8078 6.07468 14.8787 6.13484C14.9496 6.19501 15.0024 6.27383 15.0312 6.36245C15.0599 6.45107 15.0634 6.54598 15.0413 6.6365C15.0193 6.72701 14.9725 6.80954 14.9062 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M36.1694 6.87488L33.0651 9.91088L33.7982 14.1989C33.8139 14.2908 33.8037 14.3853 33.7686 14.4717C33.7336 14.5581 33.6751 14.633 33.5999 14.6879C33.5247 14.7427 33.4357 14.7753 33.3429 14.782C33.2502 14.7887 33.1574 14.7693 33.0751 14.7259L29.2368 12.7013L25.3989 14.7253C25.3166 14.7688 25.2239 14.7882 25.1311 14.7815C25.0384 14.7748 24.9494 14.7421 24.8741 14.6873C24.7989 14.6325 24.7405 14.5577 24.7054 14.4713C24.6704 14.3849 24.6601 14.2903 24.6758 14.1983L25.4089 9.91035L22.3041 6.87488C22.2375 6.80974 22.1904 6.72714 22.1681 6.63654C22.1458 6.54591 22.1492 6.45083 22.178 6.36206C22.2068 6.27329 22.2598 6.19438 22.331 6.13426C22.4021 6.07414 22.4886 6.0352 22.5807 6.02187L26.871 5.39687L28.7897 1.49587C28.9577 1.15437 29.5159 1.15437 29.6838 1.49587L31.6024 5.39687L35.8928 6.02187C35.9847 6.03556 36.071 6.07468 36.1419 6.13484C36.2128 6.19501 36.2656 6.27383 36.2943 6.36245C36.3231 6.45107 36.3266 6.54598 36.3045 6.6365C36.2825 6.72701 36.2357 6.80954 36.1694 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M57.4325 6.87488L54.3283 9.91088L55.0614 14.1989C55.0771 14.2908 55.0669 14.3853 55.0318 14.4717C54.9968 14.5581 54.9383 14.633 54.8631 14.6879C54.7879 14.7427 54.6988 14.7753 54.6061 14.782C54.5134 14.7887 54.4206 14.7693 54.3383 14.7259L50.5 12.7013L46.6621 14.7253C46.5798 14.7688 46.4871 14.7882 46.3943 14.7815C46.3015 14.7748 46.2126 14.7421 46.1373 14.6873C46.0621 14.6325 46.0037 14.5577 45.9686 14.4713C45.9336 14.3849 45.9233 14.2903 45.939 14.1983L46.6721 9.91035L43.5673 6.87488C43.5007 6.80974 43.4535 6.72714 43.4313 6.63654C43.409 6.54591 43.4124 6.45083 43.4412 6.36206C43.47 6.27329 43.523 6.19438 43.5942 6.13426C43.6653 6.07414 43.7518 6.0352 43.8439 6.02187L48.1342 5.39687L50.0529 1.49587C50.2209 1.15437 50.779 1.15437 50.9469 1.49587L52.8656 5.39687L57.156 6.02187C57.2479 6.03556 57.3341 6.07468 57.405 6.13484C57.476 6.19501 57.5288 6.27383 57.5575 6.36245C57.5862 6.45107 57.5898 6.54598 57.5677 6.6365C57.5456 6.72701 57.4989 6.80954 57.4325 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M78.6956 6.87488L75.5918 9.91088L76.3248 14.1989C76.34 14.2908 76.3301 14.3853 76.2949 14.4717C76.2596 14.5581 76.2018 14.633 76.1261 14.6879C76.051 14.7427 75.962 14.7753 75.8696 14.782C75.7766 14.7887 75.6835 14.7693 75.6018 14.7259L71.7631 12.7013L67.9251 14.7253C67.8427 14.7688 67.7504 14.7882 67.6574 14.7815C67.565 14.7748 67.476 14.7421 67.4002 14.6873C67.3251 14.6325 67.2666 14.5577 67.2321 14.4713C67.1969 14.3849 67.1862 14.2903 67.2022 14.1983L67.9351 9.91035L64.8306 6.87488C64.7639 6.80974 64.7167 6.72714 64.6945 6.63654C64.6721 6.54591 64.6756 6.45083 64.7044 6.36206C64.7331 6.27329 64.7862 6.19438 64.8573 6.13426C64.9285 6.07414 65.015 6.0352 65.1071 6.02187L69.3976 5.39687L71.316 1.49587C71.4841 1.15437 72.0422 1.15437 72.2103 1.49587L74.1287 5.39687L78.4192 6.02187C78.5109 6.03556 78.5973 6.07468 78.6684 6.13484C78.7394 6.19501 78.7919 6.27383 78.8205 6.36245C78.8498 6.45107 78.8531 6.54598 78.8311 6.6365C78.8086 6.72701 78.762 6.80954 78.6956 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M99.9588 6.87488L96.855 9.91088L97.588 14.1989C97.6032 14.2908 97.5933 14.3853 97.5581 14.4717C97.5228 14.5581 97.465 14.633 97.3893 14.6879C97.3142 14.7427 97.2252 14.7753 97.1328 14.782C97.0398 14.7887 96.9467 14.7693 96.865 14.7259L93.0263 12.7013L89.1883 14.7253C89.106 14.7688 89.0136 14.7882 88.9206 14.7815C88.8282 14.7748 88.7392 14.7421 88.6634 14.6873C88.5883 14.6325 88.5299 14.5577 88.4953 14.4713C88.4601 14.3849 88.4495 14.2903 88.4654 14.1983L89.1983 9.91035L86.0939 6.87488C86.0268 6.80974 85.9803 6.72714 85.9577 6.63654C85.9351 6.54591 85.9391 6.45083 85.9676 6.36206C85.9962 6.27329 86.0494 6.19438 86.1205 6.13426C86.1916 6.07414 86.278 6.0352 86.3703 6.02187L90.6608 5.39687L92.5792 1.49587C92.7473 1.15437 93.3054 1.15437 93.4735 1.49587L95.3919 5.39687L99.6824 6.02187C99.7741 6.03556 99.8605 6.07468 99.9316 6.13484C100.003 6.19501 100.055 6.27383 100.084 6.36245C100.113 6.45107 100.116 6.54598 100.094 6.6365C100.072 6.72701 100.025 6.80954 99.9588 6.87488Z\\\"\\n                                fill=\\\"#585959\\\" \\/>\\n                        <\\/svg>\",\"t7\":\"Lucas Moreno\",\"t8\":\"Product Manager\",\"t9\":\"Barcelona, Spain\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-8.webp\",\"t2\":\"orisa\",\"t3\":\"assets\\/imgs\\/icons\\/brand-9.webp\",\"t4\":\"orisa\",\"t5\":\"\\u201cA rare combination of technical expertise and artistic vision. The final result felt premium and purposeful.\\u201d\",\"t6\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"101\\\" height=\\\"16\\\" viewBox=\\\"0 0 101 16\\\" fill=\\\"none\\\">\\n                            <path\\n                                d=\\\"M14.9062 6.87488L11.8019 9.91088L12.535 14.1989C12.5507 14.2908 12.5405 14.3853 12.5054 14.4717C12.4704 14.5581 12.4119 14.633 12.3367 14.6879C12.2615 14.7427 12.1725 14.7753 12.0797 14.782C11.987 14.7887 11.8942 14.7693 11.8119 14.7259L7.97358 12.7013L4.13575 14.7253C4.05345 14.7688 3.96068 14.7882 3.86794 14.7815C3.7752 14.7748 3.68618 14.7421 3.61095 14.6873C3.53572 14.6325 3.47728 14.5577 3.44223 14.4713C3.40718 14.3849 3.39693 14.2903 3.41264 14.1983L4.14572 9.91035L1.04096 6.87488C0.974323 6.80974 0.927186 6.72714 0.904899 6.63654C0.882606 6.54591 0.886041 6.45083 0.914826 6.36206C0.943611 6.27329 0.99659 6.19438 1.06776 6.13426C1.13893 6.07414 1.22546 6.0352 1.31755 6.02187L5.60789 5.39687L7.52653 1.49587C7.6945 1.15437 8.25266 1.15437 8.42058 1.49587L10.3392 5.39687L14.6296 6.02187C14.7215 6.03556 14.8078 6.07468 14.8787 6.13484C14.9496 6.19501 15.0024 6.27383 15.0312 6.36245C15.0599 6.45107 15.0634 6.54598 15.0413 6.6365C15.0193 6.72701 14.9725 6.80954 14.9062 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M36.1694 6.87488L33.0651 9.91088L33.7982 14.1989C33.8139 14.2908 33.8037 14.3853 33.7686 14.4717C33.7336 14.5581 33.6751 14.633 33.5999 14.6879C33.5247 14.7427 33.4357 14.7753 33.3429 14.782C33.2502 14.7887 33.1574 14.7693 33.0751 14.7259L29.2368 12.7013L25.3989 14.7253C25.3166 14.7688 25.2239 14.7882 25.1311 14.7815C25.0384 14.7748 24.9494 14.7421 24.8741 14.6873C24.7989 14.6325 24.7405 14.5577 24.7054 14.4713C24.6704 14.3849 24.6601 14.2903 24.6758 14.1983L25.4089 9.91035L22.3041 6.87488C22.2375 6.80974 22.1904 6.72714 22.1681 6.63654C22.1458 6.54591 22.1492 6.45083 22.178 6.36206C22.2068 6.27329 22.2598 6.19438 22.331 6.13426C22.4021 6.07414 22.4886 6.0352 22.5807 6.02187L26.871 5.39687L28.7897 1.49587C28.9577 1.15437 29.5159 1.15437 29.6838 1.49587L31.6024 5.39687L35.8928 6.02187C35.9847 6.03556 36.071 6.07468 36.1419 6.13484C36.2128 6.19501 36.2656 6.27383 36.2943 6.36245C36.3231 6.45107 36.3266 6.54598 36.3045 6.6365C36.2825 6.72701 36.2357 6.80954 36.1694 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M57.4325 6.87488L54.3283 9.91088L55.0614 14.1989C55.0771 14.2908 55.0669 14.3853 55.0318 14.4717C54.9968 14.5581 54.9383 14.633 54.8631 14.6879C54.7879 14.7427 54.6988 14.7753 54.6061 14.782C54.5134 14.7887 54.4206 14.7693 54.3383 14.7259L50.5 12.7013L46.6621 14.7253C46.5798 14.7688 46.4871 14.7882 46.3943 14.7815C46.3015 14.7748 46.2126 14.7421 46.1373 14.6873C46.0621 14.6325 46.0037 14.5577 45.9686 14.4713C45.9336 14.3849 45.9233 14.2903 45.939 14.1983L46.6721 9.91035L43.5673 6.87488C43.5007 6.80974 43.4535 6.72714 43.4313 6.63654C43.409 6.54591 43.4124 6.45083 43.4412 6.36206C43.47 6.27329 43.523 6.19438 43.5942 6.13426C43.6653 6.07414 43.7518 6.0352 43.8439 6.02187L48.1342 5.39687L50.0529 1.49587C50.2209 1.15437 50.779 1.15437 50.9469 1.49587L52.8656 5.39687L57.156 6.02187C57.2479 6.03556 57.3341 6.07468 57.405 6.13484C57.476 6.19501 57.5288 6.27383 57.5575 6.36245C57.5862 6.45107 57.5898 6.54598 57.5677 6.6365C57.5456 6.72701 57.4989 6.80954 57.4325 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M78.6956 6.87488L75.5918 9.91088L76.3248 14.1989C76.34 14.2908 76.3301 14.3853 76.2949 14.4717C76.2596 14.5581 76.2018 14.633 76.1261 14.6879C76.051 14.7427 75.962 14.7753 75.8696 14.782C75.7766 14.7887 75.6835 14.7693 75.6018 14.7259L71.7631 12.7013L67.9251 14.7253C67.8427 14.7688 67.7504 14.7882 67.6574 14.7815C67.565 14.7748 67.476 14.7421 67.4002 14.6873C67.3251 14.6325 67.2666 14.5577 67.2321 14.4713C67.1969 14.3849 67.1862 14.2903 67.2022 14.1983L67.9351 9.91035L64.8306 6.87488C64.7639 6.80974 64.7167 6.72714 64.6945 6.63654C64.6721 6.54591 64.6756 6.45083 64.7044 6.36206C64.7331 6.27329 64.7862 6.19438 64.8573 6.13426C64.9285 6.07414 65.015 6.0352 65.1071 6.02187L69.3976 5.39687L71.316 1.49587C71.4841 1.15437 72.0422 1.15437 72.2103 1.49587L74.1287 5.39687L78.4192 6.02187C78.5109 6.03556 78.5973 6.07468 78.6684 6.13484C78.7394 6.19501 78.7919 6.27383 78.8205 6.36245C78.8498 6.45107 78.8531 6.54598 78.8311 6.6365C78.8086 6.72701 78.762 6.80954 78.6956 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M99.9588 6.87488L96.855 9.91088L97.588 14.1989C97.6032 14.2908 97.5933 14.3853 97.5581 14.4717C97.5228 14.5581 97.465 14.633 97.3893 14.6879C97.3142 14.7427 97.2252 14.7753 97.1328 14.782C97.0398 14.7887 96.9467 14.7693 96.865 14.7259L93.0263 12.7013L89.1883 14.7253C89.106 14.7688 89.0136 14.7882 88.9206 14.7815C88.8282 14.7748 88.7392 14.7421 88.6634 14.6873C88.5883 14.6325 88.5299 14.5577 88.4953 14.4713C88.4601 14.3849 88.4495 14.2903 88.4654 14.1983L89.1983 9.91035L86.0939 6.87488C86.0268 6.80974 85.9803 6.72714 85.9577 6.63654C85.9351 6.54591 85.9391 6.45083 85.9676 6.36206C85.9962 6.27329 86.0494 6.19438 86.1205 6.13426C86.1916 6.07414 86.278 6.0352 86.3703 6.02187L90.6608 5.39687L92.5792 1.49587C92.7473 1.15437 93.3054 1.15437 93.4735 1.49587L95.3919 5.39687L99.6824 6.02187C99.7741 6.03556 99.8605 6.07468 99.9316 6.13484C100.003 6.19501 100.055 6.27383 100.084 6.36245C100.113 6.45107 100.116 6.54598 100.094 6.6365C100.072 6.72701 100.025 6.80954 99.9588 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                        <\\/svg>\",\"t7\":\"Hannah Lee\",\"t8\":\"Creative Director\",\"t9\":\"Studio Kinetic\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-6.webp\",\"t2\":\"orisa\",\"t3\":\"assets\\/imgs\\/icons\\/brand-7.webp\",\"t4\":\"orisa\",\"t5\":\"\\u201cThey delivered not just a design, but a complete brand experience. Strategic, creative, and incredibly detail-oriented.\\u201d\",\"t6\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"101\\\" height=\\\"16\\\" viewBox=\\\"0 0 101 16\\\" fill=\\\"none\\\">\\n                            <path\\n                                d=\\\"M14.9062 6.87488L11.8019 9.91088L12.535 14.1989C12.5507 14.2908 12.5405 14.3853 12.5054 14.4717C12.4704 14.5581 12.4119 14.633 12.3367 14.6879C12.2615 14.7427 12.1725 14.7753 12.0797 14.782C11.987 14.7887 11.8942 14.7693 11.8119 14.7259L7.97358 12.7013L4.13575 14.7253C4.05345 14.7688 3.96068 14.7882 3.86794 14.7815C3.7752 14.7748 3.68618 14.7421 3.61095 14.6873C3.53572 14.6325 3.47728 14.5577 3.44223 14.4713C3.40718 14.3849 3.39693 14.2903 3.41264 14.1983L4.14572 9.91035L1.04096 6.87488C0.974323 6.80974 0.927186 6.72714 0.904899 6.63654C0.882606 6.54591 0.886041 6.45083 0.914826 6.36206C0.943611 6.27329 0.99659 6.19438 1.06776 6.13426C1.13893 6.07414 1.22546 6.0352 1.31755 6.02187L5.60789 5.39687L7.52653 1.49587C7.6945 1.15437 8.25266 1.15437 8.42058 1.49587L10.3392 5.39687L14.6296 6.02187C14.7215 6.03556 14.8078 6.07468 14.8787 6.13484C14.9496 6.19501 15.0024 6.27383 15.0312 6.36245C15.0599 6.45107 15.0634 6.54598 15.0413 6.6365C15.0193 6.72701 14.9725 6.80954 14.9062 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M36.1694 6.87488L33.0651 9.91088L33.7982 14.1989C33.8139 14.2908 33.8037 14.3853 33.7686 14.4717C33.7336 14.5581 33.6751 14.633 33.5999 14.6879C33.5247 14.7427 33.4357 14.7753 33.3429 14.782C33.2502 14.7887 33.1574 14.7693 33.0751 14.7259L29.2368 12.7013L25.3989 14.7253C25.3166 14.7688 25.2239 14.7882 25.1311 14.7815C25.0384 14.7748 24.9494 14.7421 24.8741 14.6873C24.7989 14.6325 24.7405 14.5577 24.7054 14.4713C24.6704 14.3849 24.6601 14.2903 24.6758 14.1983L25.4089 9.91035L22.3041 6.87488C22.2375 6.80974 22.1904 6.72714 22.1681 6.63654C22.1458 6.54591 22.1492 6.45083 22.178 6.36206C22.2068 6.27329 22.2598 6.19438 22.331 6.13426C22.4021 6.07414 22.4886 6.0352 22.5807 6.02187L26.871 5.39687L28.7897 1.49587C28.9577 1.15437 29.5159 1.15437 29.6838 1.49587L31.6024 5.39687L35.8928 6.02187C35.9847 6.03556 36.071 6.07468 36.1419 6.13484C36.2128 6.19501 36.2656 6.27383 36.2943 6.36245C36.3231 6.45107 36.3266 6.54598 36.3045 6.6365C36.2825 6.72701 36.2357 6.80954 36.1694 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M57.4325 6.87488L54.3283 9.91088L55.0614 14.1989C55.0771 14.2908 55.0669 14.3853 55.0318 14.4717C54.9968 14.5581 54.9383 14.633 54.8631 14.6879C54.7879 14.7427 54.6988 14.7753 54.6061 14.782C54.5134 14.7887 54.4206 14.7693 54.3383 14.7259L50.5 12.7013L46.6621 14.7253C46.5798 14.7688 46.4871 14.7882 46.3943 14.7815C46.3015 14.7748 46.2126 14.7421 46.1373 14.6873C46.0621 14.6325 46.0037 14.5577 45.9686 14.4713C45.9336 14.3849 45.9233 14.2903 45.939 14.1983L46.6721 9.91035L43.5673 6.87488C43.5007 6.80974 43.4535 6.72714 43.4313 6.63654C43.409 6.54591 43.4124 6.45083 43.4412 6.36206C43.47 6.27329 43.523 6.19438 43.5942 6.13426C43.6653 6.07414 43.7518 6.0352 43.8439 6.02187L48.1342 5.39687L50.0529 1.49587C50.2209 1.15437 50.779 1.15437 50.9469 1.49587L52.8656 5.39687L57.156 6.02187C57.2479 6.03556 57.3341 6.07468 57.405 6.13484C57.476 6.19501 57.5288 6.27383 57.5575 6.36245C57.5862 6.45107 57.5898 6.54598 57.5677 6.6365C57.5456 6.72701 57.4989 6.80954 57.4325 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M78.6956 6.87488L75.5918 9.91088L76.3248 14.1989C76.34 14.2908 76.3301 14.3853 76.2949 14.4717C76.2596 14.5581 76.2018 14.633 76.1261 14.6879C76.051 14.7427 75.962 14.7753 75.8696 14.782C75.7766 14.7887 75.6835 14.7693 75.6018 14.7259L71.7631 12.7013L67.9251 14.7253C67.8427 14.7688 67.7504 14.7882 67.6574 14.7815C67.565 14.7748 67.476 14.7421 67.4002 14.6873C67.3251 14.6325 67.2666 14.5577 67.2321 14.4713C67.1969 14.3849 67.1862 14.2903 67.2022 14.1983L67.9351 9.91035L64.8306 6.87488C64.7639 6.80974 64.7167 6.72714 64.6945 6.63654C64.6721 6.54591 64.6756 6.45083 64.7044 6.36206C64.7331 6.27329 64.7862 6.19438 64.8573 6.13426C64.9285 6.07414 65.015 6.0352 65.1071 6.02187L69.3976 5.39687L71.316 1.49587C71.4841 1.15437 72.0422 1.15437 72.2103 1.49587L74.1287 5.39687L78.4192 6.02187C78.5109 6.03556 78.5973 6.07468 78.6684 6.13484C78.7394 6.19501 78.7919 6.27383 78.8205 6.36245C78.8498 6.45107 78.8531 6.54598 78.8311 6.6365C78.8086 6.72701 78.762 6.80954 78.6956 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M99.9588 6.87488L96.855 9.91088L97.588 14.1989C97.6032 14.2908 97.5933 14.3853 97.5581 14.4717C97.5228 14.5581 97.465 14.633 97.3893 14.6879C97.3142 14.7427 97.2252 14.7753 97.1328 14.782C97.0398 14.7887 96.9467 14.7693 96.865 14.7259L93.0263 12.7013L89.1883 14.7253C89.106 14.7688 89.0136 14.7882 88.9206 14.7815C88.8282 14.7748 88.7392 14.7421 88.6634 14.6873C88.5883 14.6325 88.5299 14.5577 88.4953 14.4713C88.4601 14.3849 88.4495 14.2903 88.4654 14.1983L89.1983 9.91035L86.0939 6.87488C86.0268 6.80974 85.9803 6.72714 85.9577 6.63654C85.9351 6.54591 85.9391 6.45083 85.9676 6.36206C85.9962 6.27329 86.0494 6.19438 86.1205 6.13426C86.1916 6.07414 86.278 6.0352 86.3703 6.02187L90.6608 5.39687L92.5792 1.49587C92.7473 1.15437 93.3054 1.15437 93.4735 1.49587L95.3919 5.39687L99.6824 6.02187C99.7741 6.03556 99.8605 6.07468 99.9316 6.13484C100.003 6.19501 100.055 6.27383 100.084 6.36245C100.113 6.45107 100.116 6.54598 100.094 6.6365C100.072 6.72701 100.025 6.80954 99.9588 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                        <\\/svg>\",\"t7\":\"Amelia Wright\",\"t8\":\"Head of Marketing\",\"t9\":\"London United Kingdom\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-7.webp\",\"t2\":\"orisa\",\"t3\":\"assets\\/imgs\\/icons\\/brand-8.webp\",\"t4\":\"orisa\",\"t5\":\"\\u201cThe collaboration was seamless from start to finish. Their UX decisions significantly improved our product engagement.\\u201d\",\"t6\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"101\\\" height=\\\"16\\\" viewBox=\\\"0 0 101 16\\\" fill=\\\"none\\\">\\n                            <path\\n                                d=\\\"M14.9062 6.87488L11.8019 9.91088L12.535 14.1989C12.5507 14.2908 12.5405 14.3853 12.5054 14.4717C12.4704 14.5581 12.4119 14.633 12.3367 14.6879C12.2615 14.7427 12.1725 14.7753 12.0797 14.782C11.987 14.7887 11.8942 14.7693 11.8119 14.7259L7.97358 12.7013L4.13575 14.7253C4.05345 14.7688 3.96068 14.7882 3.86794 14.7815C3.7752 14.7748 3.68618 14.7421 3.61095 14.6873C3.53572 14.6325 3.47728 14.5577 3.44223 14.4713C3.40718 14.3849 3.39693 14.2903 3.41264 14.1983L4.14572 9.91035L1.04096 6.87488C0.974323 6.80974 0.927186 6.72714 0.904899 6.63654C0.882606 6.54591 0.886041 6.45083 0.914826 6.36206C0.943611 6.27329 0.99659 6.19438 1.06776 6.13426C1.13893 6.07414 1.22546 6.0352 1.31755 6.02187L5.60789 5.39687L7.52653 1.49587C7.6945 1.15437 8.25266 1.15437 8.42058 1.49587L10.3392 5.39687L14.6296 6.02187C14.7215 6.03556 14.8078 6.07468 14.8787 6.13484C14.9496 6.19501 15.0024 6.27383 15.0312 6.36245C15.0599 6.45107 15.0634 6.54598 15.0413 6.6365C15.0193 6.72701 14.9725 6.80954 14.9062 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M36.1694 6.87488L33.0651 9.91088L33.7982 14.1989C33.8139 14.2908 33.8037 14.3853 33.7686 14.4717C33.7336 14.5581 33.6751 14.633 33.5999 14.6879C33.5247 14.7427 33.4357 14.7753 33.3429 14.782C33.2502 14.7887 33.1574 14.7693 33.0751 14.7259L29.2368 12.7013L25.3989 14.7253C25.3166 14.7688 25.2239 14.7882 25.1311 14.7815C25.0384 14.7748 24.9494 14.7421 24.8741 14.6873C24.7989 14.6325 24.7405 14.5577 24.7054 14.4713C24.6704 14.3849 24.6601 14.2903 24.6758 14.1983L25.4089 9.91035L22.3041 6.87488C22.2375 6.80974 22.1904 6.72714 22.1681 6.63654C22.1458 6.54591 22.1492 6.45083 22.178 6.36206C22.2068 6.27329 22.2598 6.19438 22.331 6.13426C22.4021 6.07414 22.4886 6.0352 22.5807 6.02187L26.871 5.39687L28.7897 1.49587C28.9577 1.15437 29.5159 1.15437 29.6838 1.49587L31.6024 5.39687L35.8928 6.02187C35.9847 6.03556 36.071 6.07468 36.1419 6.13484C36.2128 6.19501 36.2656 6.27383 36.2943 6.36245C36.3231 6.45107 36.3266 6.54598 36.3045 6.6365C36.2825 6.72701 36.2357 6.80954 36.1694 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M57.4325 6.87488L54.3283 9.91088L55.0614 14.1989C55.0771 14.2908 55.0669 14.3853 55.0318 14.4717C54.9968 14.5581 54.9383 14.633 54.8631 14.6879C54.7879 14.7427 54.6988 14.7753 54.6061 14.782C54.5134 14.7887 54.4206 14.7693 54.3383 14.7259L50.5 12.7013L46.6621 14.7253C46.5798 14.7688 46.4871 14.7882 46.3943 14.7815C46.3015 14.7748 46.2126 14.7421 46.1373 14.6873C46.0621 14.6325 46.0037 14.5577 45.9686 14.4713C45.9336 14.3849 45.9233 14.2903 45.939 14.1983L46.6721 9.91035L43.5673 6.87488C43.5007 6.80974 43.4535 6.72714 43.4313 6.63654C43.409 6.54591 43.4124 6.45083 43.4412 6.36206C43.47 6.27329 43.523 6.19438 43.5942 6.13426C43.6653 6.07414 43.7518 6.0352 43.8439 6.02187L48.1342 5.39687L50.0529 1.49587C50.2209 1.15437 50.779 1.15437 50.9469 1.49587L52.8656 5.39687L57.156 6.02187C57.2479 6.03556 57.3341 6.07468 57.405 6.13484C57.476 6.19501 57.5288 6.27383 57.5575 6.36245C57.5862 6.45107 57.5898 6.54598 57.5677 6.6365C57.5456 6.72701 57.4989 6.80954 57.4325 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M78.6956 6.87488L75.5918 9.91088L76.3248 14.1989C76.34 14.2908 76.3301 14.3853 76.2949 14.4717C76.2596 14.5581 76.2018 14.633 76.1261 14.6879C76.051 14.7427 75.962 14.7753 75.8696 14.782C75.7766 14.7887 75.6835 14.7693 75.6018 14.7259L71.7631 12.7013L67.9251 14.7253C67.8427 14.7688 67.7504 14.7882 67.6574 14.7815C67.565 14.7748 67.476 14.7421 67.4002 14.6873C67.3251 14.6325 67.2666 14.5577 67.2321 14.4713C67.1969 14.3849 67.1862 14.2903 67.2022 14.1983L67.9351 9.91035L64.8306 6.87488C64.7639 6.80974 64.7167 6.72714 64.6945 6.63654C64.6721 6.54591 64.6756 6.45083 64.7044 6.36206C64.7331 6.27329 64.7862 6.19438 64.8573 6.13426C64.9285 6.07414 65.015 6.0352 65.1071 6.02187L69.3976 5.39687L71.316 1.49587C71.4841 1.15437 72.0422 1.15437 72.2103 1.49587L74.1287 5.39687L78.4192 6.02187C78.5109 6.03556 78.5973 6.07468 78.6684 6.13484C78.7394 6.19501 78.7919 6.27383 78.8205 6.36245C78.8498 6.45107 78.8531 6.54598 78.8311 6.6365C78.8086 6.72701 78.762 6.80954 78.6956 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M99.9588 6.87488L96.855 9.91088L97.588 14.1989C97.6032 14.2908 97.5933 14.3853 97.5581 14.4717C97.5228 14.5581 97.465 14.633 97.3893 14.6879C97.3142 14.7427 97.2252 14.7753 97.1328 14.782C97.0398 14.7887 96.9467 14.7693 96.865 14.7259L93.0263 12.7013L89.1883 14.7253C89.106 14.7688 89.0136 14.7882 88.9206 14.7815C88.8282 14.7748 88.7392 14.7421 88.6634 14.6873C88.5883 14.6325 88.5299 14.5577 88.4953 14.4713C88.4601 14.3849 88.4495 14.2903 88.4654 14.1983L89.1983 9.91035L86.0939 6.87488C86.0268 6.80974 85.9803 6.72714 85.9577 6.63654C85.9351 6.54591 85.9391 6.45083 85.9676 6.36206C85.9962 6.27329 86.0494 6.19438 86.1205 6.13426C86.1916 6.07414 86.278 6.0352 86.3703 6.02187L90.6608 5.39687L92.5792 1.49587C92.7473 1.15437 93.3054 1.15437 93.4735 1.49587L95.3919 5.39687L99.6824 6.02187C99.7741 6.03556 99.8605 6.07468 99.9316 6.13484C100.003 6.19501 100.055 6.27383 100.084 6.36245C100.113 6.45107 100.116 6.54598 100.094 6.6365C100.072 6.72701 100.025 6.80954 99.9588 6.87488Z\\\"\\n                                fill=\\\"#585959\\\" \\/>\\n                        <\\/svg>\",\"t7\":\"Lucas Moreno\",\"t8\":\"Product Manager\",\"t9\":\"Barcelona, Spain\"},{\"t1\":\"assets\\/imgs\\/template\\/avatar\\/avatar-8.webp\",\"t2\":\"orisa\",\"t3\":\"assets\\/imgs\\/icons\\/brand-9.webp\",\"t4\":\"orisa\",\"t5\":\"\\u201cA rare combination of technical expertise and artistic vision. The final result felt premium and purposeful.\\u201d\",\"t6\":\"<svg xmlns=\\\"http:\\/\\/www.w3.org\\/2000\\/svg\\\" width=\\\"101\\\" height=\\\"16\\\" viewBox=\\\"0 0 101 16\\\" fill=\\\"none\\\">\\n                            <path\\n                                d=\\\"M14.9062 6.87488L11.8019 9.91088L12.535 14.1989C12.5507 14.2908 12.5405 14.3853 12.5054 14.4717C12.4704 14.5581 12.4119 14.633 12.3367 14.6879C12.2615 14.7427 12.1725 14.7753 12.0797 14.782C11.987 14.7887 11.8942 14.7693 11.8119 14.7259L7.97358 12.7013L4.13575 14.7253C4.05345 14.7688 3.96068 14.7882 3.86794 14.7815C3.7752 14.7748 3.68618 14.7421 3.61095 14.6873C3.53572 14.6325 3.47728 14.5577 3.44223 14.4713C3.40718 14.3849 3.39693 14.2903 3.41264 14.1983L4.14572 9.91035L1.04096 6.87488C0.974323 6.80974 0.927186 6.72714 0.904899 6.63654C0.882606 6.54591 0.886041 6.45083 0.914826 6.36206C0.943611 6.27329 0.99659 6.19438 1.06776 6.13426C1.13893 6.07414 1.22546 6.0352 1.31755 6.02187L5.60789 5.39687L7.52653 1.49587C7.6945 1.15437 8.25266 1.15437 8.42058 1.49587L10.3392 5.39687L14.6296 6.02187C14.7215 6.03556 14.8078 6.07468 14.8787 6.13484C14.9496 6.19501 15.0024 6.27383 15.0312 6.36245C15.0599 6.45107 15.0634 6.54598 15.0413 6.6365C15.0193 6.72701 14.9725 6.80954 14.9062 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M36.1694 6.87488L33.0651 9.91088L33.7982 14.1989C33.8139 14.2908 33.8037 14.3853 33.7686 14.4717C33.7336 14.5581 33.6751 14.633 33.5999 14.6879C33.5247 14.7427 33.4357 14.7753 33.3429 14.782C33.2502 14.7887 33.1574 14.7693 33.0751 14.7259L29.2368 12.7013L25.3989 14.7253C25.3166 14.7688 25.2239 14.7882 25.1311 14.7815C25.0384 14.7748 24.9494 14.7421 24.8741 14.6873C24.7989 14.6325 24.7405 14.5577 24.7054 14.4713C24.6704 14.3849 24.6601 14.2903 24.6758 14.1983L25.4089 9.91035L22.3041 6.87488C22.2375 6.80974 22.1904 6.72714 22.1681 6.63654C22.1458 6.54591 22.1492 6.45083 22.178 6.36206C22.2068 6.27329 22.2598 6.19438 22.331 6.13426C22.4021 6.07414 22.4886 6.0352 22.5807 6.02187L26.871 5.39687L28.7897 1.49587C28.9577 1.15437 29.5159 1.15437 29.6838 1.49587L31.6024 5.39687L35.8928 6.02187C35.9847 6.03556 36.071 6.07468 36.1419 6.13484C36.2128 6.19501 36.2656 6.27383 36.2943 6.36245C36.3231 6.45107 36.3266 6.54598 36.3045 6.6365C36.2825 6.72701 36.2357 6.80954 36.1694 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M57.4325 6.87488L54.3283 9.91088L55.0614 14.1989C55.0771 14.2908 55.0669 14.3853 55.0318 14.4717C54.9968 14.5581 54.9383 14.633 54.8631 14.6879C54.7879 14.7427 54.6988 14.7753 54.6061 14.782C54.5134 14.7887 54.4206 14.7693 54.3383 14.7259L50.5 12.7013L46.6621 14.7253C46.5798 14.7688 46.4871 14.7882 46.3943 14.7815C46.3015 14.7748 46.2126 14.7421 46.1373 14.6873C46.0621 14.6325 46.0037 14.5577 45.9686 14.4713C45.9336 14.3849 45.9233 14.2903 45.939 14.1983L46.6721 9.91035L43.5673 6.87488C43.5007 6.80974 43.4535 6.72714 43.4313 6.63654C43.409 6.54591 43.4124 6.45083 43.4412 6.36206C43.47 6.27329 43.523 6.19438 43.5942 6.13426C43.6653 6.07414 43.7518 6.0352 43.8439 6.02187L48.1342 5.39687L50.0529 1.49587C50.2209 1.15437 50.779 1.15437 50.9469 1.49587L52.8656 5.39687L57.156 6.02187C57.2479 6.03556 57.3341 6.07468 57.405 6.13484C57.476 6.19501 57.5288 6.27383 57.5575 6.36245C57.5862 6.45107 57.5898 6.54598 57.5677 6.6365C57.5456 6.72701 57.4989 6.80954 57.4325 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M78.6956 6.87488L75.5918 9.91088L76.3248 14.1989C76.34 14.2908 76.3301 14.3853 76.2949 14.4717C76.2596 14.5581 76.2018 14.633 76.1261 14.6879C76.051 14.7427 75.962 14.7753 75.8696 14.782C75.7766 14.7887 75.6835 14.7693 75.6018 14.7259L71.7631 12.7013L67.9251 14.7253C67.8427 14.7688 67.7504 14.7882 67.6574 14.7815C67.565 14.7748 67.476 14.7421 67.4002 14.6873C67.3251 14.6325 67.2666 14.5577 67.2321 14.4713C67.1969 14.3849 67.1862 14.2903 67.2022 14.1983L67.9351 9.91035L64.8306 6.87488C64.7639 6.80974 64.7167 6.72714 64.6945 6.63654C64.6721 6.54591 64.6756 6.45083 64.7044 6.36206C64.7331 6.27329 64.7862 6.19438 64.8573 6.13426C64.9285 6.07414 65.015 6.0352 65.1071 6.02187L69.3976 5.39687L71.316 1.49587C71.4841 1.15437 72.0422 1.15437 72.2103 1.49587L74.1287 5.39687L78.4192 6.02187C78.5109 6.03556 78.5973 6.07468 78.6684 6.13484C78.7394 6.19501 78.7919 6.27383 78.8205 6.36245C78.8498 6.45107 78.8531 6.54598 78.8311 6.6365C78.8086 6.72701 78.762 6.80954 78.6956 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                            <path\\n                                d=\\\"M99.9588 6.87488L96.855 9.91088L97.588 14.1989C97.6032 14.2908 97.5933 14.3853 97.5581 14.4717C97.5228 14.5581 97.465 14.633 97.3893 14.6879C97.3142 14.7427 97.2252 14.7753 97.1328 14.782C97.0398 14.7887 96.9467 14.7693 96.865 14.7259L93.0263 12.7013L89.1883 14.7253C89.106 14.7688 89.0136 14.7882 88.9206 14.7815C88.8282 14.7748 88.7392 14.7421 88.6634 14.6873C88.5883 14.6325 88.5299 14.5577 88.4953 14.4713C88.4601 14.3849 88.4495 14.2903 88.4654 14.1983L89.1983 9.91035L86.0939 6.87488C86.0268 6.80974 85.9803 6.72714 85.9577 6.63654C85.9351 6.54591 85.9391 6.45083 85.9676 6.36206C85.9962 6.27329 86.0494 6.19438 86.1205 6.13426C86.1916 6.07414 86.278 6.0352 86.3703 6.02187L90.6608 5.39687L92.5792 1.49587C92.7473 1.15437 93.3054 1.15437 93.4735 1.49587L95.3919 5.39687L99.6824 6.02187C99.7741 6.03556 99.8605 6.07468 99.9316 6.13484C100.003 6.19501 100.055 6.27383 100.084 6.36245C100.113 6.45107 100.116 6.54598 100.094 6.6365C100.072 6.72701 100.025 6.80954 99.9588 6.87488Z\\\"\\n                                fill=\\\"#FFB618\\\" \\/>\\n                        <\\/svg>\",\"t7\":\"Hannah Lee\",\"t8\":\"Creative Director\",\"t9\":\"Studio Kinetic\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(11,2,'home_why','Why choose us',6,1,'{\"slots\":{\"s1\":\"Why choose us\",\"s2\":\"Why choose us\",\"s3\":\"Delivering measurable results through a strong balance of design excellence and functional performance.\",\"s4\":\"assets\\/imgs\\/pages\\/img-15.webp\",\"s5\":\"orisa\",\"s6\":\"assets\\/imgs\\/template\\/logo\\/logo-w-lg.png\",\"s7\":\"orisa\",\"s8\":\"Creative\",\"s9\":\"assets\\/imgs\\/pages\\/img-16.webp\",\"s10\":\"orisa\",\"s11\":\"Great digital experiences begin with a conversation. Let\\u2019s talk.\",\"s12\":\"Orisa\\u2122 goes beyond aesthetics\\u2014bringing clarity through motion, flexible structure, and practical tools that help you move faster without defining your identity.\",\"s13\":\"Active\",\"s14\":\"live cases\",\"s15\":\"We always provide people a complete solution upon focused of any business\",\"s16\":\"Trusted\",\"s17\":\"Partners\",\"s18\":\"Because sometimes the best design is the one you don\\u2019t have to think about.\"},\"groups\":{\"g1\":[{\"t1\":\"1.8M\"},{\"t1\":\"+\"}],\"g2\":[{\"t1\":\"16K\"},{\"t1\":\"+\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(12,2,'home_stats','Statistics',7,1,'{\"slots\":[],\"groups\":{\"g1\":[{\"t1\":\"10\",\"t2\":\"K+\",\"t3\":\"Years of Creative Practice\"},{\"t1\":\"50\",\"t2\":\"K+\",\"t3\":\"Projects Carefully Crafted\"},{\"t1\":\"16\",\"t2\":\"K+\",\"t3\":\"Brands Collaborated With\"},{\"t1\":\"20\",\"t2\":\"M+\",\"t3\":\"Total Funding Supported\"},{\"t1\":\"98\",\"t2\":\"%\",\"t3\":\"Client satisfaction rate\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(13,2,'home_team','Team / We are here',8,1,'{\"slots\":{\"s1\":\"MEET OUR TEAM\",\"s2\":\"MEET OUR TEAM\",\"s3\":\"Meet the minds behind Orisa Studio. Rely on our experienced professionals to find solutions tailored just for you.\",\"s4\":\"\\/team\",\"s5\":\"\\/team\",\"s6\":\"Join our Team\",\"s7\":\"\\/team\",\"s8\":\"We are here\",\"s9\":\"205 North Michigan Avenue, Suite 810\",\"s10\":\"Chicago, 60601, USA\",\"s11\":\"Phone:\",\"s12\":\"tel:+1234567890\",\"s13\":\"+1234567890\",\"s14\":\"Email:\",\"s15\":\"mailto:hello@orisa.com\",\"s16\":\"hello@nullphpscript.com\",\"s17\":\"245 Fifth Avenue, Suite 1800\",\"s18\":\"New York, NY 10016, USA\",\"s19\":\"Phone:\",\"s20\":\"tel:+2125557398\",\"s21\":\"+2125557398\",\"s22\":\"Email:\",\"s23\":\"mailto:sale@orisa.com\",\"s24\":\"sale@orisa.com\"},\"groups\":{\"g1\":[{\"t1\":\"assets\\/imgs\\/pages\\/img-17.webp\",\"t2\":\"Darrell Steward\",\"t3\":\"\\/team\\/details\",\"t4\":\"\\/team\\/details\",\"t5\":\"Darrell Steward\",\"t6\":\"UI\\/UX Designer\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-18.webp\",\"t2\":\"Amelia Courtney\",\"t3\":\"\\/team\\/details\",\"t4\":\"\\/team\\/details\",\"t5\":\"Amelia Courtney\",\"t6\":\"Project Manager\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-19.webp\",\"t2\":\"Esther Howard\",\"t3\":\"\\/team\\/details\",\"t4\":\"\\/team\\/details\",\"t5\":\"Esther Howard\",\"t6\":\"Software Developer\"},{\"t1\":\"assets\\/imgs\\/pages\\/img-20.webp\",\"t2\":\"Jacob Jones\",\"t3\":\"\\/team\\/details\",\"t4\":\"\\/team\\/details\",\"t5\":\"Jacob Jones\",\"t6\":\"Marketing CEO\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(14,2,'home_brands2','Client logos (second row)',9,1,'{\"slots\":[],\"groups\":{\"g1\":[{\"t1\":\"web3\"},{\"t1\":\"B2B & B2C\"},{\"t1\":\"SaaS Platforms\"},{\"t1\":\"Art Direction\"},{\"t1\":\"Web & Mobile Apps\"},{\"t1\":\"Motion Design\"},{\"t1\":\"UX\\/UI\"},{\"t1\":\"Branding\"},{\"t1\":\"Concept Design\"},{\"t1\":\"Presentations\"},{\"t1\":\"Social Media & Ads\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(15,2,'home_faq','FAQ',10,1,'{\"slots\":{\"s1\":\"assets\\/imgs\\/pages\\/img-21.webp\",\"s2\":\"orisa\",\"s3\":\"Still no luck? We can help!\",\"s4\":\"Let us Know how we can assist\",\"s5\":\"\\/faqs\",\"s6\":\"\\/faqs\",\"s7\":\"Support Center\",\"s8\":\"\\/faqs\",\"s9\":\"FAQ\",\"s10\":\"FAQ\",\"s11\":\"Answered questions. Everything you might want to know\\u2014up front.\",\"s12\":\"1\",\"s13\":\"How does your design process work?\",\"s14\":\"Our process includes discovery, strategy, design, feedback, and delivery \\u2014 ensuring clarity, collaboration, and results at every stage.\"},\"groups\":{\"g1\":[{\"t1\":\"2\",\"t2\":\"#collapseTwo\",\"t3\":\"collapseTwo\",\"t4\":\"How long does a typical project take?\",\"t5\":\"collapseTwo\",\"t6\":\"Timelines vary by scope, but most projects take between 2\\u20136 weeks \\u2014 with clear milestones to keep everything on track.\"},{\"t1\":\"3\",\"t2\":\"#collapseThree\",\"t3\":\"collapseThree\",\"t4\":\"Do you work with startups or only established brands?\",\"t5\":\"collapseThree\",\"t6\":\"We work with both startups and established brands \\u2014 tailoring our approach to fit each stage of growth.\"},{\"t1\":\"4\",\"t2\":\"#collapseFour\",\"t3\":\"collapseFour\",\"t4\":\"Can you handle custom or complex requests?\",\"t5\":\"collapseFour\",\"t6\":\"Yes \\u2014 we specialize in custom and complex projects, creating flexible solutions to meet unique needs.\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(16,2,'home_cta','Call-to-action banner',11,1,'{\"slots\":{\"s1\":\"assets\\/imgs\\/pages\\/img-22.webp\",\"s2\":\"orisa\",\"s3\":\"\\/\",\"s4\":\"assets\\/imgs\\/template\\/logo\\/favicon-dark.svg\",\"s5\":\"Orisa\",\"s6\":\"Orisa\",\"s7\":\"Local time: 12:30\",\"s8\":\"Let\\u2019s Create\",\"s9\":\"Meaning Together\",\"s10\":\"\\/contact\",\"s11\":\"Book A Call Now\",\"s12\":\"Book A Call Now\",\"s13\":\"[ From 8:00 To 16:30 ]\",\"s14\":\"[ A creative studio crafting bold, user-focused digital experiences. At Orisa, we blend strategy, design, and innovation to help brands stand out and grow. ]\"},\"groups\":[]}','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(17,2,'home_posts','Latest posts',12,1,'{\"slots\":{\"s1\":\"INSIDE COMPANY\",\"s2\":\"INSIDE COMPANY\",\"s3\":\"Latest Posts From Our\",\"s4\":\"blog and Event Fan page\",\"s5\":\"\\/blog\",\"s6\":\"ALL ARTICLES\",\"s7\":\"ALL ARTICLES\"},\"groups\":{\"g1\":[{\"t1\":\"\\/blog\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-23.webp\",\"t3\":\"orisa\",\"t4\":\"\\/blog\\/details\",\"t5\":\"Designing Digital Experiences That Connect Brands and People\",\"t6\":\"By\",\"t7\":\"\\/team\\/details\",\"t8\":\"Olivia\",\"t9\":\"\\u2013 July 3, 2026\"},{\"t1\":\"\\/blog\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-24.webp\",\"t3\":\"orisa\",\"t4\":\"\\/blog\\/details\",\"t5\":\"From Concept to Launch: Building Products That Truly Matter\",\"t6\":\"By\",\"t7\":\"\\/team\\/details\",\"t8\":\"Daniel\",\"t9\":\"\\u2013 July 8, 2026\"},{\"t1\":\"\\/blog\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-25.webp\",\"t3\":\"orisa\",\"t4\":\"\\/blog\\/details\",\"t5\":\"Why Strong Visual Identity Is the Foundation of Modern Brands\",\"t6\":\"By\",\"t7\":\"\\/team\\/details\",\"t8\":\"Emma\",\"t9\":\"\\u2013 July 12, 2026\"},{\"t1\":\"\\/blog\\/details\",\"t2\":\"assets\\/imgs\\/pages\\/img-26.webp\",\"t3\":\"orisa\",\"t4\":\"\\/blog\\/details\",\"t5\":\"Creating Impactful Interfaces Through Thoughtful Design Systems\",\"t6\":\"By\",\"t7\":\"\\/team\\/details\",\"t8\":\"Lucas\",\"t9\":\"\\u2013 July 17, 2026\"}]}}','2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `template` varchar(60) NOT NULL DEFAULT 'default',
  `excerpt` text DEFAULT NULL,
  `featured_media_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `publish_at` datetime DEFAULT NULL,
  `is_home` tinyint(1) NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `draft` longtext DEFAULT NULL,
  `draft_updated_at` timestamp NULL DEFAULT NULL,
  `draft_updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_parent_id_foreign` (`parent_id`),
  KEY `pages_featured_media_id_foreign` (`featured_media_id`),
  KEY `pages_draft_updated_by_foreign` (`draft_updated_by`),
  KEY `pages_created_by_foreign` (`created_by`),
  KEY `pages_updated_by_foreign` (`updated_by`),
  KEY `pages_template_index` (`template`),
  KEY `pages_status_index` (`status`),
  CONSTRAINT `pages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_draft_updated_by_foreign` FOREIGN KEY (`draft_updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_featured_media_id_foreign` FOREIGN KEY (`featured_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` (`id`, `parent_id`, `title`, `slug`, `template`, `excerpt`, `featured_media_id`, `status`, `publish_at`, `is_home`, `is_system`, `position`, `draft`, `draft_updated_at`, `draft_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (1,NULL,'Global blocks (header, footer)','_global','global',NULL,NULL,'draft',NULL,0,1,0,NULL,NULL,NULL,NULL,NULL,'2026-11-27 10:19:32','2026-11-27 10:19:32'),
(2,NULL,'Home','home','default',NULL,NULL,'published',NULL,1,1,1,NULL,NULL,NULL,NULL,NULL,'2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES (1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
(1,15),
(1,16),
(2,1),
(2,2),
(2,3),
(2,4),
(2,5),
(2,6),
(2,7),
(2,8),
(3,1),
(3,9),
(3,10),
(3,11),
(3,12);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(80) NOT NULL,
  `label` varchar(120) NOT NULL,
  `group` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`),
  KEY `permissions_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `slug`, `label`, `group`, `created_at`, `updated_at`) VALUES (1,'dashboard.view','View dashboard','General','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(2,'pages.manage','Manage pages','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(3,'sections.manage','Manage page sections','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(4,'posts.manage','Manage blog posts, categories and tags','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(5,'content.manage','Manage services, portfolio, team, testimonials, FAQs, pricing','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(6,'media.manage','Manage media library','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(7,'menus.manage','Manage navigation menus','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(8,'submissions.manage','Manage contact submissions','Content','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(9,'seo.manage','Manage SEO metadata','SEO','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(10,'sitemap.manage','Manage XML sitemap','SEO','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(11,'robots.manage','Edit robots.txt','SEO','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(12,'redirects.manage','Manage redirects','SEO','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(13,'settings.manage','Manage website settings','System','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(14,'users.manage','Manage users, roles and permissions','System','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(15,'logs.view','View activity logs','System','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(16,'database.manage','Database and backup tools','System','2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `post_tag` (
  `post_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `author_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_media_id` bigint(20) unsigned DEFAULT NULL,
  `featured_alt` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `reading_minutes` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_category_id_foreign` (`category_id`),
  KEY `posts_author_id_foreign` (`author_id`),
  KEY `posts_featured_media_id_foreign` (`featured_media_id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_published_at_index` (`published_at`),
  CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_featured_media_id_foreign` FOREIGN KEY (`featured_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `pricing_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `price` varchar(40) NOT NULL,
  `period` varchar(40) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `cta_label` varchar(80) DEFAULT NULL,
  `cta_url` varchar(500) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `pricing_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `pricing_plans` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `client` varchar(255) DEFAULT NULL,
  `category` varchar(120) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `cover_media_id` bigint(20) unsigned DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `project_url` varchar(500) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`),
  KEY `projects_cover_media_id_foreign` (`cover_media_id`),
  KEY `projects_category_index` (`category`),
  KEY `projects_status_index` (`status`),
  CONSTRAINT `projects_cover_media_id_foreign` FOREIGN KEY (`cover_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `redirects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(500) NOT NULL,
  `source_hash` varchar(64) NOT NULL,
  `destination` varchar(500) NOT NULL,
  `status_code` smallint(5) unsigned NOT NULL DEFAULT 301,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `hits` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `redirects_source_hash_unique` (`source_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `redirects` DISABLE KEYS */;
/*!40000 ALTER TABLE `redirects` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `is_system`, `created_at`, `updated_at`) VALUES (1,'Super Administrator','super-admin','Full access to everything.',1,'2026-11-27 10:19:32','2026-11-27 10:19:32'),
(2,'Content Administrator','content-admin','Manages pages, sections, blog, media and content modules.',1,'2026-11-27 10:19:32','2026-11-27 10:19:32'),
(3,'SEO Administrator','seo-admin','Manages SEO metadata, sitemap, robots.txt and redirects.',1,'2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `seo_metadata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seoable_type` varchar(60) NOT NULL,
  `seoable_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `robots_index` tinyint(1) NOT NULL DEFAULT 1,
  `robots_follow` tinyint(1) NOT NULL DEFAULT 1,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` varchar(500) DEFAULT NULL,
  `og_image_id` bigint(20) unsigned DEFAULT NULL,
  `og_type` varchar(30) DEFAULT NULL,
  `twitter_card` varchar(30) DEFAULT NULL,
  `twitter_title` varchar(255) DEFAULT NULL,
  `twitter_description` varchar(500) DEFAULT NULL,
  `twitter_image_id` bigint(20) unsigned DEFAULT NULL,
  `structured_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`structured_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_metadata_seoable_type_seoable_id_unique` (`seoable_type`,`seoable_id`),
  KEY `seo_metadata_og_image_id_foreign` (`og_image_id`),
  KEY `seo_metadata_twitter_image_id_foreign` (`twitter_image_id`),
  CONSTRAINT `seo_metadata_og_image_id_foreign` FOREIGN KEY (`og_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `seo_metadata_twitter_image_id_foreign` FOREIGN KEY (`twitter_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `seo_metadata` DISABLE KEYS */;
INSERT INTO `seo_metadata` (`id`, `seoable_type`, `seoable_id`, `title`, `description`, `keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image_id`, `og_type`, `twitter_card`, `twitter_title`, `twitter_description`, `twitter_image_id`, `structured_data`, `created_at`, `updated_at`) VALUES (1,'App\\Models\\Page',2,'Orisa - Creative Agency','',NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `seo_metadata` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(80) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `cta_label` varchar(80) DEFAULT NULL,
  `cta_url` varchar(500) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_media_id_foreign` (`media_id`),
  CONSTRAINT `services_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(40) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`, `group`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES (1,'general','site_name','Orisa','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(2,'general','site_title','Orisa - Creative Agency','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(3,'general','tagline','We build brands that last','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(4,'general','site_url','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(5,'general','site_description','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(6,'general','logo',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(7,'general','logo_dark',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(8,'general','favicon',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(9,'general','default_image',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(10,'general','default_theme','light','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(11,'contact','contact_email','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(12,'contact','contact_phone','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(13,'contact','contact_whatsapp','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(14,'contact','contact_address','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(15,'contact','business_hours','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(16,'social','social_facebook','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(17,'social','social_instagram','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(18,'social','social_linkedin','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(19,'social','social_twitter','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(20,'social','social_youtube','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(21,'social','social_tiktok','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(22,'social','social_behance','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(23,'social','social_dribbble','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(24,'footer','footer_description','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(25,'footer','footer_logo',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(26,'footer','footer_copyright','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(27,'analytics','ga_id','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(28,'analytics','gtm_id','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(29,'analytics','gsc_verification','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(30,'analytics','bing_verification','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(31,'analytics','head_scripts','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(32,'analytics','body_scripts','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(33,'seo','seo_title','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(34,'seo','seo_description','','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(35,'seo','seo_keywords','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(36,'seo','seo_image',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(37,'seo','seo_canonical_base','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(38,'seo','seo_robots_default','index,follow','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(39,'seo','seo_author','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(40,'seo','seo_publisher','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(41,'seo','seo_org_name','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(42,'seo','seo_org_logo',NULL,'media','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(43,'seo','seo_twitter_site','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(44,'seo','seo_og_type','website','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(45,'seo','seo_twitter_card','summary_large_image','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(46,'seo','robots_txt','User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: {sitemap}\n','text','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(47,'seo','sitemap_last_generated',NULL,'string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(48,'seo','sitemap_url_count','0','int','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(49,'mail','mail_notify_to','','string','2026-11-27 10:19:32','2026-11-27 10:19:32'),
(50,'mail','mail_notify_enabled','0','bool','2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `position_title` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `socials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`socials`)),
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_members_slug_unique` (`slug`),
  KEY `team_members_media_id_foreign` (`media_id`),
  CONSTRAINT `team_members_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `author_name` varchar(255) NOT NULL,
  `author_role` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `rating` tinyint(3) unsigned DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testimonials_media_id_foreign` (`media_id`),
  CONSTRAINT `testimonials_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `must_change_password`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES (1,1,'Site Administrator','admin@orisa.test','2026-11-27 10:19:32','$2y$12$d8jzvJX4xQyxTH2lunsL7uYxD86Dbu6pL2o.e9638Hu/sWiosVuJ.',1,1,NULL,NULL,'2026-11-27 10:19:32','2026-11-27 10:19:32');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

SET FOREIGN_KEY_CHECKS=1;
