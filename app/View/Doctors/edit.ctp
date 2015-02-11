<h1>Appointment with</h1>
<?php
echo $this->Form->create('Appointment');
echo $this->Form->input('status', array('options'=> array('Approved' => 'Approved', 'UnApproved' => 'UnApproved')));
echo $this->Form->end('Change Time');


?>
