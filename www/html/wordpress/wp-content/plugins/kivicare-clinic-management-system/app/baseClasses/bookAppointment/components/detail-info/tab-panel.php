<?php

?>

<div id="detail-info" class="iq-fade iq-tab-pannel">
    <div class="d-flex align-items-center justify-content-between">
        <h4>Enter Detail Information</h4>
    </div>
    <hr>
    <ul class="nav-tabs">
        <li class="tab-item active">
            <a href="#register" class="tab-link" id="register-tab" data-iq-toggle="tab">New register</a>
        </li>
        <li class="tab-item">
            <a href="#login" class="tab-link" id="login-tab" data-iq-toggle="tab">Login</a>
        </li>
    </ul>
    <form action="#confirm" id="form" data-prev="#date-time">
        <div id="login-register-panel" class="tab-content">
            <div id="register" class="iq-tab-pannel iq-fade active" data-button-title="Signup">
                <div class="d-grid grid-template-2">
                    <div class="form-group">
                        <label for="first-name">First Name <span>*</span></label>
                        <input type="text" class="iq-form-control" id="first-name" placeholder="Enter your first name">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name <span>*</span></label>
                        <input type="text" class="iq-form-control" id="last-name" placeholder="Enter your first name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span>*</span></label>
                        <input type="email" class="iq-form-control" id="email" placeholder="Enter your email">
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact <span>*</span></label>
                        <input type="tel" class="iq-form-control" id="contact" placeholder="Enter your contact number">
                    </div>
                </div>
            </div>
            <div id="login" class="iq-tab-pannel iq-fade" data-button-title="Login">
                <div class="d-grid grid-template-2">
                    <div class="form-group">
                        <label for="first-name">Username or Email <span>*</span></label>
                        <input type="text" class="iq-form-control" id="first-name" placeholder="Enter your username or email">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Password <span>*</span></label>
                        <input type="password" class="iq-form-control" id="last-name" placeholder="***********">
                        <div class="d-flex justify-content-end mt-2">
                            <a href="#" class="iq-color-secondary"><i>Forgot Password ?</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="iq-button iq-button-secondary" data-step="prev">BACK</button>
            <button type="submit" name="submit" data-step="next" value="signup" id="submit-button" class="iq-button iq-button-primary">Signup</button>
        </div>
    </form>
</div>
