<?php

namespace Drupal\ultimate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UltimateForm extends FormBase {

  /**
   * Titles of the header.
   *
   * @var
   */
  protected $titles;

  /**
   * Titles of the header.
   *
   * @var
   */
  protected $intitles;

  /**
   * Amount of tables to be built.
   *
   * @var
   */
  protected $tables = 1;

  /**
   * Amount of rows to be built for each table.
   *
   * @var
   */
  protected $rows = 1;

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'ultimate_table';
  }

  /**
   * Building header.
   *
   * @return void
   */
   public function buildTitles(): void {
     $this->titles = [
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
   * @return void
   */
   public function inactiveCells(): void {
     $this->intitles = [
       'q1' => $this->t('Q1'),
       'q2' => $this->t('Q2'),
       'q3' => $this->t('Q3'),
       'q4' => $this->t('Q4'),
       'year' => $this->t('Year'),
       'ytd' => $this->t('YTD'),
     ];
   }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#prefix'] = '<div id = "form_wrapper">';
    $form['#suffix'] = '</div>';
    $form['#attached'] = ['library' => ['ultimate/ultimate_library']];
    $this->inactiveCells();
    $this->buildTitles();
    $this->buildTables($form, $form_state);
    //  $form['#add_year'] = [
    // '#type' => 'submit',
    //  '#value' => 'Add Year',
    //  '#'
    //  ];
    $form['table'] = [
      '#type' => 'table',
    ];
    return $form;
  }

  protected function buildTables(array &$form, FormStateInterface $form_state) {
    for ($i = 0; $i < $this->tables; $i++) {
      $tableKey = 'table-' . ($i + 1);
      $form[$tableKey] = [
        '#type' => 'table',
        '#tree' => TRUE,
        '#header' => $this->titles,
      ];
      $this->buildRows($tableKey, $form[$tableKey], $form_state);
    }
  }

  /**
   * Build rows.
   */
  protected function buildRows(string $tableKey, array &$table, FormStateInterface $form_state) {
    for ($i = $this->rows; $i > 0; $i--) {
      foreach ($this->titles as $key => $value) {
        $table[$i][$key] = [
          '#type' => 'number',
          '#step' => '0.01',
        ];
        // Some additions to fields that should be calculated on the server.
        if (array_key_exists($key, $this->intitles)) {
          // Set default value linked to form_state,
          // so we can change displayed value for user.
          $value = $form_state->getValue($tableKey . '][' . $i . '][' . $key, 0);
          $table[$i][$key]['#disabled'] = TRUE;
          $table[$i][$key]['#default_value'] = round($value, 2);
        }
      }
      $table[$i]['year']['#default_value'] = date('Y') - $i + 1;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
