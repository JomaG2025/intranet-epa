<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Proteccion CSRF propia del modulo Inventario
| -------------------------------------------------------------------
| La proteccion CSRF global de la intranet esta deshabilitada
| ($config['csrf_protection'] = FALSE en application/config/config.php)
| y esta entrega tiene instruccion explicita de NO cambiar esa
| configuracion global. Este helper implementa una proteccion CSRF
| propia, usada solamente por los formularios del modulo Inventario.
|
| Funcionamiento:
|   - El token se genera una vez por sesion y se guarda en
|     $this->session->userdata('inv_csrf_token').
|   - inv_csrf_campo() imprime un input oculto con ese token en cada
|     formulario POST del modulo.
|   - inv_csrf_validar($token) se llama al inicio de todo metodo que
|     modifica datos; compara con hash_equals() (disponible nativo
|     desde PHP 5.6.0, comparacion en tiempo constante) y SIEMPRE
|     renueva el token despues de validar (haya sido valido o no),
|     para que un token ya usado no sirva una segunda vez.
|
| Generacion de bytes aleatorios (revision 3): en ESTE orden, cada uno
| solo si el anterior no esta disponible o no es confiable:
|   1. openssl_random_pseudo_bytes(), exigiendo ademas que el propio
|      PHP confirme que la fuente fue criptograficamente fuerte
|      (parametro $crypto_strong por referencia).
|   2. mcrypt_create_iv() con MCRYPT_DEV_URANDOM.
|   3. Lectura directa de /dev/urandom, si existe y es legible.
|   4. Si ninguna de las tres esta disponible, NO se genera un token
|      debil (ya no se usa mt_rand() como respaldo). Se devuelve FALSE
|      y todo el modulo lo trata como "no hay proteccion CSRF posible
|      en este servidor ahora mismo": el formulario muestra un aviso
|      explicito y cualquier validacion se rechaza (falla cerrado, no
|      abierto).
|
| Todo compatible con PHP 5.6 / CodeIgniter 2: no usa random_bytes()
| ni random_int() (son de PHP 7).
| -------------------------------------------------------------------
*/

if ( ! function_exists('inv_csrf_random_bytes'))
{
    function inv_csrf_random_bytes($longitud)
    {
        // 1) OpenSSL, solo si PHP confirma que la fuente es fuerte.
        if (function_exists('openssl_random_pseudo_bytes'))
        {
            $fuerte = NULL;
            $bytes = openssl_random_pseudo_bytes($longitud, $fuerte);

            if (($bytes !== FALSE) AND (strlen($bytes) === $longitud) AND ($fuerte === TRUE))
            {
                return $bytes;
            }
        }

        // 2) mcrypt con /dev/urandom explicito.
        if (function_exists('mcrypt_create_iv') AND defined('MCRYPT_DEV_URANDOM'))
        {
            $bytes = @mcrypt_create_iv($longitud, MCRYPT_DEV_URANDOM);

            if (($bytes !== FALSE) AND (strlen($bytes) === $longitud))
            {
                return $bytes;
            }
        }

        // 3) Lectura directa de /dev/urandom (Linux/Unix, que es el
        // sistema operativo real del servidor de la intranet).
        if (@is_readable('/dev/urandom'))
        {
            $identificador = @fopen('/dev/urandom', 'rb');

            if ($identificador !== FALSE)
            {
                $bytes = @fread($identificador, $longitud);
                @fclose($identificador);

                if (($bytes !== FALSE) AND (strlen($bytes) === $longitud))
                {
                    return $bytes;
                }
            }
        }

        // 4) Ninguna fuente segura disponible: NO se arma un token con
        // mt_rand() ni ningun otro generador no criptografico. Se
        // devuelve FALSE explicitamente para que el resto del helper
        // falle cerrado (ver inv_csrf_generar()/inv_csrf_validar()).
        return FALSE;
    }
}

if ( ! function_exists('inv_csrf_generar'))
{
    function inv_csrf_generar()
    {
        $CI =& get_instance();
        $bytes = inv_csrf_random_bytes(20);

        if ($bytes === FALSE)
        {
            // Sin fuente segura de aleatoriedad: se borra cualquier
            // token previo (si quedara uno de una fuente que dejo de
            // estar disponible) para que una validacion posterior no
            // pueda compararse contra un token viejo.
            $CI->session->unset_userdata('inv_csrf_token');

            return FALSE;
        }

        $token = bin2hex($bytes);
        $CI->session->set_userdata('inv_csrf_token', $token);

        return $token;
    }
}

if ( ! function_exists('inv_csrf_token'))
{
    function inv_csrf_token()
    {
        $CI =& get_instance();
        $token = $CI->session->userdata('inv_csrf_token');

        if ( ! $token)
        {
            $token = inv_csrf_generar();
        }

        // Puede devolver FALSE si no hay fuente segura de aleatoriedad
        // disponible en este servidor ahora mismo.
        return $token;
    }
}

if ( ! function_exists('inv_csrf_campo'))
{
    function inv_csrf_campo()
    {
        $token = inv_csrf_token();

        if ($token === FALSE)
        {
            // Mensaje claro dentro del propio formulario en vez de un
            // campo silenciosamente inseguro. El input oculto igual se
            // imprime (vacio) para que inv_csrf_validar() lo rechace de
            // forma consistente con cualquier otro token invalido.
            return '<div class="alert alert-danger"><strong>Atencion!</strong> '
                . 'No fue posible generar una proteccion de seguridad para '
                . 'este formulario (no hay una fuente de aleatoriedad segura '
                . 'disponible en el servidor). Contacta al administrador del '
                . 'sistema antes de continuar.</div>'
                . '<input type="hidden" name="inv_csrf_token" value="">';
        }

        return '<input type="hidden" name="inv_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

if ( ! function_exists('inv_csrf_validar'))
{
    function inv_csrf_validar($token_recibido)
    {
        $CI =& get_instance();
        $token_sesion = $CI->session->userdata('inv_csrf_token');

        // Sin token de sesion no hay nada valido contra que comparar
        // (incluye el caso "no hay fuente segura de aleatoriedad
        // disponible"): se rechaza siempre, de forma explicita.
        if ( ! $token_sesion)
        {
            return FALSE;
        }

        $valido = ($token_recibido AND is_string($token_recibido) AND hash_equals($token_sesion, $token_recibido));

        // Se renueva siempre, sea valido o no, para que el token quede
        // consumido despues de este intento. Si en este momento ya no
        // hay fuente segura disponible, inv_csrf_generar() limpia el
        // token de sesion, por lo que el PROXIMO intento tambien se
        // rechaza de forma segura por defecto (fail closed).
        inv_csrf_generar();

        return (bool) $valido;
    }
}

/* End of file inv_csrf_helper.php */
