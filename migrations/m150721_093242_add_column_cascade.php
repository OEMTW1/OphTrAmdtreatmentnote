<?php

class m150721_093242_add_column_cascade extends OEMigration
{
	public function up()
	{

		$this->addColumn('et_ophtramdtreatmentnote_injection_injection_drugs', 'count_as_cascade', 'integer(10) unsigned not null default 0');
		$this->addColumn('et_ophtramdtreatmentnote_injection_injection_drugs_version', 'count_as_cascade', 'integer(10) unsigned not null default 0');

	}

	public function down()
	{

		$this->dropColumn('et_ophtramdtreatmentnote_injection_injection_drugs', 'count_as_cascade');
		$this->dropColumn('et_ophtramdtreatmentnote_injection_injection_drugs_version', 'count_as_cascade');

	}

}
