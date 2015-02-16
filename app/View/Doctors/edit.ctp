<h1>Appointment with</h1>
<?php
echo $this->Form->create('Appointment');
echo $this->Form->input('status', array('options'=> array(STATUS_APPROVED => 'Approved', STATUS_UNAPPROVED => 'Un Approved')));
echo $this->Form->end('Change Time');


?>
