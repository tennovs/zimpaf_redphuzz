<?php

?>
    <div class="kivi-widget">
        <div class="container-fluid">
            <div class="widget-layout">
                <div class="iq-card iq-card-sm iq-bg-primary widget-tabs">
                    <ul class="tab-list">
                        <?php

                            require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/clinic/tab.php';
                            require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/doctor/tab.php';
                            require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/category/tab.php';
                            require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/date-time/tab.php';
                            require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/detail-info/tab.php';
                            require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/confirm/tab.php';

                        ?>
                    </ul>
                </div>
                <div class="widget-pannel">
                    <div class="iq-card iq-card-sm tab-content" id="wizard-tab">
                    <?php

                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/clinic/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/doctor/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/category/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/date-time/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/detail-info/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/confirm/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/confirm-pay/tab-panel.php';
                                require KIVI_CARE_DIR . 'app/baseClasses/bookAppointment/components/confirmed.php';

                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>


<script>
    
</script>