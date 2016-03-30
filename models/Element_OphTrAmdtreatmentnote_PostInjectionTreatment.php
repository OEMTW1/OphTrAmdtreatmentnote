<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "element_procedurelist".
 *
 * The followings are the available columns in table 'element_operation':
 * @property string $id
 * @property integer $event_id
 * @property integer $assistant_id
 * @property integer $anaesthetic_type
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class Element_OphTrAmdtreatmentnote_PostInjectionTreatment extends BaseEventTypeElement
{
	public $service;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementOperation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'et_ophtramdtreatmentnote_pit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id,pit_comment_left,pit_comment_right', 'safe'),
//			array('anaesthetic_type_id, anaesthetist_id, anaesthetic_delivery_id', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id', 'safe', 'on' => 'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'element_type' => array(self::HAS_ONE, 'ElementType', 'id','on' => "element_type.class_name='".get_class($this)."'"),
			'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'pit_treatments_left' => array(self::HAS_MANY, 'OphTrAmdtreatmentnote_PostInjectionTreatmentTreatment', 'pit_id','condition'=>'treatment_eye_id = 1'),
			'pit_treatments_right' => array(self::HAS_MANY, 'OphTrAmdtreatmentnote_PostInjectionTreatmentTreatment', 'pit_id','condition'=>'treatment_eye_id = 2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'pit_treatments_left' => 'Post-injection Treatment (L)',
			'pit_treatments_right' => 'Post-injection Treatment (R)',

			'pit_comment_left' => 'Any other details (L)',
			'pit_comment_right' => 'Any other details (R)',

		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}

	


	protected function afterSave() {



//		$left = Eye::model()->findAll('name = :Name', array(':Name' => 'Left'));
//		echo $left[0]->id;

//		if ((!empty($_POST['PostInjectionTreatmentTreatmentsLeft'])) || (!empty($_POST['PostInjectionTreatmentTreatmentsRight']))) {

			OphTrAmdtreatmentnote_PostInjectionTreatmentTreatment::model()->deleteAll('pit_id = :pitId', array(':pitId' => $this->id));

//		}

		if (!empty($_POST['PostInjectionTreatmentTreatmentsLeft'])) {

//			ComplicationComplication::model()->deleteAll('complication_id = :complicationId', array(':complicationId' => $this->id));

			$order = 1;

			foreach ($_POST['PostInjectionTreatmentTreatmentsLeft'] as $id) {
				$pit_treatment = new OphTrAmdtreatmentnote_PostInjectionTreatmentTreatment;
				$pit_treatment->pit_id = $this->id;
				$pit_treatment->treatment_option_id = $id;
				$pit_treatment->treatment_eye_id = 1; // Probably shouldn't be hard-coded?!

				if (!$pit_treatment->save()) {
					throw new Exception('Unable to save pit_treatment: '.print_r($pit_treatment->getErrors(),true));
				}

				$order++;
			}
		}


		if (!empty($_POST['PostInjectionTreatmentTreatmentsRight'])) {

//			ComplicationComplication::model()->deleteAll('complication_id = :complicationId', array(':complicationId' => $this->id));

			$order = 1;

			foreach ($_POST['PostInjectionTreatmentTreatmentsRight'] as $id) {
				$pit_treatment = new OphTrAmdtreatmentnote_PostInjectionTreatmentTreatment;
				$pit_treatment->pit_id = $this->id;
				$pit_treatment->treatment_option_id = $id;
				$pit_treatment->treatment_eye_id = 2;

				if (!$pit_treatment->save()) {
					throw new Exception('Unable to save pit_treatment: '.print_r($pit_treatment->getErrors(),true));
				}

				$order++;
			}
		}



		return parent::afterSave();
	}



	/**
	 * extends standard delete method to remove any assignments made to it
	 *
	 * (non-PHPdoc)
	 * @see CActiveRecord::delete()
	 */
	public function delete()
	{
		$transaction = $this->dbConnection->beginTransaction();
		try {
			foreach ($this->pit_treatments_left as $PostInjectionTreatmentTreatmentsLeft) {
				$PostInjectionTreatmentTreatmentsLeft->delete();
			}
			foreach ($this->pit_treatments_right as $PostInjectionTreatmentTreatmentsRight) {
				$PostInjectionTreatmentTreatmentsRight->delete();
			}
			if (parent::delete()) {
				$transaction->commit();
			}
			else {
				throw new Exception('unable to delete');
			}
		}
		catch (Exception $e) {
			$transaction->rollback();
			throw $e;
		}

	}


	public function getTreatment_option_list() {

		return CHtml::listData(Yii::app()->db->createCommand()
			->select('et_ophtramdtreatmentnote_pit_treatment_options.id, et_ophtramdtreatmentnote_pit_treatment_options.name')
			->from('et_ophtramdtreatmentnote_pit_treatment_options')
			->queryAll(), 'id', 'name');




	}

}
