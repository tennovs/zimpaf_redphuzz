<?php

$service_list=[];
// $service_list  = $serviceController->getDoctorService($doctorsList[0]['id']);
if(isset($_GET['doctor_name'])){
    $selected_doctor =  $_GET['doctor_name'];
    $service_list  = $serviceController->getDoctorService($selected_doctor);
    // echo $_COOKIE["clinic_name"]; 
}


?>

<div id="category" class="iq-fade iq-tab-pannel">
    <form action="#date-time" data-prev="#doctor">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Choose Service From Category</h4>
            <div class="iq-kivi-search">
                <svg width="20" height="20" class="iq-kivi-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <input type="text" class="iq-form-control" placeholder="Search">
            </div>
        </div>
        <hr>
        <div class="card-list-data d-flex flex-column gap-2 pt-2 pe-2">
            <div class="d-flex flex-column gap-1">
                <!-- <h6 class="iq-text-uppercase iq-color-secondary iq-letter-spacing-1">Dentist</h6> -->
                <div class="iq-category-list">
                <?php
                    if($service_list != []){
                        foreach ($service_list as $service){
                            $service = (array)$service;
                            if($service['status']== 1){
                ?>
                    <div class="iq-client-widget">
                        <input type="checkbox" class="card-checkbox" name="card-main" id="service-<?php echo $service['id'] ?>">
                        <label class="btn-border01" for="service-<?php echo $service['id'] ?>">
                            <div class="iq-card iq-card-border iq-fancy-design gap-1">
                                <!-- <div class="d-flex align-items-center justify-content-center">
                                    <div class="avatar-70 avatar icon-img">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="34" viewBox="0 0 35 36" fill="currentColor">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.97059 0.0607292C4.10649 0.551412 1.01237 3.40593 0.154222 7.27193C-0.0378851 8.13718 -0.0519589 9.64582 0.120093 10.9311C1.22221 19.1646 3.96386 27.1131 8.16411 34.2519C8.87547 35.4609 9.00515 35.626 9.38958 35.8122C9.84691 36.0335 10.516 35.9771 10.8745 35.687C11.2953 35.3464 11.3693 35.1875 11.703 33.9066C12.4324 31.1067 12.8832 29.8779 13.709 28.4384C14.3626 27.299 14.841 26.641 15.8059 25.5544C16.621 24.6364 16.8903 24.6275 17.7766 25.4891C18.9637 26.6433 20.0945 28.1991 20.8259 29.6844C21.4302 30.9117 21.7175 31.7257 22.2991 33.8587C22.4555 34.4323 22.6265 35.0023 22.6792 35.1254C23.0622 36.0208 24.2754 36.2921 24.9828 35.6406C25.4973 35.1666 27.7529 31.0504 28.9488 28.4032C31.6039 22.5252 33.5289 15.5729 34.0545 9.96354C34.4135 6.13272 32.3572 2.52336 28.8772 0.875601C27.2892 0.123709 25.4615 -0.1603 23.8036 0.0873287C22.8039 0.236581 19.5154 0.967996 16.6433 1.67985C15.3991 1.9882 14.3288 2.24055 14.265 2.24055C14.1149 2.24055 13.0707 1.67098 12.2631 1.14856C10.7248 0.153335 10.4526 0.0493998 9.3076 0.0199153C8.84317 0.00795255 8.24151 0.0263188 7.97059 0.0607292ZM10.3196 1.66845C10.4892 1.75761 11.0117 2.07694 11.4807 2.37805C13.6097 3.74482 15.0947 4.31826 17.213 4.59143C18.8545 4.80317 22.036 4.69495 22.5021 4.4115C22.8979 4.17077 22.9628 3.70197 22.6489 3.35069C22.4072 3.08012 22.4115 3.0804 20.4934 3.20826C19.572 3.26969 18.9455 3.27314 18.209 3.22086C17.2621 3.15358 17.0854 3.12621 17.1596 3.05809C17.2019 3.01918 21.8707 1.9343 22.9944 1.70223C24.2584 1.44116 25.865 1.42406 26.7763 1.6619C29.0414 2.25314 30.7741 3.62133 31.8119 5.6381C32.6027 7.17496 32.7916 8.6242 32.4929 10.8607C31.6123 17.4539 29.6137 23.9137 26.58 29.9723C25.6238 31.8817 24.0498 34.6473 24.0024 34.5011C23.9904 34.4644 23.7732 33.706 23.5196 32.8158C22.5256 29.3269 21.4898 27.3901 19.3482 25.0157C18.1617 23.7004 17.6877 23.406 16.7538 23.4046C16.0471 23.4036 15.5608 23.6748 14.8189 24.4836C13.2328 26.2128 12.0281 28.1616 11.256 30.2474C11.0228 30.877 10.4968 32.7143 10.1717 34.0339C10.1211 34.2394 10.0537 34.4136 10.0219 34.421C9.99018 34.4283 9.70342 33.991 9.38472 33.4492C5.35941 26.6056 2.66231 18.8254 1.59939 10.9913C1.23157 8.28038 1.56948 6.5301 2.80727 4.734C3.33391 3.96993 4.47973 2.89589 5.13669 2.55052C5.32585 2.45116 5.32817 2.4525 5.29446 2.64418C5.18454 3.27012 4.83917 6.06524 4.83917 6.32863C4.83917 6.66485 4.96478 6.90453 5.2096 7.03556C5.29319 7.08031 5.89555 7.22006 6.54808 7.34616C7.20069 7.47227 7.75259 7.59344 7.77468 7.61554C7.79671 7.63756 7.7331 8.38784 7.63317 9.28272C7.52192 10.2796 7.47456 10.9685 7.5108 11.0612C7.59405 11.2742 7.85596 11.5041 9.40372 12.722C11.4859 14.3605 11.3586 14.2736 11.6758 14.2736C12.1981 14.2736 12.5475 13.6837 12.2995 13.2204C12.2615 13.1494 11.5016 12.5155 10.6107 11.8117C9.71982 11.1079 8.99024 10.4952 8.98939 10.4501C8.98855 10.405 9.07412 9.57285 9.1796 8.6007C9.43258 6.26839 9.53166 6.41941 7.4963 6.0354C6.38018 5.82486 6.32009 5.8053 6.35175 5.66294C6.37019 5.58033 6.48187 4.76855 6.60002 3.85903C6.71809 2.94951 6.83265 2.10389 6.85454 1.97983C6.89261 1.76394 6.91576 1.74979 7.39729 1.64769C8.19028 1.47958 8.20485 1.47824 9.13168 1.49267C9.89799 1.50456 10.051 1.52715 10.3196 1.66845Z" fill="currentColor"></path>
                                        </svg>   
                                    </div>
                                </div> -->
                                <div class="d-flex flex-column gap-05"> 
                                    <h6>
                                    <?php echo $service['name'] ?><br />
                                    <?php echo $service['service_type'] ?>
                                        <!-- {{name}} -->
                                    </h6>
                                    <p class="iq-dentist-price">
                                    <?php echo $service['charges'] ?>
                                        <!-- ${{price}} -->
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>
                <?php   
                            }
                      }
                    }else{
                        ?>
                            <p>No Service Avaliable</p>
                        <?php
                    }
                ?>
                    <!-- {{> components/category/dentist-card id="001" name="Tooth Cavity" price="50.00" }}
                    {{> components/category/dentist-card id="002" name="Broken Tooth" price="75.00" }}
                    {{> components/category/dentist-card id="003" name="Tooth Cleaning" price="80.00" }}
                    {{> components/category/dentist-card id="004" name="Tooth Crown" price="20.00" }} -->
                </div>
            </div>
            <!-- <div class="d-flex flex-column gap-1">
                <h6 class="iq-text-uppercase iq-color-secondary iq-letter-spacing-1">Eye Care</h6>
                <div class="iq-category-list"> -->
                    <!-- {{> components/category/dentist-card id="01" name="Tooth Cavity" price="50.00" }}
                    {{> components/category/dentist-card id="02" name="Broken Tooth" price="75.00" }}
                    {{> components/category/dentist-card id="03" name="Tooth Cleaning" price="80.00" }}
                    {{> components/category/dentist-card id="04" name="Tooth Crown" price="20.00" }} -->
                <!-- </div>
            </div> -->
        </div>
        <div class="card-footer">
            <button type="close" class="iq-button iq-button-secondary iq-text-uppercase" data-step="prev">Back</button>
            <button type="submit" class="iq-button iq-button-primary iq-text-uppercase" data-step="next">Next</button>
        </div>
    </form>
</div>