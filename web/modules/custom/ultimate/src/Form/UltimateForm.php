<?php

namespace Drupal\ultimate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UltimateForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'ultimate_table';
  }

  /**
   * Building header.
   *
   * @return string[]
   */
   public function buildTitles(): array {
     return [
       'year' => $this->t('Year'),
       'jan' => $this->t('Jan'),
       'feb' => $this->t('Feb'),
       'mar' => $this->t('Mar'),
       'q1' => $this->t('Q1'),
       'apr' => $this->t('Apr'),
       'may' => $this->t('May'),
       'jun' => $this->t('Jun'),
       'q2' => $this->t('Q2'),
       'jul' => $this->t('Jul'),
       'aug' => $this->t('Aug'),
       'sep' => $this->t('Sep'),
       'q3' => $this->t('Q3'),
       'oct' => $this->t('Oct'),
       'nov' => $this->t('Nov'),
       'dec' => $this->t('Dec'),
       'q4' => $this->t('Q4'),
       'ytd' => $this->t('YTD'),
     ];
   }

  /**
   * Returning values of inactive cells.
   *
   * @return string[]
   */
   public function inactiveCells(): array {
     return [
       'q1' => '',
       'q2' => '',
       'q3' => '',
       'q4' => '',
       'year' => '',
       'ytd' => '',
     ];
   }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#prefix'] = '<div id = "form_wrapper">';
    $form['#suffix'] = '</div>';
    $form['#attached'] = ['library' => ['ultimate/ultimate_library']];
    //$form['#add_year'] = [
    // '#type' => 'submit',
    //  '#value' => 'Add Year',
    //  '#'
    //];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
