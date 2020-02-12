<?php

namespace Drupal\add_category\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class AddCategoryForm.
 */
class AddCategoryForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_category_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['category_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Category Name:'),
      '#required' => TRUE,
    );

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    $catName = $form_state->getValue('category_name'); 
    $uid = \Drupal::currentUser()->id();
    $term = Term::create([
      'name' => $catName, 
      'field_user_info' => $uid,
      'vid' => 'category',
    ])->save();
    \Drupal::messenger()->addMessage('Category Added');
    // foreach ($form_state->getValues() as $key => $value) {
    //   \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
    // }
  }

}
