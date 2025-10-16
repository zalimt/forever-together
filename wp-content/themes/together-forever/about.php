<?php
/**
 * Template Name: About
 *
 * @package Together_Forever
 */

get_header(); ?>

<main class="main">
    <article class="about-page-content">
        <section class="about-page-section" style="max-width: 1400px; padding: 0 20px; margin: 0 auto;">
            <!-- Tabs Navigation -->
            <div class="about-tabs-nav">
        <div class="container">
            <div class="tabs-navigation">
                <button class="btn btn-primary tab-button active" data-tab="history">
                    History
                </button>
                <button class="btn btn-secondary tab-button" data-tab="team">
                    Team
                </button>
                <button class="btn btn-secondary tab-button" data-tab="reports">
                    Reports
                </button>
                <button class="btn btn-secondary tab-button" data-tab="partners">
                    Partners
                </button>
            </div>
        </div>
    </section>

    <!-- Tabs Content -->
    <div class="tabs-content">
        <!-- History Tab -->
        <div class="tab-panel active" id="history">
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

    <!-- History of Foundation Section -->
    <section class="quote-section">
        <div class="container">
            <div class="section-title mb-5">HISTORY OF THE CHILDREN'S CHARITABLE FOUNDATION TOGETHER FOREVER</div>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="round-photo-gradient">
                        <a href="<?php echo get_stylesheet_directory_uri(); ?>/images/history-1.jpeg" target="_blank">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/history-1.jpeg" alt="History of Together Forever">
                        </a>
                    </div>
                </div>
                <div class="quote col-12 col-lg-8">
                    <p>Since 2016, the <span class="colored">"Together Forever Charitable Foundation"</span> has been doing everything possible to ensure that children with rare neurological diseases of the spinal cord and brain receive the treatment they need in time. This non-profit organization gives children access to quality medical care and the opportunity to undergo treatment outside their native country in one of the best clinics in Europe, Israel and the United States. These clinics are equipped with state-of-the-art technology and staffed by highly specialized professionals who treat our wards, helping them return to a full life — or discover it for the first time.
                    </p>                   
                    <p>The history of the <span class="colored">"Together Forever Charitable Foundation"</span> started long before its creation. It began with the birth of my second son, Alexander. It was after his birth that an irresistible desire to help children emerged inside of me. I decided to support the Russian charitable foundation "Line of Life", where I soon met the head of private programs - Irina Ryabushkina, a person who later played a very important role in my charitable story. Irina told me whom and how the foundation helps and got me involved in the world of charity so much that I wanted to make maximum effort to help people inside this foundation. My earnings then included a bonus program, according to which additional payments were made twice a year. For five years, with every bonus, I paid for a child's heart surgery and as a result helped the fund save 12 children, whom we gave a new life.
                    </p>    
                    <p>Helping children inspired me a lot. I wanted to do much more, but I didn't know how. My acquaintance with Irina Ryabushkina that took place later, in 2015, gave me the answers to all my questions and helped me reconsider my charity work. I realized that large Russian foundations provide children with help only within the country, but not abroad in high-tech clinics with up-to-day equipment. And the children with brain and spinal cord diseases simply do not have a chance to get there with the help of foundations. At that moment it occurred to me to create my own foundation, thanks to which the children will get this chance.</p>      
                    <p>I have six children: two sons and four daughters. For me, as for any parent, the most important thing is - their health. At the end of 2015, I had to go through a challenging test. My daughter Masha was in need of an urgent brain surgery. We managed to find a clinic in Germany where doctors performed two complex neurosurgical operations on her and brought her back to normal life. Having overcome all the stages of this difficult path, I understood exactly how parents of children in critical conditions feel. At that moment, I made a final decision to create a charitable foundation, in which children living in Russia, Ukraine, Kazakhstan, Belarus and any other countries of the post-Soviet space could turn for help.</p>      
                    <p>I was not a top manager, nor did I have a media personality. I knew that it would be challenging, but I had the most important thing - people who were ready to support my initiative. The most crucial person amongst them was Irina Ryabushkina. I will always be forever grateful for her priceless input into the creation of the fund. It was Irina together with her team who in September 2016 launched and created the structure of the <span class="colored">Together Forever</span> Foundation. My business partners in Cyprus helped me to register it. I took over the entire legal and organizational structure.</p>      
                    <p>The process of building a non-profit organization turned out to be more complicated than I had expected. It was so time-consuming, that at a certain point I even had a dilemma: to continue doing it by myself or pass it to my family that was already involved in all the activities of the foundation. However, given the complex specifics of managing a fund, accepting applications, attracting donors and paying for operations - I could not do it. Therefore, having made every effort, I continued to do what I started. Eventually, I had a team of experts who were helping me in all matters.</p>      
                    <p>Maxim Martynchik was one of the first people to contribute to the development of the <span class="colored">Together Forever</span> foundation. He gifted us our logo which consisted of the image of three elephants and we are very thankful to him for this. When no one knew about our elephants, it was very difficult for us to gain trust. Despite the fact that the work of the foundation is crystal clear and all funds received on its account are directed solely to pay for the treatment of children, it took us a long time for people to start helping the younger generation along with us. I often told my friends, colleagues, business partners and even just acquaintances about how and to whom we help. But only two years after its establishment, the <span class="colored">Together Forever</span> foundation entered a new stage of development - it began to make friends and form a community of like-minded people. People began to trust us. Until that moment, I paid for the first three operations for our wards from my own funds.</p>
                    <a target="_blank" href="<?php echo get_stylesheet_directory_uri(); ?>/images/history-2.jpeg">
                        <img class="inner-image mb-4 mt-4" src="<?php echo get_stylesheet_directory_uri(); ?>/images/history-2.jpeg" alt="Together Forever History">
                    </a>
                    <p>Gradually, there were more and more people who wanted to change the lives of children for the better. Along with their number, the possibilities of the fund grew as well and over time, there appeared people whom I could delegate the management of the <span class="colored">Together Forever</span> foundation, leaving key decisions to myself.</p>       
                    <p>Together with the team we regularly participate and hold our charity events, during which we have the opportunity to communicate face-to-face with those who, similar to us, feel the urge to help others. Over several years of the foundation's existence, we have managed to form an entire community of kind and caring people who share our values and look in the same direction with us. We feel their strong, reliable shoulder in every fundrising, and there are no words to express how grateful we are to them for their trust and constant support. Together with them, we are one big family of like-minded people for whom there are no "other people's children".</p>       
                    <p>Thanks to our common efforts, we have already raised hundreds of thousands of euros, paid for dozens of the most complicated neurosurgical operations and given children a chance of living a healthy and fulfilling life.</p>       
                    <p>I sincerely hope that our name <span class="colored">Together Forever</span> which I gave to the foundation back in August 2016, and the friends, having joined us once, will stay with us forever and the three elephants on our logo will symbolize our friendship.</p>       
                    <p class="quote-author">Let's change the lives of children together!</p>             
                </div>
            </div>
        </div>
    </section>
        </div>

        <!-- Team Tab -->
        <div class="tab-panel" id="team">
            <section class="team-section">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="team-item team-item--main wow animate fadeInUp" style="visibility: visible; animation-delay: 0ms; animation-name: fadeInUp;" data-wow-delay="0ms">
                                <div class="team-photo">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Sergey_4.jpg" alt="Sergey Stopnevich">
                                </div>
                                <div class="team-content">
                                    <h2 class="team-content--name">Sergey Stopnevich</h2>
                                    <div class="team-content--position">Founder of Together Forever Foundation</div>
                                    <div class="team-content--text">Sergey is a businessman, investor, philanthropist and founder of the Together Forever Foundation. He is not just a leader but the true captain of our ship of kindness. Sergey sets the strategic direction for the foundation and makes key decisions, guiding us toward new goals and achievements. His energy and vision inspire the entire team to move forward.</div>
                                    <div class="social-icons">
                                        <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                        <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="team-item team-item--main wow animate fadeInUp" style="visibility: visible; animation-delay: 0ms; animation-name: fadeInUp;" data-wow-delay="0ms">
                                <div class="team-photo">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Nastia_1.jpg" alt="Anastasia Stopnevich">
                                </div>
                                <div class="team-content">
                                    <h2 class="team-content--name">Anastasia Stopnevich</h2>
                                    <div class="team-content--position">Member of the Board of Directors of the Together Forever Foundation</div>
                                    <div class="team-content--text">Since the moment she first became acquainted with the foundation, Anastasia has been a passionate supporter of its mission. She actively engages her friends and partners in the cause, bringing energy and enthusiasm to every initiative. Her innovative ideas and proactive approach continue to drive the foundation's growth and progress.</div>
                                    <div class="social-icons">
                                        <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                        <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="team-list">
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 300ms; animation-name: fadeInUp;" data-wow-delay="300ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1010.PNG" alt="Ekaterina Balandina">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Ekaterina Balandina</div>
                                        <div class="team-content--position">Director of the foundation</div>
                                        <div class="team-content--text">Ekaterina truly believes that even the smallest act of kindness can change someone's life for the better. She builds strong connections with partners, communicates with the parents of our beneficiaries and clinics, actively participates in organizing our charity events, and ensures that our projects deliver a meaningful impact.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 600ms; animation-name: fadeInUp;" data-wow-delay="600ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1021.PNG" alt="Elena Duschenko">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Elena Duschenko</div>
                                        <div class="team-content--position">Member of the Board of Directors of the Together Forever Foundation</div>
                                        <div class="team-content--text">Elena Duschenko is a cornerstone of our foundation. Her professional legal expertise has been a vital contribution to the foundation's growth since its inception. Elena firmly believes that our mission is not just about providing help, but also showing parents that there are people ready to stand by their side and fight for a better future for their children.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 900ms; animation-name: fadeInUp;" data-wow-delay="900ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1023.PNG" alt="Andri Sokratous">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Andri Sokratous</div>
                                        <div class="team-content--position">Member of the Board of Directors of the Together Forever Foundation</div>
                                        <div class="team-content--text">Andri's mentor taught her that "we should always help others, no matter how small our contribution may be." As a Director at TFF, she feels she is carrying on this important legacy. For Andri, giving is a natural expression of who we are — it brings joy and fosters a deep sense of connection. She firmly believes that together, we can make a real difference and positively impact lives.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 1200ms; animation-name: fadeInUp;" data-wow-delay="1200ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1031.PNG" alt="Kseniia Stopnevich">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Kseniia Stopnevich</div>
                                        <div class="team-content--position">Member of the Board of Directors of the Together Forever Foundation</div>
                                        <div class="team-content--text">Ksenia sincerely believes that every child deserves a happy and unrestricted childhood. Inspired by the example of her parents, she does everything she can to help the Together Forever Foundation grow, evolve, and expand its support for those who need it most.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 1500ms; animation-name: fadeInUp;" data-wow-delay="1500ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1032.PNG" alt="Artem Stopnevich">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Artem Stopnevich</div>
                                        <div class="team-content--position">Board Member of the Together Forever Foundation</div>
                                        <div class="team-content--text">Artem began his journey with the foundation as a volunteer and has been actively participating in our charity events for the past nine years. Artem also dedicated his participation in marathons and triathlons to our beneficiaries helping raise funds for their treatment. Today, he combines this experience with his business expertise, engaging his partners in our mission and helping the foundation grow, evolve, and reach new horizons.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 1800ms; animation-name: fadeInUp;" data-wow-delay="1800ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1034.PNG" alt="Maiia Veliada">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Maiia Veliada</div>
                                        <div class="team-content--position">SMM-Specialist</div>
                                        <div class="team-content--text">Maya considers her mission in the foundation, together with the Together Forever team, to create as many touching stories as possible, where children find a chance for a healthy and happy future, and parents find faith in goodness and support.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="team-item wow animate fadeInUp" style="visibility: visible; animation-delay: 2100ms; animation-name: fadeInUp;" data-wow-delay="2100ms">
                                    <div class="team-photo">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Team-1037.PNG" alt="Evgeniia Kalashnikova">
                                    </div>
                                    <div class="team-content">
                                        <div class="team-content--name">Evgeniia Kalashnikova</div>
                                        <div class="team-content--position">Administrator</div>
                                        <div class="team-content--text">Evgeniia knows that behind every number, document, and letter lies someone's hope, and that each working day is an important step toward a happy and fulfilling life for our beneficiaries. She helps manage our paperwork and takes part in organizing the foundation's charity events.</div>
                                        <div class="social-icons">
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Reports Tab (Combined Documents & Reports) -->
        <div class="tab-panel" id="reports">
            <section class="reports-tab-section">
                <div class="container">
                    <h2 class="section-title">Reports</h2>
                    
                    <!-- Foundation Documents Section -->
                    <div class="reports-subsection">
                        <h3 class="subsection-title">Foundation Documents</h3>
                        <div class="empty-content">
                            <p class="empty-description">Content coming soon...</p>
                        </div>
                    </div>
                    
                    <!-- Audit Reports Section -->
                    <div class="reports-subsection">
                        <h3 class="subsection-title">Audit Reports</h3>
                        <div class="empty-content">
                            <p class="empty-description">Content coming soon...</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Partners Tab -->
        <div class="tab-panel" id="partners">
            <section class="partners-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 partners-item wow animate fadeInUp" style="visibility: visible; animation-delay: 0ms; animation-name: fadeInUp;" data-wow-delay="0ms">
                            <div class="partners-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/wwg.jpeg" alt="Wise Wolves Group">
                            </div>
                            <a href="https://wise-wolves.group/" target="_blank" class="partners-title">Wise Wolves Group</a>
                            <div class="partners-text">
                                Wise Wolves Group is a financial holding company operating in the EU, the UK and Switzerland. It has three core areas: Brokerage, Fiduciary Services and Payment Solutions. The range of services that group offers to the clients offering helps to provide an integrated approach to solving business problems of any complexity and orientation within the same group scope
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 partners-item wow animate fadeInUp" style="visibility: visible; animation-delay: 300ms; animation-name: fadeInUp;" data-wow-delay="300ms">
                            <div class="partners-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/aris_main.png" alt="Aris">
                            </div>
                            <a href="https://arisfc.com/" target="_blank" class="partners-title">Aris</a>
                            <div class="partners-text">
                                Aris - is a professional football club of Cyprus. It was founded in the 1930 and based in Limassol.
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 partners-item wow animate fadeInUp" style="visibility: visible; animation-delay: 600ms; animation-name: fadeInUp;" data-wow-delay="600ms">
                            <div class="partners-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/thumbnail_instamam_logo.png" alt="Instamam Cyprus">
                            </div>
                            <a href="https://www.instagram.com/instamam_cyprus/" target="_blank" class="partners-title">Instamam Cyprus</a>
                            <div class="partners-text">
                                Instamam Cyprus - a community for Russian speakers in Cyprus. In the online group you can find useful and interesting information about the island and its events, get recommendations, answers to your questions, find new friends, advertise your services, tell about yourself and participate in offline events of the group.

                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 partners-item wow animate fadeInUp" style="visibility: visible; animation-delay: 900ms; animation-name: fadeInUp;" data-wow-delay="900ms">
                            <div class="partners-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/russian_womens_club.jpeg" alt="The Russian Women's Club of Cyprus">
                            </div>
                            <a href="https://www.instagram.com/russianwomensclub_cyprus/" target="_blank" class="partners-title">The Russian Women's Club of Cyprus</a>
                            <div class="partners-text">
                                The Russian Women's Club of Cyprus is one of the largest communities on the island. It was founded for the purpose of communication, mutual support and unification of all Russian-speaking women living in Cyprus. Doesn't matter what age, social and material status you are, in this community you are always welcome.
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 partners-item wow animate fadeInUp" style="visibility: visible; animation-delay: 1200ms; animation-name: fadeInUp;" data-wow-delay="1200ms">
                            <div class="partners-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/miss.jpg" alt="Miss Air">
                            </div>
                            <a href="https://www.instagram.com/miss.airr/" target="_blank" class="partners-title">Miss Air</a>
                            <div class="partners-text">
                                Cultural events in Cyprus: performances, concerts, exhibitions, festivals, lectures
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 partners-item wow animate fadeInUp" style="visibility: visible; animation-delay: 1500ms; animation-name: fadeInUp;" data-wow-delay="1500ms">
                            <div class="partners-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easy_print.png" alt="Easy Print">
                            </div>
                            <a href="http://www.easyprint.ws/" target="_blank" class="partners-title">Easy Print</a>
                            <div class="partners-text">
                                Based in Limassol, Easy Print provides all kinds of services, from printing leaflets, calendars, banners, business cards, books and other promotional items to outdoor advertising. We turn to Easy Print whenever Together Forever Foundation needs promotional material.
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </div>
        </section>
    </article>
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
