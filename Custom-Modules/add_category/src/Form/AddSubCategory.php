<?php

namespace Drupal\add_category\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Class AddSubCategory.
 */
class AddSubCategory extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_sub_category';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    //Get current logged in user
    $uid = \Drupal::currentUser()->id();
    // Create an object of type Select.
    $database = \Drupal::database();
    $query = $database->select('taxonomy_term__field_user_info','tu');
    $query->condition('tu.field_user_info_target_id', $uid, '=');
    $query->fields('tu',['entity_id','field_user_info_target_id']);
    $result = $query->execute()->fetchCol();
    foreach($result as $termIds){
      $getterm = Term::load($termIds);
      $terms[$termIds] = $getterm->getName();
    }    
    $form['sub_category_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sub Category Name'),
      '#maxlength' => 120,
      '#size' => 120,
      '#weight' => '0',
    ];
    $form['select_category'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Category'),
      '#options' => $terms,
      '#size' => 1,
      '#weight' => '0',
      '#required' => true
    ];
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
    $subcatName = $form_state->getValue('sub_category_name'); 
    $catId = $form_state->getValue('select_category'); 
    $uid = \Drupal::currentUser()->id();
     // Create the taxonomy term.
      $new_term = Term::create([
        'name' => $subcatName,
        'field_user_info' => $uid,
        'vid' => 'category',
        'parent' => $catId,
      ])->save();
      \Drupal::messenger()->addMessage('Sub Category Added');
  }

}
