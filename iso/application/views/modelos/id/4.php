                            <div class="row">
                        		<div class="col-md-12">
                        			<img src="<?= base_url(); ?>img/mapa.png" alt="" class="img-responsive" usemap="#image-map-2017" />
                                    <map name="image-map-2017">
                                        
                                        <area href="<?= site_url('modelos/proceso/85') ?>" coords="139,239,318,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/86') ?>" coords="369,239,546,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/87') ?>" coords="1113,239,1309,341" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/88') ?>" coords="1428,239,1625,341" shape="rect">
                                        
                                        <area href="<?= site_url('modelos/proceso/89') ?>" coords="541,813,758,898" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/90') ?>" coords="1102,813,1319,898" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/91') ?>" coords="806,745,1023,831" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/92') ?>" coords="1057,619,1273,719" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/93') ?>" coords="539,619,853,719" shape="rect">
                                        
                                        <area href="<?= site_url('modelos/proceso/94') ?>" coords="243,1089,424,1191" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/95') ?>" coords="440,1089,618,1191" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/96') ?>" coords="634,1089,812,1191" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/97') ?>" coords="834,1089,1013,1191" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/98') ?>" coords="1038,1089,1215,1191" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/99') ?>" coords="1233,1089,1411,1191" shape="rect">
                                        <area href="<?= site_url('modelos/proceso/100') ?>" coords="1428,1089,1607,1191" shape="rect">
                                        
                                        <area href="<?= site_url('modelos/grupo/26') ?>" coords="102,130,102,368,581,368,576,209,314,212,314,130" shape="poly">
                                        <area href="<?= site_url('modelos/grupo/27') ?>" coords="1073,212,1228,209,1228,128,1433,127,1437,209,1700,209,1704,368,1072,368"  shape="poly">
                                        <area href="<?= site_url('modelos/grupo/28') ?>" coords="498,577,498,934,1349,934,1349,576,893,576,894,520,384,521,384,576"  shape="poly">
                                        <area href="<?= site_url('modelos/grupo/29') ?>" coords="223,1010,226,1217,1625,1219,1624,1061,521,1061,521,1008"  shape="poly">
                                        
                                    </map>
                                </div>
                            </div>

                            <script>
                                $(function(){
                                    setInterval(function(){
                                    $('map[name=image-map-2017]').imageMapResize();
                                    }, 200);
                                                           
                                });
                            </script>