<div class="page" id="OphCiExamination_print">
<div class="page">
	<div class="header">
		<div class="title middle">
<!--			<img src="/img/_print/mtw.jpg" alt="letterhead_seal" class="seal" width="180" height="30"/>-->
			<strong style="font-size: 12pt;">AMD Assessment (OpenEyes)</strong>
				<font style="font-size: 8pt;"><i>  Created: <?php echo Helper::convertDate2NHS($this->event->created_date) ?>.  Last Modified:  <?php echo Helper::convertDate2NHS($this->event->last_modified_date) ?>.  Printed: <?php echo Helper::convertDate2NHS(date('Y-m-d')) ?>.</i></font>
			<hr>
		</div>
		<div class="headerInfo">
			<div class="patientDetails">
				<strong style="font-size: 10pt;"><?php echo $this->patient->correspondenceName?></strong>
				<br />
				<?php echo $this->patient->contact->address ? $this->patient->contact->address->getLetterHtml() : ''?>
				<br>
				<br>
				Hospital No: <strong style="font-size: 10pt;"><?php echo $this->patient->hos_num ?></strong>
				<br>
				NHS No: <strong style="font-size: 10pt;"><?php echo $this->patient->nhs_num ?></strong>
				<br>
				DOB: <strong style="font-size: 10pt;"><? echo Helper::convertDate2NHS($this->patient->dob) ?> (<?php echo $this->patient->getAge()?>)</strong>
			</div>

<!--
			<div class="headerDetails">
-				<strong style="font-size: 10pt;"><?php echo $this->event->episode->firm->name;?></strong>
				<br>
				Service: <strong style="font-size: 10pt;"><?php echo $this->event->episode->firm->getSubspecialtyText() ?></strong>

			</div>
-->
		</div>
	</div>
<hr>

<div class="body">


<?php $this->renderOpenElements('print'); ?>

<?php

//$this->renderDefaultElements($this->action->id);

 ?>
</div>

<?php
//$this->renderOptionalElements($this->action->id);
 ?>
</div>
