<?php
/**
 * Fonctions utilitaires du portfolio
 */

/**
 * Initialise la base de données SQLite
 */
function initDatabase() {
    $db = new SQLite3(DB_PATH);
    
    // Table pour les projets
    $db->exec('
        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            subtitle TEXT,
            description TEXT NOT NULL,
            context TEXT NOT NULL,
            role TEXT NOT NULL,
            objectives TEXT NOT NULL,
            technologies TEXT NOT NULL,
            results TEXT NOT NULL,
            skills_proven TEXT NOT NULL,
            image_url TEXT,
            project_url TEXT,
            github_url TEXT,
            order_index INTEGER DEFAULT 0,
            is_featured INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    // Table pour les contacts
    $db->exec('
        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            company TEXT,
            project_type TEXT,
            message TEXT NOT NULL,
            ip_address TEXT,
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            status TEXT DEFAULT "new"
        )
    ');
    
    return $db;
}

/**
 * Sécurise les données d'entrée
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Valide une adresse email
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Vérifie le token CSRF
 */
function verify_csrf_token($token) {
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Génère un token CSRF pour les formulaires
 */
function get_csrf_token() {
    return $_SESSION['csrf_token'];
}

/**
 * Récupère tous les projets depuis la base de données
 */
function get_projects() {
    $db = new SQLite3(DB_PATH);
    $result = $db->query('SELECT * FROM projects ORDER BY order_index ASC, created_at DESC');
    
    $projects = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $projects[] = $row;
    }
    
    $db->close();
    return $projects;
}

/**
 * Récupère un projet par son ID
 */
function get_project($id) {
    $db = new SQLite3(DB_PATH);
    $stmt = $db->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->bindValue(1, $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $project = $result->fetchArray(SQLITE3_ASSOC);
    $db->close();
    
    return $project;
}

/**
 * Enregistre un nouveau contact
 */
function save_contact($data) {
    $db = new SQLite3(DB_PATH);
    
    $stmt = $db->prepare('
        INSERT INTO contacts (name, email, company, project_type, message, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ');
    
    $stmt->bindValue(1, $data['name'], SQLITE3_TEXT);
    $stmt->bindValue(2, $data['email'], SQLITE3_TEXT);
    $stmt->bindValue(3, $data['company'], SQLITE3_TEXT);
    $stmt->bindValue(4, $data['project_type'], SQLITE3_TEXT);
    $stmt->bindValue(5, $data['message'], SQLITE3_TEXT);
    $stmt->bindValue(6, $_SERVER['REMOTE_ADDR'], SQLITE3_TEXT);
    $stmt->bindValue(7, $_SERVER['HTTP_USER_AGENT'] ?? '', SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $contact_id = $db->lastInsertRowID();
    $db->close();
    
    return $contact_id;
}

/**
 * Envoie un email de notification
 */
function send_email_notification($contact_data) {
    $to = ADMIN_EMAIL;
    $subject = '[Portfolio] Nouveau message de ' . $contact_data['name'];
    
    $message = "
    Nouveau message reçu via le portfolio :
    
    Nom: {$contact_data['name']}
    Email: {$contact_data['email']}
    Entreprise: {$contact_data['company']}
    Type de projet: {$contact_data['project_type']}
    
    Message:
    {$contact_data['message']}
    
    ---
    IP: {$_SERVER['REMOTE_ADDR']}
    Date: " . date('Y-m-d H:i:s');
    
    $headers = "From: noreply@portfolio.com\r\n";
    $headers .= "Reply-To: {$contact_data['email']}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    return mail($to, $subject, $message, $headers);
}

/**
 * Insère les données d'exemple si la base est vide
 */
function seed_sample_data() {
    $db = new SQLite3(DB_PATH);
    
    // Vérifier si des projets existent déjà
    $result = $db->query('SELECT COUNT(*) as count FROM projects');
    $count = $result->fetchArray(SQLITE3_ASSOC)['count'];
    
    if ($count == 0) {
        // Insérer des projets d'exemple
        $sample_projects = [
            [
                'title' => 'Plateforme E-commerce B2B',
                'subtitle' => 'Solution complète pour grossiste alimentaire',
                'description' => 'Développement d\'une plateforme e-commerce sur-mesure avec gestion des catalogues, commandes et facturation automatisée.',
                'context' => 'Un grossiste alimentaire souhaitait digitaliser ses ventes avec un système adapté aux spécificités B2B (tarifs dégressifs, catalogues personnalisés, etc.)',
                'role' => 'Chef de projet technique et développeur principal',
                'objectives' => '• Digitaliser le processus commercial\n• Réduire les erreurs de commande\n• Automatiser la facturation\n• Améliorer l\'expérience client',
                'technologies' => 'PHP 8, MySQL, JavaScript, Bootstrap, API REST, PDF generation',
                'results' => '• +40% d\'efficacité commerciale\n• Réduction de 70% des erreurs\n• 150+ clients migrés\n• ROI atteint en 6 mois',
                'skills_proven' => 'Gestion de projet complète, architecture logicielle, développement full stack, relation client',
                'image_url' => 'https://images.pexels.com/photos/230544/pexels-photo-230544.jpeg',
                'order_index' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'Application de Gestion RH',
                'subtitle' => 'Digitalisation des processus RH d\'une PME',
                'description' => 'Conception et développement d\'une application web pour la gestion des congés, planning et évaluations d\'une entreprise de 80 salariés.',
                'context' => 'Une PME gérait manuellement ses processus RH via Excel, générant des erreurs et une perte de temps considérable.',
                'role' => 'Analyste fonctionnel et développeur full stack',
                'objectives' => '• Centraliser la gestion RH\n• Automatiser les workflows\n• Améliorer la communication\n• Sécuriser les données',
                'technologies' => 'Node.js, Express, MongoDB, React, JWT Auth, Chart.js',
                'results' => '• 60% de gain de temps RH\n• Satisfaction employés: 95%\n• 0 erreur de planning\n• Conformité RGPD assurée',
                'skills_proven' => 'Analyse fonctionnelle, UX/UI, sécurité des données, conduite du changement',
                'image_url' => 'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg',
                'order_index' => 2,
                'is_featured' => 1
            ],
            [
                'title' => 'Site Vitrine + CMS',
                'subtitle' => 'Refonte complète pour cabinet d\'expertise',
                'description' => 'Création d\'un site vitrine moderne avec CMS intégré pour permettre au client de gérer facilement son contenu.',
                'context' => 'Un cabinet d\'expertise comptable avait besoin d\'une présence web moderne et d\'un moyen simple pour publier ses actualités.',
                'role' => 'Chef de projet web et développeur',
                'objectives' => '• Moderniser l\'image de marque\n• Améliorer le référencement\n• Faciliter la gestion du contenu\n• Optimiser la conversion',
                'technologies' => 'PHP, MySQL, TinyMCE, Responsive design, SEO optimization',
                'results' => '• +200% de trafic organique\n• Taux de conversion: +35%\n• Autonomie client: 100%\n• Score PageSpeed: 95/100',
                'skills_proven' => 'Webdesign, développement CMS, SEO, formation utilisateur',
                'image_url' => 'https://images.pexels.com/photos/196644/pexels-photo-196644.jpeg',
                'order_index' => 3,
                'is_featured' => 0
            ]
        ];
        
        foreach ($sample_projects as $project) {
            $stmt = $db->prepare('
                INSERT INTO projects (title, subtitle, description, context, role, objectives, technologies, results, skills_proven, image_url, order_index, is_featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            
            $stmt->bindValue(1, $project['title'], SQLITE3_TEXT);
            $stmt->bindValue(2, $project['subtitle'], SQLITE3_TEXT);
            $stmt->bindValue(3, $project['description'], SQLITE3_TEXT);
            $stmt->bindValue(4, $project['context'], SQLITE3_TEXT);
            $stmt->bindValue(5, $project['role'], SQLITE3_TEXT);
            $stmt->bindValue(6, $project['objectives'], SQLITE3_TEXT);
            $stmt->bindValue(7, $project['technologies'], SQLITE3_TEXT);
            $stmt->bindValue(8, $project['results'], SQLITE3_TEXT);
            $stmt->bindValue(9, $project['skills_proven'], SQLITE3_TEXT);
            $stmt->bindValue(10, $project['image_url'], SQLITE3_TEXT);
            $stmt->bindValue(11, $project['order_index'], SQLITE3_INTEGER);
            $stmt->bindValue(12, $project['is_featured'], SQLITE3_INTEGER);
            
            $stmt->execute();
        }
    }
    
    $db->close();
}
?>