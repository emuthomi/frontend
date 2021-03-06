<?php

namespace www\tests\acceptance;

use www\tests\AcceptanceTester;

class ProblemsCest {
    public function checkPageProblems(AcceptanceTester $I) {
        $I->loginAsDemo();
        $I->amOnPage('/problems');

        $I->canSeeLink('Time Conversion', '/problem?problem_id=1');
    }


    public function checkSearchProblemByExistedId(AcceptanceTester $I) {
        $I->loginAsDemo();
        $I->amOnPage('/problems');

        $I->fillField('id', '1');
        $I->click('Search');
        $I->wait(3);
        $I->canSeeLink('Time Conversion', '/problem?problem_id=1');
    }


    public function checkSearchProblemByNoneExistedId(AcceptanceTester $I) {
        $I->loginAsDemo();
        $I->amOnPage('/problems');

        $I->fillField('id', 'a');
        $I->click('Search');
        $I->wait(3);
        $I->cantSeeLink('Time Conversion');
    }


    public function checkSearchProblemByExistedTitle(AcceptanceTester $I) {
        $I->loginAsDemo();
        $I->amOnPage('/problems');

        $I->fillField("title", 'Conversion');
        $I->click('Search');
        $I->wait(3);
        $I->canSeeLink('Time Conversion');
    }


    public function checkSearchProblemByNoneExistedTitle(AcceptanceTester $I) {
        $I->loginAsDemo();
        $I->amOnPage('/problems');

        $I->fillField("title", 'Guilden Morden boar');
        $I->click('Search');
        $I->wait(3);
        $I->cantSeeLink('Time Conversion');
    }
}