<?php

echo $this->Session->flash('test');
?>


<h1>Create Appointment</h1>
<?php
echo $this->Form->create('Appointment');
echo $this->Form->input('Name');
echo $this->Form->input('ID');
echo $this->Form->input('AppoinmentTime');


echo $this->Form->end('Create Appointment');
?>