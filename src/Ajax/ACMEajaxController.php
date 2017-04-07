<?php
namespace Drupal\agendas-meetings\Ajax;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\agendas-meetings\Ajax\AgAndMinCommand;
use Drupal\Core\Controller\ControllerBase;
class ACMEajaxController extends ControllerBase {
  public function ajaxEvents($method, $event_id = '') {
    if($method == 'ajax') {
    	$response = new AjaxResponse();
    	$response->addCommand(new AgAndMinCommand());
    }
    return $response;
  }

}