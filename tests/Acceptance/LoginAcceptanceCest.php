<?php

declare(strict_types=1);

namespace Forge\User\Tests\Acceptance;

use Forge\User\Tests\Support\AcceptanceTester;

final class LoginAcceptanceCest
{
    public function testLoginPage(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->expectTo('see log in page.');
        $I->seeInTitle('Login');
    }

    public function testLoginEmptyDataTest(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->click('Send');

        $I->expectTo('see validations errors.');
        $I->see('This field is required.');
        $I->see('This field is required.');
        $I->seeInField('login-button', 'Send');
    }

    public function testLoginSubmitFormWrongDataUsername(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->fillField('#loginform-login', 'admin1');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see validations errors.');
        $I->see('Invalid credentials.');
        $I->seeInField('login-button', 'Send');
    }

    public function testLoginSubmitFormWrongDataPassword(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/login');

        $I->fillField('#loginform-login', 'admin');
        $I->fillField('#loginform-password', '1');

        $I->click('Send');

        $I->expectTo('see validations errors.');
        $I->see('Invalid credentials.');
        $I->seeInField('login-button', 'Send');
    }

    /**
     * @depends Forge\User\Tests\Acceptance\RegisterAcceptanceCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testLoginUsernameSubmitFormSuccessData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->fillField('#loginform-login', 'admin');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see login success message.');
        $I->see('Sign in successful - This is your first login - Welcome.');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->click('//*[@id="logout"]');

        $I->expectTo('no see link logout.');
        $I->dontSeeLink('logout');
    }

    /**
     * @depends Forge\User\Tests\Acceptance\RegisterAcceptanceCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testLoginEmailSubmitFormSuccessData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/login');

        $I->fillField('#loginform-login', 'administrator@example.com');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see login success message.');
        $I->see('Sign in successful');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->expectTo('go to the login page');
        $I->amOnPage('/login');

        $I->expectTo('dont see login page with user logging.');
        $I->dontSee('Login');

        $I->click('//*[@id="logout"]');

        $I->expectTo('no see link logout.');
        $I->dontSeeLink('logout');
    }

    public function testLoginSettingsPasswordRecoveryTrue(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->expectTo('see link recover password.');
        $I->see('Recover password');
    }
}
