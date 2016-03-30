<?php

class m140924_125356_table_versioning extends OEMigration
{
	public function up()
	{


		$this->renameTable('et_ophtramdtreatmentnote_complication_complication_options','et_ophtramdtreatmentnote_complication_complication_optns');


		$this->addColumn('et_ophtramdtreatmentnote_complication_complication_optns', 'active', 'boolean not null default true');
		$this->addColumn('et_ophtramdtreatmentnote_injection_injection_drugs', 'active', 'boolean not null default true');
		$this->addColumn('et_ophtramdtreatmentnote_pit_treatment_options', 'active', 'boolean not null default true');
		$this->addColumn('et_ophtramdtreatmentnote_pre_treatment_options', 'active', 'boolean not null default true');


		$this->versionExistingTable('et_ophtramdtreatmentnote_aed');
		$this->versionExistingTable('et_ophtramdtreatmentnote_bleb');
		$this->versionExistingTable('et_ophtramdtreatmentnote_complication');
		$this->versionExistingTable('et_ophtramdtreatmentnote_complication_complication');
		$this->versionExistingTable('et_ophtramdtreatmentnote_complication_complication_optns');
		$this->versionExistingTable('et_ophtramdtreatmentnote_injection');
		$this->versionExistingTable('et_ophtramdtreatmentnote_injection_injection_drugs');
		$this->versionExistingTable('et_ophtramdtreatmentnote_iop');
		$this->versionExistingTable('et_ophtramdtreatmentnote_paracentesis');
		$this->versionExistingTable('et_ophtramdtreatmentnote_pit');
		$this->versionExistingTable('et_ophtramdtreatmentnote_pit_treatment');
		$this->versionExistingTable('et_ophtramdtreatmentnote_pit_treatment_options');
		$this->versionExistingTable('et_ophtramdtreatmentnote_pre');
		$this->versionExistingTable('et_ophtramdtreatmentnote_pre_treatment');
		$this->versionExistingTable('et_ophtramdtreatmentnote_pre_treatment_options');




	}

	public function down()
	{


		$this->dropTable('et_ophtramdtreatmentnote_aed_version');
		$this->dropTable('et_ophtramdtreatmentnote_bleb_version');
		$this->dropTable('et_ophtramdtreatmentnote_complication_version');
		$this->dropTable('et_ophtramdtreatmentnote_complication_complication_version');
		$this->dropTable('et_ophtramdtreatmentnote_complication_complication_optns_version');
		$this->dropTable('et_ophtramdtreatmentnote_injection_version');
		$this->dropTable('et_ophtramdtreatmentnote_injection_injection_drugs_version');
		$this->dropTable('et_ophtramdtreatmentnote_iop_version');
		$this->dropTable('et_ophtramdtreatmentnote_paracentesis_version');
		$this->dropTable('et_ophtramdtreatmentnote_pit_version');
		$this->dropTable('et_ophtramdtreatmentnote_pit_treatment_version');
		$this->dropTable('et_ophtramdtreatmentnote_pit_treatment_options_version');
		$this->dropTable('et_ophtramdtreatmentnote_pre_version');
		$this->dropTable('et_ophtramdtreatmentnote_pre_treatment_version');
		$this->dropTable('et_ophtramdtreatmentnote_pre_treatment_options_version');

		$this->dropColumn('et_ophtramdtreatmentnote_complication_complication_optns', 'active');
		$this->dropColumn('et_ophtramdtreatmentnote_injection_injection_drugs', 'active');
		$this->dropColumn('et_ophtramdtreatmentnote_pit_treatment_options', 'active');
		$this->dropColumn('et_ophtramdtreatmentnote_pre_treatment_options', 'active');


		$this->renameTable('et_ophtramdtreatmentnote_complication_complication_optns','et_ophtramdtreatmentnote_complication_complication_options');


	}

}
