<?php 
        
		echo $this->Html->css('bootstrap-datetimepicker.min');
        echo $this->Html->script(array(
            'jquery','moment','transition', 'collapse', 'bootstrap.min', 'bootstrap-datetimepicker.min'
        ));
        
        

?>

<h1>Change Time</h1>
<?php
echo $this->Form->create('Appointment');
echo $this->Form->input('doctor_id', array('options'=>$doctorList,'class'=>'','disabled'));
echo $this->Form->input('appointment_time', array('type'=>'text','id'=>'appointmentTime'));
echo $this->Form->end('Change Time');


?>

<script type="text/javascript">
    $(function () {
        
        $('#appointmentTime').datetimepicker(
                {   minDate: '2015-2-15',
                    stepping: 15
                }
                );
        
    });
</script>