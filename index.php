<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Portfolio - Expert Full Stack & Chef de Projet";
$current_page = 'home';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Portfolio professionnel - Expert en développement Full Stack et gestion de projet technique. Spécialisé dans la livraison de projets complets de A à Z.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section id="hero" class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="highlight">Expert Full Stack</span><br>
                        & Chef de Projet Technique
                    </h1>
                    <p class="hero-subtitle">
                        De l'analyse des besoins à la livraison finale, je pilote vos projets web 
                        avec une expertise technique pointue et une approche méthodologique rigoureuse.
                    </p>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Projets livrés</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">360°</span>
                            <span class="stat-label">Approche complète</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">A→Z</span>
                            <span class="stat-label">De l'idée au déploiement</span>
                        </div>
                    </div>
                    <div class="hero-actions">
                        <a href="#projects" class="btn btn-primary">Voir mes réalisations</a>
                        <a href="#contact" class="btn btn-outline">Me contacter</a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="tech-stack">
                        <div class="tech-category">
                            <h4>Frontend</h4>
                            <div class="tech-items">
                                <span class="tech-item">HTML5/CSS3</span>
                                <span class="tech-item">JavaScript ES6+</span>
                                <span class="tech-item">React</span>
                            </div>
                        </div>
                        <div class="tech-category">
                            <h4>Backend</h4>
                            <div class="tech-items">
                                <span class="tech-item">PHP</span>
                                <span class="tech-item">Node.js</span>
                                <span class="tech-item">MySQL</span>
                            </div>
                        </div>
                        <div class="tech-category">
                            <h4>Gestion</h4>
                            <div class="tech-items">
                                <span class="tech-item">Scrum/Agile</span>
                                <span class="tech-item">Cahier des charges</span>
                                <span class="tech-item">Roadmap</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="section-header">
                <h2>À propos</h2>
                <p>Une double expertise au service de vos projets</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    <h3>Un profil hybride unique : développeur ET chef de projet</h3>
                    <p>
                        Fort d'un parcours académique solide (BTS SIO SLAM, Licence Informatique, 
                        Master Expert Full Stack en cours chez Ynov Rouen), j'ai développé une 
                        approche unique qui combine expertise technique et vision méthodologique.
                    </p>
                    
                    <div class="competencies">
                        <div class="competency-group">
                            <h4>🚀 Chef de Projet Technique</h4>
                            <ul>
                                <li>Recueil et analyse des besoins clients</li>
                                <li>Rédaction de cahiers des charges détaillés</li>
                                <li>Planification et roadmapping de projets</li>
                                <li>Gestion des équipes et des livrables</li>
                                <li>Validation et recettage fonctionnel</li>
                            </ul>
                        </div>
                        
                        <div class="competency-group">
                            <h4>⚡ Développeur Full Stack</h4>
                            <ul>
                                <li>Architecture et développement d'applications web</li>
                                <li>Maîtrise des technologies front-end et back-end</li>
                                <li>Optimisation des performances et sécurité</li>
                                <li>Tests, debugging et maintenance</li>
                                <li>Déploiement et mise en production</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="about-visual">
                    <div class="journey-timeline">
                        <div class="timeline-item">
                            <div class="timeline-year">2022</div>
                            <div class="timeline-content">
                                <h4>BTS SIO SLAM</h4>
                                <p>Fondations solides en développement</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2023</div>
                            <div class="timeline-content">
                                <h4>Licence Informatique</h4>
                                <p>Approfondissement technique</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2024</div>
                            <div class="timeline-content">
                                <h4>Master Expert Full Stack</h4>
                                <p>Vision 360° des projets web</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2025</div>
                            <div class="timeline-content">
                                <h4>PO Technique / Architecte</h4>
                                <p>Objectif : Évolution vers le management technique</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="projects-section">
        <div class="container">
            <div class="section-header">
                <h2>Projets réalisés</h2>
                <p>Découvrez mes réalisations complètes, de l'analyse à la livraison</p>
            </div>
            
            <div id="projects-grid" class="projects-grid">
                <!-- Projects will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- Downloads Section -->
    <section id="downloads" class="downloads-section">
        <div class="container">
            <div class="section-header">
                <h2>📁 Téléchargements</h2>
                <p>Accédez à mes documents professionnels et exemples de livrables</p>
            </div>
            
            <div class="downloads-grid">
                <div class="download-card">
                    <div class="download-icon">📄</div>
                    <h3>CV Détaillé</h3>
                    <p>Mon parcours complet et mes compétences techniques</p>
                    <a href="assets/downloads/cv.pdf" class="btn btn-download" download>Télécharger PDF</a>
                </div>
                
                <div class="download-card">
                    <div class="download-icon">📋</div>
                    <h3>Exemple Cahier des Charges</h3>
                    <p>Modèle de cahier des charges fonctionnel et technique</p>
                    <a href="assets/downloads/exemple-cdc.pdf" class="btn btn-download" download>Télécharger PDF</a>
                </div>
                
                <div class="download-card">
                    <div class="download-icon">🗺️</div>
                    <h3>Template Roadmap</h3>
                    <p>Exemple de planification et découpage de projet</p>
                    <a href="assets/downloads/template-roadmap.pdf" class="btn btn-download" download>Télécharger PDF</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2>Contact</h2>
                <p>Discutons de votre prochain projet</p>
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Parlons de vos besoins</h3>
                    <p>
                        Vous avez un projet web à concrétiser ? Vous cherchez un expert capable 
                        de prendre en charge l'ensemble de la démarche projet, de l'analyse des 
                        besoins à la livraison technique ?
                    </p>
                    <p><strong>Je suis à votre écoute pour :</strong></p>
                    <ul>
                        <li>Analyser vos besoins et définir votre projet</li>
                        <li>Rédiger vos cahiers des charges</li>
                        <li>Développer vos solutions web sur-mesure</li>
                        <li>Piloter vos projets de A à Z</li>
                    </ul>
                </div>
                
                <form id="contact-form" class="contact-form">
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Entreprise</label>
                        <input type="text" id="company" name="company">
                    </div>
                    
                    <div class="form-group">
                        <label for="project-type">Type de projet</label>
                        <select id="project-type" name="project_type">
                            <option value="">-- Sélectionnez --</option>
                            <option value="development">Développement web</option>
                            <option value="consulting">Conseil / Audit</option>
                            <option value="project-management">Gestion de projet</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Décrivez votre projet *</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Contexte, objectifs, contraintes, délais..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
</body>
</html>