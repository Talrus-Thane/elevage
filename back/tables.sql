CREATE TABLE IF NOT EXISTS `#__acym_species` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NULL,
    PRIMARY KEY (`id`)
    )
    ENGINE = InnoDB
/*!40100
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acym_color` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NULL,
    `species_id` INT NOT NULL,
    `pregnancy_time` INT NOT NULL,
    PRIMARY KEY (`id`)
    )
    ENGINE = InnoDB
/*!40100
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acym_mount` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NULL,
    `color_id` INT NOT NULL,
    `mother_id` INT NULL,
    `father_id` INT NULL,
    `max_reproductions` INT NOT NULL,
    `reproductions_counter` INT NOT NULL DEFAULT 0,
    `last_partner_id` INT NULL,
    `gender` TINYINT(1) NOT NULL,
    `last_mating` INT NULL,
    `purity_level` INT NOT NULL,
    `reproductive` TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
    )
    ENGINE = InnoDB
/*!40100
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acym_configuration` (
	`name` VARCHAR(255) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`name`)
)
	ENGINE = InnoDB
	/*!40100
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci*/;
