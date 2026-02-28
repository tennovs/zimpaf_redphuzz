<?php

// print_r($doctorsList);
// $selected_clinic = 1;
// if(isset($_GET['clinic_name'])){
//     $selected_clinic =  $_GET['clinic_name'];
// }
// $doctor_s   = $bookAppointmentWidgetObject->getDoctorsArray($selected_clinic);
// echo $doctor_s;
$doctorsList = [];

?>

<div id="doctor" class="iq-fade iq-tab-pannel">
    <form action="#category" data-prev="#clinic" method="GET">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Select doctor</h4>
            <div class="iq-kivi-search">
                <svg width="20" height="20" class="iq-kivi-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <input type="text" class="iq-form-control" placeholder="Search">
            </div>
        </div>
        <hr>
        <div class="card-list card-list-data text-center pt-2 pe-2">
        <?php
            if($doctorsList != []){
                foreach ($doctorsList as $doctors){
                    // print_r($doctors);
        ?>
            <div class="iq-client-widget position-relative">
                <input type="radio" onchange="this.form.submit()" class="card-checkbox" name="doctor_name" id="doctor-<?php echo $doctors['id']; ?>" value="<?php echo $doctors['id']; ?>">
                <label class="btn-border01" for="doctor-<?php echo $doctors['id']; ?>">
                    <div class="iq-card iq-card-border iq-fancy-design iq-doctor-widget">
                        <div class="profile-bg"></div>
                        <div class="iq-top-left-ribbon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.5807 12.9484C13.6481 14.4752 12.416 15.7662 10.8288 15.8311C10.7119 15.836 5.01274 15.8245 5.01274 15.8245C3.43328 15.9444 2.05094 14.8094 1.92636 13.2884C1.91697 13.1751 1.91953 7.06 1.91953 7.06C1.84956 5.53163 3.08002 4.23733 4.66801 4.16998C4.78661 4.16424 10.4781 4.17491 10.4781 4.17491C12.0653 4.05665 13.4519 5.19984 13.5747 6.72821C13.5833 6.83826 13.5807 12.9484 13.5807 12.9484Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M13.5834 8.31621L16.3275 6.07037C17.0075 5.51371 18.0275 5.99871 18.0267 6.87621L18.0167 13.0004C18.0159 13.8779 16.995 14.3587 16.3167 13.802L13.5834 11.5562" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div class="media d-flex align-items-center justify-content-center mt-3">
                            <img src="<?php echo $doctors['user_profile']; ?>" class="avatar-90 rounded-circle" alt="01">
                        </div>
                        <h5 class="mt-2 mb-0"><?php echo $doctors['display_name']; ?></h5>
                        <span class="iq-letter-spacing-1 iq-text-uppercase">sector</span>
                        <div class="my-4 iq-doctor-badge">
                            <span class="iq-badge iq-bg-secondary iq-color-white">Exp : 2 yrs</span>
                        </div>
                        <div class="d-flex d-flex justify-content-evenly flex-wrap gap-1">
                            <div>
                                <h6 class="mb-0">Contact</h6>
                                <p class="mt-1 mb-0"><?php echo $doctors['contact_no']; ?></p>
                            </div>
                            <div>
                                <h6 class="mb-0">Email</h6>
                                <p class="mt-1 mb-0">kathryn@gmail.com</p>
                            </div>
                        </div>
                        <h6 class="iq-text-uppercase iq-color-primary mb-0 mt-4">View Details </h6>
                    </div>
                </label>
            </div>
        <?php
                }
            }else{
                ?>
                    <p>No Doctor Avaliable</p>
                <?php
            }
        ?>
            <!-- {{> components/doctor/doctor-card id="001" doctor-name="Kathryn Murphy" sector="Dentist" experience="02 yr"}}
            {{> components/doctor/doctor-card id="002" doctor-name="Kathryn Murphy" sector="Dentist" experience="02 yr"}} -->
        </div>
        <div class="card-footer">
            <button type="close" class="iq-button iq-button-secondary iq-text-uppercase" data-step="prev">Back</button>
            <button type="submit" class="iq-button iq-button-primary iq-text-uppercase" data-step="next">Next</button>
        </div>
    </form>
</div>
