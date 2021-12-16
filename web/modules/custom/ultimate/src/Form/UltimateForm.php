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
        'wrapper' => 'form_wrapper',
        'progress' => array('type' => 'none'),
      ],
    ];

    $form['addRow'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Row'),
      '#submit' => ['::addRow'],
      '#ajax' => [
        'wrapper' => 'form_wrapper',
        'progress' => [
          'type' => 'none',
        ],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
      '#submit' => ['::submitAjaxForm'],
      '#ajax' => [
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
   * @param array $table
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  protected function buildRow($table_key, array &$table, FormStateInterface $form_state): void {
    for ($i = $this->rows; $i > 0; $i--) {
      foreach ($this->titles as $key => $value) {
        $table[$i][$key] = [
          '#type' => 'number',
          '#step' => '0.01',
        ];
        if (array_key_exists($key, $this->intitles)) {
          $value = $form_state->getValue($table_key . '][' . $i . '][' . $key, 0);
          $table[$i][$key]['#disabled'] = TRUE;
          $table[$i][$key]['#default_value'] = round($value, 2);
        }
      }
      $table[$i]['year']['#default_value'] = date('Y') - $i + 1;
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

  public function submitAjaxForm(array $form, FormStateInterface $form_state): array {
    return $form;
  }
  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
