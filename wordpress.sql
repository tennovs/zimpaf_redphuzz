-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: wordpress
-- ------------------------------------------------------
-- Server version	5.7.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wp_commentmeta`
--
-- This is essential!
CREATE DATABASE IF NOT EXISTS wordpress;
use wordpress;

DROP TABLE IF EXISTS `wp_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_commentmeta`
--

LOCK TABLES `wp_commentmeta` WRITE;
/*!40000 ALTER TABLE `wp_commentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_commentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_comments`
--

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
INSERT INTO `wp_comments` VALUES (1,1,'A WordPress Commenter','wapuu@wordpress.example','https://wordpress.org/','','2026-02-20 16:45:30','2026-02-20 16:45:30','Hi, this is a comment.\nTo get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.\nCommenter avatars come from <a href=\"https://en.gravatar.com/\">Gravatar</a>.',0,'1','','comment',0,0);
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_links`
--

LOCK TABLES `wp_links` WRITE;
/*!40000 ALTER TABLE `wp_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (1,'siteurl','http://localhost/wordpress','yes'),(2,'home','http://localhost/wordpress','yes'),(3,'blogname','Fuzzing target','yes'),(4,'blogdescription','','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@local.net','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','1','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','','yes'),(29,'rewrite_rules','','yes'),(30,'hack_file','0','yes'),(31,'blog_charset','UTF-8','yes'),(32,'moderation_keys','','no'),(33,'active_plugins','a:0:{}','yes'),(34,'category_base','','yes'),(35,'ping_sites','http://rpc.pingomatic.com/','yes'),(36,'comment_max_links','2','yes'),(37,'gmt_offset','0','yes'),(38,'default_email_category','1','yes'),(39,'recently_edited','','no'),(40,'template','twentytwentythree','yes'),(41,'stylesheet','twentytwentythree','yes'),(42,'comment_registration','0','yes'),(43,'html_type','text/html','yes'),(44,'use_trackback','0','yes'),(45,'default_role','subscriber','yes'),(46,'db_version','53496','yes'),(47,'uploads_use_yearmonth_folders','1','yes'),(48,'upload_path','','yes'),(49,'blog_public','1','yes'),(50,'default_link_category','2','yes'),(51,'show_on_front','posts','yes'),(52,'tag_base','','yes'),(53,'show_avatars','1','yes'),(54,'avatar_rating','G','yes'),(55,'upload_url_path','','yes'),(56,'thumbnail_size_w','150','yes'),(57,'thumbnail_size_h','150','yes'),(58,'thumbnail_crop','1','yes'),(59,'medium_size_w','300','yes'),(60,'medium_size_h','300','yes'),(61,'avatar_default','mystery','yes'),(62,'large_size_w','1024','yes'),(63,'large_size_h','1024','yes'),(64,'image_default_link_type','none','yes'),(65,'image_default_size','','yes'),(66,'image_default_align','','yes'),(67,'close_comments_for_old_posts','0','yes'),(68,'close_comments_days_old','14','yes'),(69,'thread_comments','1','yes'),(70,'thread_comments_depth','5','yes'),(71,'page_comments','0','yes'),(72,'comments_per_page','50','yes'),(73,'default_comments_page','newest','yes'),(74,'comment_order','asc','yes'),(75,'sticky_posts','a:0:{}','yes'),(76,'widget_categories','a:0:{}','yes'),(77,'widget_text','a:0:{}','yes'),(78,'widget_rss','a:0:{}','yes'),(79,'uninstall_plugins','a:0:{}','no'),(80,'timezone_string','','yes'),(81,'page_for_posts','0','yes'),(82,'page_on_front','0','yes'),(83,'default_post_format','0','yes'),(84,'link_manager_enabled','0','yes'),(85,'finished_splitting_shared_terms','1','yes'),(86,'site_icon','0','yes'),(87,'medium_large_size_w','768','yes'),(88,'medium_large_size_h','0','yes'),(89,'wp_page_for_privacy_policy','3','yes'),(90,'show_comments_cookies_opt_in','1','yes'),(91,'admin_email_lifespan','1787157928','yes'),(92,'disallowed_keys','','no'),(93,'comment_previously_approved','1','yes'),(94,'auto_plugin_theme_update_emails','a:0:{}','no'),(95,'auto_update_core_dev','enabled','yes'),(96,'auto_update_core_minor','enabled','yes'),(97,'auto_update_core_major','enabled','yes'),(98,'wp_force_deactivated_plugins','a:0:{}','yes'),(99,'initial_db_version','53496','yes'),(100,'wp_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:61:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),(101,'fresh_site','1','yes'),(102,'user_count','1','no'),(103,'widget_block','a:6:{i:2;a:1:{s:7:\"content\";s:19:\"<!-- wp:search /-->\";}i:3;a:1:{s:7:\"content\";s:154:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->\";}i:4;a:1:{s:7:\"content\";s:227:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {\"displayAvatar\":false,\"displayDate\":false,\"displayExcerpt\":false} /--></div><!-- /wp:group -->\";}i:5;a:1:{s:7:\"content\";s:146:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->\";}i:6;a:1:{s:7:\"content\";s:150:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->\";}s:12:\"_multiwidget\";i:1;}','yes'),(104,'sidebars_widgets','a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}s:13:\"array_version\";i:3;}','yes'),(105,'cron','a:6:{i:1771605935;a:6:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:18:\"wp_https_detection\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1771605942;a:3:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:21:\"wp_update_user_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1771605943;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1771606002;a:1:{s:28:\"wp_update_comment_type_batch\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1771692335;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}s:7:\"version\";i:2;}','yes'),(106,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(107,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(108,'widget_archives','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(109,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(110,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(111,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(112,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(113,'widget_meta','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(114,'widget_search','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(115,'widget_recent-posts','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(116,'widget_recent-comments','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(117,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(118,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(119,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(120,'_transient_doing_cron','1771605936.0046410560607910156250','yes'),(121,'_site_transient_update_core','O:8:\"stdClass\":4:{s:7:\"updates\";a:11:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:7:\"upgrade\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.9.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.9.1.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-6.9.1-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-6.9.1-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.9.1\";s:7:\"version\";s:5:\"6.9.1\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";}i:1;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.9.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.9.1.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.9.1-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.9.1-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.9.1\";s:7:\"version\";s:5:\"6.9.1\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:2;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.9.0.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.9.0.zip\";s:10:\"no_content\";s:0:\"\";s:11:\"new_bundled\";s:0:\"\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.9.0\";s:7:\"version\";s:5:\"6.9.0\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:3;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.8.3.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.8.3.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.8.3-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.8.3-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.8.3\";s:7:\"version\";s:5:\"6.8.3\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:4;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.7.4.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.7.4.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.7.4-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.7.4-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.7.4\";s:7:\"version\";s:5:\"6.7.4\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:5;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.6.4.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.6.4.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.6.4-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.6.4-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.6.4\";s:7:\"version\";s:5:\"6.6.4\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:6;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.5.7.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.5.7.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.5.7-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.5.7-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.5.7\";s:7:\"version\";s:5:\"6.5.7\";s:11:\"php_version\";s:5:\"7.0.0\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:7;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.4.7.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.4.7.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.4.7-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.4.7-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.4.7\";s:7:\"version\";s:5:\"6.4.7\";s:11:\"php_version\";s:5:\"7.0.0\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:8;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.3.7.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.3.7.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.3.7-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.3.7-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.3.7\";s:7:\"version\";s:5:\"6.3.7\";s:11:\"php_version\";s:5:\"7.0.0\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:9;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.2.8.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.2.8.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.2.8-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.2.8-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.2.8\";s:7:\"version\";s:5:\"6.2.8\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:10;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.1.9.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.1.9.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.1.9-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.1.9-new-bundled.zip\";s:7:\"partial\";s:61:\"https://downloads.w.org/release/wordpress-6.1.9-partial-1.zip\";s:8:\"rollback\";s:62:\"https://downloads.w.org/release/wordpress-6.1.9-rollback-1.zip\";}s:7:\"current\";s:5:\"6.1.9\";s:7:\"version\";s:5:\"6.1.9\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:5:\"6.1.1\";s:9:\"new_files\";s:0:\"\";}}s:12:\"last_checked\";i:1771605942;s:15:\"version_checked\";s:5:\"6.1.1\";s:12:\"translations\";a:0:{}}','no'),(122,'_site_transient_update_plugins','O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1771605965;s:8:\"response\";a:16:{s:19:\"akismet/akismet.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:21:\"w.org/plugins/akismet\";s:4:\"slug\";s:7:\"akismet\";s:6:\"plugin\";s:19:\"akismet/akismet.php\";s:11:\"new_version\";s:3:\"5.6\";s:3:\"url\";s:38:\"https://wordpress.org/plugins/akismet/\";s:7:\"package\";s:54:\"https://downloads.wordpress.org/plugin/akismet.5.6.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:60:\"https://ps.w.org/akismet/assets/icon-256x256.png?rev=2818463\";s:2:\"1x\";s:60:\"https://ps.w.org/akismet/assets/icon-128x128.png?rev=2818463\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:63:\"https://ps.w.org/akismet/assets/banner-1544x500.png?rev=2900731\";s:2:\"1x\";s:62:\"https://ps.w.org/akismet/assets/banner-772x250.png?rev=2900731\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.8\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"7.2\";s:16:\"requires_plugins\";a:0:{}}s:51:\"all-in-one-wp-security-and-firewall/wp-security.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:49:\"w.org/plugins/all-in-one-wp-security-and-firewall\";s:4:\"slug\";s:35:\"all-in-one-wp-security-and-firewall\";s:6:\"plugin\";s:51:\"all-in-one-wp-security-and-firewall/wp-security.php\";s:11:\"new_version\";s:5:\"5.4.6\";s:3:\"url\";s:66:\"https://wordpress.org/plugins/all-in-one-wp-security-and-firewall/\";s:7:\"package\";s:84:\"https://downloads.wordpress.org/plugin/all-in-one-wp-security-and-firewall.5.4.6.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:88:\"https://ps.w.org/all-in-one-wp-security-and-firewall/assets/icon-256x256.png?rev=2798307\";s:2:\"1x\";s:88:\"https://ps.w.org/all-in-one-wp-security-and-firewall/assets/icon-128x128.png?rev=2798307\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:91:\"https://ps.w.org/all-in-one-wp-security-and-firewall/assets/banner-1544x500.png?rev=2798307\";s:2:\"1x\";s:90:\"https://ps.w.org/all-in-one-wp-security-and-firewall/assets/banner-772x250.png?rev=2798307\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.0\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"5.6\";s:16:\"requires_plugins\";a:0:{}}s:45:\"show-all-comments-in-one-page/bt-comments.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:43:\"w.org/plugins/show-all-comments-in-one-page\";s:4:\"slug\";s:29:\"show-all-comments-in-one-page\";s:6:\"plugin\";s:45:\"show-all-comments-in-one-page/bt-comments.php\";s:11:\"new_version\";s:5:\"7.0.1\";s:3:\"url\";s:60:\"https://wordpress.org/plugins/show-all-comments-in-one-page/\";s:7:\"package\";s:72:\"https://downloads.wordpress.org/plugin/show-all-comments-in-one-page.zip\";s:5:\"icons\";a:1:{s:2:\"1x\";s:82:\"https://ps.w.org/show-all-comments-in-one-page/assets/icon-128x128.png?rev=1647812\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:84:\"https://ps.w.org/show-all-comments-in-one-page/assets/banner-772x250.jpg?rev=1647825\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:5:\"3.6.1\";s:6:\"tested\";s:5:\"6.1.9\";s:12:\"requires_php\";b:0;s:16:\"requires_plugins\";a:0:{}}s:35:\"crm-perks-forms/crm-perks-forms.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:29:\"w.org/plugins/crm-perks-forms\";s:4:\"slug\";s:15:\"crm-perks-forms\";s:6:\"plugin\";s:35:\"crm-perks-forms/crm-perks-forms.php\";s:11:\"new_version\";s:5:\"1.1.7\";s:3:\"url\";s:46:\"https://wordpress.org/plugins/crm-perks-forms/\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/plugin/crm-perks-forms.1.1.7.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:68:\"https://ps.w.org/crm-perks-forms/assets/icon-256x256.png?rev=1926593\";s:2:\"1x\";s:68:\"https://ps.w.org/crm-perks-forms/assets/icon-128x128.png?rev=1926593\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:70:\"https://ps.w.org/crm-perks-forms/assets/banner-772x250.png?rev=1926593\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"3.8\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"5.3\";s:16:\"requires_plugins\";a:0:{}}s:47:\"essential-real-estate/essential-real-estate.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:35:\"w.org/plugins/essential-real-estate\";s:4:\"slug\";s:21:\"essential-real-estate\";s:6:\"plugin\";s:47:\"essential-real-estate/essential-real-estate.php\";s:11:\"new_version\";s:5:\"5.2.6\";s:3:\"url\";s:52:\"https://wordpress.org/plugins/essential-real-estate/\";s:7:\"package\";s:70:\"https://downloads.wordpress.org/plugin/essential-real-estate.5.2.6.zip\";s:5:\"icons\";a:1:{s:2:\"1x\";s:74:\"https://ps.w.org/essential-real-estate/assets/icon-128x128.png?rev=1603267\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:76:\"https://ps.w.org/essential-real-estate/assets/banner-772x250.jpg?rev=1603267\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.5\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";b:0;s:16:\"requires_plugins\";a:0:{}}s:56:\"joomsport-sports-league-results-management/joomsport.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:56:\"w.org/plugins/joomsport-sports-league-results-management\";s:4:\"slug\";s:42:\"joomsport-sports-league-results-management\";s:6:\"plugin\";s:56:\"joomsport-sports-league-results-management/joomsport.php\";s:11:\"new_version\";s:5:\"5.7.4\";s:3:\"url\";s:73:\"https://wordpress.org/plugins/joomsport-sports-league-results-management/\";s:7:\"package\";s:91:\"https://downloads.wordpress.org/plugin/joomsport-sports-league-results-management.5.7.4.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:95:\"https://ps.w.org/joomsport-sports-league-results-management/assets/icon-256x256.gif?rev=2448442\";s:2:\"1x\";s:95:\"https://ps.w.org/joomsport-sports-league-results-management/assets/icon-128x128.gif?rev=2445491\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:98:\"https://ps.w.org/joomsport-sports-league-results-management/assets/banner-1544x500.png?rev=1683464\";s:2:\"1x\";s:97:\"https://ps.w.org/joomsport-sports-league-results-management/assets/banner-772x250.png?rev=1683464\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.0\";s:6:\"tested\";s:5:\"6.8.3\";s:12:\"requires_php\";s:3:\"7.0\";s:16:\"requires_plugins\";a:0:{}}s:71:\"kivicare-clinic-management-system/kivicare-clinic-management-system.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:47:\"w.org/plugins/kivicare-clinic-management-system\";s:4:\"slug\";s:33:\"kivicare-clinic-management-system\";s:6:\"plugin\";s:71:\"kivicare-clinic-management-system/kivicare-clinic-management-system.php\";s:11:\"new_version\";s:5:\"4.1.2\";s:3:\"url\";s:64:\"https://wordpress.org/plugins/kivicare-clinic-management-system/\";s:7:\"package\";s:82:\"https://downloads.wordpress.org/plugin/kivicare-clinic-management-system.4.1.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:87:\"https://ps.w.org/kivicare-clinic-management-system/assets/icon-256×256.png?rev=2404604\";s:2:\"1x\";s:87:\"https://ps.w.org/kivicare-clinic-management-system/assets/icon-128×128.png?rev=2404604\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:89:\"https://ps.w.org/kivicare-clinic-management-system/assets/banner-772×250.jpg?rev=3448613\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:5:\"3.0.1\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"8.0\";s:16:\"requires_plugins\";a:0:{}}s:33:\"nirweb-support/nirweb-support.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:28:\"w.org/plugins/nirweb-support\";s:4:\"slug\";s:14:\"nirweb-support\";s:6:\"plugin\";s:33:\"nirweb-support/nirweb-support.php\";s:11:\"new_version\";s:5:\"3.0.3\";s:3:\"url\";s:45:\"https://wordpress.org/plugins/nirweb-support/\";s:7:\"package\";s:57:\"https://downloads.wordpress.org/plugin/nirweb-support.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:59:\"https://ps.w.org/nirweb-support/assets/icon.svg?rev=2529767\";s:3:\"svg\";s:59:\"https://ps.w.org/nirweb-support/assets/icon.svg?rev=2529767\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:70:\"https://ps.w.org/nirweb-support/assets/banner-1544x500.png?rev=2529767\";s:2:\"1x\";s:69:\"https://ps.w.org/nirweb-support/assets/banner-772x250.png?rev=2529767\";}s:11:\"banners_rtl\";a:2:{s:2:\"2x\";s:74:\"https://ps.w.org/nirweb-support/assets/banner-1544x500-rtl.png?rev=2529767\";s:2:\"1x\";s:73:\"https://ps.w.org/nirweb-support/assets/banner-772x250-rtl.png?rev=2529767\";}s:8:\"requires\";s:3:\"5.0\";s:6:\"tested\";s:5:\"6.4.7\";s:12:\"requires_php\";s:3:\"7.4\";s:16:\"requires_plugins\";a:0:{}}s:31:\"photo-gallery/photo-gallery.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:27:\"w.org/plugins/photo-gallery\";s:4:\"slug\";s:13:\"photo-gallery\";s:6:\"plugin\";s:31:\"photo-gallery/photo-gallery.php\";s:11:\"new_version\";s:6:\"1.8.38\";s:3:\"url\";s:44:\"https://wordpress.org/plugins/photo-gallery/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/photo-gallery.1.8.38.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:66:\"https://ps.w.org/photo-gallery/assets/icon-256x256.png?rev=2068745\";s:2:\"1x\";s:66:\"https://ps.w.org/photo-gallery/assets/icon-128x128.png?rev=2068745\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:69:\"https://ps.w.org/photo-gallery/assets/banner-1544x500.png?rev=2068745\";s:2:\"1x\";s:68:\"https://ps.w.org/photo-gallery/assets/banner-772x250.png?rev=2068745\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.6\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";b:0;s:16:\"requires_plugins\";a:0:{}}s:29:\"pie-register/pie-register.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:26:\"w.org/plugins/pie-register\";s:4:\"slug\";s:12:\"pie-register\";s:6:\"plugin\";s:29:\"pie-register/pie-register.php\";s:11:\"new_version\";s:7:\"3.8.4.8\";s:3:\"url\";s:43:\"https://wordpress.org/plugins/pie-register/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/pie-register.3.8.4.8.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:65:\"https://ps.w.org/pie-register/assets/icon-256x256.png?rev=2467686\";s:2:\"1x\";s:65:\"https://ps.w.org/pie-register/assets/icon-128x128.png?rev=2308581\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:67:\"https://ps.w.org/pie-register/assets/banner-772x250.jpg?rev=2858772\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.0\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"5.6\";s:16:\"requires_plugins\";a:0:{}}s:15:\"rezgo/rezgo.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:19:\"w.org/plugins/rezgo\";s:4:\"slug\";s:5:\"rezgo\";s:6:\"plugin\";s:15:\"rezgo/rezgo.php\";s:11:\"new_version\";s:6:\"4.21.8\";s:3:\"url\";s:36:\"https://wordpress.org/plugins/rezgo/\";s:7:\"package\";s:55:\"https://downloads.wordpress.org/plugin/rezgo.4.21.8.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:50:\"https://ps.w.org/rezgo/assets/icon.svg?rev=2408050\";s:3:\"svg\";s:50:\"https://ps.w.org/rezgo/assets/icon.svg?rev=2408050\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:61:\"https://ps.w.org/rezgo/assets/banner-1544x500.png?rev=2974866\";s:2:\"1x\";s:60:\"https://ps.w.org/rezgo/assets/banner-772x250.png?rev=2974866\";}s:11:\"banners_rtl\";a:2:{s:2:\"2x\";s:65:\"https://ps.w.org/rezgo/assets/banner-1544x500-rtl.png?rev=2974866\";s:2:\"1x\";s:64:\"https://ps.w.org/rezgo/assets/banner-772x250-rtl.png?rev=2974866\";}s:8:\"requires\";s:5:\"3.3.0\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"5.2\";s:16:\"requires_plugins\";a:0:{}}s:31:\"seo-local-rank/seolocalrank.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:28:\"w.org/plugins/seo-local-rank\";s:4:\"slug\";s:14:\"seo-local-rank\";s:6:\"plugin\";s:31:\"seo-local-rank/seolocalrank.php\";s:11:\"new_version\";s:5:\"2.2.9\";s:3:\"url\";s:45:\"https://wordpress.org/plugins/seo-local-rank/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/seo-local-rank.2.2.9.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/seo-local-rank/assets/icon-256x256.png?rev=2536438\";s:2:\"1x\";s:67:\"https://ps.w.org/seo-local-rank/assets/icon-128x128.png?rev=2536438\";}s:7:\"banners\";a:0:{}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:5:\"3.0.1\";s:6:\"tested\";s:5:\"6.3.7\";s:12:\"requires_php\";b:0;s:16:\"requires_plugins\";a:0:{}}s:27:\"ubigeo-peru/ubigeo-peru.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:25:\"w.org/plugins/ubigeo-peru\";s:4:\"slug\";s:11:\"ubigeo-peru\";s:6:\"plugin\";s:27:\"ubigeo-peru/ubigeo-peru.php\";s:11:\"new_version\";s:3:\"4.7\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/ubigeo-peru/\";s:7:\"package\";s:58:\"https://downloads.wordpress.org/plugin/ubigeo-peru.4.7.zip\";s:5:\"icons\";a:1:{s:2:\"1x\";s:64:\"https://ps.w.org/ubigeo-peru/assets/icon-128x128.png?rev=3223856\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/ubigeo-peru/assets/banner-1544x500.png?rev=3223856\";s:2:\"1x\";s:66:\"https://ps.w.org/ubigeo-peru/assets/banner-772x250.png?rev=3223856\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.6\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"8.0\";s:16:\"requires_plugins\";a:0:{}}s:53:\"webp-converter-for-media/webp-converter-for-media.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:38:\"w.org/plugins/webp-converter-for-media\";s:4:\"slug\";s:24:\"webp-converter-for-media\";s:6:\"plugin\";s:53:\"webp-converter-for-media/webp-converter-for-media.php\";s:11:\"new_version\";s:5:\"6.5.3\";s:3:\"url\";s:55:\"https://wordpress.org/plugins/webp-converter-for-media/\";s:7:\"package\";s:73:\"https://downloads.wordpress.org/plugin/webp-converter-for-media.6.5.3.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:77:\"https://ps.w.org/webp-converter-for-media/assets/icon-256x256.png?rev=2636288\";s:2:\"1x\";s:77:\"https://ps.w.org/webp-converter-for-media/assets/icon-128x128.png?rev=2636288\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:80:\"https://ps.w.org/webp-converter-for-media/assets/banner-1544x500.png?rev=2757184\";s:2:\"1x\";s:79:\"https://ps.w.org/webp-converter-for-media/assets/banner-772x250.png?rev=2757184\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.9\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"7.1\";s:16:\"requires_plugins\";a:0:{}}s:25:\"usc-e-shop/usc-e-shop.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:24:\"w.org/plugins/usc-e-shop\";s:4:\"slug\";s:10:\"usc-e-shop\";s:6:\"plugin\";s:25:\"usc-e-shop/usc-e-shop.php\";s:11:\"new_version\";s:7:\"2.11.27\";s:3:\"url\";s:41:\"https://wordpress.org/plugins/usc-e-shop/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/plugin/usc-e-shop.2.11.27.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:55:\"https://ps.w.org/usc-e-shop/assets/icon.svg?rev=2072308\";s:3:\"svg\";s:55:\"https://ps.w.org/usc-e-shop/assets/icon.svg?rev=2072308\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:65:\"https://ps.w.org/usc-e-shop/assets/banner-772x250.png?rev=2072308\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.6\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"7.4\";s:16:\"requires_plugins\";a:0:{}}s:45:\"nmedia-user-file-uploader/wp-file-manager.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:39:\"w.org/plugins/nmedia-user-file-uploader\";s:4:\"slug\";s:25:\"nmedia-user-file-uploader\";s:6:\"plugin\";s:45:\"nmedia-user-file-uploader/wp-file-manager.php\";s:11:\"new_version\";s:4:\"23.6\";s:3:\"url\";s:56:\"https://wordpress.org/plugins/nmedia-user-file-uploader/\";s:7:\"package\";s:73:\"https://downloads.wordpress.org/plugin/nmedia-user-file-uploader.23.6.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:78:\"https://ps.w.org/nmedia-user-file-uploader/assets/icon-256x256.jpg?rev=2393125\";s:2:\"1x\";s:78:\"https://ps.w.org/nmedia-user-file-uploader/assets/icon-128x128.jpg?rev=2393125\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:80:\"https://ps.w.org/nmedia-user-file-uploader/assets/banner-772x250.jpg?rev=2394615\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"3.5\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";b:0;s:16:\"requires_plugins\";a:0:{}}}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:4:{s:9:\"hello.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:25:\"w.org/plugins/hello-dolly\";s:4:\"slug\";s:11:\"hello-dolly\";s:6:\"plugin\";s:9:\"hello.php\";s:11:\"new_version\";s:5:\"1.7.2\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/hello-dolly/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/hello-dolly.1.7.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-256x256.jpg?rev=2052855\";s:2:\"1x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-128x128.jpg?rev=2052855\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/hello-dolly/assets/banner-1544x500.jpg?rev=2645582\";s:2:\"1x\";s:66:\"https://ps.w.org/hello-dolly/assets/banner-772x250.jpg?rev=2052855\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.6\";}s:31:\"newsletter-optin-box/noptin.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:34:\"w.org/plugins/newsletter-optin-box\";s:4:\"slug\";s:20:\"newsletter-optin-box\";s:6:\"plugin\";s:31:\"newsletter-optin-box/noptin.php\";s:11:\"new_version\";s:5:\"4.1.7\";s:3:\"url\";s:51:\"https://wordpress.org/plugins/newsletter-optin-box/\";s:7:\"package\";s:69:\"https://downloads.wordpress.org/plugin/newsletter-optin-box.4.1.7.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:73:\"https://ps.w.org/newsletter-optin-box/assets/icon-256x256.png?rev=3355797\";s:2:\"1x\";s:73:\"https://ps.w.org/newsletter-optin-box/assets/icon-128x128.png?rev=3355797\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:76:\"https://ps.w.org/newsletter-optin-box/assets/banner-1544x500.png?rev=2837760\";s:2:\"1x\";s:75:\"https://ps.w.org/newsletter-optin-box/assets/banner-772x250.png?rev=2837760\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.7\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"7.4\";s:16:\"requires_plugins\";a:0:{}}s:25:\"phastpress/phastpress.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:24:\"w.org/plugins/phastpress\";s:4:\"slug\";s:10:\"phastpress\";s:6:\"plugin\";s:25:\"phastpress/phastpress.php\";s:11:\"new_version\";s:3:\"3.9\";s:3:\"url\";s:41:\"https://wordpress.org/plugins/phastpress/\";s:7:\"package\";s:57:\"https://downloads.wordpress.org/plugin/phastpress.3.9.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:63:\"https://ps.w.org/phastpress/assets/icon-256x256.png?rev=1873505\";s:2:\"1x\";s:63:\"https://ps.w.org/phastpress/assets/icon-128x128.png?rev=1873505\";}s:7:\"banners\";a:0:{}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.2\";s:6:\"tested\";s:5:\"6.8.3\";s:12:\"requires_php\";s:3:\"7.3\";s:16:\"requires_plugins\";a:0:{}}s:27:\"woocommerce/woocommerce.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:25:\"w.org/plugins/woocommerce\";s:4:\"slug\";s:11:\"woocommerce\";s:6:\"plugin\";s:27:\"woocommerce/woocommerce.php\";s:11:\"new_version\";s:6:\"10.5.2\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/woocommerce/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/plugin/woocommerce.10.5.2.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:56:\"https://ps.w.org/woocommerce/assets/icon.svg?rev=3234504\";s:3:\"svg\";s:56:\"https://ps.w.org/woocommerce/assets/icon.svg?rev=3234504\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/woocommerce/assets/banner-1544x500.png?rev=3234504\";s:2:\"1x\";s:66:\"https://ps.w.org/woocommerce/assets/banner-772x250.png?rev=3234504\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.8\";s:6:\"tested\";s:5:\"6.9.1\";s:12:\"requires_php\";s:3:\"7.4\";s:16:\"requires_plugins\";a:0:{}}}s:7:\"checked\";a:25:{s:19:\"akismet/akismet.php\";s:5:\"5.0.1\";s:51:\"all-in-one-wp-security-and-firewall/wp-security.php\";s:5:\"4.4.0\";s:69:\"arprice-responsive-pricing-table/arprice-responsive-pricing-table.php\";s:3:\"3.6\";s:45:\"show-all-comments-in-one-page/bt-comments.php\";s:5:\"7.0.0\";s:35:\"crm-perks-forms/crm-perks-forms.php\";s:5:\"1.0.7\";s:47:\"essential-real-estate/essential-real-estate.php\";s:5:\"3.9.5\";s:31:\"gallery-album/gallery-album.php\";s:5:\"1.9.9\";s:9:\"hello.php\";s:5:\"1.7.2\";s:31:\"hypercomments/hypercomments.php\";s:5:\"1.2.1\";s:56:\"joomsport-sports-league-results-management/joomsport.php\";s:5:\"5.1.7\";s:71:\"kivicare-clinic-management-system/kivicare-clinic-management-system.php\";s:5:\"2.3.8\";s:33:\"nirweb-support/nirweb-support.php\";s:5:\"2.7.6\";s:31:\"newsletter-optin-box/noptin.php\";s:5:\"1.6.4\";s:25:\"phastpress/phastpress.php\";s:5:\"1.110\";s:31:\"photo-gallery/photo-gallery.php\";s:5:\"1.6.2\";s:29:\"pie-register/pie-register.php\";s:7:\"3.7.2.3\";s:15:\"rezgo/rezgo.php\";s:5:\"4.1.6\";s:25:\"totop-link/totop-link.php\";s:3:\"1.7\";s:31:\"seo-local-rank/seolocalrank.php\";s:5:\"2.2.2\";s:27:\"ubigeo-peru/ubigeo-peru.php\";s:5:\"3.6.3\";s:53:\"webp-converter-for-media/webp-converter-for-media.php\";s:5:\"4.0.2\";s:15:\"udraw/uDraw.php\";s:5:\"3.3.2\";s:25:\"usc-e-shop/usc-e-shop.php\";s:5:\"2.8.4\";s:27:\"woocommerce/woocommerce.php\";s:5:\"7.0.1\";s:45:\"nmedia-user-file-uploader/wp-file-manager.php\";s:4:\"21.2\";}}','no'),(123,'_site_transient_timeout_theme_roots','1771607743','no'),(124,'_site_transient_theme_roots','a:3:{s:15:\"twentytwentyone\";s:7:\"/themes\";s:17:\"twentytwentythree\";s:7:\"/themes\";s:15:\"twentytwentytwo\";s:7:\"/themes\";}','no'),(125,'_site_transient_update_themes','O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1771605943;s:7:\"checked\";a:3:{s:15:\"twentytwentyone\";s:3:\"1.7\";s:17:\"twentytwentythree\";s:3:\"1.0\";s:15:\"twentytwentytwo\";s:3:\"1.3\";}s:8:\"response\";a:3:{s:15:\"twentytwentyone\";a:6:{s:5:\"theme\";s:15:\"twentytwentyone\";s:11:\"new_version\";s:3:\"2.7\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentyone/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentyone.2.7.zip\";s:8:\"requires\";s:3:\"5.3\";s:12:\"requires_php\";s:3:\"5.6\";}s:17:\"twentytwentythree\";a:6:{s:5:\"theme\";s:17:\"twentytwentythree\";s:11:\"new_version\";s:3:\"1.6\";s:3:\"url\";s:47:\"https://wordpress.org/themes/twentytwentythree/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/theme/twentytwentythree.1.6.zip\";s:8:\"requires\";s:3:\"6.1\";s:12:\"requires_php\";s:3:\"5.6\";}s:15:\"twentytwentytwo\";a:6:{s:5:\"theme\";s:15:\"twentytwentytwo\";s:11:\"new_version\";s:3:\"2.1\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentytwo/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentytwo.2.1.zip\";s:8:\"requires\";s:3:\"5.9\";s:12:\"requires_php\";s:3:\"5.6\";}}s:9:\"no_update\";a:0:{}s:12:\"translations\";a:0:{}}','no'),(126,'_site_transient_timeout_browser_d96611be18cd31e375040b4fc3589012','1772210743','no'),(127,'_site_transient_browser_d96611be18cd31e375040b4fc3589012','a:10:{s:4:\"name\";s:7:\"Firefox\";s:7:\"version\";s:5:\"147.0\";s:8:\"platform\";s:5:\"Linux\";s:10:\"update_url\";s:32:\"https://www.mozilla.org/firefox/\";s:7:\"img_src\";s:44:\"http://s.w.org/images/browsers/firefox.png?1\";s:11:\"img_src_ssl\";s:45:\"https://s.w.org/images/browsers/firefox.png?1\";s:15:\"current_version\";s:2:\"56\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}','no'),(128,'_site_transient_timeout_php_check_a0b03c46dbe37253c3391e32a7bb296f','1772210743','no'),(129,'_site_transient_php_check_a0b03c46dbe37253c3391e32a7bb296f','a:5:{s:19:\"recommended_version\";s:3:\"8.3\";s:15:\"minimum_version\";s:6:\"7.2.24\";s:12:\"is_supported\";b:1;s:9:\"is_secure\";b:1;s:13:\"is_acceptable\";b:1;}','no'),(130,'can_compress_scripts','0','no'),(131,'recently_activated','a:0:{}','yes');
/*!40000 ALTER TABLE `wp_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
INSERT INTO `wp_postmeta` VALUES (1,2,'_wp_page_template','default'),(2,3,'_wp_page_template','default');
/*!40000 ALTER TABLE `wp_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (1,1,'2026-02-20 16:45:30','2026-02-20 16:45:30','<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->','Hello world!','','publish','open','open','','hello-world','','','2026-02-20 16:45:30','2026-02-20 16:45:30','',0,'http://localhost/wordpress/?p=1',0,'post','',1),(2,1,'2026-02-20 16:45:30','2026-02-20 16:45:30','<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://localhost/wordpress/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','publish','closed','open','','sample-page','','','2026-02-20 16:45:30','2026-02-20 16:45:30','',0,'http://localhost/wordpress/?page_id=2',0,'page','',0),(3,1,'2026-02-20 16:45:30','2026-02-20 16:45:30','<!-- wp:heading --><h2>Who we are</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Our website address is: http://localhost/wordpress.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Comments</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Media</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Cookies</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Embedded content from other websites</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Who we share your data with</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you request a password reset, your IP address will be included in the reset email.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>How long we retain your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What rights you have over your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Where your data is sent</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Visitor comments may be checked through an automated spam detection service.</p><!-- /wp:paragraph -->','Privacy Policy','','draft','closed','open','','privacy-policy','','','2026-02-20 16:45:30','2026-02-20 16:45:30','',0,'http://localhost/wordpress/?page_id=3',0,'page','',0),(4,1,'2026-02-20 16:45:43','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2026-02-20 16:45:43','0000-00-00 00:00:00','',0,'http://localhost/wordpress/?p=4',0,'post','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_relationships`
--

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (1,1,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_taxonomy`
--

LOCK TABLES `wp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES (1,1,'category','',0,1);
/*!40000 ALTER TABLE `wp_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_termmeta`
--

DROP TABLE IF EXISTS `wp_termmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_termmeta`
--

LOCK TABLES `wp_termmeta` WRITE;
/*!40000 ALTER TABLE `wp_termmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_termmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_terms`
--

LOCK TABLES `wp_terms` WRITE;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
INSERT INTO `wp_terms` VALUES (1,'Uncategorized','uncategorized',0);
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'syntax_highlighting','true'),(7,1,'comment_shortcuts','false'),(8,1,'admin_color','fresh'),(9,1,'use_ssl','0'),(10,1,'show_admin_bar_front','true'),(11,1,'locale',''),(12,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(13,1,'wp_user_level','10'),(14,1,'dismissed_wp_pointers',''),(15,1,'show_welcome_panel','1'),(16,1,'session_tokens','a:1:{s:64:\"213827547c8c28f036d2156f8780fd5f9b6cb57418722866061dc37d7536b090\";a:4:{s:10:\"expiration\";i:1772815542;s:2:\"ip\";s:10:\"172.19.0.1\";s:2:\"ua\";s:78:\"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:147.0) Gecko/20100101 Firefox/147.0\";s:5:\"login\";i:1771605942;}}'),(17,1,'wp_dashboard_quick_press_last_post_id','4');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'admin','$P$BkQjX2jfRz.asTfYhPGsNg7p7Vyh5o/','admin','admin@local.net','http://localhost/wordpress','2026-02-20 16:45:29','',0,'admin');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-20 16:49:08
