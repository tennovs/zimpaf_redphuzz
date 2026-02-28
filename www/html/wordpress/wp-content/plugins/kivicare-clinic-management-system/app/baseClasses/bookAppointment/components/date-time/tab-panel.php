<?php

// if(isset($_GET['clinic_name'])){
//     $selected_clinic =  $_GET['clinic_name'];
//     print_r($selected_clinic);
// }
// if(isset($_GET['doctor_name'])){
//     $selected_doctor =  $_GET['doctor_name'];
//     print_r($selected_doctor);
// }
// $time_slots   = $bookAppointmentWidgetObject->getTimeSlotsList($selected_doctor,$selected_clinic);
// $timeList = $time_slots != [] ? $time_slots : [];

?>

<div id="date-time" class="iq-fade iq-tab-pannel">
    <div>
        <div>
            <h4>Select Date and time</h4>
        </div>
        <hr>
    </div>
    <form action="#detail-info" data-prev="#category">
        <div class="grid-template-2" id="datepicker-grid">
            <div class="iq-inline-datepicker" id="calender-slot">
                <input type="hidden" class="inline-flatpicker" style="opacity: 0;">
            </div>
            <div class="d-none" id="time-slot">
                <div class="iq-card iq-bg-primary-light text-center">
                    <h5>Avaliable Slots For March 12</h5>
                    <hr>
                    <div class="d-grid grid-template-3">
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">9:00 PM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">11:00 PM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">12:00 PM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">3:00 AM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">4:00 AM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">6:00 PM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">7:00 AM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">8:00 AM</button>
                        <button type="button" class="iq-button iq-button-white" data-toggle="active">9:00 AM</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="iq-button iq-button-secondary" data-step="prev">PREV</button>
            <button type="submit" class="iq-button iq-button-primary" data-step="next">Next</button>
        </div>
    </form>
</div>
