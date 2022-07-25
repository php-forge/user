<?php

declare(strict_types=1);

return [
    // attempt-email-change-service
    'attempt.email.change.service.token_expired' => 'El código de confirmación ha expirado.',
    'attempt.email.change.service.success' => 'El correo electrónico ha sido cambiado.',

    // default-email-change-service
    'default.service.email.change.success' => 'Un mensaje de confirmación ha sido enviado a su nuevo correo electrónico {email}',

    // confirm
    'confirm.email.success' => 'Su cuenta ha sido confirmada.',

    // email-change
    'email.change.title' => 'Cambiar correo electrónico',

    // forms
    'form.attribute.bio' => 'Biografía',
    'form.attribute.email' => 'Correo electrónico',
    'form.attribute.location' => 'Ubicación',
    'form.attribute.name' => 'Nombre',
    'form.attribute.old.email' => 'Correo electrónico anterior',
    'form.attribute.password_verify' => 'Confirmar contraseña',
    'form.attribute.password' => 'Contraseña',
    'form.attribute.public.email' => 'Correo electrónico público',
    'form.attribute.rememberMe' => 'Recordarme',
    'form.attribute.timezone' => 'Zona horaria',
    'form.attribute.username' => 'Usuario',
    'form.attribute.website' => 'Sitio web',

    // insecure-email-change-service
    'insecure.email.change.service.success' => 'Su correo electrónico ha sido cambiado.',

    // login
    'login.button.submit' => 'Enviar',
    'login.confirmation.resend.link' => 'Reenviar correo de confirmación',
    'login.link' => 'Iniciar sesión',
    'login.recovery.password.link' => 'Recuperar contraseña',
    'login.register.link' => 'Registro',
    'login.sign.in' => 'Inicio de sesión exitoso - {lastLogin}',
    'login.title' => 'Iniciar sesión',
    'login.welcome' => 'Esta es su primera conexión - Bienvenido.',

    // mailer
    'mailer.email.change.instruction' => 'Para confirmar su cambio, por favor haga clic en el siguiente enlace:',
    'mailer.email.change.subject' => 'Cambio de correo electrónico en {moduleName}',
    'mailer.email.change' => 'Su correo electrónico {email} en {moduleName} ha sido cambiado.',
    'mailer.footer' => 'Si no puede hacer clic en el enlace, por favor copie el texto y péguelo en su navegador.',
    'mailer.header' => 'Hola, {username},',
    'mailer.ignore' => 'Si no ha solicitado este cambio, puede ignorar este mensaje.',
    'mailer.recovery.instruction' => 'Para completar su solicitud, por favor haga clic en el siguiente enlace:',
    'mailer.recovery' => 'Hemos recibido una solicitud para restablecer la contraseña de su cuenta en {moduleName}.',
    'mailer.request' => 'Su restablecimiento de contraseña {moduleName} ha sido enviada.',
    'mailer.resend.instruction' => 'Para confirmar su cuenta, por favor haga clic en el siguiente enlace:',
    'mailer.resend' => 'Confirmar cuenta en {moduleName}',
    'mailer.welcome.instruction' => 'Para completar su solicitud, por favor haga clic en el siguiente enlace:',
    'mailer.welcome.password' => 'Hemos generado una contraseña para usted:',
    'mailer.welcome' => 'Su cuenta en {moduleName} ha sido creada.',

    // profile
    'profile.button.submit' => 'Enviar',
    'profile.title' => 'Perfil',
    'profile.updated' => 'Su perfil ha sido actualizado.',

    // register
    'register.button.submit' => 'Enviar',
    'register.confirmed' => 'Su cuenta ya está confirmada.',
    'register.link' => 'Registro',
    'register.title' => 'Registro',
    'register.unconfirmed' => 'Su cuenta aún no ha sido confirmada. Por favor, compruebe su correo electrónico.',

    // request
    'request.button.submit' => 'Enviar',
    'request.sent' => 'Hemos enviado un correo electrónico con instrucciones para restablecer su contraseña.',
    'request.title' => 'Recuperar contraseña',

    // resend
    'resend.button.submit' => 'Enviar',
    'resend.recovery.success' => 'Hemos enviado un correo electrónico {email} con instrucciones para restablecer su contraseña.',
    'resend.title' => 'Reenviar correo de confirmación',

    // reset
    'reset.button.submit' => 'Enviar',
    'reset.recovery.success' => 'Su password ha sido cambiado.',
    'reset.title' => 'Restablecer contraseña',

    // secure-email-change-service
    'secure.email.change.service.success' => 'Hemos enviado enlaces de confirmación a las direcciones de correo electrónico anterior: {email} y correo electrónico nuevo: {newEmail}. Debe hacer clic en ambos enlaces para completar su solicitud.',

    // validator
    'validator.account.blocked' => 'La cuenta está bloqueada.',
    'validator.account.not.confirmed' => 'La cuenta no está confirmada.',
    'validator.email.already.confirmed' => 'El correo electrónico ya ha sido confirmado.',
    'validator.email.already.exists' => 'El correo electrónico ya existe.',
    'validator.email.not.confirmed' => 'El correo electrónico no ha sido confirmado.',
    'validator.email.not.found' => 'El correo electrónico no se encuentra registrado.',
    'validator.invalid.credentials' => 'Credenciales inválidas.',
    'validator.password.verify.match' => 'Las contraseñas no coinciden.',
    'validator.username.already.exists' => 'El nombre de usuario ya existe.',
];
