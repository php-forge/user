<?php

declare(strict_types=1);

namespace Forge\User\Tests\Acceptance;

use Forge\User\Tests\Support\AcceptanceTester;

final class ProfileAcceptanceCest
{
    public function testProfilePage(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/profile');

        $I->expectTo('no see profile page.');
        $I->dontSee('Profile');
    }

    /**
     * @depends Forge\User\Tests\Acceptance\RegisterAcceptanceCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testProfilePageSuccess(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the profile page.');
        $I->amOnPage('/profile');

        $I->amGoingTo('see log in page.');
        $I->see('Login');
        $I->fillField('#loginform-login', 'admin');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/profile');

        $I->expectTo('see profile page.');
        $I->seeInTitle('Profile');
    }

    /**
     * @depends Forge\User\Tests\Acceptance\RegisterAcceptanceCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testProfilePageUpdateData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the profile page.');
        $I->amOnPage('/profile');

        $I->amGoingTo('see log in page.');
        $I->see('Login');
        $I->fillField('#loginform-login', 'admin');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the login page.');
        $I->amOnPage('/profile');

        $I->expectTo('see profile page.');
        $I->seeInTitle('Profile');

        $I->fillField('#profileform-name', 'Joe Doe');
        $I->fillField('#profileform-publicemail', 'joedoe@example.com');
        $I->fillField('#profileform-website', 'http://example.com');
        $I->fillField('#profileform-location', 'Rusia');
        $I->selectOption('#profileform-timezone', 'Europe/Moscow (UTC +03:00)');
        $I->fillField('#profileform-bio', 'Developer Yii3.');

        $I->click('Send');

        $I->expectTo('see profile success message.');
        $I->see('Profile updated.');

        $I->expectTo('see save data.');
        $I->seeInField('ProfileForm[name]', 'Joe Doe');
        $I->seeInField('ProfileForm[publicEmail]', 'joedoe@example.com');
        $I->seeInField('ProfileForm[website]', 'http://example.com');
        $I->seeInField('ProfileForm[location]', 'Rusia');
        $I->seeInField('ProfileForm[timezone]', 'Europe/Moscow (UTC +03:00)');
        $I->seeInField('ProfileForm[bio]', 'Developer Yii3.');
    }
}
