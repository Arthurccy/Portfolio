<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/config.php';
require_once '../includes/functions.php';

// Initialiser la base de données
initDatabase();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

try {
    // Récupération et validation des données
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Données invalides');
    }
    
    // Validation CSRF token
    if (!isset($input['csrf_token']) || !verify_csrf_token($input['csrf_token'])) {
        throw new Exception('Token de sécurité invalide');
    }
    
    // Validation des champs obligatoires
    $required_fields = ['name', 'email', 'message'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            throw new Exception("Le champ '$field' est obligatoire");
        }
    }
    
    // Validation email
    if (!is_valid_email($input['email'])) {
        throw new Exception('Adresse email invalide');
    }
    
    // Sanitisation des données
    $contact_data = [
        'name' => sanitize_input($input['name']),
        'email' => sanitize_input($input['email']),
        'company' => sanitize_input($input['company'] ?? ''),
        'project_type' => sanitize_input($input['project_type'] ?? ''),
        'message' => sanitize_input($input['message'])
    ];
    
    // Validation longueur
    if (strlen($contact_data['name']) < 2 || strlen($contact_data['name']) > 100) {
        throw new Exception('Le nom doit contenir entre 2 et 100 caractères');
    }
    
    if (strlen($contact_data['message']) < 10 || strlen($contact_data['message']) > 2000) {
        throw new Exception('Le message doit contenir entre 10 et 2000 caractères');
    }
    
    // Anti-spam basique
    if (preg_match('/\b(viagra|casino|loan|bitcoin)\b/i', $contact_data['message'])) {
        throw new Exception('Message détecté comme spam');
    }
    
    // Enregistrement en base
    $contact_id = save_contact($contact_data);
    
    if (!$contact_id) {
        throw new Exception('Erreur lors de l\'enregistrement');
    }
    
    // Envoi email de notification (optionnel en développement)
    if (defined('ADMIN_EMAIL') && ADMIN_EMAIL) {
        send_email_notification($contact_data);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Votre message a été envoyé avec succès. Je vous recontacterai rapidement !'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>