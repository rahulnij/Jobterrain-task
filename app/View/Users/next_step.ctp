<?php 
echo $this->form->create();
echo $this->form->label('','I am:', array('class' => 'main-lbl'));
$options = array('PATIENT' => 'Patient', 'DOCTOR' => 'Doctor');
$attributes = array('div' => false, 'legend'=> false, 'separator' => '');
echo $this->Form->radio('user_type', $options, $attributes);
echo $this->form->end('Next>>');
?>
