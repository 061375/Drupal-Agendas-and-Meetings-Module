<?php
namespace Drupal\agendas-meetings\Model;
use Drupal\node\Entity\Node;
use Drupal\field\FieldConfigInterface;
/**
 *
 * AgendasAndMinutes
 *
 * @author Jeremy Heminger 
 * @version 1.0.0
 *
 * */
class mdlAgendasAndMinutes {

	private $error = array();
	
	/** 
	* @param: string $type
	* @param: string $category
	* @param: string $datefield
	* @param: string $qdate
	* @return object
	*/
	public function getNode($type,$category,$datefield,$qdate,$load = false) {
		/*
		echo $type."<br>";
		echo $category.'<br>';
		echo $datefield.'<br>';
		echo $qdate.'<br>';
		*/

		try {
		    $nids = \Drupal::entityQuery('node')
		    	->condition('type',$type)
		    	->condition('field_meeting_categories',$category)
		    	->condition($datefield, '%'.$qdate.'%', 'LIKE')
		    	->execute();
			$return = \Drupal\node\Entity\Node::loadMultiple($nids);
			if(false === $load) {
				return $return;
			}else{
				return $this->loadNodes($return);
			}
		} catch(\Drupal\Core\Entity\Query\QueryException $e) {

			return false;
		}
	}
	/** 
	* @param: string $type
	* @return object
	*/
	public function getAll($type, $load = false) {
		/*
		echo $type."<br>";
		echo $category.'<br>';
		echo $datefield.'<br>';
		echo $qdate.'<br>';
		*/
		try {
		    $nids = \Drupal::entityQuery('node')
		    	->condition('type',$type)
		    	->execute();
			$return = \Drupal\node\Entity\Node::loadMultiple($nids);
			if(false === $load) {
				return $return;
			}else{
				return $this->loadNodes($return);
			}
		} catch(\Drupal\Core\Entity\Query\QueryException $e) {

			return false;
		}
	}
	/** 
	* @param: string $type
	* @param: string $datefield
	* @param: string $qdate
	* @return object
	*/
	public function getNodeByYear($type,$datefield,$qdate,$load = false) {
		/*
		echo $type."<br>";
		echo $category.'<br>';
		echo $datefield.'<br>';
		echo $qdate.'<br>';
		*/
		try {
		    $nids = \Drupal::entityQuery('node')
		    	->condition('type',$type)
		    	->condition($datefield, '%'.$qdate.'%', 'LIKE')
		    	->execute();
			$return = \Drupal\node\Entity\Node::loadMultiple($nids);
			if(false === $load) {
				return $return;
			}else{
				return $this->loadNodes($return);
			}
		} catch(\Drupal\Core\Entity\Query\QueryException $e) {

			return false;
		}
	}
	public function getSelectAllowed($node) {
		$field = \Drupal\field\Entity\FieldStorageConfig::loadByName('node',$node);
		return isset($field->get('settings')['allowed_values']) ? $field->get('settings')['allowed_values'] : array();
	}
	/** 
	 * @return array
	 */
	public function get_errors() 
	{
		return $this->error;
	}
	public function loadNodes($result) {
		if (!empty($result)) {
			$nids = array_keys($result);
			return node_load_multiple($nids);
		}
		return array();
	}
}