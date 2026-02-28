<?php

namespace App\controllers;

use App\baseClasses\KCBase;
use App\baseClasses\KCRequest;
use WP_User;

class KCGoogleCalenderController extends KCBase
{
    /**
     * @var KCRequest
     */
    private $request;

    public function __construct()
    {
        $this->request = new KCRequest();
    }
    
    public function saveConfig(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcpro_saved_google_config', ['data'=>$request_data]);
        echo json_encode($response);
    }
    public function editConfigData(){
        $response = apply_filters('kcpro_edit_google_cal', []);
        $patientResponse = apply_filters('kcpro_patient_edit_google_cal', []);
        echo json_encode(['google' =>$response ,'patient' => $patientResponse]);
    }
    public function connectDoctor(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcpro_connect_doctor', [
            'id'=>$request_data['doctor_id'],
            'code'=>$request_data['code'],
        ]);
        echo json_encode($response);
    }
    public function disconnectDoctor(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcpro_disconnect_doctor', [
            'id'=>$request_data['doctor_id']
        ]);
        echo json_encode($response);
    }
    public function getGoogleEventTemplate () {
        $response = apply_filters('kcpro_get_google_event_template', []);
        echo json_encode($response);
    }
    public function saveGoogleEventTemplate(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcpro_save_google_event_template', [
            'data'=>$request_data['data']
        ]);
        echo json_encode($response);

    }

    public function saveGoogleMeetConfig(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcgm_saved_googlemeet_config', ['data'=>$request_data]);
        echo json_encode($response);
    }
    public function editGoogleMeetConfigData(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcgm_edit_googlemeet', []);
        echo json_encode($response);
    }

    public function getGoogleMeetEventTemplate () {
        $response = apply_filters('kcgm_get_googlemeet_event_template', []);
        echo json_encode($response);
    }
    public function saveGoogleMeetEventTemplate(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcgm_save_googlemeet_event_template', [
            'data'=>$request_data['data']
        ]);
        echo json_encode($response);
    }

    public function connectGoogleMeetDoctor(){
        $request_data = $this->request->getInputs();
        $response = apply_filters('kcgm_connect_doctor', [
            'id'=>$request_data['doctor_id'],
            'code'=>$request_data['code'],
        ]);
        echo json_encode($response);
    }

    public function disconnectMeetDoctor(){

        $request_data = $this->request->getInputs();
        $response = apply_filters('kcgm_disconnect_doctor', [
            'id'=>$request_data['doctor_id']
        ]);
        
        echo json_encode($response);

    }
}