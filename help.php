<?php
// help.php
include_once 'auth/session.php';
include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin: 2rem 0;">
        <h1>Help & Support</h1>
        <p>Find answers to common questions and learn how to use the University Hall Booking System</p>
    </div>
    
    <div style="display: grid; gap: 2rem; margin: 2rem 0;">
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>🔍 How to Search for Halls</h3>
            <p>Use our search feature to find the perfect hall:</p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li>Enter hall name, location, or keywords in the search box</li>
                <li>Filter by minimum capacity to find halls that fit your group size</li>
                <li>Select a date to check availability</li>
                <li>Browse all available halls on the "Browse Halls" page</li>
            </ul>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>📅 Making a Booking</h3>
            <p>Follow these steps to book a hall:</p>
            <ol style="margin: 1rem 0; padding-left: 2rem;">
                <li>Sign in to your account (or create one if you're new)</li>
                <li>Find a hall that meets your requirements</li>
                <li>Click "Book Now" on the hall card</li>
                <li>Select your preferred date and time</li>
                <li>Provide details about your event purpose</li>
                <li>Review the cost and submit your request</li>
                <li>Wait for admin approval</li>
            </ol>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>📋 Managing Your Bookings</h3>
            <p>Keep track of your reservations:</p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li>Visit "My Bookings" to see all your reservations</li>
                <li>Check the status: Pending, Confirmed, or Cancelled</li>
                <li>View booking details including date, time, and cost</li>
                <li>Contact admin if you need to modify or cancel a booking</li>
            </ul>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>💰 Pricing & Payment</h3>
            <p>Understanding our pricing structure:</p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li>Each hall has an hourly rate displayed on its card</li>
                <li>Total cost is calculated based on booking duration</li>
                <li>Prices may vary based on hall size and amenities</li>
                <li>Payment arrangements will be confirmed after booking approval</li>
            </ul>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>🏛️ Available Amenities</h3>
            <p>Our halls come with various amenities:</p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li><strong>Audio/Visual:</strong> Projectors, sound systems, microphones</li>
                <li><strong>Technology:</strong> WiFi, power outlets, screens</li>
                <li><strong>Comfort:</strong> Air conditioning, heating, comfortable seating</li>
                <li><strong>Accessibility:</strong> Wheelchair access, accessible parking</li>
                <li><strong>Support:</strong> Whiteboards, flipcharts, podiums</li>
            </ul>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>📞 Contact Support</h3>
            <p>Need additional help?</p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li><strong>Email:</strong> halls@university.edu</li>
                <li><strong>Phone:</strong> (555) 123-4567</li>
                <li><strong>Office Hours:</strong> Monday-Friday, 8:00 AM - 5:00 PM</li>
                <li><strong>Location:</strong> Administration Building, Room 201</li>
            </ul>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>❓ Frequently Asked Questions</h3>
            
            <div style="margin: 1rem 0;">
                <h4>How far in advance can I book a hall?</h4>
                <p>You can book halls up to 6 months in advance. We recommend booking at least 2 weeks ahead for popular dates.</p>
            </div>
            
            <div style="margin: 1rem 0;">
                <h4>Can I cancel or modify my booking?</h4>
                <p>Yes, contact the admin team to cancel or modify bookings. Cancellations made 48 hours in advance may receive a full refund.</p>
            </div>
            
            <div style="margin: 1rem 0;">
                <h4>What if I need technical support during my event?</h4>
                <p>Technical support can be arranged for an additional fee. Contact us when making your booking to arrange this service.</p>
            </div>
            
            <div style="margin: 1rem 0;">
                <h4>Are there any restrictions on event types?</h4>
                <p>Most academic and professional events are welcome. Commercial events may require special approval and different pricing.</p>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
