<?php

declare(strict_types=1);

namespace Forge\User\Tests\Acceptance;

use Forge\User\Tests\Support\AcceptanceTester;

final class RegisterAcceptanceCest
{
    public function testRegisterPage(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->expectTo('see register page.');
        $I->seeInTitle('Register');
    }

    public function testRegisterSuccessDataDefaultAccountConfirmationFalse(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'administrator@example.com');
        $I->fillField('#registerform-username', 'admin');
        $I->fillField('#registerform-password', '123456');
        $I->fillField('#registerform-passwordverify', '123456');

        $I->click('Send');

        $I->expectTo('see register success message.');
        $I->see('Your account is already confirmed.');
    }

    public function testRegisterEmptyData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('This field is required.');
        $I->see('This field is required.');
        $I->see('This field is required.');
        $I->see('This field is required.');
        $I->seeInField('register-button', 'Send');
    }

    public function testRegisterWrongEmailData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'register');
        $I->fillField('#registerform-username', 'register');
        $I->fillField('#registerform-password', '123456');
        $I->fillField('#registerform-passwordverify', '123456');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('This value is not a valid email address');
        $I->seeInField('register-button', 'Send');
    }

    public function testRegisterEmailExistData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'administrator@example.com');
        $I->fillField('#registerform-username', 'administrator');
        $I->fillField('#registerform-password', '123456');
        $I->fillField('#registerform-passwordverify', '123456');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('Email already exists.');
        $I->seeInField('register-button', 'Send');
    }

    public function testRegisterInvalidUsernameData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'demo@example.com');
        $I->fillField('#registerform-username', '**admin');
        $I->fillField('#registerform-password', '123456');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('Value is invalid');

        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'demo@example.com');
        $I->fillField('#registerform-username', '**');
        $I->fillField('#registerform-password', '123456');
        $I->fillField('#registerform-passwordverify', '123456');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('This value should contain at least 3 characters or more, currently has 2');
        $I->seeInField('register-button', 'Send');
    }

    public function testRegisterUsernameExistData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'demo@example.com');
        $I->fillField('#registerform-username', 'admin');
        $I->fillField('#registerform-password', '123456');
        $I->fillField('#registerform-passwordverify', '123456');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('Username already exists.');
        $I->seeInField('register-button', 'Send');
    }

    public function testRegisterInvalidPasswordData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'demo@example.com');
        $I->fillField('#registerform-username', 'demo');
        $I->fillField('#registerform-password', '123');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('This value should contain at least 6 characters or more, currently has 3');
        $I->seeInField('register-button', 'Send');
    }

    public function testRegisterPasswordNotMatch(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->fillField('#registerform-email', 'demo@example.com');
        $I->fillField('#registerform-username', 'demo');
        $I->fillField('#registerform-password', '123456');
        $I->fillField('#registerform-passwordverify', '1234');

        $I->click('Send');

        $I->expectTo('see registration register validation.');
        $I->see('Passwords do not match');
        $I->seeInField('register-button', 'Send');
    }
}
