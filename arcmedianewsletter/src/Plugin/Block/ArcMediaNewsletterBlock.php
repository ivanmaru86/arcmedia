<?php

namespace Drupal\arcmedianewsletter\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ArcMediaNewsletterBlock' block.
 *
 * @Block(
 *  id = "arcmedia_newsletter_block",
 *  admin_label = @Translation("Arcmedia Newsletter"),
 * )
 */
class ArcMediaNewsletterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['#theme'] = 'arcmedianewsletter';
    $build['#form'] = \Drupal::formBuilder()->getForm('Drupal\arcmedianewsletter\Form\ArcMediaNewsLetterForm');
    $build['#attached'] = [
      'library' => [
        'arcmedianewsletter/arcmedianewsletter',
      ]
    ];

    return $build;
  }

}
