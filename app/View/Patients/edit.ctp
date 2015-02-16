<?php 
        
		echo $this->Html->css('bootstrap-datetimepicker.min');
        echo $this->Html->script(array(
            'jquery','moment','transition', 'collapse', 'bootstrap.min', 'bootstrap-datetimepicker.min'
        ));
        
        

?>

<h1>Reschedule Time</h1>
<?php
echo $this->Form->create('Appointment');
echo $this->Form->input('doctor_id', array('label'=> 'Doctor Name &amp; ID', 'options'=>$doctorList));
echo $this->Form->input('appointment_time', array('type'=>'text','id'=>'appointmentTime'));
echo $this->Form->end('Update');


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