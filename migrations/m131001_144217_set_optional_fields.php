<?php

class m131001_144217_set_optional_fields extends CDbMigration
{
	public function up()
	{

		$this->update('element_type',array('default'=>1),"class_name='Element_OphTrAmdtreatmentnote_Injection'");
		$this->update('element_type',array('default'=>0),"class_name='Element_OphTrAmdtreatmentnote_ActualEventDate'");
		$this->update('element_type',array('default'=>0),"class_name='Element_OphTrAmdtreatmentnote_Bleb'");
		$this->update('element_type',array('default'=>0),"class_name='Element_OphTrAmdtreatmentnote_Paracentesis'");
		$this->update('element_type',array('default'=>0),"class_name='Element_OphTrAmdtreatmentnote_PostInjectionIOP'");
		$this->update('element_type',array('default'=>0),"class_name='Element_OphTrAmdtreatmentnote_Complication'");
		$this->update('element_type',array('default'=>1),"class_name='Element_OphTrAmdtreatmentnote_PostInjectionTreatment'");
		$this->update('element_type',array('default'=>1),"class_name='Element_OphTrAmdtreatmentnote_PreInjectionTreatment'");


		$this->update('element_type',array('required'=>1),"class_name='Element_OphTrAmdtreatmentnote_Injection'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_ActualEventDate'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_Bleb'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_Paracentesis'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_PostInjectionIOP'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_Complication'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_PostInjectionTreatment'");
		$this->update('element_type',array('required'=>0),"class_name='Element_OphTrAmdtreatmentnote_PreInjectionTreatment'");

	}

	public function down()
	{
	}

}
