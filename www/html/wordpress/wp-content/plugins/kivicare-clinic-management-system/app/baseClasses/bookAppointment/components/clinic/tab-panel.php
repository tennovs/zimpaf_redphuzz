<?php

$clinics_s  = $bookAppointmentWidgetObject->getClinicArray();

$clinicList = $clinics_s != [] ? $clinics_s : [];

?>
<script>
    
</script>

<div id="clinic" class="iq-fade iq-tab-pannel active">
    <form action="#doctor" method="post">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Choose Clinic From Below</h4>
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

                if($clinicList != []){
                    foreach ($clinicList['data'] as $clinics){  
            ?>

            <div class="iq-client-widget">
                <input type="radio" class="card-checkbox selected-clinic" name="clinic_name" id="clinic-<?php echo $clinics['id']; ?>" value="<?php echo $clinics['id']; ?>">
                <label class="btn-border01" for="clinic-<?php echo $clinics['id']; ?>">
                    <div class="iq-card iq-card-lg iq-fancy-design iq-card-border">
                        <div class="media">
                            <img src="<?php echo $clinics['profile_image'] != null ? $clinics['profile_image'] : ''; ?>" class="avatar-90 rounded-circle" alt="<?php echo $clinics['id']; ?>">
                        </div>
                        <h5><?php echo $clinics['name']; ?></h5>
                        <p><?php echo $clinics['address'].', '. $clinics['city'].', '. $clinics['country'].', '.$clinics['postal_code']; ?></p>
                        <hr>
                        <div class="d-flex d-flex justify-content-evenly flex-wrap gap-1">
                            <div>
                                <h6>Contact</h6>
                                <p><?php echo $clinics['telephone_no']; ?></p>
                            </div>
                            <div>
                                <h6>Email</h6>
                                <p>kathryn@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <?php
                    }
                }else{

                    ?>
                    <p>No Clinic Found</p>
                    <?php
                }
            ?>
        </div>
        <span name="doctorLists" id="doctorLists">
        </span>
        <div class="card-footer">
            <!-- <button type="close" class="iq-button iq-button-secondary iq-text-uppercase" data-step="prev">Back</button> -->
            <button type="submit" class="iq-button iq-button-primary iq-text-uppercase" data-step="next">Next</button>
        </div>
    </form>
</div>
