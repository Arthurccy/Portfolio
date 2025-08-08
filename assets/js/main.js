// === CONFIGURATION ET CONSTANTES ===
const CONFIG = {
    API_BASE: '/api',
    ANIMATION_DURATION: 300,
    SCROLL_OFFSET: 80
};

// === INITIALISATION ===
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initAnimations();
    initProjectsLoader();
    initContactForm();
    initScrollEffects();
});

// === NAVIGATION ===
function initNavigation() {
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Toggle mobile menu
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Smooth scrolling et active states
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - CONFIG.SCROLL_OFFSET;
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Fermer le menu mobile après clic
                if (navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                }
            }
        });
    });
    
    // Mise à jour de l'état actif au scroll
    updateActiveNavOnScroll();
}

function updateActiveNavOnScroll() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    
    window.addEventListener('scroll', throttle(function() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - CONFIG.SCROLL_OFFSET - 50;
            const sectionHeight = section.offsetHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    }, 100));
}

// === CHARGEMENT DES PROJETS ===
function initProjectsLoader() {
    loadProjects();
}

async function loadProjects() {
    const projectsGrid = document.getElementById('projects-grid');
    
    if (!projectsGrid) return;
    
    try {
        projectsGrid.innerHTML = '<div class="loading-spinner"></div>';
        
        const response = await fetch(`${CONFIG.API_BASE}/projects.php`);
        const data = await response.json();
        
        if (data.success && data.data) {
            renderProjects(data.data);
        } else {
            throw new Error(data.error || 'Erreur lors du chargement des projets');
        }
    } catch (error) {
        console.error('Erreur:', error);
        projectsGrid.innerHTML = `
            <div class="error-message">
                <p>Erreur lors du chargement des projets. Veuillez réessayer.</p>
            </div>
        `;
    }
}

