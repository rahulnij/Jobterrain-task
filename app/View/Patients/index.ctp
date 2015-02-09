
<h1>Appointments</h1>
<?php 
//$this->requestAction('posts/latest/1/1/12:1/ff:ff');
echo $this->Html->link(
    'Create Appointment',
    array('controller' => 'patients', 'action' => 'add')
); ?>
<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Appointment.id','S.No')?></th>
        <th><?php echo $this->Paginator->sort('Doctor.first_name','Doctor Name')?></th>
        <th><?php echo $this->Paginator->sort('Appointment.appointmentTime', 'Appointment Time')?></th>
        <th><?php echo $this->Paginator->sort('Appointment.created', 'Create Date')?></th>
    </tr>
<?php
	foreach($appointments as $appointment) {
        
?>
<tr>
	<td>--</td>
	<td><?php echo $appointment['Doctor']['first_name'];?></td>
	<td><?php echo $appointment['Appointment']['appointmentTime'];?></td>
    <td><?php echo $appointment['Appointment']['created'];?></td>
</tr>
<?php
	}
?>

</table>
<div>
<?php echo $this->Paginator->numbers(); ?>    
             <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>    
    <?php echo $this->Paginator->counter(); ?>
</div>