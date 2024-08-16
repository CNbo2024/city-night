<?php

return [

    /**
     *
     * Welcome page.
     *
     */
    'welcome'         => [
        'name'                      => 'migrateshop',
        'version'                   => 'V 3.1',
        'title'                     => 'Bienvenido al instalador!',
        'message'                   => 'Asistente de fácil instalación y configuración',
        'requirementcheckingbutton' => 'Empezar con requisitos del servidor',
        'serverrequirements'        => 'Requisitos del servidor',
        'back_button'               => 'Atrás',
        'check_permission'          => 'Verificar permisos',
        'serverpermissions'         => 'Permisos de carpetas',
        'check_perchase_code'       => 'Verificar código de compra de Envato',
        'verify_code_title'         => 'Verificar código de compra de Envato',
        'envato_label_text'         => 'Código de compra de Envato',
        'envato_placeholder_text'   => 'Ingrese código de compra',
        'verify_button'             => 'Verificar código de compra',
        'current_text'              => 'Actual',
        'version_text'              => 'version',
        'required_text'             => 'requerido',
        'cancel'                    => 'Cancelar',
    ],

    /**
     *
     * Database page.
     *
     */
    'database'        => [
        'title'          => 'Configuración de base de datos',
        'sub-title'      => 'Si no sabes cómo llenar este formulario contacta a tu hosting',
        'dbname-label'   => 'Nombre de la base de datos (donde desea que esté su aplicación)',
        'username-label' => 'Nombre de usuario (su inicio de sesión en la base de datos)',
        'password-label' => 'Contraseña (La contraseña de su base de datos)',
        'host-label'     => 'Nombre del host (debe ser "localhost", si no funciona, pregúntale a tu proveedor de alojamiento)',
        'wait'           => 'Por favor, espera un momento...',
        'dbbutton'       => 'Crear base de datos',
    ],

    /**
     *
     * Database error page.
     *
     */
    'database-error'  => [
        'title'     => 'Error en conexión con base de datos',
        'sub-title' => 'No podemos conectarnos a la base de datos con su configuración:',
        'item1'     => '¿Estás segura de tu nombre de usuario y contraseña?',
        'item2'     => '¿Estás segura de tu nombre de host?',
        'item3'     => '¿Está seguro de que su servidor de base de datos está funcionando?',
        'message'   => 'Si no está muy seguro de comprender todos estos términos, debe comunicarse con su proveedor de alojamiento.',
        'button'    => 'Intentar otra vez !',
    ],

    /**
     *
     * Register page.
     *
     */
    'register'        => [
        'title'              => 'Creación de administrador',
        'sub-title'          => 'Ahora debes ingresar información para crear administrador.',
        'base-label'         => 'Su ',
        'message'            => 'Necesitará su contraseña para iniciar sesión, ¡así que manténgala segura!',
        'create_user_button' => 'Crear usuario',
    ],

    /**
     *
     * Register fields for labels.
     *
     */
    'register-fields' => [
        'first_name' => 'Nombre',
        'last_name'  => 'Apellido',
        'username' => 'Usuario',
        'email'      => 'correo electrónico',
        'password'   => 'contraseña',
    ],

    /**
     *
     * End page.
     *
     */
    'end'             => [
        'title'     => 'Su aplicación ha sido instalada exitosamente!',
        'sub-title' => 'La aplicación ya está instalada y puedes usarla.',
        'login'     => 'Su nombre de usuario : ',
        'password'  => 'Su contraseña :',
        'button'    => 'Iniciar sesión',
    ],

];
