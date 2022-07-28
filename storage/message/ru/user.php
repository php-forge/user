<?php

declare(strict_types=1);

return [
    // attempt-email-change-service
    'attempt.email.change.service.token_expired' => 'Срок действия токена истек.',
    'attempt.email.change.service.success' => 'Электронная почта успешно изменена.',

    // default-email-change-service
    'default.service.email.change.success' => 'На ваш новый адрес электронной почты {email} было отправлено подтверждающее сообщение.',

    // confirm
    'confirm.email.success' => 'Ваша учетная запись подтверждена.',

    // email-change
    'email.change.title' => 'Изменить электронную почту',

    // forms
    'form.attribute.bio' => 'Био',
    'form.attribute.email' => 'Электронная почта',
    'form.attribute.location' => 'Местонахождение',
    'form.attribute.name' => 'Имя',
    'form.attribute.old.email' => 'Старая электронная почта',
    'form.attribute.password.verify' => 'Проверка пароля',
    'form.attribute.password' => 'Пароль',
    'form.attribute.public.email' => 'Публичная электронная почта',
    'form.attribute.rememberMe' => 'Помни меня',
    'form.attribute.timezone' => 'Часовой пояс',
    'form.attribute.username' => 'Имя пользователя',
    'form.attribute.website' => 'Сайт',

    // insecure-email-change-service
    'insecure.email.change.service.success' => 'Ваш адрес электронной почты успешно изменен.',

    // login
    'login.button.submit' => 'Отправить',
    'login.confirmation.resend.link' => 'Повторная отправка подтверждения по электронной почте',
    'login.link' => 'Вход в систему',
    'login.recovery.password.link' => 'Восстановить пароль',
    'login.sign.in' => 'Вход успешный - {lastLogin}',
    'login.title' => 'Вход в систему',
    'login.welcome' => 'Это ваш первый вход в систему - добро пожаловать.',

    // Module
    'module.name' => 'Пользователь Module Forge для Yii3.',

    // mailer
    'mailer.email.change.instruction' => 'Чтобы подтвердить изменения, щелкните по ссылке ниже или вставьте ее в браузер.',
    'mailer.email.change.subject' => 'Изменить электронную почту',
    'mailer.email.change' => 'Ваш адрес электронной почты {email} в {moduleName} был изменен',
    'mailer.footer' => 'Если вы не можете перейти по ссылке, попробуйте вставить текст в браузер.',
    'mailer.header' => 'Здравствуйте,  {username},',
    'mailer.ignore' => 'Если вы не делали этого запроса, вы можете проигнорировать это письмо.',
    'mailer.recovery.instruction' => 'Чтобы сбросить пароль, нажмите на ссылку ниже или вставьте ее в браузер.',
    'mailer.recovery' => 'Мы получили запрос на сброс пароля для вашей учетной записи на {moduleName}.',
    'mailer.request' => 'Завершите сброс пароля на {moduleName}',
    'mailer.resend.instruction' => 'Чтобы подтвердить свою учетную запись, щелкните по ссылке ниже или вставьте ее в браузер.',
    'mailer.resend' => 'Подтвердите учетную запись на {moduleName}',
    'mailer.welcome.instruction' => 'Чтобы заполнить заявку, пожалуйста, перейдите по ссылке ниже:',
    'mailer.welcome.password' => 'Мы сгенерировали для вас пароль:',
    'mailer.welcome' => 'Ваша учетная запись на {moduleName} была создана.',

    // profile
    'profile.button.submit' => 'Отправить',
    'profile.title' => 'Профиль',
    'profile.updated' => 'Профиль обновлен.',

    // register
    'register.button.submit' => 'Отправить',
    'register.confirmed' => 'Ваша учетная запись уже подтверждена.',
    'register.link' => 'Зарегистрироваться',
    'register.title' => 'Зарегистрироваться',
    'register.unconfirmed' => 'Ваша учетная запись еще не подтверждена. Пожалуйста, проверьте свою электронную почту.',

    // request
    'request.button.submit' => 'Отправить',
    'request.sent' => 'Мы отправили вам электронное письмо с инструкциями по восстановлению пароля.',
    'request.title' => 'Сброс пароля',

    // resend
    'resend.button.submit' => 'Отправить',
    'resend.recovery.success' => 'На ваш адрес электронной почты {email} было отправлено подтверждающее сообщение с инструкциями по сбросу пароля.',
    'resend.title' => 'Повторная отправка подтверждения по электронной почте',

    // reset
    'reset.button.submit' => 'Отправить',
    'reset.recovery.success' => 'Ваш пароль был восстановлен.',
    'reset.title' => 'Сброс пароля',

    // secure-email-change-service
    'secure.email.change.service.success' => 'Мы отправили ссылки подтверждения на старый адрес электронной почты: {email} и новый адрес электронной почты: {newEmail}. Для заполнения заявления необходимо перейти по обеим ссылкам.',

    // validator
    'validator.account.blocked' => 'Счет заблокирован.',
    'validator.account.not.confirmed' => 'Аккаунт не подтвержден.',
    'validator.email.already.confirmed' => 'Электронная почта уже подтверждена.',
    'validator.email.already.exists' => 'Электронная почта уже существует.',
    'validator.email.not.confirmed' => 'Электронная почта не подтверждена.',
    'validator.email.not.found' => 'Электронная почта не найдена.',
    'validator.invalid.credentials' => 'Неверные учетные данные.',
    'validator.password.verify.match' => 'Пароли не совпадают.',
    'validator.username.already.exists' => 'Имя пользователя уже существует.',
];
