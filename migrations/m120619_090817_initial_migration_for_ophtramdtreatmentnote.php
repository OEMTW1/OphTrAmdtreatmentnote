<?php

class m120619_090817_initial_migration_for_ophtramdtreatmentnote extends CDbMigration
{
public function up() {

	// Get the event group for Treatment events
	$group = $this->dbConnection->createCommand()
	->select('id')
	->from('event_group')
	->where('code=:code', array(':code'=>'Tr'))
	->queryRow();

	// Create the new event_type
	$this->insert('event_type', array(
	'name' => 'AMD Treatment',
	'event_group_id' => $group['id'],
	'class_name' => 'OphTrAmdtreatmentnote'
	));

	// Get the newly created event type
	$event_type = $this->dbConnection->createCommand()
	->select('id')
	->from('event_type')
	->where('class_name=:class_name', array(':class_name'=>'OphTrAmdtreatmentnote'))
	->queryRow();


	// Create an element for the new event type
	$this->insert('element_type', array(
	'name' => 'Treatment Type',
	'class_name' => 'Element_OphTrAmdtreatmentnote_Injection',
	'event_type_id' => $event_type['id'],
	'display_order' => 10,
	'default' => 1,
	));


	// Create basic table for the injection element
	$this->createTable('et_ophtramdtreatmentnote_injection', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_injection_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_injection_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_injection_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_injection_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_injection_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_injection_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');


//	$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'AMD Treatment'))->queryRow();
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_Injection'))->queryRow();



	// Injection drugs drop-down
	$this->createTable('et_ophtramdtreatmentnote_injection_injection_drugs',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'count_as_injection' => 'int(10) unsigned NOT NULL default 0',
				'count_as_other' => 'int(10) unsigned NOT NULL default 0',
				'count_as_ref' => 'varchar(10) COLLATE utf8_bin NOT NULL',
				'count_as_chart' => 'varchar(10) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_iid_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_iid_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_iid_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_iid_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);

$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'None','display_order'=>1,'count_as_injection'=>0,'count_as_other'=>0,'count_as_ref'=>'NONE','count_as_chart'=>''));
$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Lucentis 0.5mg in 0.05mls','display_order'=>2,'count_as_injection'=>1,'count_as_other'=>0,'count_as_ref'=>'LUC050','count_as_chart'=>'L'));
$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Avastin 1.25mg in 0.05mls','display_order'=>3,'count_as_injection'=>1,'count_as_other'=>0,'count_as_ref'=>'AVA125','count_as_chart'=>'A'));
$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Macugen 0.3mg in 0.9mls','display_order'=>4,'count_as_injection'=>1,'count_as_other'=>0,'count_as_ref'=>'MAC030','count_as_chart'=>'M'));

$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Aflibercept 2mg in 0.05mls','display_order'=>5,'count_as_injection'=>1,'count_as_other'=>0,'count_as_ref'=>'AFL200','count_as_chart'=>'Af'));
$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Triamcinolone 4mg in 0.1mls','display_order'=>6,'count_as_injection'=>1,'count_as_other'=>0,'count_as_ref'=>'TRI400','count_as_chart'=>'T'));
$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'PDT','display_order'=>7,'count_as_injection'=>0,'count_as_other'=>0,'count_as_ref'=>'PDT','count_as_chart'=>'P'));
$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Photocoagulation','display_order'=>8,'count_as_injection'=>0,'count_as_other'=>0,'count_as_ref'=>'PHOTO','count_as_chart'=>'Ph'));




