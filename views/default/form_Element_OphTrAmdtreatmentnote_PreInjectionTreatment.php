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
?>

<?


// EJM (22/07/2015) 
if ((isset($this->episode)) && (isset($this->patient)) && (! isset($element->id)) && ($element->elementType->default ==1) ){

	if (in_array(Yii::app()->getController()->getAction()->id,array('create'))) {


		$dateIntT = 0; //  Use event_date as the event could have been back-dated
		if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

			$mrelementT = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphTrAmdtreatmentnote_PreInjectionTreatment');

			if (isset($mrelementT)){
				$mrelementT_injection = Element_OphTrAmdtreatmentnote_Injection::model()->find('event_id=?',array($mrelementT->event_id));
				$mreventT = Event::model()->findByPk($mrelementT->event_id);
				$dateIntT = str_replace('-','',str_replace(' ','',str_replace(':','',$mreventT->event_date)));
			}

		}


		$dateIntA = 0;
		if ($api = Yii::app()->moduleAPI->get('OphCiAmdassessment')) {

			$mrelementA = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphCiAmdassessment_TreatmentPlan');

			if (isset($mrelementA)){
				$mreventA = Event::model()->findByPk($mrelementA->event_id);
				$dateIntA = str_replace('-','',str_replace(' ','',str_replace(':','',$mreventA->event_date)));
			}

		}


		$pre_treatments_left_cascaded = false;
		$pre_treatments_right_cascaded = false;


		// Default to using most recent treatment event
		if((isset($mrelementT->pre_treatments_left) && isset($mrelementT_injection->injection_type_left))){
			if(($mrelementT_injection->injection_type_left->count_as_cascade > 0) && ($mrelementT_injection->injection_type_left->count_as_injection > 0)){
				$element->pre_treatments_left = $mrelementT->pre_treatments_left;
				if(isset($mrelementT->pre_comment_left)){
					$element->pre_comment_left = $mrelementT->pre_comment_left;
				}
				$pre_treatments_left_cascaded = true;
			}
		}
		if((isset($mrelementT->pre_treatments_right) && isset($mrelementT_injection->injection_type_right))){
			if(($mrelementT_injection->injection_type_right->count_as_cascade > 0) && ($mrelementT_injection->injection_type_right->count_as_injection > 0)){
				$element->pre_treatments_right = $mrelementT->pre_treatments_right;
				if(isset($mrelementT->pre_comment_right)){
					$element->pre_comment_right = $mrelementT->pre_comment_right;
				}
				$pre_treatments_right_cascaded = true;
			}
		}



		// If there is a (more recent) assessment event (drug) then consider that
		if($dateIntT <= $dateIntA){
			if(isset($mrelementA->tp_drug_left)){
				$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_left);
				if(isset($drug->id)){
					if(($drug->count_as_cascade > 0) && ($drug->count_as_injection > 0)){
						if(! ($pre_treatments_left_cascaded)){
							$criteria = new CDbCriteria;
							$criteria->compare('display_order',1);
							$criteria->limit = 1;
							$option =  OphTrAmdtreatmentnote_pretreatmentOption::model()->find($criteria);

							$pre_treatment = new OphTrAmdtreatmentnote_PreInjectionTreatmentTreatment;
							$pre_treatment->treatment_option_id = $option->id;

							$element->pre_treatments_left = Array($pre_treatment);

							unset($element->pre_comment_left);
						}
					} else {
						unset($element->pre_treatments_left);
						unset($element->pre_comment_left);
					}
				}
			}
			if(isset($mrelementA->tp_drug_right)){
				$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_right);
				if(isset($drug->id)){
					if(($drug->count_as_cascade > 0) && ($drug->count_as_injection > 0)){
						if(! ($pre_treatments_right_cascaded)){
							$criteria = new CDbCriteria;
							$criteria->compare('display_order',1);
							$criteria->limit = 1;
							$option =  OphTrAmdtreatmentnote_pretreatmentOption::model()->find($criteria);

							$pre_treatment = new OphTrAmdtreatmentnote_PreInjectionTreatmentTreatment;
							$pre_treatment->treatment_option_id = $option->id;

							$element->pre_treatments_right = Array($pre_treatment);

							unset($element->pre_comment_right);
						}
					} else {
						unset($element->pre_treatments_right);
						unset($element->pre_comment_right);
					}
				}
			}

		}

		// If there is a treatment plan specified that should not be cascaded then unset (regardless of from where any drug has been cascaded)
		if($dateIntT <= $dateIntA){
			if(isset($mrelementA->tp_type_left)){
				if(($mrelementA->tp_type_left->count_as_cascade <= 0) || ($mrelementA->tp_type_left->count_as_treatment <= 0) || ($mrelementA->tp_type_left->count_as_none == 1)) {
					unset($element->pre_treatments_left);
					unset($element->pre_comment_left);
				}
			}
			if(isset($mrelementA->tp_type_right)){
				if(($mrelementA->tp_type_right->count_as_cascade <= 0) || ($mrelementA->tp_type_right->count_as_treatment <= 0) || ($mrelementA->tp_type_right->count_as_none == 1)) {
					unset($element->pre_treatments_right);
					unset($element->pre_comment_right);
				}
			}
		}



	}

}

