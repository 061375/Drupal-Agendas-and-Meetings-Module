<?php
/**
 * @file
 * Contains \Drupal\agendas-meetings\Controller.
 */
namespace Drupal\agendas-meetings\Controller;
use Drupal\Core\Controller\ControllerBase;
class ACMEController extends ControllerBase {
  public function content() {
    return array(
        '#type' => 'markup',
        '#markup' => $this->t('ACME Controller'),
    );
  }
}