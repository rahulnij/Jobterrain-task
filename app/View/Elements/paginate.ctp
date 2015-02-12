<div class="pgintn">
    <?php 
        if ((int) $this->Paginator->counter(array('format' => '%pages%')) > 1) {
            echo $this->Paginator->prev('«', null, null, array('class' => 'disabled')); 
            echo $this->Paginator->numbers(array('separator'=>''));
            echo $this->Paginator->next('»', null, null, array('class' => 'disabled'));    
        }
    ?>
    <?php echo $this->Paginator->counter(array('format' => '<div class="fr">Page %page% of %pages%</div>')); ?>
</div>