/*
if ((isset($this->episode)) && (isset($this->patient)) && (! isset($element->id)) && ($element->elementType->default ==1) ){

	if (in_array(Yii::app()->getController()->getAction()->id,array('create'))) {


		$dateIntT = 0; //  Use event_date as the event could have been back-dated
		if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

			$mrelementT = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphTrAmdtreatmentnote_PreInjectionTreatment');

			if (isset($mrelementT)){
				$mreventT = Event::model()->findByPk($mrelementT->event_id);
				$dateIntT = str_replace('-','',str_replace(' ','',str_replace(':','',$mreventT->event_date)));
			}

		}


		$dateIntA = 0;
		if ($api = Yii::app()->moduleAPI->get('OphCiAmdassessment')) {

			$mrelementA = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphCiAmdassessment_TreatmentPlan');

			if (isset($mrelementA)){
				$mreventA = Event::model()->findByPk($mrelementA->event_id);
				$dateIntA = str_replace('-','',str_replace(' ','',str_replace(':','',$mreventA->event_date)));
			}

		}


		// Default to using most recent treatment event
		if(isset($mrelementT->pre_treatments_left)){$element->pre_treatments_left = $mrelementT->pre_treatments_left;}
		if(isset($mrelementT->pre_treatments_right)){$element->pre_treatments_right = $mrelementT->pre_treatments_right;}

		if(isset($mrelementT->pre_comment_left)){$element->pre_comment_left = $mrelementT->pre_comment_left;}
		if(isset($mrelementT->pre_comment_right)){$element->pre_comment_right = $mrelementT->pre_comment_right;}


		// If there is a (more recent) assessment event then consider that instead of treatment event
		if($dateIntT <= $dateIntA){

			if(isset($mrelementA->tp_left)){

				$tp = OphCiAmdassessment_tpType::model()->findByPk($mrelementA->tp_left);

				if(isset($tp->count_as_treatment)){

					if ($tp->count_as_treatment > 0){

						if(isset($mrelementA->tp_drug_left)){

							$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_left);

							if(isset($drug->count_as_injection)){

								if ($drug->count_as_injection > 0 ){


									$criteria = new CDbCriteria;
									$criteria->compare('display_order',1);
									$criteria->limit = 1;
									$option =  OphTrAmdtreatmentnote_pretreatmentOption::model()->find($criteria);

									$pre_treatment = new OphTrAmdtreatmentnote_PreInjectionTreatmentTreatment;
									$pre_treatment->treatment_option_id = $option->id;

									$element->pre_treatments_left = Array($pre_treatment);


								} else {  // Unset the default (if set)

									if(isset($element->pre_treatments_left)){unset($element->pre_treatments_left);}
									if(isset($element->pre_comment_left)){unset($element->pre_comment_left);}

								}

							}

						}

					} else {  // Unset the default (if set)

						if(isset($element->pre_treatments_left)){unset($element->pre_treatments_left);}
						if(isset($element->pre_comment_left)){unset($element->pre_comment_left);}

					}

				}

			}

			if(isset($mrelementA->tp_right)){

				$tp = OphCiAmdassessment_tpType::model()->findByPk($mrelementA->tp_right);

				if(isset($tp->count_as_treatment)){

					if ($tp->count_as_treatment > 0){

						if(isset($mrelementA->tp_drug_right)){

							$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_right);

							if(isset($drug->count_as_injection)){

								if ($drug->count_as_injection > 0 ){


									$criteria = new CDbCriteria;
									$criteria->compare('display_order',1);
									$criteria->limit = 1;
									$option =  OphTrAmdtreatmentnote_pretreatmentOption::model()->find($criteria);

									$pre_treatment = new OphTrAmdtreatmentnote_PreInjectionTreatmentTreatment;
									$pre_treatment->treatment_option_id = $option->id;

									$element->pre_treatments_right = Array($pre_treatment);


								} else {  // Unset the default (if set)

									if(isset($element->pre_treatments_right)){unset($element->pre_treatments_right);}
									if(isset($element->pre_comment_right)){unset($element->pre_comment_right);}

								}

							}

						}

					} else {  // Unset the default (if set)

						if(isset($element->pre_treatments_right)){unset($element->pre_treatments_right);}
						if(isset($element->pre_comment_right)){unset($element->pre_comment_right);}

					}

				}

			}

		}

	}

}
*/

