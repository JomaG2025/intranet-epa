<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa fa-briefcase"></i> Cartera de Proyectos</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Listado de Iniciativas EPA</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr class="text-primary">
                            <th>BIP</th>
                            <th>NOMBRE</th>
                            <th>ESTADO</th>
                            <th class="text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $p): ?>
                        <tr>
                            <td><?= $p['cod_bip'] ?></td>
                            <td><strong><?= $p['nombre'] ?></strong></td>
                            <td><?= $p['estado_nombre'] ?></td>
                            <td class="text-center">
                                <a href="ficha/<?= $p['id'] ?>" class="btn btn-default btn-xs"><i class="fa fa-search"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>