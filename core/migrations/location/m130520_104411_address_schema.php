<?php

class m130520_104411_address_schema extends CDbMigration
{
	public function up()
	{
        $this->execute("
        CREATE TABLE IF NOT EXISTS `address` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `address_line_1` varchar(255) NOT NULL,
          `address_line_2` varchar(255) DEFAULT NULL,
          `city` varchar(128) NOT NULL,
          `region` varchar(255) DEFAULT NULL,
          `postal_code` varchar(128) NOT NULL,
          `country` varchar(2) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
	}

	public function down()
	{
		echo "m130520_104411_address_schema does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}