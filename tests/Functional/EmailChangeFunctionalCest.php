<?php

declare(strict_types=1);

namespace Forge\User\Tests\Functional;

use Forge\User\Tests\Support\FunctionalTester;

final class EmailChangeFunctionalCest
{
    public function testEmailChangeValidateEmailExist(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings email change strategy insecure.');
        $I->updateInDatabase('settings', ['emailChangeStrategy' => 0], ['identity_id' => 1]);

        $I->amGoingTo('load fixture user confirmed.');
        $this->userConfirmed($I);

        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->amGoingTo('log in user alex.');
        $I->submitForm('#form-auth-login', [
            'LoginForm[login]' => 'alex@example.com',
            'LoginForm[password]' => '123456',
        ]);

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the email change page.');
        $I->amOnPage('/email/change');
        $I->see('Change email');

        $I->amGoingTo('change email register user.');
        $I->submitForm('#form-email-change', [
            'EmailChangeForm[email]' => 'administrator@example.com',
        ]);

        $I->expectTo('see email error validation.');
        $I->see('Email already exists.');

        $I->amGoingTo('update settings email change strategy default');
        $I->updateInDatabase('settings', ['emailChangeStrategy' => 1], ['identity_id' => 1]);
    }

    public function testEmailChangeStrategyEmptyValues(FunctionalTester $I): void
    {
        $I->amGoingTo('email change strategy empty.');
        $I->amOnPage('/email/attempt');

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testEmailChangeStrategyWrongToken(FunctionalTester $I): void
    {
        $I->amGoingTo('email change strategy empty.');
        $I->amOnPage('/email/attempt/1/NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6');

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testEmailChangeStrategyInsecure(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings email change strategy insecure.');
        $I->updateInDatabase('settings', ['emailChangeStrategy' => 0], ['id' => 1]);

        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->amGoingTo('log in user admin.');
        $I->submitForm('#form-auth-login', [
            'LoginForm[login]' => 'admin',
            'LoginForm[password]' => '123456',
        ]);

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the email change page.');
        $I->amOnPage('/email/change');
        $I->see('Change email');

        $I->amGoingTo('change email register user.');
        $I->submitForm('#form-email-change', [
            'EmailChangeForm[email]' => 'administrator100@example.com',
        ]);

        $I->expectTo('see email change strategy insecure.');
        $I->seeInField('EmailChangeForm[email]', 'administrator100@example.com');

        $I->amGoingTo('update settings email change strategy default');
        $I->updateInDatabase('settings', ['emailChangeStrategy' => 1], ['id' => 1]);
    }

    public function testEmailChangeStrategyDefault(FunctionalTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->amGoingTo('log in user admin.');
        $I->submitForm('#form-auth-login', [
            'LoginForm[login]' => 'admin',
            'LoginForm[password]' => '123456',
        ]);

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the email change page.');
        $I->amOnPage('/email/change');
        $I->see('Change email');

        $I->amGoingTo('change email register user.');
        $I->submitForm('#form-email-change', [
            'EmailChangeForm[email]' => 'administrator@example.com',
        ]);

        $I->expectTo('see email change strategy default.');
        $I->seeInField('EmailChangeForm[email]', 'administrator@example.com');

        $I->expectTo('see email change strategy default.');
        $emailUnconfirmed = $I->grabColumnFromDatabase('account', 'unconfirmed_email', ['identity_id' => 1]);

        $token = $I->grabColumnFromDatabase('token', 'code', ['identity_id' => 1, 'type' => 2]);

        $I->expectTo('find the unconfirmed email registered in the database.');
        $I->assertSame('administrator@example.com', $emailUnconfirmed[0]);

        $I->expectTo('confirm user by token.');
        $I->amOnPage('/email/attempt/1/' . $token[0]);

        $I->expectTo('go to email change page.');
        $I->see('Email changed successfully.');
        $I->seeInField('EmailChangeForm[email]', 'administrator@example.com');

        $emailUnconfirmed = $I->grabColumnFromDatabase('account', 'unconfirmed_email', ['identity_id' => 1]);

        $I->expectTo('unconfirmed mail empty string in database.');
        $I->assertEmpty($emailUnconfirmed[0]);
    }

    public function testEmailChangeStrategySecure(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings email change strategy secure.');
        $I->updateInDatabase('settings', ['emailChangeStrategy' => 2], ['id' => 1]);

        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->amGoingTo('log in user admin.');
        $I->submitForm('#form-auth-login', [
            'LoginForm[login]' => 'admin',
            'LoginForm[password]' => '123456',
        ]);

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the email change page.');
        $I->amOnPage('/email/change');
        $I->see('Change email');

        $I->amGoingTo('change email register user.');
        $I->submitForm('#form-email-change', [
            'EmailChangeForm[email]' => 'administrator100@example.com',
        ]);

        $I->expectTo('see email change strategy secure.');
        $I->seeInField('EmailChangeForm[email]', 'administrator100@example.com');
        $I->see('A confirmation message has been sent to your new email address');

        $I->expectTo('see email change strategy secure first confirmation email.');
        $emailUnconfirmed = $I->grabColumnFromDatabase('account', 'unconfirmed_email', ['identity_id' => 1]);
        $token = $I->grabColumnFromDatabase('token', 'code', ['identity_id' => 1, 'type' => 2]);

        $I->expectTo('find the unconfirmed email registered in the database.');
        $I->assertSame('administrator100@example.com', $emailUnconfirmed[0]);

        $I->expectTo('confirm user by token mail administrator100@example.com.');
        $I->amOnPage('/email/attempt/1/' . $token[0]);

        $I->expectTo('go to email change page.');
        $I->see('Change email');
        $I->see('email not confirmed.');
        $I->seeInField('EmailChangeForm[email]', 'administrator100@example.com');

        $I->expectTo('see email change strategy secure second confirmation email.');
        $token = $I->grabColumnFromDatabase('token', 'code', ['identity_id' => 1, 'type' => 3]);

        $I->expectTo('confirm user by token mail administrator@example.com.');
        $I->amOnPage('/email/attempt/1/' . $token[0]);

        $I->expectTo('go to email change page.');
        $I->see('Email changed successfully.');
        $I->see('Change email');
        $I->seeInField('EmailChangeForm[email]', 'administrator100@example.com');

        $emailUnconfirmed = $I->grabColumnFromDatabase('account', 'unconfirmed_email', ['identity_id' => 1]);

        $I->expectTo('unconfirmed mail empty string in database.');
        $I->assertEmpty($emailUnconfirmed[0]);

        $I->amGoingTo('update settings email change strategy default.');
        $I->updateInDatabase('settings', ['emailChangeStrategy' => 1], ['id' => 1]);
    }

    private function userConfirmed(FunctionalTester $I): void
    {
        $time = time();

        $I->haveInDatabase(
            'identity',
            [
                'id' => 100,
                'auth_key' => 'mhh1A6KfqQLmHP-MiWN0WB0M90Q2u5I',
            ]
        );

        $I->haveInDatabase(
            'account',
            [
                'identity_id' => 100,
                'username' => 'alex',
                'email' => 'alex@example.com',
                'password_hash' => '$argon2i$v=19$m=65536,t=4,p=1$ZVlUZk1NS2wwdi45d0t6dw$pn/0BLB3EzYtNdm3NSj6Yntk9lUT1pEOFsd85xV3Ig4',
                'created_at' => $time,
                'updated_at' => $time,
                'confirmed_at' => $time,
                'blocked_at' => 0,
            ]
        );

        $I->haveInDatabase(
            'token',
            [
                'identity_id' => 100,
                'code' => '6f5d0dad53ef73e6ba6f01a441c0e602',
                'type' => 1,
                'created_at' => $time,
            ]
        );
    }
}
