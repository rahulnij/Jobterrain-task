<h1>Appointment</h1>
<?php
echo $this->Form->create('Appointment');
echo $this->Form->input('status', array('options'=> array(STATUS_APPROVED => 'Approved', STATUS_UNAPPROVED => 'Declined')));
echo $this->Form->end('Save');


?>
