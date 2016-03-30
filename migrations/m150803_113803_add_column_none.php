<?php

class m150803_113803_add_column_none extends OEMigration
{
	public function up()
	{

		$this->addColumn('et_ophtramdtreatmentnote_injection_injection_drugs', 'count_as_none', 'tinyint(1) unsigned not null default 0');
		$this->addColumn('et_ophtramdtreatmentnote_injection_injection_drugs_version', 'count_as_none', 'tinyint(1) unsigned not null default 0');


	}

	public function down()
	{

		$this->dropColumn('et_ophtramdtreatmentnote_injection_injection_drugs', 'count_as_none');
		$this->dropColumn('et_ophtramdtreatmentnote_injection_injection_drugs_version', 'count_as_none');

	}

}
