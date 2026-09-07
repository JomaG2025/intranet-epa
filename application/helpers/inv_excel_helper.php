<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Helper de importacion Excel - Modulo Inventario
| -------------------------------------------------------------------
| Funciones puras de apoyo para Inv_importador (application/libraries/
| Inv_importador.php): deteccion de la libreria PHPExcel, normalizacion
| de texto para comparar catalogos, conversion de fechas (serial Excel
| o texto DD-MM-YYYY) y de valores contables (miles/decimales chilenos).
| No dependen de PHPExcel estar cargado -- son matematica/texto plano,
| se pueden usar (y probar) igual este disponible o no la libreria.
| -------------------------------------------------------------------
*/

if ( ! function_exists('inv_excel_lib_disponible'))
{
    /*
    | PHPExcel 1.8.1, copiada a mano (sin Composer) tal como llega el
    | codigo fuente clasico de PHPOffice/PHPExcel: PHPExcel.php es el
    | unico punto de entrada, y desde ahi el propio PHPExcel_Autoloader
    | resuelve el resto de las clases (PHPExcel_IOFactory,
    | PHPExcel_Reader_Excel2007, etc.) contra la carpeta PHPExcel/ que
    | vive junto a el -- por eso basta con comprobar este archivo.
    | Ruta esperada: application/third_party/PHPExcel/PHPExcel.php
    | (NO application/third_party/PHPExcel/Classes/PHPExcel.php -- esa
    | carpeta Classes/ es como viene el repositorio completo en GitHub,
    | pero aqui se copia solo su contenido directamente dentro de
    | third_party/PHPExcel/, sin ese nivel de carpeta de mas).
    */
    function inv_excel_lib_disponible()
    {
        return is_file(APPPATH . 'third_party/PHPExcel/PHPExcel.php');
    }
}

if ( ! function_exists('inv_normalizar'))
{
    function inv_normalizar($valor)
    {
        $texto = trim((string) $valor);

        if (function_exists('mb_strtolower'))
        {
            return mb_strtolower($texto, 'UTF-8');
        }

        // Sin mbstring instalado: solo se pliegan mayusculas/minusculas
        // ASCII. Dos valores que solo difieran en una tilde (ej. "AREA"
        // vs "AREA" con A acentuada) no se detectarian como iguales en
        // este caso extremo -- muy improbable en un Linux con PHP 5.6
        // tipico (mbstring casi siempre viene compilado), pero se deja
        // documentado por si acaso.
        return strtolower($texto);
    }
}

if ( ! function_exists('inv_excel_fecha_a_ymd'))
{
    /*
    | Devuelve:
    |   NULL          -> celda vacia (no es un error, no hace falta advertencia)
    |   FALSE         -> habia algo pero no se pudo interpretar como fecha
    |   'YYYY-MM-DD'  -> fecha valida
    |
    | Nunca interpreta un numero de serie de Excel como texto: si $valor
    | es numerico, SIEMPRE se trata como serial de Excel (dias desde el
    | 30-12-1899, la misma convencion que usa el propio Excel/PHPExcel),
    | nunca como si fuera, por ejemplo, un RUT o un codigo.
    */
    function inv_excel_fecha_a_ymd($valor)
    {
        if ($valor === NULL) return NULL;

        if (is_object($valor) AND method_exists($valor, 'format'))
        {
            return $valor->format('Y-m-d');
        }

        if (is_numeric($valor))
        {
            $serial = (float) $valor;
            if ($serial <= 0) return NULL;

            // Formula estandar de conversion de serial Excel a Unix:
            // 25569 = dias entre el "dia 0" de Excel (30-12-1899, ya
            // considerando el bug historico del a\xf1o bisiesto 1900 de
            // Excel) y el 01-01-1970. gmdate() evita corrimientos de un
            // dia por zona horaria (una fecha de Excel no tiene hora).
            $unix = ($serial - 25569) * 86400;

            return gmdate('Y-m-d', (int) round($unix));
        }

        $texto = trim((string) $valor);
        if ($texto === '') return NULL;

        // Formato visto en la planilla real: DD-MM-YYYY (nunca se
        // interpreta como MM-DD-YYYY / formato estadounidense).
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $texto, $m))
        {
            return inv_excel_validar_fecha((int) $m[3], (int) $m[2], (int) $m[1]);
        }

        // Tambien acepta DD/MM/YYYY por si el separador viene distinto.
        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $texto, $m))
        {
            return inv_excel_validar_fecha((int) $m[3], (int) $m[2], (int) $m[1]);
        }

        // Ya viene como YYYY-MM-DD.
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $texto, $m))
        {
            return inv_excel_validar_fecha((int) $m[1], (int) $m[2], (int) $m[3]);
        }

        return FALSE;
    }
}

