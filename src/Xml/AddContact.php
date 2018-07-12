<?php

namespace Upchuk\WatsonCampaignApi\Xml;

use Upchuk\WatsonCampaignApi\Contact;

/**
 * Builds the AddContact request body
 */
class AddContact {

  /**
   * @var \Upchuk\WatsonCampaignApi\Contact
   */
  private $contact;

  /**
   * The ID of the database
   *
   * @var string
   */
  private $database;

  /**
   * @var bool
   */
  private $update;

  /**
   * AddContact constructor.
   *
   * @param \Upchuk\WatsonCampaignApi\Contact $contact
   * @param $database
   * @param bool $update
   *   Whether to update if contact is found.
   */
  public function __construct(Contact $contact, $database, $update = TRUE) {
    $this->contact = $contact;
    $this->database = $database;
    $this->update = $update;
  }

  /**
   * @return string
   */
  public function build() {
    $db = (string) $this->database;
    $update = $this->update ? 'true' : 'false';
    $email = $this->contact->getEmail();
    $preferences = $this->buildColumns();
    return "
      <AddRecipient>
        <LIST_ID>$db</LIST_ID>
        <CREATED_FROM>2</CREATED_FROM>
        <UPDATE_IF_FOUND>$update</UPDATE_IF_FOUND>
        <COLUMN>
          <NAME>EMAIL</NAME>
          <VALUE>$email</VALUE>
			  </COLUMN>
        $preferences
		</AddRecipient>
    ";
  }

  /**
   * Builds the field columns
   */
  private function buildColumns() {
    $preferences = $this->contact->getPreferences();
    if (!$preferences) {
      return '';
    }

    $output = '';
    foreach ($preferences as $name => $value) {
      $output .= "
        <COLUMN>
          <NAME>$name</NAME>
          <VALUE>$value</VALUE>
			  </COLUMN>
      ";
    }

    return $output;
  }
}