$this->insert('et_ophtramdtreatmentnote_injection_injection_drugs',array('name'=>'Other (Please specify)','display_order'=>100,'count_as_injection'=>1,'count_as_other'=>1,'count_as_ref'=>'OTHER'));


	$this->addColumn('et_ophtramdtreatmentnote_injection','injection_drug_left','int(10) unsigned NOT NULL');
	$this->addColumn('et_ophtramdtreatmentnote_injection','injection_drug_right','int(10) unsigned NOT NULL');

	$this->createIndex('et_ophtramdtreatmentnote_iidl_fk','et_ophtramdtreatmentnote_injection','injection_drug_left');
	$this->addForeignKey('et_ophtramdtreatmentnote_iidl_fk','et_ophtramdtreatmentnote_injection','injection_drug_left','et_ophtramdtreatmentnote_injection_injection_drugs','id');

	$this->createIndex('et_ophtramdtreatmentnote_iidr_fk','et_ophtramdtreatmentnote_injection','injection_drug_right');
	$this->addForeignKey('et_ophtramdtreatmentnote_iidr_fk','et_ophtramdtreatmentnote_injection','injection_drug_right','et_ophtramdtreatmentnote_injection_injection_drugs','id');



	// Any other injection details free-text
	$this->addColumn('et_ophtramdtreatmentnote_injection','injection_comment_left','text NOT NULL');
	$this->addColumn('et_ophtramdtreatmentnote_injection','injection_comment_right','text NOT NULL');


	// Any other injection details free-text
