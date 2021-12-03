<?php

namespace Drupal\ultimate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UltimateForm extends FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId(): string {
    return 'ultimate_table';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}
