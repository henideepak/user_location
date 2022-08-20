<?php

namespace Drupal\user_location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AdminconfigrationForm.
 */
class AdminconfigrationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'user_location.adminconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('user_location.adminconfig');
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Add your Country name.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('country') ?? '',
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#description' => $this->t('Add you City name.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('city') ?? '',
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#description' => $this->t('Add you timezone.'),
      '#default_value' => $config->get('timezone') ?? '',
      '#empty_option' => $this->t('- Select -'),
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),
      ]
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // get sanitized form values.
    $values = $form_state->getValues();

    $fields = ['country', 'city'];
    foreach ($fields as $key => $field) {
      if ( preg_match('/[^A-Za-z\s]/', $values[$field] )) {
        $form_state->setErrorByName($field, $this->t('@field accepted character.', ['@field' => ucwords($field)]));
      }
    }
    
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('user_location.adminconfig')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
  }

}
