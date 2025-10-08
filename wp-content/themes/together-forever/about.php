<?php
/**
 * Template Name: About
 *
 * @package Together_Forever
 */

get_header(); ?>

<main class="main">
    <!-- Tabs Navigation -->
    <section class="about-tabs-nav">
        <div class="container">
            <div class="tabs-navigation">
                <button class="btn btn-primary tab-button active" data-tab="about">
                    About
                </button>
                <button class="btn btn-secondary tab-button" data-tab="history">
                    History
                </button>
                <button class="btn btn-secondary tab-button" data-tab="articles">
                    Articles
                </button>
                <button class="btn btn-secondary tab-button" data-tab="team">
                    Team
                </button>
                <button class="btn btn-secondary tab-button" data-tab="documents">
                    Documents
                </button>
                <button class="btn btn-secondary tab-button" data-tab="clinics">
                    Clinics
                </button>
                <button class="btn btn-secondary tab-button" data-tab="partners">
                    Partners
                </button>
                <button class="btn btn-secondary tab-button" data-tab="reports">
                    Reports
                </button>
            </div>
        </div>
    </section>

    <!-- Tabs Content -->
    <div class="tabs-content">
        <!-- About Tab -->
        <div class="tab-panel active" id="about">
            <!-- Quote Section with Founder -->
            <section class="quote-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="round-photo-gradient">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Sergey.png" alt="Sergey Stopnevich">
                    </div>
                </div>
                <div class="quote col-12 col-lg-8">
                    <p>
                        <span class="colored">Charity is not self-fulfilment</span> Neither is it avoiding problems, or shifting problem solving to others. Rather, it is a very strong sense of personal responsibility. Everyone understands, what charity means for them and for whom they are ready to donate, in their own way. 
                        <br><span class="colored">In our case - we raise funds for children who need our help.</span> 
                        <br>This help could not be provided by their parents, friends and most doctors. They have only one chance - a high-tech neurosurgery operation performed by professionals with the use of the best materials and modern equipment.
                    </p>
                    <p>
                        <span class="colored">The activities of the Foundation are aimed at saving children who have brain or spinal cord diseases and their treatment in clinics in Israel, Germany, Spain and Cyprus.</span>
                        <br>Charity is not about how much a person donates, but how a person does it and what emotions are invested in this action.
                        <br>No matter how much you've donated insofar as possible, - a lot or little, - your desire and understanding are what truly matters.
                    </p>
                    <p>
                        If you are reading these lines, it means you have taken the first steps towards good deeds. You are on the website of the Charitable Foundation for Children, because you care about the fate of little patients. Take the next step - support our difficult, but incredibly necessary and important work!
                    </p>
                    <p class="colored">
                        Every saved life is priceless !!
                        <br>Make a difference together!
                    </p>
                    <p class="quote-author">Founder of Together Forever Sergey Stopnevich</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Foundation Info Section with Elephant Family -->
    <section class="dotted-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-5">
                    <img src="https://via.placeholder.com/500x400/f8f6ff/667eea?text=Elephant+Family" alt="Elephant Family">
                </div>
                <div class="col-12 col-lg-7 elephant-family-text">
                    <p class="fz-18">
                        <strong>Together Forever Foundation was created in October 2016 in Cyprus with the purpose of helping children by providing them with medical assistance in the EU and Israel</strong>
                    </p>
                    <p>The foundation brings together all those who care and are willing to support lifesaving efforts</p>
                    <p>We are a private charitable foundation</p>
                    <p>We give children hope of recovery, a chance for a healthier and happier life</p>
                    <div class="mt-3 mt-md-5 text-center text-md-left">
                        <a class="btn btn-rounded btn-purple-custom" href="#contact">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Goals Section -->
    <section class="our-goals">
        <div class="container">
            <h3 class="section-title">Our goals</h3>
            <ul class="goals-list">
                <li class="goals-item">
                    Our mission: <span class="extra">is to save lives of children</span> affected by grave disorders regardless of the payment ability of the child's family or the economic situation in the region
                </li>
                <li class="goals-item">
                    We help children suffering <span class="extra">from neurological disorders</span>
                </li>
                <li class="goals-item">
                    According to the WHO*, up to 70% neurologic cases <span class="extra">are curable</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Organization Tasks Section -->
    <section class="organizations-tasks">
        <div class="container">
            <div class="section-title text-white">Our objectives</div>
            <div class="row task-list">
                <div class="col-12 col-sm-6 col-xl-3 task-item">
                    <div class="task-img">
                        <img src="https://via.placeholder.com/50x50/ffffff/667eea?text=📋" alt="Organize">
                    </div>
                    <div class="task-content text-white">
                        <strong>To organize</strong> medical examination, treatment, transportation, rehabilitation, legal support and psychological services for children and their families in the best clinics of the world
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 task-item">
                    <div class="task-img">
                        <img src="https://via.placeholder.com/50x50/ffffff/667eea?text=💰" alt="Raise Funds">
                    </div>
                    <div class="task-content text-white">
                        <strong>To raise funds</strong> and resources so that children suffering from neurological disorders have access to world-class neurosurgical care
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 task-item">
                    <div class="task-img">
                        <img src="https://via.placeholder.com/50x50/ffffff/667eea?text=📢" alt="Raise Awareness">
                    </div>
                    <div class="task-content text-white">
                        <strong>To raise</strong> awareness and promote charitable mentality
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 task-item">
                    <div class="task-img">
                        <img src="https://via.placeholder.com/50x50/ffffff/667eea?text=🤝" class="custom" alt="Involve Business">
                    </div>
                    <div class="task-content text-white">
                        <strong>To involve</strong> large businesses in charity programmes
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>

        <!-- History Tab -->
        <div class="tab-panel" id="history">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">History</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Articles Tab -->
        <div class="tab-panel" id="articles">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">Articles</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Team Tab -->
        <div class="tab-panel" id="team">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">Team</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Documents Tab -->
        <div class="tab-panel" id="documents">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">Documents</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Clinics Tab -->
        <div class="tab-panel" id="clinics">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">Clinics</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Partners Tab -->
        <div class="tab-panel" id="partners">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">Partners</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Reports Tab -->
        <div class="tab-panel" id="reports">
            <section class="empty-tab-section">
                <div class="container">
                    <div class="empty-content">
                        <h2 class="empty-title">Reports</h2>
                        <p class="empty-description">Content coming soon...</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class and btn-primary from all buttons, add btn-secondary
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-secondary');
            });
            
            // Remove active class from all panels
            tabPanels.forEach(panel => panel.classList.remove('active'));
            
            // Add active class, btn-primary, and remove btn-secondary from clicked button
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-secondary');
            
            // Add active class to corresponding panel
            document.getElementById(targetTab).classList.add('active');
        });
    });
});
</script>

<?php
get_footer();
?>
