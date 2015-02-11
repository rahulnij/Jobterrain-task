<div>
<?php echo $this->Paginator->numbers(); ?>    
             <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>    
    <?php echo $this->Paginator->counter(); ?>
</div>