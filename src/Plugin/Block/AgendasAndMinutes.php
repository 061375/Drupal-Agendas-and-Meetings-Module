<?php 
namespace Drupal\agendas-meetings\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\agendas-meetings\Libraries\libAgendasAndMinutes;
use Drupal\agendas-meetings\Model\mdlAgendasAndMinutes;
/**
 *
 * AgendasAndMinutes
 *
 * @author Jeremy Heminger
 * @version 1.0.0
 *
 * */
/** 
 * 
 * @todo category_field should be placed in 'install' or settings in module GUI
 * 
 * 
 * 
 * 
 * Provides a 'Agendas and Minutes' Block
 *
 * @Block(
 *   id = "agendas_meetings_agenda_minutes",
 *   admin_label = @Translation("Agendas and Minutes"),
 * )
 */
class AgendasAndMinutes extends BlockBase  implements BlockPluginInterface {

	private $config = array();

	private $libs = array();

	/**
	* {@inheritdoc}
	*/
	public function build() { 
 
    	$this->config = $this->getConfiguration();


    	// check if the repository user name is set (if not then die)
	    if(empty($this->config['type_machine_name'])) {
	      return array(
	      '#markup' => '<p>Please set the drupal type machine name you wish to display</p>',
	        '#allowed_tags' => ['p']
	      ); 
	    }
	    if(empty($this->config['date_field'])) {
	      return array(
	      '#markup' => '<p>Please set the drupal type date field</p>',
	        '#allowed_tags' => ['p']
	      ); 
	    }

	    // instanciate the library
	    $this->libs['aandm'] = new libAgendasAndMinutes();
	    $result = $this->libs['aandm']->getNode($this->config,true);

	    // make sure the module has been configured
	    if(false === $result) {
	    	return array(
		      '#markup' => '<p>An error occured: Please ensure that the block field settings match existing content type fields.</p>',
		        '#allowed_tags' => ['p']
		     );	
	    }

	    $result = $this->libs['aandm']->buildNode($result,$this->config);

		// return the HTML
	    return array(
	      '#markup' => $result,
	      '#allowed_tags' => ['a','p','table','tr','td','th','thead','tbody']
	    );
    }
    /**
    * {@inheritdoc}
    */
    public function blockForm($form, FormStateInterface $form_state) {
	    
	    $this->libs['model'] = new mdlAgendasAndMinutes();

	    $form = parent::blockForm($form, $form_state);

	    $config = $this->getConfiguration();

	    // @todo this should check if the machine name exists via Ajax
	    $form['agendas-meetings_agandmin_block'] = array(
	      '#type' => 'textfield',
	      '#title' => $this->t('Machine Name'),
	      '#description' => $this->t('The Drupal type machine name you wish to display'),
	      '#default_value' => isset($config['type_machine_name']) ? $config['type_machine_name'] : '',
	    );
	    // @todo this should check if the machine name exists via Ajax
	    /*
	    $form['agendas-meetings_agandmin_block_categoryfield'] = array (
	      '#type' => 'select',
	      '#title' => $this->t('Meeting Category'),
	      '#default_value' => (isset($config['category_field']) ? $config['category_field'] : ''),
	      '#options' => array(
	        '0' => $this->t('MSRC Meeting'),
	        '1' => $this->t('TAC Meeting'),
	        '2' => $this->t('Scope Changes Committee Meeting'),
	        '3' => $this->t('Administrative Committee Meeting')
	      )
	    );*/
		$goptions = $this->libs['model']->getSelectAllowed('field_meeting_categories');
		foreach ($goptions as $key => $value) {
			$options[$key] = $this->t($value);
		}
		$form['agendas-meetings_agandmin_block_categoryfield'] = array (
	      '#type' => 'select',
	      '#title' => $this->t('Meeting Category'),
	      '#default_value' => (isset($config['category_field']) ? $config['category_field'] : ''),
	      '#options' => $options
	    );
	    // @todo this should check if the machine name exists via Ajax
	    $form['agendas-meetings_agandmin_block_datefield'] = array(
	      '#type' => 'textfield',
	      '#title' => $this->t('Date Field Machine Name'),
	      '#description' => $this->t('The Date Field to query by default'),
	      '#default_value' => isset($config['date_field']) ? $config['date_field'] : '',
	    );
	    return $form;
    }
    /**
	   * {@inheritdoc}
	*/
	public function blockSubmit($form, FormStateInterface $form_state) {
		// @todo this should check if the machine name exists
	    $this->setConfigurationValue('type_machine_name', $form_state->getValue('agendas-meetings_agandmin_block'));
	    $this->setConfigurationValue('category_field', $form_state->getValue('agendas-meetings_agandmin_block_categoryfield'));
	    $this->setConfigurationValue('date_field', $form_state->getValue('agendas-meetings_agandmin_block_datefield'));
	}
	/**
	 * {@inheritdoc}
	 */
	  public function defaultConfiguration() {
	    $default_config = \Drupal::config('agendas-meetings.settings');
	    return array(
	      'type_machine_name' => $default_config->get('agendas-meetings_block.type_machine_name')
	    );
	  }

}