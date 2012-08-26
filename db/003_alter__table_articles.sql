ALTER TABLE  `articles` CHANGE  `article_id`  `com_article_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE  `hits`  `com_hits` INT( 11 ) NOT NULL ,
CHANGE  `active`  `com_active` TINYINT( 1 ) NOT NULL DEFAULT  '1',
CHANGE  `time`  `com_time` INT( 11 ) NOT NULL ,
CHANGE  `order`  `com_order` INT( 11 ) NOT NULL;
