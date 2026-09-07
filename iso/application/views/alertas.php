<?php  if($this->session->flashdata('alerta')){
                                              
    $alerta = explode('_', $this->session->flashdata('alerta')); 
    
    switch($alerta[0])
    {
        case 'danger':
        $icon = 'fa fa-times-circle';
            
        case 'warning':
        $icon = 'fa fa-warning';
            
        case 'info':
        $icon = 'fa fa-info';
            
        case 'success':
        $icon = 'fa fa-check';
    }
?>

<script>
    $.notify({
        message : '<strong><?= $this->lang->line($alerta[0]); ?></strong> <?= $this->lang->line($this->session->flashdata('alerta')); ?>',
        icon : '<?= $icon; ?>'
    },{
        type: '<?= $alerta[0]; ?>',
        placement: {
            from: 'bottom',
            align: 'right'
        }
    });
</script>

<?php } ?>