if ( ! function_exists('inv_excel_validar_fecha'))
{
    function inv_excel_validar_fecha($anio, $mes, $dia)
    {
        if ( ! checkdate($mes, $dia, $anio)) return FALSE;

        return sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
    }
}

if ( ! function_exists('inv_excel_valor_contable'))
{
    /*
    | Convierte Valor Libro / Dep. Acum. Total a entero (la columna es
    | DECIMAL(14,0), sin decimales) o NULL si la celda viene vacia.
    | Acepta numero de Excel directo, o texto con espacios, simbolo $,
    | y separador de miles chileno (punto), con coma como decimal.
    |
    | Devuelve siempre un array:
    |   array('valor' => int|NULL, 'valido' => bool, 'negativo' => bool)
    | 'valido' = FALSE solo cuando habia texto pero no se pudo interpretar
    | como numero (en ese caso 'valor' queda en NULL).
    */
    function inv_excel_valor_contable($valor)
    {
        $resultado = array('valor' => NULL, 'valido' => TRUE, 'negativo' => FALSE);

        if ($valor === NULL) return $resultado;

        // OJO: el atajo de "ya viene numerico" solo debe tomarse cuando
        // PHP/PHPExcel entregaron un int o float NATIVO (una celda de
        // Excel realmente numerica). NO alcanza con is_numeric($valor):
        // is_numeric("22.995") tambien es TRUE en PHP (lo interpreta como
        // el decimal 22.995), lo que rompe por completo la lectura
        // chilena de esa misma cadena como "22.995" = veintidos mil
        // novecientos noventa y cinco. Por eso una cadena de texto SIEMPRE
        // pasa por el analisis de separador de miles/decimal de mas abajo,
        // nunca por este atajo, aunque "parezca" numerica.
        if (is_int($valor) OR is_float($valor))
        {
            $numero = (float) $valor;
            $resultado['valor'] = (int) round($numero);
            $resultado['negativo'] = ($numero < 0);

            return $resultado;
        }

        $texto = trim((string) $valor);
        if ($texto === '') return $resultado;

        $texto = str_replace(array(' ', '$'), '', $texto);

        // Separador de miles chileno (punto) seguido de grupos de 3
        // digitos: se elimina. La coma (decimal chileno) se convierte a
        // punto recien despues, para no confundirla con el punto de miles.
        $texto = preg_replace('/\.(?=\d{3}(\D|$))/', '', $texto);
        $texto = str_replace(',', '.', $texto);

        if ( ! is_numeric($texto))
        {
            $resultado['valido'] = FALSE;

            return $resultado;
        }

        $numero = (float) $texto;
        $resultado['valor'] = (int) round($numero);
        $resultado['negativo'] = ($numero < 0);

        return $resultado;
    }
}

if ( ! function_exists('inv_excel_interpretar_baja'))
{
    /*
    | Interpreta la columna "Baja Si/No" segun las reglas pedidas:
    | Si/SI/si/SÍ/Sí/1 -> dado_baja=1 ; No/NO/no/0/vacio -> dado_baja=0.
    | Cualquier otro texto (ej. "Revisar", visto en la planilla real)
    | tambien cae en dado_baja=0 (opcion segura, nunca se asume baja por
    | error), pero 'reconocido' queda en FALSE para que el importador
    | marque la fila con ADVERTENCIA en vez de darla por buena en silencio.
    */
    function inv_excel_interpretar_baja($valor)
    {
        $texto = trim((string) $valor);

        $variantes_si = array('Si', 'SI', 'si', 'sI', "S\xC3\x8D", "S\xC3\xAD", "s\xC3\xAD", "s\xC3\x8D", '1');
        $variantes_no = array('No', 'NO', 'no', 'nO', '0', '');

        if (in_array($texto, $variantes_si, TRUE))
        {
            return array('dado_baja' => 1, 'reconocido' => TRUE);
        }

        if (in_array($texto, $variantes_no, TRUE))
        {
            return array('dado_baja' => 0, 'reconocido' => TRUE);
        }

        return array('dado_baja' => 0, 'reconocido' => FALSE);
    }
}

/* End of file inv_excel_helper.php */
