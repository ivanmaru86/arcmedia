<?php

namespace Drupal\arcmedianewsletter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\CssCommand;

/**
 * Class ArcMediaNewsLetterForm.
 */
class ArcMediaNewsLetterForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'arcmedia_newsletter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $markup = '<h2>Arcmedia Newsletter</h2>';
    $markup .= '<div id="message"></div>';
    $form['#markup'] = $markup;
    $form['#action'] = '/form/newsletter';
    $newsletters = [
      'General' => 'general',
      'Health and Beauty' => 'health',
      'Sports and Leisure' => 'sports',
      'Garden Greens' => 'garden',
    ];
    foreach ($newsletters as $newsletter => $nwslid) {
      if ($newsletter == 'General') {
        $form['newsletter_' . $nwslid] = [
        '#default_value' => TRUE,
        '#type' => 'hidden',
        '#title' => $newsletter,
        '#value' => 1,
        ];
      }
      else {
        $form['newsletter_' . $nwslid] = [
          '#type' => 'checkbox',
          '#title' => $newsletter,
          '#weight' => '0',
        ];
      }
    }
    if(\Drupal::currentUser()->isAnonymous()){
      $form['username'] = [
        '#type' => 'textfield',
        '#placeholder' => 'Enter your name',
      ];
      $form['email'] = [
        '#type' => 'email',
        '#placeholder' => 'Enter your email',
      ];
    }
    $form['submit'] = [
      '#type' => 'submit',
      '#class' => 'button-subscribe',
      '#value' => $this->t('Subscribe now'),
      '#ajax' => [
        'callback' => '::submitAjaxForm',
      ]
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Implements custom submit form
   */
  public function submitAjaxForm(array &$form, FormStateInterface $form_state) {
    $user = \Drupal::currentUser();
    $user_name = '';
    $user_email =  '';
    $response = new AjaxResponse();
    if(\Drupal::currentUser()->isAnonymous()){
      $user_name = $form_state->getValue('username');
      $user_email = $form_state->getValue('email');
      if(empty($user_name)){
        $response->addCommand(new HtmlCommand('#message', 'The field username cannot be empty.'));
        $response->addCommand(new InvokeCommand('#message', 'addClass',['error-message']));
        return $response;
      }
      if(!\Drupal::service('email.validator')->isValid($user_email)) {
        $response->addCommand(new HtmlCommand('#message', 'The email is not a valid email address'));
        $response->addCommand(new InvokeCommand('#message', 'addClass',['error-message']));
        return $response;
      }
    }
    else {
      $user_name = $user->getUsername();
      $user_email =  $user->getEmail();
    };
    $user_ip = \Drupal::request()->getClientIp();
    $connection = \Drupal::database();

    $query = $connection->insert('arcmedianewsletters')
    ->fields(
      [
        'username' => $user_name,
        'email' => $user_email,
        'date' => date('Y-m-d h:i:s'),
        'ip' => $user_ip,
        'newsletter_general' => $form_state->getValue('newsletter_general'),
        'newsletter_health' => $form_state->getValue('newsletter_health'),
        'newsletter_sports' => $form_state->getValue('newsletter_sports'),
        'newsletter_garden' => $form_state->getValue('newsletter_garden'),
      ]
    )
    ->execute();

    $response->addCommand(new InvokeCommand('#block-arcmedianewsletter', 'hideBlock'));
    return $response;
  }

}
