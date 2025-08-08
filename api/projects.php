<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/config.php';
require_once '../includes/functions.php';

// Initialiser la base de données et les données d'exemple
initDatabase();
seed_sample_data();

try {
    $projects = get_projects();
    
    // Formater les données pour le frontend
    $formatted_projects = array_map(function($project) {
        return [
            'id' => $project['id'],
            'title' => $project['title'],
            'subtitle' => $project['subtitle'],
            'description' => $project['description'],
            'context' => $project['context'],
            'role' => $project['role'],
            'objectives' => explode("\n", $project['objectives']),
            'technologies' => $project['technologies'],
            'results' => explode("\n", $project['results']),
            'skills_proven' => $project['skills_proven'],
            'image_url' => $project['image_url'],
            'project_url' => $project['project_url'],
            'github_url' => $project['github_url'],
            'is_featured' => (bool) $project['is_featured']
        ];
    }, $projects);
    
    echo json_encode([
        'success' => true,
        'data' => $formatted_projects
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des projets'
    ]);
}
?>