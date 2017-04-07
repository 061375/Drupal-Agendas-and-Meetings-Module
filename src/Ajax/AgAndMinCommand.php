<?php 
namespace Drupal\agendas-meetings\Ajax;
use Drupal\Core\Ajax\CommandInterface;
use Drupal\agendas-meetings\Model\mdlAgendasAndMinutes;
use Drupal\agendas-meetings\Libraries\libAgendasAndMinutes;
use Drupal\agendas-meetings\Libraries\General;
/** 
 * 
 * 
 * 
 * */
class AgAndMinCommand implements CommandInterface {
	
	// ---

	protected $success; // int ( 0 , 1 )

	// ---

	protected $message; // mixed | the returned result

	// ---

	private $libs; // array | associative array of libraries to use locally

	// ---

	private $errors; // array | list of errors

	// ---

	public function __construct() {
		$this->libs['model'] = new mdlAgendasAndMinutes();
		$this->libs['agandmin'] = new libAgendasAndMinutes();
		$this->selector = $selector;
		$method = General::post_variable('method',false);
		if(false === $method OR false == method_exists($this, $method)) {
			$this->success = false;
			$this->message = 'requested method does not exist';
		}else{
			$result = $this->$method(isset($_POST['data']) ? $_POST['data'] : false);
			if(false === $result)
				$result['message'] = $this->errors;
			$this->success = $result['success'];
			$this->message = $result['message'];
		}
	}
	/** 
	 * @param array 
	 * @return array
	 * */
	private function getYears($data) {
		$success = 0;
		$return = array();
		$result = $this->libs['model']->getAll('meetings_agendas_and_minutes');
		if (!empty($result)) {
			// convert the object into something I can loop through
		  	$nids = array_keys($result);
		  	$nodes = node_load_multiple($nids);
		  	// loop the object
		    foreach ($nodes as $key => $value) {
		    	$_val = date('Y',strtotime($value->get('field_date_and_time')->getValue()[0]['value']));
		    	if(!in_array($_val, $return)) {
		    		$return[] = $_val;
		    	}
		    }
		    $success = 1;
		}
	  	return array(
	  		'success'=>$success,
	  		'message'=>$return 
	  	);
	}
	/** 
	 * @param array $data
	 * @return array
	 * */
	private function getByYear($data) {
		$year = General::is_set($data,'year',false);
		if(false === $year) {
			// there should always be a 'year' variable, so this protects us
			$year = strtotime('now');
		}
		// if js sends us anempty year then assume the year is now
		if('false' == $year) $year = strtotime('now');


		$type = General::is_set($data,'type',false);
		if(false === $type) {
			$this->errors[] = 'type is a required field';
		}
		$date_field = General::is_set($data,'dfield',false);
		if(false === $date_field) {
			$this->errors[] = 'dfield is a required field';
		}
		// if errors return false
		if(count($this->errors) > 0)
			return false;

		// not required
		$meeting = General::is_set($data,'meeting','false');
		// if no meeting category 
		if('false' == $meeting) {
			// get the meeting(s) by year
			$result = $this->libs['model']->getNodeByYear($type,$date_field,$year,true);
			// if the rsult is not empty
			if(!empty($result)) {
				// loop the results
				foreach ($result as $key => $value) {
					// get the meeting category ID
					$nid = General::is_set($value->get('field_meeting_categories')->getValue()[0],'value','');
					// get the meeting from the database
					$return[$nid] = $this->libs['agandmin']->buildNode(array($key=>$value),array(
						'type_machine_name'=>$type,
						'date_field'=>$date_field,
						'category_field'=>$nid,
						'no_th'=>true
					));
				}
			}
		}else{
			$nid = '';
			// if a meeting was selected then get that specific meeting by year and category
			$result = $this->libs['model']->getNode($type,$meeting,$date_field,$year,true);
			$value = reset($result);
			if(!empty($value)) {
				$nid = General::is_set($value->get('field_meeting_categories')->getValue()[0],'value','');
			}
			$return = $this->libs['agandmin']->buildNode($result,array(
				'type_machine_name'=>$type,
				'date_field'=>$date_field,
				'category_field'=>$nid,
				'no_th'=>true
			));
		}
		// return the result
		return array(
	  		'success'=>1,
	  		'message'=>$return 
	  	);
	}
	/** 
	 * render the result as a JSON string
	 * @return array
	 * */
	public function render() {
		return array(
			'success'=>$this->success,
			'message'=>$this->message
		);
	}
}