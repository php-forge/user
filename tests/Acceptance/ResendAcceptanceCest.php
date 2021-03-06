<?php

declare(strict_types=1);

namespace Forge\User\Tests\Acceptance;

use Forge\User\Tests\Support\AcceptanceTester;

final class ResendAcceptanceCest
{
    public function testResendDefaultAccountConfirmationFalse(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the resend page.');
        $I->amOnPage('/resend');

        $I->see('We were unable to find the page "/en/resend".');
    }
}
