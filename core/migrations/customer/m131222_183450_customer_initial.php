<?php

class m131222_183450_customer_initial extends CDbMigration
{
	public function up()
	{
        $this->execute("
        CREATE TABLE IF NOT EXISTS `customer` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `first_name` varchar(255) NULL,
          `last_name` varchar(255) NULL,
          `private_email` varchar(255) NOT NULL,
          `mobile_phone` varchar(20) NULL,
          `password` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

        INSERT INTO `customer` (`id`, `first_name`, `last_name`, `private_email`, `mobile_phone`, `password`) VALUES
        (1, 'Ivan', 'Ivanov', 'ivan.ivanov@example.com', '+380000000000', 'b91cf70c9b8b7edeb72693620b6a50c9f987636946db3c2ed6faf5bd316c88a4'),
        (2, 'Petr', 'Petrov', 'petr.petrov@example.com', '+380000000001', 'b91cf70c9b8b7edeb72693620b6a50c9f987636946db3c2ed6faf5bd316c88a4');
        ");
	}

	public function down()
	{
		echo "m131222_183450_customer_initial does not support migration down.\n";
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