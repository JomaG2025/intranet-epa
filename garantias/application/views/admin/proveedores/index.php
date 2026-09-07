			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Proveedores</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Proveedores</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
					        <div class="table-responsive">
								<table class="table table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th>id</th>
											<th>RUT</th>
											<th>Razón Social</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									
									<?php foreach($proveedores as $proveedor){ ?>
									
										<tr>
											<td style="width: 5%;"><?= $proveedor['id']; ?></td>
											<td><a href="<?= site_url('admin/proveedores/editar/'. $proveedor['id']); ?>" title=""><?= formato_monto($proveedor['rut']); ?>-<?= $proveedor['dv']; ?></a></td>
											<td><?= $proveedor['razon']; ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/proveedores/editar/'. $proveedor['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
									
										</tr>
									<?php } ?>
									
									</tbody>
								</table>
								<script>
								    $(function(){
								        $('.table').dataTable({
									         bStateSave : true,      
										      fnStateSave :function(settings,data){
										        localStorage.setItem("dataTables_state", JSON.stringify(data));
										      },
										      fnStateLoad: function(settings) {
										        return JSON.parse(localStorage.getItem("dataTables_state"));
										      },
										     'aoColumnDefs': [
											 	{ "bSortable": false, "aTargets": [ 3 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 3 ] }
											 ]
								        });
								    });
								</script>
							</div>
			        	</div>						
					</div>
			    </div>
			</div>