/*
if ((isset($this->episode)) && (isset($this->patient)) && (! isset($element->id)) && ($element->elementType->default ==1) ){

	if (in_array(Yii::app()->getController()->getAction()->id,array('create'))) {


		if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

			$mrelementT = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphTrAmdtreatmentnote_PreInjectionTreatment');

			if (isset($mrelementT)){$mreventT = Event::model()->findByPk($mrelementT->event_id);}

		}


		if ($api = Yii::app()->moduleAPI->get('OphCiAmdassessment')) {

			$mrelementA = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphCiAmdassessment_TreatmentPlan');

			if (isset($mrelementA)){$mreventA = Event::model()->findByPk($mrelementA->event_id);}

		}


		// Keep the most recent out of the two event types, consider the event_date as the event could have been back-dated.
		if(isset($mreventT) && isset($mreventA)){ 

			$dateIntT = str_replace('-','',str_replace(' ','',str_replace(':','',$mreventT->event_date)));
			$dateIntA = str_replace('-','',str_replace(' ','',str_replace(':','',$mreventA->event_date)));

			if($dateIntT > $dateIntA){unset($mrelementA);} else {unset($mrelementT);}

		}

		if(isset($mrelementT->pre_treatments_left)){$element->pre_treatments_left = $mrelementT->pre_treatments_left;}
		if(isset($mrelementT->pre_treatments_right)){$element->pre_treatments_right = $mrelementT->pre_treatments_right;}

		if(isset($mrelementT->pre_comment_left)){$element->pre_comment_left = $mrelementT->pre_comment_left;}
		if(isset($mrelementT->pre_comment_right)){$element->pre_comment_right = $mrelementT->pre_comment_right;}


		if(isset($mrelementA->tp_drug_left)){

			$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_left);

			if(isset($drug->count_as_injection)){

			if ($drug->count_as_injection > 0){

				$criteria = new CDbCriteria;
				$criteria->compare('display_order',1);
				$criteria->limit = 1;
				$option =  OphTrAmdtreatmentnote_pretreatmentOption::model()->find($criteria);

				$pre_treatment = new OphTrAmdtreatmentnote_PreInjectionTreatmentTreatment;
				$pre_treatment->treatment_option_id = $option->id;

				$element->pre_treatments_left = Array($pre_treatment);

			}

			}

		}

		if(isset($mrelementA->tp_drug_right)){

			$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_right);

			if(isset($drug->count_as_injection)){

			if ($drug->count_as_injection > 0){

				$criteria = new CDbCriteria;
				$criteria->compare('display_order',1);
				$criteria->limit = 1;
				$option =  OphTrAmdtreatmentnote_pretreatmentOption::model()->find($criteria);

				$pre_treatment = new OphTrAmdtreatmentnote_PreInjectionTreatmentTreatment;
				$pre_treatment->treatment_option_id = $option->id;

				$element->pre_treatments_right = Array($pre_treatment);

			}

			}

		}


	}

}
*/


