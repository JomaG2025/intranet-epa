                            
                            <div class="navbar-action hidden-xs" style="right: 240px;">
                                <?php if($this->session->userdata('full') OR ($this->uri->segment(3) == 'full')){ ?>
                                <a href="<?= site_url('modelos/'. $modelo['id'] . '/simple'); ?>" class="btn btn-primary"><i class="fa fa-map-o"></i> Ver mapa simple</a>
                                <?php }else{ ?>
                                <a href="<?= site_url('modelos/'. $modelo['id'] . '/full'); ?>" class="btn btn-primary"><i class="fa fa-map-o"></i> Ver mapa completo</a>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php if($this->session->userdata('full') OR ($this->uri->segment(3) == 'full')){ ?>
                                <div class="col-md-12">
                        			<img src="<?= base_url(); ?>img/mapa_2019_full.png" alt="" class="img-responsive" usemap="#image-map-2019-full" />
                                    <map name="image-map-2019-full">
                                        <area href="<?= site_url('modelos/proceso/101'); ?>" coords="139,237,317,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/102'); ?>" coords="369,237,547,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/103'); ?>" coords="1113,237,1304,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/104'); ?>" coords="1429,237,1627,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/106'); ?>" coords="807,746,1026,830" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/107'); ?>" coords="539,812,758,897" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/108'); ?>" coords="1103,812,1321,897" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/109'); ?>" coords="245,1089,424,1192" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/110'); ?>" coords="440,1089,618,1192" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/111'); ?>" coords="634,1089,814,1192" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/112'); ?>" coords="834,1089,1013,1192" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/105'); ?>" coords="1036,1089,1217,1192" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/113'); ?>" coords="1428,1089,1607,1192" shape="rect">
                                        <area href="#" class="btn disabled" coords="1233,1089,1413,1192" shape="rect">
                                        
                                        <area href="<?= site_url('modelos/grupo/30'); ?>" coords="100,130,310,130,310,211,577,211,577,367,100,367" shape="poly">
                                        <area href="<?= site_url('modelos/grupo/31'); ?>" coords="1233,130,1427,130,1427,211,1701,211,1701,367,1072,367,1072,211,1233,211" shape="poly">
                                        <area href="<?= site_url('modelos/grupo/32'); ?>" coords="893,533,893,579,1350,579,1350,935,501,935,499,561,383,561,383,530" shape="poly">
                                        <area href="<?= site_url('modelos/grupo/33'); ?>" coords="225,1017,523,1017,524,1062,1624,1062,1625,1220,225,1220" shape="poly">
                                    </map>
                                </div>
                                <?php }else{ ?>
                        		<div class="col-lg-10 col-lg-offset-1 col-md-12">
                        			<img src="<?= base_url(); ?>img/mapa_2019.png" alt="" class="img-responsive" usemap="#image-map-2019" />
                                    <map name="image-map-2019">
                                        <area href="#modal-sub-01" data-toggle="modal" coords="88,117,435,328" shape="rect">
                                        <area href="#modal-sub-02" data-toggle="modal" coords="1336,117,1674,328" shape="rect">
                                        <area href="#modal-sub-03" data-toggle="modal" coords="593,1031,832,1167" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/106'); ?>" coords="950,469,1242,581" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/107'); ?>" coords="950,603,1242,715" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/108'); ?>" coords="950,747,1242,859" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/105'); ?>" coords="927,1029,1166,1167" shape="rect">
                                        
                                        <area href="<?= site_url('modelos/grupo/30'); ?>" coords="96,66,524,105" shape="rect">
                                        <area href="<?= site_url('modelos/grupo/31'); ?>" coords="1208,69,1675,106" shape="rect">
                                        <area href="<?= site_url('modelos/grupo/32'); ?>" coords="540,365,540,415,452,415,452,896,1306,898,1306,415,1225,415,1225,365" shape="poly">
                                        <area href="<?= site_url('modelos/grupo/33'); ?>" coords="582,945,964,945,963,993,1203,995,1202,1204,560,1203,561,993,583,992" shape="poly">
                                    </map>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="modal fade" id="modal-sub-01" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">SISTEMA</h4>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?= base_url(); ?>img/sub_01_2019.png" alt="" class="img-responsive" usemap="#image-map-sub-01" />
                                            <map name="image-map-sub-01">
                                                <area href="<?= site_url('modelos/proceso/101'); ?>" coords="31,33,265,156" shape="rect">
                                                <area href="<?= site_url('modelos/proceso/102'); ?>" coords="301,33,535,156" shape="rect">
                                            </map>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modal-sub-02" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">PROCESOS ESTRATÉGICOS</h4>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?= base_url(); ?>img/sub_02_2019.png" alt="" class="img-responsive" usemap="#image-map-sub-02" />
                                            <map name="image-map-sub-02">
                                                <area href="<?= site_url('modelos/proceso/103'); ?>" coords="35,35,271,158" shape="rect">
                                                <area href="<?= site_url('modelos/proceso/104'); ?>" coords="302,35,536,158" shape="rect">
                                            </map>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modal-sub-03" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">RECURSOS</h4>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?= base_url(); ?>img/sub_03_2019.png" alt="" class="img-responsive" usemap="#image-map-sub-03" />
                                            <map name="image-map-sub-03">
                                                <area href="<?= site_url('modelos/proceso/109'); ?>" coords="26,33,239,156" shape="rect">
                                                <area href="<?= site_url('modelos/proceso/110'); ?>" coords="259,33,472,156" shape="rect">
                                                <area href="<?= site_url('modelos/proceso/111'); ?>" coords="492,33,707,156" shape="rect">
                                                <area href="<?= site_url('modelos/proceso/112'); ?>" coords="735,33,948,156" shape="rect">
                                                <area href="<?= site_url('modelos/proceso/113'); ?>" coords="975,33,1190,156" shape="rect">
                                            </map>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
	                       </div>

                            <script>
                                $(function(){
                                    setInterval(function(){
                                    $('map[name=image-map-2019], map[name=image-map-2019-full], map[name=image-map-sub-01], map[name=image-map-sub-02], map[name=image-map-sub-03]').imageMapResize();
                                    }, 200);                                                           
                                });
                            </script>