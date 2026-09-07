        	<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
			</div>
        	<div class="panel panel-default no-border">
        		<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
				            <form method="post" action="<?= site_url('admin/usuarios/grabar'); ?>" enctype="multipart/form-data">
                                <fieldset>
                                    <legend>Nuevo Usuario</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>Usuario</label>
                                                    <input name="usuario" type="text" class="form-control" placeholder="Usuario" maxlength="32" required autocomplete="off" autofocus>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>E-mail</label>
                                                    <input name="email" type="email" class="form-control" placeholder="nombre@puertoarica.cl" maxlength="32" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input name="nombre" type="text" class="form-control" placeholder="Nombre" maxlength="32" required autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label>Cargo</label>
                                                <input name="cargo" type="text" class="form-control" placeholder="Cargo" maxlength="64" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>Rut</label>
                                                    <div class="row">
                                                        <div class="col-xs-9">
                                                            <input type="text" maxlength="8" name="rut" class="form-control" />
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <input type="text" maxlength="1" name="dv" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Sexo</label>
                                                    <select name="sexo" class="form-control">
                                                        <option value="M">Masculino</option>
                                                        <option value="F">Femenino</option>
                                                    </select>
												</div>
								            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>Privilegio</label>
                                                    <select name="privilegio" class="form-control">
                                                        <?php foreach($privilegios as $privilegio){ ?>
                                                        <option value="<?= $privilegio['abrev'] ?>"><?= $privilegio['nombre']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>El usuario debe cambiar la contraseña en el próximo inicio?</label>
                                                    <div class="clearfix mb-5"></div>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="primer_inicio" value="0" checked /> No
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="primer_inicio" value="1" /> Si
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
												<div class="form-group col-md-6">
                                                    <label>Contraseña</label>
													<div class="input-group">
								    					<input type="password" name="password" maxlength="64" class="form-control" pattern="[a-zA-Z0-9]{8,}"/>
								    					<span class="input-group-btn">
													  	    <a class="btn btn-default btn-ver-password"><i class="fa fa-eye"></i></a>
                                                        </span>
												    </div>
							    	            </div>
							    				<div class="form-group col-md-6">
							    					<label>Re-ingrese Contraseña</label>
							    					<div class="input-group">
								    					<input type="password" name="password2" maxlength="64" class="form-control" pattern="[a-zA-Z0-9]{8,}"/>
								    					<span class="input-group-btn">
                                                            <a class="btn btn-default btn-ver-password"><i class="fa fa-eye"></i></a>
                                                        </span>
							    					</div>
							    	            </div>
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">
                                                        <strong>INFO!</strong> La Contraseña debe tener longitud mínima de 8 caracteres, debe contener al menos 1 letra y 1 número y además debe ser diferente a las utilizadas últimamente. 	
                                                    </div>
                                                </div>
								            </div>
											<div class="row">
												<div class="form-group col-sm-6">
													<a href="<?= site_url('admin/usuarios'); ?>" class="btn btn-default btn-block btn-cancelar submit"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
												<div class="form-group col-sm-6">
													<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
												</div>
											</div>
                                        </div>
								    </div>
								</fieldset>
                            </form>
                        </div>
                    </div>
			    </div>
			</div>