<?php 
include_once 'auth/session.php';
include_once 'includes/header.php';
?>

<head>
    <title>Meet Our Team</title>
    <style>
        
        .team-section {
            text-align: center;
            padding: 2rem;
        }
        .team-section h1 {
            margin-bottom: 2rem;
        }
        .team-container {
            display: flex;
            max-width: calc(300px * 3 + 2rem * 2);
            margin: 0 auto;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
        }
        .member-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 250px;
            padding: 1rem;
            text-align: center;
            transition: 
                transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                border 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            border-radius: 16px;
        }
        .member-card img {
            width: 100%;
            border-radius: 15%;
            height: auto;
            max-width: 100%;
        }
        .member-card h3 {
            margin: 1rem 0 0.5rem 0;
        }
        .member-card p.role {
            color: #555;
            font-style: italic;
            margin-bottom: 0.5rem;
        }
        .member-card:hover {
            transform: translateY(-10px) scale(1.05);
            border: 2px solid rgba(190, 159, 178, 1); /* Indigo border */
            box-shadow: 0 10px 25px rgba(92, 65, 90, 0.4);
        }
    </style>
</head>

<div class="team-section">
    <h1>Meet Our Team</h1>
    <div class="team-container">
        <div class="member-card">
            <img src="images/team/person5.svg" alt="Thinula Harischandra">
            <h3>Thinula Harischandra</h3>
            <p class="role">Team Leader & Developer</p>
            <p>S17478</p>
            
            <a href="http://www.linkedin.com/in/thinula-harischandra" target="_blank">
                <img src="images\team\linkedin_logo.jpg" alt="LinkedIn" style="width:60px; height:60px; border-radius:8px;">
            </a>

        </div>

        <div class="member-card">
            <img src="images\team\sasmika.jpg" alt="Sasmika Gunasekara">
            <h3>Sasmika Gunawardhana</h3>
            <p class="role">Developer</p>
            <p>S17474</p>
            <a href="https://www.linkedin.com/in/sasmika-deshan-64a363311" target="_blank">
                <img src="images\team\linkedin_logo.jpg" alt="LinkedIn" style="width:60px; height:60px; border-radius:8px;">
            </a>
            
        </div>

        <div class="member-card">
            <img src="images/team/person3.jpg" alt="Rasini Hansika">
            <h3>Rasini Hansika</h3>
            <p class="role">Developer</p>
            <p>S17476</p>
            <a href="https://www.facebook.com/profile.php?id=100090503982035" target="_blank">
                <img src="images/team/fb_logo.svg" alt="Facebook" style="width:40px; height:40px; border-radius:8px;">
            </a>
            
        </div>

        <div class="member-card">
            <img src="images/team/person4.svg" alt="Devindi Hansani">
            <h3>Devindi Hansani</h3>
            <p class="role">Developer</p>
            <p>S17475</p>
            <a href="https://www.linkedin.com/in/hansani-senarath-772191383" target="_blank">
                <img src="images\team\linkedin_logo.jpg" alt="LinkedIn" style="width:60px; height:60px; border-radius:8px;">
            </a>
            
        </div>

        <div class="member-card">
            <img src="images/team/person1.svg" alt="Nilakshi Gunasena">
            <h3>Nilakshi Gunasena</h3>
            <p class="role">Developer</p>
            <p>S17473</p>
            <a href="https://www.facebook.com/share/19b5r5UMdf/" target="_blank">
                <img src="images/team/fb_logo.svg" alt="Facebook" style="width:40px; height:40px; border-radius:8px;">
            </a>
            
        </div>

    </div>
</div>


<?php include_once 'includes/footer.php'; ?>