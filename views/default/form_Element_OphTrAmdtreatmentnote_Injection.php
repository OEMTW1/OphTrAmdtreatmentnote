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

// EJM (22/07/2015) Yet another re-write
if ((isset($this->episode)) && (isset($this->patient)) && (! isset($element->id)) && ($element->elementType->default ==1) ){

	if (in_array(Yii::app()->getController()->getAction()->id,array('create'))) {


		$dateIntT = 0; //  Use event_date as the event could have been back-dated
		if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

			$mrelementT = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphTrAmdtreatmentnote_Injection');

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
		if(isset($mrelementT->injection_type_left)){
			if($mrelementT->injection_type_left->count_as_cascade > 0){
				$element->injection_drug_left = $mrelementT->injection_drug_left;
				if(isset($mrelementT->injection_comment_left)){
					$element->injection_comment_left = $mrelementT->injection_comment_left;
				}
			}
		}
		if(isset($mrelementT->injection_type_right)){
			if($mrelementT->injection_type_right->count_as_cascade > 0){
				$element->injection_drug_right = $mrelementT->injection_drug_right;
				if(isset($mrelementT->injection_comment_right)){
					$element->injection_comment_right = $mrelementT->injection_comment_right;
				}
			}
		}



		// If there is a (more recent) assessment event (drug) then consider that instead of treatment event
		if($dateIntT <= $dateIntA){
			if(isset($mrelementA->tp_drug_left)){
				$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_left);
				if(isset($drug->id)){
					if($drug->count_as_cascade > 0){
						$element->injection_drug_left = $mrelementA->tp_drug_left;
						unset($element->injection_comment_left);
					}
				}
			}
			if(isset($mrelementA->tp_drug_right)){
				$drug = OphTrAmdtreatmentnote_InjectionDrug::model()->findByPk($mrelementA->tp_drug_right);
				if(isset($drug->id)){
					if($drug->count_as_cascade > 0){
						$element->injection_drug_right = $mrelementA->tp_drug_right;
						unset($element->injection_comment_right);
					}
				}
			}
		}

		// If there is a treatment plan specified that should not be cascaded then unset (regardless of from where any drug has been cascaded)
		if($dateIntT <= $dateIntA){
			if(isset($mrelementA->tp_type_left)){
				if($mrelementA->tp_type_left->count_as_cascade <= 0) {
					unset($element->injection_drug_left);
					unset($element->injection_comment_left);
				}

			}
			if(isset($mrelementA->tp_type_right)){
				if($mrelementA->tp_type_right->count_as_cascade <= 0) {
					unset($element->injection_drug_right);
					unset($element->injection_comment_right);
				}

			}
		}


		// If "No Treatment" specified then set drug to first matching "None"
		if($dateIntT <= $dateIntA){
			if(isset($mrelementA->tp_type_left)){
				if($mrelementA->tp_type_left->count_as_none == 1) {
					$criteria = new CDbCriteria;
					$criteria->compare('count_as_none',1);
					$criteria->limit = 1;
					$criteria->order = 'display_order';
					$drug =  OphTrAmdtreatmentnote_InjectionDrug::model()->find($criteria);
					if (isset($drug->id)){$element->injection_drug_left = $drug->id;}
				}
			}
			if(isset($mrelementA->tp_type_right)){
				if($mrelementA->tp_type_right->count_as_none == 1) {
					$criteria = new CDbCriteria;
					$criteria->compare('count_as_none',1);
					$criteria->limit = 1;
					$criteria->order = 'display_order';
					$drug =  OphTrAmdtreatmentnote_InjectionDrug::model()->find($criteria);
					if (isset($drug->id)){$element->injection_drug_right = $drug->id;}
				}
			}
		}


		// If the treatment plan and drug do not correspond w.r.t. counting as treatment/injection (or not) then unset
		if($dateIntT <= $dateIntA){
			if((isset($mrelementA->tp_type_left)) && (isset($element->injection_type_left))){
				if($mrelementA->tp_type_left->count_as_treatment <> $element->injection_type_left->count_as_injection) {
					unset($element->injection_drug_left);
					unset($element->injection_comment_left);
				}
			}
			if((isset($mrelementA->tp_type_right)) && (isset($element->injection_type_right))){
				if($mrelementA->tp_type_right->count_as_treatment <> $element->injection_type_right->count_as_injection) {
					unset($element->injection_drug_right);
					unset($element->injection_comment_right);
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

			$mrelementT = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphTrAmdtreatmentnote_Injection');

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
		if(isset($mrelementT->injection_drug_left)){$element->injection_drug_left = $mrelementT->injection_drug_left;}
		if(isset($mrelementT->injection_drug_right)){$element->injection_drug_right = $mrelementT->injection_drug_right;}

		if(isset($mrelementT->injection_comment_left)){$element->injection_comment_left = $mrelementT->injection_comment_left;}
		if(isset($mrelementT->injection_comment_right)){$element->injection_comment_right = $mrelementT->injection_comment_right;}



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

								$element->injection_drug_left = $mrelementA->tp_drug_left;
								if(isset($element->injection_comment_left)){unset($element->injection_comment_left);}


								} else {  // Unset the default (if set)

								if(isset($element->injection_drug_left)){unset($element->injection_drug_left);}
								if(isset($element->injection_comment_left)){unset($element->injection_comment_left);}

								}

							}

						}

					} else {  // Unset the default (if set)

						if(isset($element->injection_drug_left)){unset($element->injection_drug_left);}
						if(isset($element->injection_comment_left)){unset($element->injection_comment_left);}

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

								$element->injection_drug_right = $mrelementA->tp_drug_right;
								if(isset($element->injection_comment_right)){unset($element->injection_comment_right);}

								} else {  // Unset the default (if set)

								if(isset($element->injection_drug_right)){unset($element->injection_drug_right);}
								if(isset($element->injection_comment_right)){unset($element->injection_comment_right);}

								}

							}

						}

					} else {  // Unset the default (if set)

						if(isset($element->injection_drug_right)){unset($element->injection_drug_right);}
						if(isset($element->injection_comment_right)){unset($element->injection_comment_right);}

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

			$mrelementT = $api->getElementForLatestEventInEpisode($this->episode, 'Element_OphTrAmdtreatmentnote_Injection');

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

		if(isset($mrelementT->injection_drug_left)){$element->injection_drug_left = $mrelementT->injection_drug_left;}
		if(isset($mrelementT->injection_drug_right)){$element->injection_drug_right = $mrelementT->injection_drug_right;}

		if(isset($mrelementT->injection_comment_left)){$element->injection_comment_left = $mrelementT->injection_comment_left;}
		if(isset($mrelementT->injection_comment_right)){$element->injection_comment_right = $mrelementT->injection_comment_right;}


		if(isset($mrelementA->tp_drug_left)){$element->injection_drug_left = $mrelementA->tp_drug_left;}
		if(isset($mrelementA->tp_drug_right)){$element->injection_drug_right = $mrelementA->tp_drug_right;}


	}

}
*/



