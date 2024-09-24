<footer>
@if (is_object($wa) && isset($wa->No_wa) && !empty($wa->No_wa))
    <a href="https://api.whatsapp.com/send?phone={{ $wa->No_wa }}&text={{ urlencode($wa->Message_wa ?? '') }}" class="whatsapp-float" target="_blank" aria-label="Chat with us on WhatsApp" id="whatsappButton">
        <i class="fa-brands fa-whatsapp fa-3x" style="color: #f5f5f5;"></i>
    </a>
@endif


    <div class="containers">
        <div class="footer-content">
            <h3>Contact</h3>
            <p>Email: {{ $contact->email ?? '-' }}</p>
            <p>Phone (main): {{ is_object($contact) && isset($contact->phone) ? '+62' . $contact->phone : '-'  }}</p>
            <p>Phone (Second): {{ is_object($contact) && isset($contact->phones) ? '+62' . $contact->phones : '-' }}</p>
        </div>
        <div class="footer-content">
            <h3>Quick Links</h3>
            <ul class="list">
                <li>
                    <a class="{{ Route::is('PTGes') ? 'active' : '' }}" href="/" style="text-decoration:none;">Home</a>
                </li>
                <li>
                    <a class="{{ Route::is('About') ? 'active' : '' }}" href="/About" style="text-decoration:none;">About Us</a>
                </li>
                <li>
                    <a class="{{ Route::is('Why') ? 'active' : '' }}" href="/Why" style="text-decoration:none;">Why Us</a>
                </li>
                <li>
                    <a class="{{ Route::is('Tracking') ? 'active' : '' }}" href="/Tracking" style="text-decoration:none;">Tracking</a>
                </li>
                @if (!Route::is('About') && !Route::is('Why') && !Route::is('Tracking') && !Route::is('Slide'))
                <li>
                    <a class="{{ request()->is('Services') ? 'active' : '' }}" href="#Services" style="text-decoration:none;">Service</a>
                </li>
                @endif
            </ul>
        </div>
        <div class="footer-content">
            <h3>Follow Us</h3>
            <ul class="social-icons">
                <li><a href=""><i class="fab fa-facebook"></i></a></li>
                <li><a href=""><i class="fab fa-instagram"></i></a></li>
                <li><a href=""><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="bottom-bar">
        <p>&copy; 2024 PT Ges . All rights reserved</p>
    </div>
</footer>
