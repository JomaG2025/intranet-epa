            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Modelos</h4>
			</div>

            <div class="navbar-action hidden-xs">
			    <form action="#">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" id="input-buscar" class="form-control" required autocomplete="off" placeholder="Buscar..."/>
                        </div>
                    </fieldset>
                </form>
			</div>
			
        	<div class="panel with-nav-tabs panel-default no-border" id="modelos">
				<ul class="nav nav-tabs">
                    
                    <?php $i = 0; ?>
                    
                    <?php foreach($modelos as $key => $modelo){ ?>
                        
                        <?php if(($key == $i) AND $modelo['activo'] == 1){ ?>
                            <li class="active"><a href="#modelo-<?= $modelo['id'] ?>" data-toggle="tab"><span class="hidden-xs hidden-sm"><?= $modelo['nombre']; ?></span><span class="visible-xs visible-sm"><?= character_limiter($modelo['nombre'], 12); ?></span></a></li>
                        <?php }else if(($key == $i) AND $modelo['activo'] == 0){ 
                                
                            $i++;
                        ?>
                            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                            <li style="text-decoration: line-through; "><a href="#modelo-<?= $modelo['id'] ?>" data-toggle="tab"><span class="hidden-xs hidden-sm"><?= $modelo['nombre']; ?></span><span class="visible-xs visible-sm"><?= character_limiter($modelo['nombre'], 12); ?></span></a></li>
                            <?php } ?>
                        <?php }else{ ?>
                            
                            <?php if($modelo['activo'] == 1){ ?>
                                <li><a href="#modelo-<?= $modelo['id'] ?>" data-toggle="tab"><span class="hidden-xs hidden-sm"><?= $modelo['nombre']; ?></span><span class="visible-xs visible-sm"><?= character_limiter($modelo['nombre'], 12); ?></span></a></li>
                            <?php }else if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                                <li style="text-decoration: line-through; "><a href="#modelo-<?= $modelo['id'] ?>" data-toggle="tab"><span class="hidden-xs hidden-sm"><?= $modelo['nombre']; ?></span><span class="visible-xs visible-sm"><?= character_limiter($modelo['nombre'], 12); ?></span></a></li>
                            <?php } ?>
  
                        <?php } ?>
                    
                    <?php } ?>
                    
                </ul>
				
				<script>
					
					$(function(){
                                     
                        $.widget('custom.catcomplete', $.ui.autocomplete, {
                            _create: function() {
                                this._super();
                                this.widget().menu('option', 'items', '> :not(.ui-autocomplete-category)');
                            },
                            _renderMenu: function(ul, items) {
                                var that = this,
                                currentCategory = '';
                                $.each( items, function(index, item) {
                                    var li;
                                    if (item.proceso_nombre != currentCategory) {
                                        if(item.proceso_codigo) {
                                            ul.append('<li class="ui-autocomplete-category">' + item.proceso_codigo + ' ' + item.proceso_nombre + '</li>');
                                        }else{
                                            ul.append('<li class="ui-autocomplete-category">' + item.proceso_nombre + '</li>');
                                        }
                                        currentCategory = item.proceso_nombre;
                                    }
                                    li = that._renderItemData( ul, item );
                                    if ( item.proceso_nombre ) {
                                        li.attr('aria-label', item.proceso_nombre + ' : ' + item.value);
                                    }
                                });
                            }
                        });
 
                        $('#input-buscar').catcomplete({
                            delay: 0,
                            source: function( request, response ) {
                                $.ajax( {
                                    url: '<?= site_url('api/documentos'); ?>',
                                    dataType: 'jsonp',
                                    data: {
                                        term: request.term
                                    },
                                    success: function( data ) {
                                        response(data);
                                    }
                                });
                            },
                            focus: function(event, ui) {
                                $('#input-buscar').val( ui.item.value);
                                return false;
                            },
                            select: function(event, ui) {
                                $(location).attr('href', '<?= site_url('modelos/proceso'); ?>/' + ui.item.proceso_id + '#documento-' + ui.item.id);
                            },
                            minLength: 2,
                            position: { my : 'right top', at: 'right bottom' }
                        });
                        
                        <?php if($this->uri->segment(2)){ ?>
						$('#modelos .nav-tabs [href="#modelo-<?= $this->uri->segment(2); ?>"]').tab('show');
						<?php } ?>
                        
					});
				
				</script>
				
				<div class="panel-body">
			        
			         <div class="tab-content">
                         
                        <?php foreach($modelos AS $key => $modelo){ ?>
                            
                            <?php if($modelo['activo'] == 1){ ?>
                                    
                                <div class="tab-pane fade in <?= ($key == $i) ? 'active' : ''; ?>" id="modelo-<?= $modelo['id']; ?>"><?= $this->load->view('modelos/id/'. $modelo['id'], array('modelo' => $modelo)); ?></div>
                            
                            <?php }else if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                            
                                <div class="tab-pane fade in <?= ($key == $i) ? 'active' : ''; ?>" id="modelo-<?= $modelo['id']; ?>"><?= $this->load->view('modelos/id/'. $modelo['id'], array('modelo' => $modelo)); ?></div>
                         
                            <?php } ?>
                        
                         <?php } ?> 

                    </div>
			        
			    </div>
			</div>