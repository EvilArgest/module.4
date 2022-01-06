<?php

namespace Drupal\ultimate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UltimateForm extends FormBase {

  /**
   * Titles of the header.
   *
   * @var array
   */
  protected $titles;

  /**
   * Titles of the header.
   *
   * @var array
   */
  protected $intitles;

  /**
   * Amount of tables to be built.
   *
   * @var int
   */
  protected $tables = 1;

  /**
   * Amount of rows to be built for each table.
   *
   * @var int
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
   protected function buildTitles(): void {
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
   protected function inactiveCells(): void {
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
    $this->buildTable($form, $form_state);

    $form['addTable'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::addTable'],
      '#ajax' => [
        'callback' => '::submitAjaxForm',
        'wrapper' => 'form_wrapper',
        'progress' => [
          'type' => 'none'
        ],
      ],
    ];

    $form['addRow'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Row'),
      '#submit' => ['::addRow'],
      '#ajax' => [
        'callback' => '::submitAjaxForm',
        'wrapper' => 'form_wrapper',
        'progress' => [
          'type' => 'none',
        ],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::submitAjaxForm',
        'wrapper' => 'form_wrapper',
        'progress' => [
          'type' => 'none',
        ]
      ]
    ];
    return $form;
  }

  /**
   * Function adds a new table.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  protected function buildTable(array &$form, FormStateInterface $form_state): void {
    for ($i = 0; $i < $this->tables; $i++) {
      $table_key = $i;
      $form[$table_key] = [
        '#type' => 'table',
        '#header' => $this->titles,
        '#tree' => TRUE,
      ];
      $this->buildRow($table_key, $form[$table_key], $form_state);
    }
  }

  /**
   * Function adds rows to the existing table.
   *
   * @param $table_key
   * @param array $tablecell
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  protected function buildRow($table_key, array &$tablecell, FormStateInterface $form_state): void {
    for ($i = $this->rows; $i > 0; $i--) {
      foreach ($this->titles as $key => $value) {
        $tablecell[$i][$key] = [
          '#type' => 'number',
          '#step' => '0.01',
        ];
        if (array_key_exists($key, $this->intitles)) {
          $value = $form_state->getValue($table_key . '][' . $i . '][' . $key, 0);
          $tablecell[$i][$key]['#default_value'] = round($value, 2);
          $tablecell[$i][$key]['#disabled'] = TRUE;
        }
      }
      $tablecell[$i]['year']['#default_value'] = date('Y') - $i + 1;
    }
  }

  /**
   * Function which adds a new row to the table by incrementing rows.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function addRow(array $form, FormStateInterface $form_state): array {
    $this->rows++;
    $form_state->setRebuild();
    return $form;
  }

  /**
   * Function which adds a new table by incrementing tables.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function addTable(array $form, FormStateInterface $form_state): array {
    $this->tables++;
    $form_state->setRebuild();
    return $form;
  }

  public function babababa($array): array {
    $values  = [];
    $inactive_cells = $this->intitles;
    for($i = $this->rows; $i > 0; $i--) {
      foreach ($array[$i] as $key => $value) {
        if (!array_key_exists($key, $inactive_cells)) {
          $values[] = $value;
        }
      }
    }
    return $values;
  }

  public function submitAjaxForm(array $form, FormStateInterface $form_state): array {
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $table_values = $form_state->getValues();
    $start_point = NULL;
    $end_point = NULL;
    $active_values = [];
    for ($i = 0; $i < $this->tables; $i++) {
      $values = $this->babababa($table_values[$i]);
      $active_values[] = $values;
      foreach ($values as $key => $value) {
        for ($j = 0; $j < 12; $j++) {
          if (empty($active_values[0][$j]) !== empty($active_values[$i][$j])) {
            $form_state->setErrorByName($i, 'Tables are not the same...');
          }
        }
        if (!empty($value)) {
          $start_point = $key;
          break;
        }
      }
      if ($start_point !== NULL) {
        for ($l = $start_point; $l < count($values) + 1; $l++) {
          if (empty($values[$l])) {
            $end_point = $l;
            break;
          }
        }
      }
      if ($end_point !== NULL) {
        for ($f = $end_point; $f < count($values) + 1; $f++) {
          if (!empty($values[$f])) {
            $form_state->setErrorByName($f, 'Form is not valid');
          }
        }
      }
    }
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $table_values = $form_state->getValues();
    $this->messenger()->addStatus("The form is valid");
    $form_state->setRebuild();
  }

}
