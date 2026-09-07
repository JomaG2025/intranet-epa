<?php
// /intranet/proyectos/exportar_excel.php
require_once 'config/conexion.php';

$sql = "SELECT p.*, e.nombre AS estado_nombre,
               GROUP_CONCAT(pe.usuario ORDER BY pe.usuario SEPARATOR ', ') AS encargados
        FROM pry_proyectos p
        LEFT JOIN pry_estados e ON p.id_estado = e.id
        LEFT JOIN pry_proyectos_encargados pe ON pe.id_proyecto = p.id
        WHERE 1=1
        GROUP BY p.id
        ORDER BY p.id DESC";
$proyectos = $pdo->query($sql)->fetchAll();

// Cabeceras para descarga de Excel (formato XML Spreadsheet 2003 — sin librerías)
$fecha = date('Y-m-d');
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"Cartera_Proyectos_EPA_{$fecha}.xls\"");
header("Pragma: no-cache");
header("Expires: 0");

// Función para limpiar valores para XML
function xls($val) {
    return htmlspecialchars(strip_tags((string)$val), ENT_QUOTES, 'UTF-8');
}

function celda_num($val) {
    $v = str_replace(['.', ','], ['', '.'], trim((string)$val));
    return is_numeric($v) ? $v : 0;
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:x="urn:schemas-microsoft-com:office:excel">
  <Styles>
    <Style ss:ID="header">
      <Font ss:Bold="1" ss:Color="#FFFFFF"/>
      <Interior ss:Color="#2C3E50" ss:Pattern="Solid"/>
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
      <Borders>
        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
      </Borders>
    </Style>
    <Style ss:ID="subheader">
      <Font ss:Bold="1"/>
      <Interior ss:Color="#D6E4F0" ss:Pattern="Solid"/>
      <Alignment ss:Horizontal="Center"/>
    </Style>
    <Style ss:ID="data">
      <Alignment ss:Vertical="Top" ss:WrapText="1"/>
      <Borders>
        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DDDDDD"/>
      </Borders>
    </Style>
    <Style ss:ID="num">
      <Alignment ss:Horizontal="Right" ss:Vertical="Top"/>
      <NumberFormat ss:Format="#,##0"/>
    </Style>
    <Style ss:ID="pct">
      <Alignment ss:Horizontal="Center" ss:Vertical="Top"/>
    </Style>
    <Style ss:ID="title">
      <Font ss:Bold="1" ss:Size="14" ss:Color="#2C3E50"/>
    </Style>
  </Styles>

  <Worksheet ss:Name="Cartera Proyectos">
    <Table ss:DefaultRowHeight="15">

      <!-- Fila título -->
      <Row ss:Height="24">
        <Cell ss:MergeAcross="17" ss:StyleID="title">
          <Data ss:Type="String">CARTERA DE PROYECTOS EPA — <?= date('d/m/Y') ?></Data>
        </Cell>
      </Row>
      <Row ss:Height="6"/>

      <!-- Encabezados -->
      <Row ss:Height="36" ss:StyleID="header">
        <Cell ss:StyleID="header"><Data ss:Type="String">N°</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Código BIP</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Nombre de la Iniciativa</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Tipología</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Etapa Postulación</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Estado</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">RATE</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Prioridad Estratégica</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Gerencia</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Encargados</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Fuente Financiamiento</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Clasificación</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Presupuesto IP ($)</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Presupuesto Adjudicado ($)</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Presupuesto Ejecutado ($)</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">% Ejecución</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Fecha Inicio</Data></Cell>
        <Cell ss:StyleID="header"><Data ss:Type="String">Fecha Entrega Est.</Data></Cell>
      </Row>

      <!-- Datos -->
      <?php $n = 1; foreach ($proyectos as $p): ?>
      <Row ss:AutoFitHeight="1">
        <Cell ss:StyleID="pct"><Data ss:Type="Number"><?= $n++ ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['cod_bip']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['nombre']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['tipologia']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['etapa_postulacion']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['estado_nombre']) ?></Data></Cell>
        <Cell ss:StyleID="pct"><Data ss:Type="String"><?= xls($p['rate']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['prioridad_estrategica']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['codigo_gerencia']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['encargados']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['fuente_financiamiento']) ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls($p['clasificacion']) ?></Data></Cell>
        <Cell ss:StyleID="num"><Data ss:Type="Number"><?= celda_num($p['presupuesto_ip']) ?></Data></Cell>
        <Cell ss:StyleID="num"><Data ss:Type="Number"><?= celda_num($p['presupuesto_adjudicado']) ?></Data></Cell>
        <Cell ss:StyleID="num"><Data ss:Type="Number"><?= celda_num($p['presupuesto_ejecutado']) ?></Data></Cell>
        <Cell ss:StyleID="pct"><Data ss:Type="Number"><?= (int)$p['porcentaje_ejecucion'] ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls(($p['fecha_inicio'] && $p['fecha_inicio'] !== '0000-00-00') ? $p['fecha_inicio'] : '') ?></Data></Cell>
        <Cell ss:StyleID="data"><Data ss:Type="String"><?= xls(($p['fecha_entrega_estimada'] && $p['fecha_entrega_estimada'] !== '0000-00-00') ? $p['fecha_entrega_estimada'] : '') ?></Data></Cell>
      </Row>
      <?php endforeach; ?>

    </Table>
    <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
      <FreezePanes/>
      <FrozenNoSplit/>
      <SplitHorizontal>3</SplitHorizontal>
      <TopRowBottomPane>3</TopRowBottomPane>
      <ActivePane>2</ActivePane>
    </WorksheetOptions>
  </Worksheet>
</Workbook>