/*
if ((isset($this->episode)) && (isset($this->patient)) && (! isset($element->id)) && ($element->elementType->default ==1) ){

	if (in_array(Yii::app()->getController()->getAction()->id,array('create'))) { // 

		if ($api = Yii::app()->moduleAPI->get('OphCiAmdassessment')) {

			$mrelementA = $api->getElementForLatestEventInEpisode($this->patient, $this->episode, 'Element_OphCiAmdassessment_TreatmentPlan');

			if (isset($mrelementA)){

echo $mrelementA->created_date;

				if(isset($mrelementA->tp_drug_left)){$element->injection_drug_left = $mrelementA->tp_drug_left;}
				if(isset($mrelementA->tp_drug_right)){$element->injection_drug_right = $mrelementA->tp_drug_right;}

			}

		}


		if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

			$mrelementT = $api->getElementForLatestEventInEpisode($this->patient, $this->episode, 'Element_OphTrAmdtreatmentnote_Injection');

			if (isset($mrelementT)){
echo $mrelementT->created_date;
				if(isset($mrelementT->injection_drug_left)){$element->injection_drug_left = $mrelementT->injection_drug_left;}
				if(isset($mrelementT->injection_drug_right)){$element->injection_drug_right = $mrelementT->injection_drug_right;}

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

			$mrelement = $api->getMostRecentElementInEpisode($this->episode->id, $this->event_type->id, 'Element_OphTrAmdtreatmentnote_Injection');

			$mrevent = $api->getMostRecentEventInEpisode($this->episode->id, $this->event_type->id);

			if (isset($mrevent) && isset($mrelement)){ // EJM (17/10/2013) 
			if ($mrevent->id == $mrelement->event_id){ // EJM (17/10/2013) 

				if(isset($mrelement->injection_drug_left)){$element->injection_drug_left = $mrelement->injection_drug_left;}
				if(isset($mrelement->injection_drug_right)){$element->injection_drug_right = $mrelement->injection_drug_right;}

				if(isset($mrelement->injection_comment_left)){$element->injection_comment_left = $mrelement->injection_comment_left;}
				if(isset($mrelement->injection_comment_right)){$element->injection_comment_right = $mrelement->injection_comment_right;}

			}
			}

		}

	}

}

*/


/*
if ((isset($this->episode)) && (isset($this->patient))){

// EJM (15/07/2013) Using the API seems to be the best way
if ($api = Yii::app()->moduleAPI->get('OphTrAmdtreatmentnote')) {

	$mrelement = $api->getMostRecentElementInEpisode($this->episode->id, $this->event_type->id, 'Element_OphTrAmdtreatmentnote_Injection');

	if(isset($mrelement->injection_drug_left)){$element->injection_drug_left = $mrelement->injection_drug_left;}
	if(isset($mrelement->injection_drug_right)){$element->injection_drug_right = $mrelement->injection_drug_right;}



}

}
*/
?>




<div class="<?php echo $element->elementType->class_name?>">
<!--	<h4 class="elementTypeName"><?php echo $element->elementType->name ?></h4> -->
<table>

<tr>
<td>
	<?php echo $form->dropDownList($element,'injection_drug_right',$element->Injection_drug_list,array('empty'=>'- Please select -'))?>
	<?php echo $form->textArea($element,'injection_comment_right',array('rows' => 2, 'cols' => 40))?>
</td>
<td>
	<?php echo $form->dropDownList($element,'injection_drug_left',$element->Injection_drug_list,array('empty'=>'- Please select -'))?>
	<?php echo $form->textArea($element,'injection_comment_left',array('rows' => 2, 'cols' => 40))?>
</td>
</tr>




</table>

</div>

