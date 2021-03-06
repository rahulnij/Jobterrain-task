<?php 
//$this->requestAction('posts/latest/1/1/12:1/ff:ff');
echo $this->Html->link(
    'Request New Appointment',
    array('controller' => 'patients', 'action' => 'add')
); ?>


<?php if (count($appointments) > 0) {?>
<h1>Appointments</h1>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Appointment.id','S.No')?></th>
        <th><?php echo $this->Paginator->sort('User.first_name','Doctor Name')?></th>
        <th><?php echo $this->Paginator->sort('Appointment.appointment_time', 'Appointment Time')?></th>
        <th><?php echo $this->Paginator->sort('Appointment.status', 'Status')?></th>
        <th><?php echo $this->Paginator->sort('Appointment.created', 'Create Date')?></th>
        <th><?php echo $this->Paginator->sort(null, 'Reschedule ')?></th>
    </tr>
<?php
    $i = 1;
	foreach($appointments as $appointment) {
        
?>
<tr>
	<td><?php echo $i++;?></td>
	<td><?php echo $appointment['Doctor']['first_name'];?></td>
	<td><?php echo $appointment['Appointment']['appointment_time'];?></td>
    <td><?php echo status($appointment['Appointment']['status']);?></td>

    <td><?php echo $appointment['Appointment']['created'];?></td>
    <td><?php if ($appointment['Appointment']['status'] == STATUS_UNAPPROVED) {
            echo $this->Html->link('Edit',   array('controller' => 'patients', 'action' => 'edit',$appointment['Appointment']['id']));
        }?>
    </td>
</tr>
<?php
	}
?>

</table>
<?php echo $this->element('paginate');?>
<?php }?>