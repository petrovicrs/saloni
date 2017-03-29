CREATE TABLE IF NOT EXISTS `salon_grupa_usluga` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifier.',
  `opis` varchar(255) DEFAULT NULL COMMENT 'Opis',
  `tip_grupa_usluga` int(10) unsigned DEFAULT NULL COMMENT 'Tip grupe usluge',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Grupa Usluga';
--
CREATE TABLE IF NOT EXISTS `salon_klijent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifier.',
  `ime` varchar(64) DEFAULT NULL COMMENT 'First name.',
  `prezime` varchar(64) DEFAULT NULL COMMENT 'Last name.',
  `adresa` varchar(64) DEFAULT NULL COMMENT 'Adresa',
  `br_telefona` varchar(64) DEFAULT NULL COMMENT 'Br. telefona',
  `br_mobilnog_telefona` varchar(64) DEFAULT NULL COMMENT 'Br. mobilnog telefona',
  `email` varchar(64) DEFAULT NULL COMMENT 'Email',
  `datum_rodjenja` datetime DEFAULT NULL COMMENT 'Datum rodjenja',
  `napomena` varchar(255) DEFAULT NULL COMMENT 'Napomena',
  `datum_registrovanja` datetime DEFAULT NULL COMMENT 'Datum registrovanja',
  `popust` float DEFAULT NULL COMMENT 'Datum registrovanja',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Klijent';
--
CREATE TABLE IF NOT EXISTS `salon_termin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifier.',
  `id_klijent` int(10) unsigned DEFAULT NULL COMMENT 'Id firme',
  `id_zaposlenog` int(10) unsigned DEFAULT NULL COMMENT 'Id zaposenog',
  `id_usluge` int(10) unsigned DEFAULT NULL COMMENT 'Id usluge',
  `termin_od` int(10) unsigned DEFAULT NULL COMMENT 'Termin od',
  `termin_do` int(10) unsigned DEFAULT NULL COMMENT 'Termin do',
  `datum_rezervacije` datetime DEFAULT NULL COMMENT 'Datum rezervisanja',
  `datum_termina` date DEFAULT NULL COMMENT 'Datum termina',
  `cena` float DEFAULT NULL COMMENT 'Cena',
  `cena_bez_popusta` float DEFAULT NULL COMMENT 'Cena bez popusta',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Termin';
--
CREATE TABLE IF NOT EXISTS `salon_tip_usluga` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifier.',
  `naziv` varchar(255) DEFAULT NULL COMMENT 'Opis',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tip Usluga';
--
INSERT INTO `salon_tip_usluga` (`naziv`, `created_by`, `created_on`, `modified_by`, `modified_on`) VALUES
	('Frizer', 1, NOW(), NULL, NULL),
	('Solarijum', 1, NOW(), NULL, NULL),
	('Kozmeticar', 1, NOW(), NULL, NULL),
	('Masaza', 1, NOW(), NULL, NULL);
--
CREATE TABLE IF NOT EXISTS `salon_tip_zaposlenja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifier.',
  `naziv` varchar(64) DEFAULT NULL COMMENT 'Tip zaposlenja.',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Employees table';
--
INSERT INTO `salon_tip_zaposlenja` (`naziv`, `created_by`, `created_on`, `modified_by`, `modified_on`) VALUES
	('Frizer', 1, NOW(), NULL, NULL),
	('Masazer', 1, NOW(), NULL, NULL),
	('Kozmeticar', 1, NOW(), NULL, NULL);
--
CREATE TABLE IF NOT EXISTS `salon_usluga` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifier.',
  `grupa_usluga` int(10) unsigned DEFAULT NULL COMMENT 'Grupa usluga',
  `opis` varchar(255) DEFAULT NULL COMMENT 'Cena',
  `cena` varchar(64) DEFAULT NULL COMMENT 'Cena',
  `kod` varchar(64) DEFAULT NULL COMMENT 'Kod',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usluga';
--
CREATE TABLE IF NOT EXISTS `salon_zaposleni` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Employee identifier.',
  `ime` varchar(64) DEFAULT NULL COMMENT 'First name.',
  `prezime` varchar(64) DEFAULT NULL COMMENT 'Last name.',
  `jmbg` varchar(64) DEFAULT NULL COMMENT 'JMBG',
  `brlk` varchar(64) DEFAULT NULL COMMENT 'Br. LK',
  `adresa` varchar(64) DEFAULT NULL COMMENT 'Adresa',
  `br_telefona` varchar(64) DEFAULT NULL COMMENT 'Br. telefona',
  `br_mobilnog_telefona` varchar(64) DEFAULT NULL COMMENT 'Br. mobilnog telefona',
  `email` varchar(64) DEFAULT NULL COMMENT 'Email',
  `procenat` varchar(64) DEFAULT NULL COMMENT 'Procenat',
  `tip_zaposlenog` varchar(64) DEFAULT NULL COMMENT 'Tip zaposlenog',
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'Created by',
  `created_on` datetime DEFAULT NULL COMMENT 'Created on',
  `modified_by` int(10) unsigned DEFAULT NULL COMMENT 'Modified by',
  `modified_on` datetime DEFAULT NULL COMMENT 'Modified on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Employees table';