//	$this->addColumn('et_ophtramdtreatmentnote_injection','injection_legacy_left','text NOT NULL');
//	$this->addColumn('et_ophtramdtreatmentnote_injection','injection_legacy_right','text NOT NULL');


	// Create an element for aed
	$this->insert('element_type', array(
	'name' => 'Actual Event Date',
	'class_name' => 'Element_OphTrAmdtreatmentnote_ActualEventDate',
	'event_type_id' => $event_type['id'],
	'display_order' => 0,
	'default' => 1,
	));

	// Create basic table for the aed element
	$this->createTable('et_ophtramdtreatmentnote_aed', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'actual_event_date' => 'datetime',
//	'comment_left' => 'TEXT NOT NULL',
//	'comment_right' => 'TEXT NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_aed_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_aed_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_aed_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_aed_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_aed_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_aed_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');





	// Create an element for bleb
	$this->insert('element_type', array(
	'name' => 'Presence of Bleb',
	'class_name' => 'Element_OphTrAmdtreatmentnote_Bleb',
	'event_type_id' => $event_type['id'],
	'display_order' => 20,
	'default' => 1,
	));

	// Create basic table for the bleb element
	$this->createTable('et_ophtramdtreatmentnote_bleb', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'bleb_left' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
	'bleb_right' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_bleb_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_bleb_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_bleb_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_bleb_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_bleb_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_bleb_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



	// Create an element for paracentesis
	$this->insert('element_type', array(
	'name' => 'Paracentesis',
	'class_name' => 'Element_OphTrAmdtreatmentnote_Paracentesis',
	'event_type_id' => $event_type['id'],
	'display_order' => 40,
	'default' => 1,
	));

	// Create basic table for the paracentesis element
	$this->createTable('et_ophtramdtreatmentnote_paracentesis', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'paracentesis_left' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
	'paracentesis_right' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_paracentesis_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_paracentesis_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_paracentesis_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_paracentesis_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_paracentesis_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_paracentesis_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



	// Create an element for iop
	$this->insert('element_type', array(
	'name' => 'Post-injection IOP',
	'class_name' => 'Element_OphTrAmdtreatmentnote_PostInjectionIOP',
	'event_type_id' => $event_type['id'],
	'display_order' => 50,
	'default' => 1,
	));

	// Create basic table for the iop element
	$this->createTable('et_ophtramdtreatmentnote_iop', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'iop_left' => 'tinyint(4) unsigned DEFAULT NULL',
	'iop_right' => 'tinyint(4) unsigned DEFAULT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_iop_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_iop_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_iop_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_iop_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_iop_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_iop_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



	// Create an element for complication
	$this->insert('element_type', array(
	'name' => 'Complication',
	'class_name' => 'Element_OphTrAmdtreatmentnote_Complication',
	'event_type_id' => $event_type['id'],
	'display_order' => 60,
	'default' => 1,
	));

	// Create basic table for the complication element
	$this->createTable('et_ophtramdtreatmentnote_complication', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'complication_comment_left' => 'TEXT NOT NULL',
	'complication_comment_right' => 'TEXT NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_complication_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_complication_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_complication_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_complication_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_complication_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_complication_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');




	// Drop-down for complication options
	$this->createTable('et_ophtramdtreatmentnote_complication_complication_options',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_cco_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_cco_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_cco_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_cco_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);

	$this->insert('et_ophtramdtreatmentnote_complication_complication_options',array('name'=>'None','display_order'=>1));
	$this->insert('et_ophtramdtreatmentnote_complication_complication_options',array('name'=>'Lens touch','display_order'=>2));
	$this->insert('et_ophtramdtreatmentnote_complication_complication_options',array('name'=>'Vitreous haemorrhage','display_order'=>3));
	$this->insert('et_ophtramdtreatmentnote_complication_complication_options',array('name'=>'Corneal abrasion','display_order'=>4));
	$this->insert('et_ophtramdtreatmentnote_complication_complication_options',array('name'=>'Pain (consider subconjunctival anaesthesia next time)','display_order'=>5));
	$this->insert('et_ophtramdtreatmentnote_complication_complication_options',array('name'=>'Hyphaema','display_order'=>6));


	// Create basic table for the complication one-to-many
	$this->createTable('et_ophtramdtreatmentnote_complication_complication', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'complication_id' => 'int(10) unsigned NOT NULL',
				'complication_option_id' => 'int(10) unsigned NOT NULL',
				'complication_eye_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_cc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_cc_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtramdtreatmentnote_cc_complication_id_fk` (`complication_id`)',
				'KEY `et_ophtramdtreatmentnote_cc_complication_option_id_fk` (`complication_option_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_cc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_cc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_cc_complication_id_fk` FOREIGN KEY (`complication_id`) REFERENCES `et_ophtramdtreatmentnote_complication` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_cc_option_id_fk` FOREIGN KEY (`complication_option_id`) REFERENCES `et_ophtramdtreatmentnote_complication_complication_options` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);




	// Create an element for post-injection treatment
	$this->insert('element_type', array(
	'name' => 'Post-injection Treatment',
	'class_name' => 'Element_OphTrAmdtreatmentnote_PostInjectionTreatment',
	'event_type_id' => $event_type['id'],
	'display_order' => 70,
	'default' => 1,
	));

	// Create basic table for the post-injection treatment element
	$this->createTable('et_ophtramdtreatmentnote_pit', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'pit_comment_left' => 'TEXT NOT NULL',
	'pit_comment_right' => 'TEXT NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_pit_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_pit_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_pit_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_pit_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_pit_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_pit_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');




	// Drop-down for post-injection treatment options
	$this->createTable('et_ophtramdtreatmentnote_pit_treatment_options',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_pto_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_pto_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pto_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pto_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);

	$this->insert('et_ophtramdtreatmentnote_pit_treatment_options',array('name'=>'G. Celluvisc 1% PRN for 3 days','display_order'=>1));
	$this->insert('et_ophtramdtreatmentnote_pit_treatment_options',array('name'=>'G. Chloramphenicol 0.5% qid for 5 days','display_order'=>2));
	$this->insert('et_ophtramdtreatmentnote_pit_treatment_options',array('name'=>'G. Chloramphenicol 0.5% preservative free qid for 5 days','display_order'=>3));
	$this->insert('et_ophtramdtreatmentnote_pit_treatment_options',array('name'=>'G. Ofloxacin 3mg/ml qid for 5 days','display_order'=>4));


	// Create basic table for the post-injection treatment one-to-many
	$this->createTable('et_ophtramdtreatmentnote_pit_treatment', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'pit_id' => 'int(10) unsigned NOT NULL',
				'treatment_option_id' => 'int(10) unsigned NOT NULL',
				'treatment_eye_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_pt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_pt_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtramdtreatmentnote_pt_treatment_id_fk` (`pit_id`)',
				'KEY `et_ophtramdtreatmentnote_pt_treatment_option_id_fk` (`treatment_option_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pt_treatment_id_fk` FOREIGN KEY (`pit_id`) REFERENCES `et_ophtramdtreatmentnote_pit` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pt_option_id_fk` FOREIGN KEY (`treatment_option_id`) REFERENCES `et_ophtramdtreatmentnote_pit_treatment_options` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);





	// Create an element for pre-injection treatment
	$this->insert('element_type', array(
	'name' => 'Pre-injection Treatment',
	'class_name' => 'Element_OphTrAmdtreatmentnote_PreInjectionTreatment',
	'event_type_id' => $event_type['id'],
	'display_order' => 5,
	'default' => 1,
	));

	// Create basic table for the post-injection treatment element
	$this->createTable('et_ophtramdtreatmentnote_pre', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'pre_comment_left' => 'TEXT NOT NULL',
	'pre_comment_right' => 'TEXT NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_pre_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_pre_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_pre_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_pre_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_pre_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_pre_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');




	// Drop-down for post-injection treatment options
	$this->createTable('et_ophtramdtreatmentnote_pre_treatment_options',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_pro_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_pro_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pro_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pro_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);

	$this->insert('et_ophtramdtreatmentnote_pre_treatment_options',array('name'=>'Povidone iodine 5% with topical anaesthesia','display_order'=>1));
	$this->insert('et_ophtramdtreatmentnote_pre_treatment_options',array('name'=>'Povidone iodine 5% with subconjunctival anaesthesia','display_order'=>2));

	// Create basic table for the post-injection treatment one-to-many
	$this->createTable('et_ophtramdtreatmentnote_pre_treatment', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'pre_id' => 'int(10) unsigned NOT NULL',
				'treatment_option_id' => 'int(10) unsigned NOT NULL',
				'treatment_eye_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_pr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_pr_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtramdtreatmentnote_pr_treatment_id_fk` (`pre_id`)',
				'KEY `et_ophtramdtreatmentnote_pr_treatment_option_id_fk` (`treatment_option_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pr_treatment_id_fk` FOREIGN KEY (`pre_id`) REFERENCES `et_ophtramdtreatmentnote_pre` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_pr_option_id_fk` FOREIGN KEY (`treatment_option_id`) REFERENCES `et_ophtramdtreatmentnote_pre_treatment_options` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);



/*

	// Create an element for Follow-up
	$this->insert('element_type', array(
	'name' => 'Follow-up',
	'class_name' => 'Element_OphTrAmdtreatmentnote_FollowUp',
	'event_type_id' => $event_type['id'],
	'display_order' => 80,
	'default' => 1,
	));

	// Create basic table for the comment element
	$this->createTable('et_ophtramdtreatmentnote_followup', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'followup_comment' => 'TEXT NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_followup_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_followup_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_followup_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_followup_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_followup_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_followup_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');


	// Drop-down for followup options
	$this->createTable('et_ophtramdtreatmentnote_followup_followup_options',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_ffo_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_ffo_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_ffo_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_ffo_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);

	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'None','display_order'=>1));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'1 month','display_order'=>2));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'2 months','display_order'=>3));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'3 months','display_order'=>4));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'4 months','display_order'=>5));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'5 months','display_order'=>6));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'6 months','display_order'=>7));
	$this->insert('et_ophtramdtreatmentnote_followup_followup_options',array('name'=>'Discharge','display_order'=>8));


	// Create an element for referral
	$this->insert('element_type', array(
	'name' => 'Referral',
	'class_name' => 'Element_OphTrAmdtreatmentnote_Referral',
	'event_type_id' => $event_type['id'],
	'display_order' => 80,
	'default' => 1,
	));

	// Create basic table for the referral element
	$this->createTable('et_ophtramdtreatmentnote_referral', array(
	'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
	'event_id' => 'int(10) unsigned NOT NULL',
	'referral_comment' => 'TEXT NOT NULL',
	'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',	'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
	00:00:00\'',
	'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
	'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
	'PRIMARY KEY (`id`)',
	'KEY `et_ophtramdtreatmentnote_referral_event_id_fk` (`event_id`)',
	'KEY `et_ophtramdtreatmentnote_referral_created_user_id_fk`
	(`created_user_id`)',
	'KEY `et_ophtramdtreatmentnote_referral_last_modified_user_id_fk`
	(`last_modified_user_id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_referral_event_id_fk` FOREIGN KEY
	(`event_id`) REFERENCES `event` (`id`)',
	'CONSTRAINT `et_ophtramdtreatmentnote_referral_created_user_id_fk`
	FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
	'CONSTRAINT
	`et_ophtramdtreatmentnote_referral_last_modified_user_id_fk` FOREIGN KEY
	(`last_modified_user_id`) REFERENCES `user` (`id`)',
	), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');


	// Drop-down for referral options
	$this->createTable('et_ophtramdtreatmentnote_referral_referral_options',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtramdtreatmentnote_rro_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtramdtreatmentnote_rro_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_rro_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtramdtreatmentnote_rro_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
	);


	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'None','display_order'=>1));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'Low vision assessment','display_order'=>2));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'ECLO','display_order'=>3));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'Slight impairment registration','display_order'=>4));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'Glaucoma','display_order'=>5));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'Oculoplastics','display_order'=>6));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'Vitreoretinal','display_order'=>7));
	$this->insert('et_ophtramdtreatmentnote_referral_referral_options',array('name'=>'Corneal','display_order'=>8));

*/


}



public function down() {



	// Find the event type
	$event_type = $this->dbConnection->createCommand()
	->select('id')
	->from('event_type')
	->where('class_name=:class_name', array(':class_name'=>'OphTrAmdtreatmentnote'))
	->queryRow();


	// Find the Injection element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_Injection'))->queryRow();


	// Delete the Injection element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for injection
	$this->dropTable('et_ophtramdtreatmentnote_injection');
	$this->dropTable('et_ophtramdtreatmentnote_injection_injection_drugs');



	// Find the AED element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_ActualEventDate'))->queryRow();


	// Delete the AED element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for aed
	$this->dropTable('et_ophtramdtreatmentnote_aed');



	// Find the bleb element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_Bleb'))->queryRow();


	// Delete the bleb element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for bleb
	$this->dropTable('et_ophtramdtreatmentnote_bleb');




	// Find the paracentesis element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_Paracentesis'))->queryRow();


	// Delete the paracentesis element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for paracentesis
	$this->dropTable('et_ophtramdtreatmentnote_paracentesis');




	// Find the iop element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_PostInjectionIOP'))->queryRow();


	// Delete the iop element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for iop
	$this->dropTable('et_ophtramdtreatmentnote_iop');



	// Find the complication element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_Complication'))->queryRow();


	// Delete the complication element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for complication one-to-many
	$this->dropTable('et_ophtramdtreatmentnote_complication_complication');

	// Drop the table created for complication options
	$this->dropTable('et_ophtramdtreatmentnote_complication_complication_options');

	// Drop the table created for complication
	$this->dropTable('et_ophtramdtreatmentnote_complication');


	// Find the pit element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_PostInjectionTreatment'))->queryRow();

	// Delete the pit element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for pit one-to-many
	$this->dropTable('et_ophtramdtreatmentnote_pit_treatment');

	// Drop the table created for pit options
	$this->dropTable('et_ophtramdtreatmentnote_pit_treatment_options');

	// Drop the table created for pit
	$this->dropTable('et_ophtramdtreatmentnote_pit');


	// Find the pre element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_PreInjectionTreatment'))->queryRow();

	// Delete the pre element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for pre one-to-many
	$this->dropTable('et_ophtramdtreatmentnote_pre_treatment');

	// Drop the table created for pre options
	$this->dropTable('et_ophtramdtreatmentnote_pre_treatment_options');

	// Drop the table created for pre
	$this->dropTable('et_ophtramdtreatmentnote_pre');




/*
	// Find the followup element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_FollowUp'))->queryRow();

	// Delete the followup element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for followup options
	$this->dropTable('et_ophtramdtreatmentnote_followup_followup_options');

	// Drop the table created for followup
	$this->dropTable('et_ophtramdtreatmentnote_followup');



	// Find the referral element type
	$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'Element_OphTrAmdtreatmentnote_Referral'))->queryRow();

	// Delete the followup element type
	$this->delete('element_type','id='.$element_type['id']);

	// Drop the table created for followup options
	$this->dropTable('et_ophtramdtreatmentnote_referral_referral_options');

	// Drop the table created for followup
	$this->dropTable('et_ophtramdtreatmentnote_referral');

*/



	// Delete the event type
	$this->delete('event_type','id='.$event_type['id']);





}



}
