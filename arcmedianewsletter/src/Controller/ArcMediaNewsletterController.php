<?php

namespace Drupal\arcmedianewsletter\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ArcMediaNewsletterController.
 */
class ArcMediaNewsletterController extends ControllerBase {

  /**
   * showSubscriptions.
   *
   * @return string
   *   Return Hello string.
   */
  public function showSubscriptions() {
    $connection = \Drupal::database();

    $query = $connection->select('arcmedianewsletters', 'newsletters', [])
    ->fields('newsletters', [])
    ->execute();

    $newsletters = $query->fetchAllAssoc('nwid');

    return [
      '#theme' => 'subscriptionlist',
      '#newsletters' => $newsletters,
    ];
  }

}
