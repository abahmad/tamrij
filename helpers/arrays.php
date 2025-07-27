<?php

$blog_id = get_current_blog_id();

// Field Array
$custom_posts = 
array(
	'reservation_type' =>
	array(
	    'type_name' => array(
	        'label'			=> __('Name','tamarji'),
	        'name' 			=> 'type_name',
	        'type'			=> 'text',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'type_name',
	        'class' 		=> '',
	    ),
	    'price' => array(
	        'label'			=> __('Price','tamarji'),
	        'name' 			=> 'price',
	        'type'			=> 'number',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'price',
	        'class' 		=> '',
	    )
	),
	
	'reservation' =>
	array(
	    'type' => array(
	        'label'			=> __('Type','tamarji'),
	        'name' 			=> 'type',
	        'type'			=> 'select',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'type_select',
	        'class' 		=> '',
	        'entity' 		=> 'post'
	    ),
	    'patient_id' => array(
	        'label'			=> __('Patient','tamarji'),
	        'name' 			=> 'patient_id',
	        'type'			=> 'select',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'patient_select',
	        'class' 		=> '',
	        'entity' 		=> 'user'
	    ),
	    'department_id' => array(
	        'label'			=> __('Department','tamarji'),
	        'name' 			=> 'department_id',
	        'type'			=> 'select',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'department_select',
	        'class' 		=> '',
	        'entity' 		=> 'taxonomy'
	    ),
	    'doctor_id' => array(
	        'label'			=> __('Doctor','tamarji'),
	        'name' 			=> 'doctor_id',
	        'type'			=> 'select',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'doctor_select',
	        'class' 		=> '',
	        'entity' 		=> 'user',
	        'empty' 		=> 'true'
	    ),
	    'lastModified' => array(
	        'label'			=> '',
	        'name' 			=> 'lastModified',
	        'type'			=> 'now',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'attendOn' => array(
	        'label'			=> __('Attend On','tamarji'),
	        'name' 			=> 'attendOn',
	        'type'			=> 'datepicker',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'attendOn_select',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'confirmedOn' => array(
	        'label'			=> '',
	        'name' 			=> 'confirmedOn',
	        'type'			=> 'now',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'cancelledOn' => array(
	        'label'			=> '',
	        'name' 			=> 'cancelledOn',
	        'type'			=> 'now',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'complaint' => array(
	        'label'			=> __('Complaints','tamarji'),
	        'name' 			=> 'complaint',
	        'type'			=> 'textarea',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'complaint',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'notes' => array(
	        'label'			=> __('Notes','tamarji'),
	        'name' 			=> 'notes',
	        'type'			=> 'textarea',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'notes',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'history' => array(
	        'label'			=> '',
	        'name' 			=> 'history',
	        'type'			=> '',
	        'validation' 	=> '',
	        'save_as'		=> 'multi',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> '',
	        'action'		=> 'add'
	    ),
	    // 'paid' => array(
	        // 'label'			=> '',
	        // 'name' 			=> 'paid',
	        // 'type'			=> '',
	        // 'validation' 	=> '',
	        // 'save_as'		=> 'single',
	        // 'desc' 			=> '',
	        // 'id' 			=> '',
	        // 'class' 		=> '',
	        // 'entity' 		=> '',
	    // )
	
	),
	
	'visit' =>
	array(
	  'reservation_id' => array(
	        'label'			=> '',
	        'name' 			=> 'reservation_id',
	        'type'			=> 'hidden',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	  'department_id' => array(
	        'label'			=> '',
	        'name' 			=> 'department_id',
	        'type'			=> 'hidden',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	  'reservation_id' => array(
	        'label'			=> '',
	        'name' 			=> 'reservation_id',
	        'type'			=> 'hidden',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	  'reservation_id' => array(
	        'label'			=> '',
	        'name' 			=> 'reservation_id',
	        'type'			=> 'hidden',
	        'validation' 	=> 'required',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> '',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'complaint' => array(
	        'label'			=> __('Complaints','tamarji'),
	        'name' 			=> 'complaint',
	        'type'			=> 'textarea',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'complaint',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'symptom' => array(
	        'label'			=> __('Symptoms','tamarji'),
	        'name' 			=> 'symptom',
	        'type'			=> 'text',
	        'validation' 	=> 'required',
	        'save_as'		=> 'array',
	        'desc' 			=> '',
	        'id' 			=> 'symptom',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'diagnosis' => array(
	        'label'			=> __('Diagnosis','tamarji'),
	        'name' 			=> 'diagnosis',
	        'type'			=> 'text',
	        'validation' 	=> 'required',
	        'save_as'		=> 'array',
	        'desc' 			=> '',
	        'id' 			=> 'diagnosis',
	        'class' 		=> '',
	        'entity' 		=> '',
	       
	    ),
	    'prescription' => array(
	        'label'			=> __('Prescriptions','tamarji'),
	        'name' 			=> 'prescription',
	        'type'			=> 'text',
	        'validation' 	=> 'required',
	        'save_as'		=> 'multi', // multi array
	        'desc' 			=> '',
	        'id' 			=> 'prescription',
	        'class' 		=> '',
	        'entity' 		=> '',
	       
	    ),
	    'hide_symptom' => array(
	        'label'			=> __('Hide Symptoms','tamarji'),
	        'name' 			=> 'hide_symptom',
	        'type'			=> 'check',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'hide_symptom',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'hide_complaint' => array(
	        'label'			=> __('Hide Complaints','tamarji'),
	        'name' 			=> 'hide_complaint',
	        'type'			=> 'check',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'hide_complaint',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'hide_diagnosis' => array(
	        'label'			=> __('Hide Diagnosis','tamarji'),
	        'name' 			=> 'hide_diagnosis',
	        'type'			=> 'check',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'hide_diagnosis',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'image_id' => array(
	        // 'label'			=> __('Hide Diagnosis','tamarji'),
	        'name' 			=> 'image_id',
	        'type'			=> 'image',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'image_id',
	        'class' 		=> '',
	        'entity' 		=> ''
	    ),
	    'image_url' => array(
	        // 'label'			=> __('Hide Diagnosis','tamarji'),
	        'name' 			=> 'image_url',
	        // 'type'			=> 'check',
	        'validation' 	=> '',
	        'save_as'		=> 'single',
	        'desc' 			=> '',
	        'id' 			=> 'image_url',
	        'class' 		=> '',
	        'entity' 		=> ''
	    )
	)
	
);
?>