function renderProjects(projects) {
    const projectsGrid = document.getElementById('projects-grid');
    
    if (!projects.length) {
        projectsGrid.innerHTML = '<p>Aucun projet à afficher pour le moment.</p>';
        return;
    }
    
    projectsGrid.innerHTML = projects.map(project => `
        <div class="project-card animate-on-scroll">
            <div class="project-image" style="background-image: url('${escapeHtml(project.image_url || '')}')"></div>
            <div class="project-content">
                <div class="project-header">
                    <h3>${escapeHtml(project.title)}</h3>
                    <p class="subtitle">${escapeHtml(project.subtitle || '')}</p>
                </div>
                
                <p class="project-description">${escapeHtml(project.description)}</p>
                
                <div class="project-details">
                    <div class="project-detail-section">
                        <h5>🎯 Contexte</h5>
                        <p>${escapeHtml(project.context)}</p>
                    </div>
                    
                    <div class="project-detail-section">
                        <h5>👤 Mon rôle</h5>
                        <p>${escapeHtml(project.role)}</p>
                    </div>
                    
                    <div class="project-detail-section">
                        <h5>🎯 Objectifs</h5>
                        <ul>
                            ${project.objectives.map(obj => `<li>${escapeHtml(obj)}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="project-detail-section">
                        <h5>📊 Résultats</h5>
                        <ul>
                            ${project.results.map(result => `<li>${escapeHtml(result)}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="project-detail-section">
                        <h5>✅ Compétences prouvées</h5>
                        <p>${escapeHtml(project.skills_proven)}</p>
                    </div>
                </div>
                
                <div class="project-technologies">
                    ${project.technologies.split(',').map(tech => 
                        `<span class="tech-tag">${escapeHtml(tech.trim())}</span>`
                    ).join('')}
                </div>
                
                <div class="project-actions">
                    ${project.project_url ? 
                        `<a href="${escapeHtml(project.project_url)}" class="btn btn-primary btn-sm" target="_blank" rel="noopener">Voir le projet</a>` 
                        : ''
                    }
                    ${project.github_url ? 
                        `<a href="${escapeHtml(project.github_url)}" class="btn btn-outline btn-sm" target="_blank" rel="noopener">Code source</a>` 
                        : ''
                    }
                </div>
            </div>
        </div>
    `).join('');
    
    // Réinitialiser les animations
    initScrollAnimations();
}

// === FORMULAIRE DE CONTACT ===
function initContactForm() {
    const contactForm = document.getElementById('contact-form');
    
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', handleContactFormSubmit);
    
    // Validation en temps réel
    const inputs = contactForm.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
}

async function handleContactFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Validation côté client
    if (!validateContactForm(form)) {
        return;
    }
    
    // Préparation des données
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        company: formData.get('company'),
        project_type: formData.get('project_type'),
        message: formData.get('message'),
        csrf_token: getCSRFToken()
    };
    
    try {
        // État de chargement
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Envoi en cours...';
        
        const response = await fetch(`${CONFIG.API_BASE}/contact.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showFormSuccess(result.message);
            form.reset();
        } else {
            throw new Error(result.error);
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        showFormError(error.message || 'Une erreur est survenue. Veuillez réessayer.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Envoyer le message';
    }
}

function validateContactForm(form) {
    let isValid = true;
    
    // Validation nom
    const name = form.querySelector('#name');
    if (!name.value.trim() || name.value.length < 2) {
        showFieldError(name, 'Le nom doit contenir au moins 2 caractères');
        isValid = false;
    }
    
    // Validation email
    const email = form.querySelector('#email');
    if (!isValidEmail(email.value)) {
        showFieldError(email, 'Veuillez saisir une adresse email valide');
        isValid = false;
    }
    
    // Validation message
    const message = form.querySelector('#message');
    if (!message.value.trim() || message.value.length < 10) {
        showFieldError(message, 'Le message doit contenir au moins 10 caractères');
        isValid = false;
    }
    
    return isValid;
}

function validateField(e) {
    const field = e.target;
    
    switch (field.id) {
        case 'name':
            if (field.value.length > 0 && field.value.length < 2) {
                showFieldError(field, 'Le nom doit contenir au moins 2 caractères');
            } else {
                clearFieldError(field);
            }
            break;
            
        case 'email':
            if (field.value.length > 0 && !isValidEmail(field.value)) {
                showFieldError(field, 'Veuillez saisir une adresse email valide');
            } else {
                clearFieldError(field);
            }
            break;
            
        case 'message':
            if (field.value.length > 0 && field.value.length < 10) {
                showFieldError(field, 'Le message doit contenir au moins 10 caractères');
            } else {
                clearFieldError(field);
            }
            break;
    }
}

function showFieldError(field, message) {
    field.classList.add('error');
    
    let errorElement = field.parentNode.querySelector('.form-error');
    if (!errorElement) {
        errorElement = document.createElement('span');
        errorElement.className = 'form-error';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('error');
    const errorElement = field.parentNode.querySelector('.form-error');
    if (errorElement) {
        errorElement.remove();
    }
}

function showFormSuccess(message) {
    const form = document.getElementById('contact-form');
    let successElement = form.querySelector('.form-success');
    
    if (!successElement) {
        successElement = document.createElement('div');
        successElement.className = 'form-success';
        form.insertBefore(successElement, form.firstChild);
    }
    
    successElement.innerHTML = `
        <span class="success-checkmark"></span>
        ${escapeHtml(message)}
    `;
    
    successElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function showFormError(message) {
    const form = document.getElementById('contact-form');
    let errorElement = form.querySelector('.form-error-global');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'form-error-global';
        errorElement.style.cssText = `
            background: var(--error);
            color: white;
            padding: var(--space-4);
            border-radius: 8px;
            margin-bottom: var(--space-6);
            text-align: center;
        `;
        form.insertBefore(errorElement, form.firstChild);
    }
    
    errorElement.textContent = message;
    errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// === ANIMATIONS ===
function initAnimations() {
    initScrollAnimations();
}

function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    animatedElements.forEach(el => {
        observer.observe(el);
    });
}

function initScrollEffects() {
    // Header background on scroll
    const header = document.querySelector('.main-header');
    
    window.addEventListener('scroll', throttle(() => {
        if (window.scrollY > 50) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = 'none';
        }
    }, 10));
}

// === UTILITIES ===
function throttle(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function getCSRFToken() {
    // Dans un contexte réel, récupérer le token depuis une meta tag ou cookie
    // Pour cette démonstration, on simule
    return 'demo-csrf-token';
}

// === GESTION DES ERREURS GLOBALES ===
window.addEventListener('error', function(e) {
    console.error('Erreur JavaScript:', e.error);
});

window.addEventListener('unhandledrejection', function(e) {
    console.error('Promise rejetée:', e.reason);
});

// === DEBUG (uniquement en développement) ===
if (window.location.hostname === 'localhost') {
    window.portfolioDebug = {
        loadProjects,
        validateContactForm,
        CONFIG
    };
    console.log('Portfolio Debug Tools disponibles via window.portfolioDebug');
}