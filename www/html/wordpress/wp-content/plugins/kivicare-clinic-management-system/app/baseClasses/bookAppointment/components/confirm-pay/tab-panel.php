<?php

?>

<div id="confirm-pay" class="iq-fade iq-tab-pannel">         
    <form action="#confirmed" data-prev="#confirm">
        <h4>Confirmation Detail</h4>
        <hr>
        <div class="card-list">
            <div>
                <h6 class="iq-text-uppercase iq-color-secondary iq-letter-spacing-1">Payment method</h6>
                <div class="iq-card iq-bg-primary-light card-list mt-3">
                    <div class="iq-client-widget">
                        <input type="checkbox" class="card-checkbox" name="card-main" id="payment-001">
                        <label class="btn-border01" for="payment-001">
                            <div class="iq-card iq-fancy-design iq-bg-white iq-card-border iq-btn-lg text-center">
                                PayPal
                            </div>
                        </label>
                    </div>
                    <div class="iq-client-widget">
                        <input type="checkbox" class="card-checkbox" name="card-main" id="payment-002">
                        <label class="btn-border01" for="payment-002">
                            <div class="iq-card iq-fancy-design iq-bg-white iq-card-border iq-btn-lg text-center">
                                Pay Later
                            </div>
                        </label>
                    </div>
                    <div class="iq-client-widget">
                        <input type="checkbox" class="card-checkbox" name="card-main" id="payment-003">
                        <label class="btn-border01" for="payment-003">
                            <div class="iq-card iq-fancy-design iq-bg-white iq-card-border iq-btn-lg text-center">
                                VISA
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="iq-payment-detail">
                <h6 class="iq-text-uppercase iq-color-secondary iq-letter-spacing-1">Appointment summary</h6>
                <div class="iq-card iq-card-border mt-3">
                    <h6>Services</h6>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <p>Tooth Cleaning :</p>
                        <h6>$25.99</h6>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <p>Root Canel :</p>
                        <h6>$30.89</h6>
                    </div>
                    <hr>
                    <h6>Additional Charges</h6>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <p>Paypal Charge :</p>
                        <h6>$25</h6>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <p>GST :</p>
                        <h6>10%</h6>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <p>SCST :</p>
                        <h6>5%</h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Total Price</h6>
                        <h5 class="iq-color-primary">$12.58</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="close" class="iq-button iq-button-secondary iq-text-uppercase" data-step="prev">Back</button>
            <button type="submit" class="iq-button iq-button-primary iq-text-uppercase" data-step="next">Pay</button>
        </div>
    </form>
</div>
