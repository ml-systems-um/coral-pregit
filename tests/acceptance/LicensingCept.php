<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can create/delete a license and see it in the list');

// License creation
$I->amOnPage("/licensing/");
$I->click("New License");
$I->fillField("#licenseShortName", "Test License");
$I->fillField("#organizationName", "Test Organization");
$I->click(".submit-button");
$I->waitForText("License Added Successfully.", 5);
$I->click("Continue");
// we are redirected to the test license's page
$I->waitForText("Edit License", 5); // ensure that we are on the test license's page

// Find license in list an go to it's page
$I->amOnPage("/licensing/");
$I->waitForPageToBeReady(); // Ensure that the list has loaded by Ajax
$I->click("Test License");

// Delete license
$I->willAcceptTheNextConfirmBox();
$I->click("Remove License");
$I->waitForText("records per page"); // Ensure that the list has loaded by Ajax.
// So the next check can't do a false positive
$I->dontSee("Test License");
