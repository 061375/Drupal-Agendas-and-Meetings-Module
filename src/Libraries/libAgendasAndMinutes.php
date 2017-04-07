<?php
namespace Drupal\agendas-meetings\Libraries;
use Drupal\node\Entity\Node;
use Drupal\field\FieldConfigInterface;
use Drupal\agendas-meetings\Model\mdlAgendasAndMinutes;
/**
 *
 * libAgendasAndMinutes
 *
 * @author Jeremy Heminger 
 * @version 1.0.0
 *
 * */
class libAgendasAndMinutes {

	private $error = array();
	
	private $libs = array();

	function __construct() {
		// instanciate the model
	    $this->libs['model'] = new mdlAgendasAndMinutes();
	}
	/** 
	* @param: string $type
	* @param: string $category
	* @param: string $datefield
	* @param: string $qdate
	* @return object
	*/
	public function getNode($config) {
		
	    // get the agendas
	    $result = $this->libs['model']->getNode(
	    	$config['type_machine_name'],
	    	$config['category_field'],
	    	$config['date_field'],
	    	date('Y',strtotime('now'))
	    );
	    return $result;
	}
	public function buildNode($result,$config) {

	    // get the meeting category
		$options = $this->libs['model']->getSelectAllowed('field_meeting_categories');
		$meeting = isset($options[$config['category_field']]) ? $options[$config['category_field']] : '';
	    /** 
	     * Build HTML for export
	     * */
	    // if no_th is set then don't add an HTML head to the table
	    if(false === isset($config['no_th'])) {
		    $return = '
		    <table class="table table-bordered type_machine_name-'.$config['type_machine_name'].' category_field-'.$config['category_field'].' date_field-'.$config['date_field'].' ">
		    <thead>
		    <tr class="thead">
		    	<th>
		    		'.$meeting.'
		    	</th>
		    	<th>
		    		Time 
		    	</th>
		    	<th>
		    		Agenda
		    	</th>
		    	<th>
		    		Minutes 
		    	</th>
		    </tr>
		    </thead>
		    <tbody>';
		}
		if (!empty($result)) {
		  	// loop the object
		    foreach ($result as $key => $value) {
		    	$date = date('F j, Y',strtotime($value->get('field_date_and_time')->getValue()[0]['value']));
		    	if(1 == $value->get('field_meeting_status')->getValue()[0]['value']) {
		    		$time = 'Cancelled';
		    	}else{
		    		$time = date('g:i A',strtotime($value->get('field_date_and_time')->getValue()[0]['value']));
		    	}
		    	if(isset($value->get('field_minutes_file_s_')->entity->uri->value)) {
		    		$minutes = '<a href="/sites/default/files'.str_replace('public:', '',$value->get('field_minutes_file_s_')->entity->uri->value).'">[FILE ICON]</a>';	
		    	}else{
		    		$minutes = '';
		    	}
		    	if(isset($value->get('field_agenda_file')->entity->uri->value)) {
		    		$agendas = '<a href="/sites/default/files'.str_replace('public:', '',$value->get('field_agenda_file')->entity->uri->value).'">[FILE ICON]</a>';	
		    	}else{
		    		$agendas = '';
		    	}
		    	
		    	$return .= 
		    	'<tr>
		    		<td>'.$date.'</td>
		    		<td>'.$time.'</td>
		    		<td>'.$agendas.'</td>
		    		<td>'.$minutes.'</td>
		    	</tr>';
		    }
		}else{
			$return .= '<td colspan="4">There are no meetings for this time period.</td>';
		}
		$return .= '</tbody></table>';

		return $return;
	}
}