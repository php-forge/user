<?php

declare(strict_types=1);

namespace Forge\User\Tests\Functional;

use Forge\User\Tests\Support\FunctionalTester;

final class RegisterFunctionalCest
{
    public function testRegisterSettingsRegisterFalse(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings register false.');
        $I->updateInDatabase('settings', ['register' => false], ['id' => 1]);

        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->expectTo('see register success message.');
        $I->see('We were unable to find the page "/en/register".');

        $I->amGoingTo('update settings register true.');
        $I->updateInDatabase('settings', ['register' => true], ['id' => 1]);
    }

    public function testRegisterSuccessDataDefaultAccountSettingsConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');
        $I->submitForm('#form-registration-register', [
            'RegisterForm[email]' => 'administrator1@example.com',
            'RegisterForm[username]' => 'admin1',
            'RegisterForm[password]' => '123456',
            'RegisterForm[passwordVerify]' => '123456',
        ]);

        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/login');

        $I->expectTo('see register validation.');
        $I->submitForm('#form-auth-login', [
            'LoginForm[login]' => 'admin1',
            'LoginForm[password]' => '123456',
        ]);

        $I->expectTo('see register success message.');
        $I->see('Account is not confirmed.');

        $I->amGoingTo('update settings confirmation false.');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }

    public function testRegisterSuccessDataGeratingPasswordTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings generating password true.');
        $I->updateInDatabase('settings', ['generatingPassword' => true], ['id' => 1]);

        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');
        $I->submitForm('#form-registration-register', [
            'RegisterForm[email]' => 'admin2@example.com',
            'RegisterForm[username]' => 'admin2',
        ]);

        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/login');

        $I->amGoingTo('update settings generating password false.');
        $I->updateInDatabase('settings', ['generatingPassword' => false], ['id' => 1]);
    }
}