/*
// EJM (08/10/2013) TO DO - Try to find a way of making this work by calling a function in the model that does not hang the expand
if ((isset($this->episode)) && (isset($this->patient)) && (! isset($element->id)) && ($element->elementType->default ==1) ){

	if (in_array(Yii::app()->getController()->getAction()->id,array('create'))) { // 

		if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

			$mrelement = $api->getMostRecentElementInEpisode($this->episode->id, $this->event_type->id, 'Element_OphTrAmdtreatmentnote_PreInjectionTreatment');

			$mrevent = $api->getMostRecentEventInEpisode($this->episode->id, $this->event_type->id);

			if (isset($mrevent) && isset($mrelement)){ // EJM (17/10/2013) 
			if ($mrevent->id == $mrelement->event_id){ // EJM (17/10/2013) 

				if(isset($mrelement->pre_treatments_left)){$element->pre_treatments_left = $mrelement->pre_treatments_left;}
				if(isset($mrelement->pre_treatments_right)){$element->pre_treatments_right = $mrelement->pre_treatments_right;}

				if(isset($mrelement->pre_comment_left)){$element->pre_comment_left = $mrelement->pre_comment_left;}
				if(isset($mrelement->pre_comment_right)){$element->pre_comment_right = $mrelement->pre_comment_right;}

			}
			}

		}

	}

}
*/



// EJM (15/07/2013) Using the API seems to be the best way
/*
if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

	$mrelement = $api->getMostRecentElementInEpisode($this->episode->id, $this->event_type->id, 'Element_OphTrAmdtreatmentnote_PreInjectionTreatment');

	if(isset($mrelement->pre_treatments_left)){$element->pre_treatments_left = $mrelement->pre_treatments_left;}
	if(isset($mrelement->pre_treatments_right)){$element->pre_treatments_right = $mrelement->pre_treatments_right;}

}
}
*/
?>


<div class="<?php echo $element->elementType->class_name?>">
<!--	<h4 class="elementTypeName"><?php echo $element->elementType->name ?></h4> -->

<table>
<tr>
<td>
	<?php echo $form->multiSelectList($element, 'PreInjectionTreatmentTreatmentsRight', 'pre_treatments_right', 'treatment_option_id', $element->Treatment_option_list, array(), array('empty' => '- Treatments -', 'label' => 'Treatments (R)'))?>
	<?php echo $form->textArea($element,'pre_comment_right',array('rows' => 2, 'cols' => 40))?>
</td>
<td>
	<?php echo $form->multiSelectList($element, 'PreInjectionTreatmentTreatmentsLeft', 'pre_treatments_left', 'treatment_option_id', $element->Treatment_option_list, array(), array('empty' => '- Treatments -', 'label' => 'Treatments (L)'))?>
	<?php echo $form->textArea($element,'pre_comment_left',array('rows' => 2, 'cols' => 40))?>
</td>

</tr>
</table>

</div>
